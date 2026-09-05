/**
 * ====================================================================
 * عميل Supabase السحابي لنظام إدارة الصيدلية الذكي (Supabase Cloud Client)
 * يدعم الاستعلام اللحظي (Realtime Queries)، المزامنة السحابية، والعمل دون انقطاع
 * ====================================================================
 */

const SUPABASE_CONFIG = {
    url: 'https://jwregyxonmeuhktpmhyy.supabase.co',
    anonKey: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imp3cmVneXhvbm1ldWhrdHBtaHl5Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3ODU5NDA5MjIsImV4cCI6MjEwMTUxNjkyMn0.qi8Rm3HN4hV_gabCQOYuwh4D44mNxutLgIdRlL6yXFE',
    publishableKey: 'sb_publishable_XuQ1vrP3yJa3QAtwZwIvvQ_yC0NeLKH'
};

class SupabaseService {
    constructor() {
        this.url = SUPABASE_CONFIG.url;
        this.key = SUPABASE_CONFIG.anonKey;
        this.client = null;
        this.isConnected = false;
        this.init();
    }

    init() {
        if (typeof window.supabase !== 'undefined' && window.supabase.createClient) {
            try {
                this.client = window.supabase.createClient(this.url, this.key);
                this.isConnected = true;
                console.log('[Supabase] Initialized Supabase Cloud Client successfully.');
            } catch (e) {
                console.warn('[Supabase] Client init warning:', e);
            }
        }
    }

    /**
     * استرجاع كافة الأدوية من سحابة Supabase
     */
    async getMedicines() {
        if (this.client) {
            try {
                const { data, error } = await this.client
                    .from('medicines')
                    .select('*')
                    .order('id', { ascending: true });

                if (!error && data && data.length > 0) {
                    return { success: true, data: data, source: 'supabase_cloud' };
                }
            } catch (e) {
                console.warn('[Supabase] Fetch error:', e);
            }
        }

        // استخدام REST API المباشر لسحابة Supabase عبر fetch
        try {
            const res = await fetch(`${this.url}/rest/v1/medicines?select=*&order=id.asc`, {
                headers: {
                    'apikey': this.key,
                    'Authorization': `Bearer ${this.key}`,
                    'Content-Type': 'application/json'
                }
            });

            if (res.ok) {
                const data = await res.json();
                if (data && data.length > 0) {
                    return { success: true, data: data, source: 'supabase_rest' };
                }
            }
        } catch (e) {
            console.warn('[Supabase] REST fallback:', e);
        }

        return { success: false, data: [] };
    }

    /**
     * حفظ فاتورة صرف جديدة في سحابة Supabase
     */
    async saveOrder(orderData, items) {
        let savedOrder = null;

        // 1. إدراج الفاتورة في جدول orders
        const payload = {
            invoice_number: orderData.invoice_number || `INV-${Date.now()}`,
            user_id: 1,
            order_type: orderData.order_type || 'patient_sale',
            customer_name: orderData.customer_name || 'عميل نقدي',
            payment_method: orderData.payment_method || 'cash',
            total_amount: parseFloat(orderData.total_amount) || 0.0,
            status: orderData.status || 'completed',
            payment_status: 'paid',
            notes: orderData.notes || '',
            order_date: new Date().toISOString()
        };

        if (this.client) {
            try {
                const { data, error } = await this.client
                    .from('orders')
                    .insert([payload])
                    .select();

                if (!error && data && data.length > 0) {
                    savedOrder = data[0];
                }
            } catch (e) {
                console.warn('[Supabase] Order insert error:', e);
            }
        }

        // محاولة عبر REST API إذا لم ينجح العميل
        if (!savedOrder) {
            try {
                const res = await fetch(`${this.url}/rest/v1/orders`, {
                    method: 'POST',
                    headers: {
                        'apikey': this.key,
                        'Authorization': `Bearer ${this.key}`,
                        'Content-Type': 'application/json',
                        'Prefer': 'return=representation'
                    },
                    body: JSON.stringify(payload)
                });

                if (res.ok) {
                    const d = await res.json();
                    if (d && d.length > 0) savedOrder = d[0];
                }
            } catch (e) {}
        }

        // 2. إدراج بنود الفاتورة في order_items وتحديث أرصدة الأدوية سحابياً
        if (savedOrder && items && items.length > 0) {
            const itemPayloads = items.map(it => ({
                order_id: savedOrder.id,
                medicine_id: it.medicine_id || it.id || null,
                medicine_name: it.medicine_name || it.name || '',
                quantity: it.quantity || 1,
                unit_price: parseFloat(it.unit_price || it.price) || 0.0,
                total_price: (parseFloat(it.unit_price || it.price) || 0.0) * (it.quantity || 1)
            }));

            try {
                if (this.client) {
                    await this.client.from('order_items').insert(itemPayloads);
                    // تحديث رصيد كل صنف في جدول medicines سحابياً
                    for (const it of items) {
                        if (it.medicine_id) {
                            const { data: medData } = await this.client.from('medicines').select('stock_quantity').eq('id', it.medicine_id).maybeSingle();
                            if (medData) {
                                const newQty = (orderData.order_type === 'routine_restock') ?
                                    ((medData.stock_quantity || 0) + (it.quantity || 1)) :
                                    Math.max(0, (medData.stock_quantity || 0) - (it.quantity || 1));
                                await this.client.from('medicines').update({ stock_quantity: newQty }).eq('id', it.medicine_id);
                            }
                        }
                    }
                } else {
                    await fetch(`${this.url}/rest/v1/order_items`, {
                        method: 'POST',
                        headers: {
                            'apikey': this.key,
                            'Authorization': `Bearer ${this.key}`,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(itemPayloads)
                    });
                }
            } catch (e) {}
        }

        return savedOrder ? { success: true, data: savedOrder } : { success: false };
    }

    /**
     * استرجاع سجل الفواتير والمبيعات من سحابة Supabase
     */
    async getOrders() {
        if (this.client) {
            try {
                const { data, error } = await this.client
                    .from('orders')
                    .select('*, order_items(*)')
                    .order('id', { ascending: false });

                if (!error && data) {
                    return { success: true, data: data, source: 'supabase_cloud' };
                }
            } catch (e) {}
        }

        try {
            const res = await fetch(`${this.url}/rest/v1/orders?select=*,order_items(*)&order=id.desc`, {
                headers: {
                    'apikey': this.key,
                    'Authorization': `Bearer ${this.key}`,
                    'Content-Type': 'application/json'
                }
            });

            if (res.ok) {
                const data = await res.json();
                return { success: true, data: data, source: 'supabase_rest' };
            }
        } catch (e) {}

        return { success: false, data: [] };
    }

    /**
     * فحص حالة الاتصال بسحابة Supabase
     */
    async testConnection() {
        try {
            const start = performance.now();
            const res = await fetch(`${this.url}/rest/v1/medicines?select=id&limit=1`, {
                headers: {
                    'apikey': this.key,
                    'Authorization': `Bearer ${this.key}`
                }
            });
            const latency = Math.round(performance.now() - start);

            if (res.ok) {
                return { status: 'connected', latency: `${latency}ms`, message: 'متصل بسحابة Supabase بنجاح' };
            } else if (res.status === 404 || res.status === 401) {
                return { status: 'needs_schema', latency: `${latency}ms`, message: 'تم الوصول للسحابة، الجداول بحاجة للتغذية الأوليّة' };
            }
            return { status: 'error', latency: `${latency}ms`, message: `كود الاستجابة: ${res.status}` };
        } catch (e) {
            return { status: 'offline', latency: '0ms', message: 'تعذر الوصول لخادم Supabase' };
        }
    }
}

// إنشاء نسخة عامة واحدة لجميع صفحات التطبيق
window.SupabaseDB = new SupabaseService();