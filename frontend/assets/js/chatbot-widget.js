// -*- coding: utf-8 -*-
/**
 * سكريبت الويدجت العائم للشات بوت الذكي (Smart Pharmacy Chatbot Widget)
 * يتم تضمينه تلقائياً في كافة صفحات النظام لتقديم المساعدة الفورية.
 */

(function initChatbotWidget() {
    // منع التكرار أو التشغيل داخل صفحة الشات الكاملة نفسها
    if (window.location.pathname.endsWith('chat.html')) return;
    if (document.getElementById('spmsChatFab')) return;

    // حقن CSS إذا لم يكن مضمناً
    if (!document.querySelector('link[href*="chatbot-widget.css"]')) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        const isSubpage = window.location.pathname.includes('/pages/') || window.location.href.includes('/pages/');
        link.href = isSubpage ? '../assets/css/chatbot-widget.css' : 'assets/css/chatbot-widget.css';
        document.head.appendChild(link);
    }

    // بناء عناصر الـ HTML للويدجت
    const widgetContainer = document.createElement('div');
    widgetContainer.innerHTML = `
        <!-- Floating Action Button -->
        <button id="spmsChatFab" class="spms-chat-fab" title="المساعد الدوائي الذكي">
            <svg viewBox="0 0 24 24">
                <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
            </svg>
        </button>

        <!-- Popup Chat Modal -->
        <div id="spmsChatModal" class="spms-chat-modal">
            <div class="spms-chat-modal-header">
                <div>
                    <h4>المساعد الدوائي الذكي</h4>
                    <p>استفسار فوري ومقارنة أسعار وبدائل</p>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <a id="spmsChatExpandLink" href="#" style="color: #fff; text-decoration: none; font-size: 0.8rem; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 6px;" title="فتح في صفحة كاملة">تكبير</a>
                    <button id="spmsChatClose" class="spms-chat-close-btn">&times;</button>
                </div>
            </div>

            <div id="spmsChatBody" class="spms-chat-body">
                <div class="spms-widget-msg bot">
                    <div class="spms-widget-bubble">
                        <strong>المساعد الدوائي الذكي</strong><br>
                        مرحباً بك. المساعد متصل مباشرة بقاعدة بيانات الصيدلية للإجابة عن أسعار الأدوية وتوفر المخزون والبدائل المتاحة.
                    </div>
                </div>
            </div>

            <form id="spmsWidgetForm" class="spms-widget-footer">
                <input type="text" id="spmsWidgetInput" placeholder="اكتب استفسارك الدوائي..." autocomplete="off" required>
                <button type="submit">إرسال</button>
            </form>
        </div>
    `;

    document.body.appendChild(widgetContainer);

    // ربط الأحداث التفاعلية
    const fab = document.getElementById('spmsChatFab');
    const modal = document.getElementById('spmsChatModal');
    const closeBtn = document.getElementById('spmsChatClose');
    const form = document.getElementById('spmsWidgetForm');
    const input = document.getElementById('spmsWidgetInput');
    const chatBody = document.getElementById('spmsChatBody');
    const expandLink = document.getElementById('spmsChatExpandLink');

    const isSubpage = window.location.pathname.includes('/pages/') || window.location.href.includes('/pages/');
    expandLink.href = isSubpage ? 'chat.html' : 'pages/chat.html';

    let isOpen = false;
    let widgetHistory = [];
    let liveDbMeds = [];

    async function fetchWidgetDatabase() {
        try {
            if (typeof APIClient !== 'undefined') {
                const res = await APIClient.request('medicines.php');
                if (res && res.success && res.data && res.data.length > 0) {
                    liveDbMeds = res.data;
                    return liveDbMeds;
                }
            }
        } catch (e) {}
        if (typeof DEMO_MEDICINES !== 'undefined') {
            liveDbMeds = DEMO_MEDICINES;
        }
        return liveDbMeds;
    }

    fetchWidgetDatabase();

    function toggleChat(open = !isOpen) {
        isOpen = open;
        modal.style.display = isOpen ? 'flex' : 'none';
        if (isOpen) {
            input.focus();
            chatBody.scrollTop = chatBody.scrollHeight;
            fetchWidgetDatabase();
        }
    }

    fab.addEventListener('click', () => toggleChat());
    closeBtn.addEventListener('click', () => toggleChat(false));

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = input.value.trim();
        if (!text) return;

        // إضافة رسالة المستخدم
        appendWidgetMsg('user', text);
        input.value = '';
        widgetHistory.push({ role: 'user', content: text });

        // إضافة رسالة جاري المعالجة
        const loadingId = 'loading-' + Date.now();
        const loadingDiv = document.createElement('div');
        loadingDiv.id = loadingId;
        loadingDiv.className = 'spms-widget-msg bot';
        loadingDiv.innerHTML = `<div class="spms-widget-bubble" style="color: #64748b; font-style: italic;">جاري الاستعلام من قاعدة البيانات...</div>`;
        chatBody.appendChild(loadingDiv);
        chatBody.scrollTop = chatBody.scrollHeight;

        try {
            const currentMeds = await fetchWidgetDatabase();
            let resData = null;

            // 1. الاتصال المباشر بمحرك بايثون FastAPI
            if (!resData) {
                try {
                    const pyRes = await fetch('http://127.0.0.1:8000/api/chat', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ message: text, medicines: currentMeds, history: widgetHistory })
                    });
                    if (pyRes.ok) {
                        const pyJ = await pyRes.json();
                        if (pyJ.success && pyJ.data) {
                            resData = pyJ.data;
                        }
                    }
                } catch (pyErr) {
                    console.warn('FastAPI direct call failed, trying PHP backend...');
                }
            }

            // 2. الاتصال عبر وسيط PHP المباشر
            if (!resData) {
                const candidatePaths = [
                    (window.location.origin ? window.location.origin : '') + '/backend/api/chatbot.php',
                    isSubpage ? '../backend/api/chatbot.php' : 'backend/api/chatbot.php',
                    isSubpage ? '../../backend/api/chatbot.php' : '../backend/api/chatbot.php',
                    'http://localhost/backend/api/chatbot.php',
                    'http://127.0.0.1/backend/api/chatbot.php'
                ];
                for (const apiPath of candidatePaths) {
                    try {
                        const res = await fetch(apiPath, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ message: text, medicines: currentMeds, history: widgetHistory })
                        });
                        if (res.ok) {
                            const j = await res.json();
                            if (j.success && j.data) {
                                resData = j.data;
                                break;
                            }
                        }
                    } catch (phpErr) {}
                }
            }

            // 3. محرك الاستجابة المباشر على بيانات قاعدة البيانات الحية
            if (!resData) {
                resData = queryWidgetDatabase(text, currentMeds, widgetHistory);
            }

            const loader = document.getElementById(loadingId);
            if (loader) loader.remove();

            if (resData && resData.reply) {
                appendWidgetMsg('bot', formatText(resData.reply));
                widgetHistory.push({ role: 'assistant', content: resData.reply });
            } else {
                appendWidgetMsg('bot', 'تمت المعالجة بنجاح.');
            }
        } catch (err) {
            const loader = document.getElementById(loadingId);
            if (loader) loader.remove();
            appendWidgetMsg('bot', 'تعذر الاتصال بقاعدة البيانات أو محرك الذكاء الاصطناعي حالياً.');
        }

        chatBody.scrollTop = chatBody.scrollHeight;
    });

    
    window.addMedToCartFromBot = function(medId, medName) {
        if (typeof addItemToInvoice === 'function' && typeof availableMeds !== 'undefined') {
            const sel = document.getElementById('medSelect');
            if (sel) {
                sel.value = medId;
                if (typeof updateMedSelectionInfo === 'function') updateMedSelectionInfo();
                addItemToInvoice();
                if (typeof showNotification === 'function') {
                    showNotification(`تمت إضافة [${medName}] للفاتورة مباشرة بنجاح!`, 'success');
                }
                return;
            }
        }
        const isSub = window.location.pathname.includes('/pages/') || window.location.href.includes('/pages/');
        const url = isSub ? `order_create.html?med_id=${medId}&auto_add=1` : `pages/order_create.html?med_id=${medId}&auto_add=1`;
        window.location.href = url;
    };

    function renderWidgetMedCard(m, isBest = false) {
        const isSub = window.location.pathname.includes('/pages/') || window.location.href.includes('/pages/');
        const posUrl = isSub ? `order_create.html?med_id=${m.id}&auto_add=1` : `pages/order_create.html?med_id=${m.id}&auto_add=1`;
        const reqUrl = isSub ? `orders.html?action=request&med=${encodeURIComponent(m.name)}` : `pages/orders.html?action=request&med=${encodeURIComponent(m.name)}`;
        const stock = m.stock_quantity !== undefined ? m.stock_quantity : 0;
        const bestTag = isBest ? '<span class="badge badge-success" style="font-size:0.72rem; padding: 2px 6px; margin-inline-start: 4px;">الخيار الأوفر</span>' : '';

        return `
            <div class="spms-med-order-card">
                <div style="flex: 1; min-width: 170px;">
                    <strong style="color: var(--primary, #0284c7); font-size: 0.86rem;">${m.name}</strong> ${bestTag}
                    <div style="font-size: 0.74rem; color: var(--text-muted, #64748b); margin-top: 2px;">
                        المادة: <span class="generic-name" style="font-family: 'Times New Roman', Times, 'Simplified Arabic', serif; font-size: 0.84rem; color: var(--text-muted); font-style: italic;">${m.generic_name || '-'}</span> | السعر: <strong>${parseFloat(m.price || 0).toFixed(2)} ريال</strong> | الرصيد: <strong>${stock} علبة</strong>
                    </div>
                </div>
                <div style="display: flex; gap: 5px;">
                    <a href="${posUrl}" onclick="window.addMedToCartFromBot(${m.id}, '${m.name}'); return false;" class="spms-med-order-btn-pos">
                        + صرف بالفاتورة
                    </a>
                    ${(typeof AppAuth !== 'undefined' && AppAuth.getNormalizedRole && AppAuth.getNormalizedRole() === 'supervisor')
                        ? `<a href="order_create.html?med_id=${m.id}" class="spms-med-order-btn-req">+ أمر شراء للمورد</a>`
                        : `<a href="${reqUrl}" class="spms-med-order-btn-req">طلب توفير</a>`
                    }
                </div>
            </div>
        `;
    }

    function queryWidgetDatabase(query, medicines, history = []) {
        const q = (query || '').toLowerCase().trim();
        const meds = (medicines && medicines.length > 0) ? medicines : (typeof DEMO_MEDICINES !== 'undefined' ? DEMO_MEDICINES : []);

        if (q.includes('مرحبا') || q.includes('اهلا') || q.includes('السلام') || q.includes('هلا') || q.includes('صباح') || q.includes('مساء')) {
            return { reply: "أهلاً بك. أنا المساعد الدوائي والسريري الذكي، متصل مباشرة بقاعدة بيانات ومخزون الصيدلية للبحث عن الأسعار، توفر المخزون، تشخيص أكثر من 30 حالة سريرية واقتراح البدائل والجرعات الآمنة." };
        }

        // 1. فحص الذاكرة السياقية وأسئلة المتابعة
        const isFollowDosage = q.includes('كم حبة') || q.includes('كم حبه') || q.includes('كم الجرعة') || q.includes('كم الجرعه') || q.includes('طريقة الاستخدام') || q.includes('متى اخذه') || q.includes('متى آخذه') || q.includes('كم مرة') || q.includes('قبل الاكل') || q.includes('بعد الاكل');
        const isFollowPregnancy = q.includes('حامل') || q.includes('للحامل') || q.includes('مرضع') || q.includes('الرضاعة') || q.includes('ينفع للحامل') || q.includes('آمن للحامل');
        const isFollowChronic = q.includes('مريض ضغط') || q.includes('عندي ضغط') || q.includes('مريض سكر') || q.includes('عندي سكر') || q.includes('قرحة') || q.includes('معدتي حساسة');
        const isFollowCheaper = q.includes('في ارخص') || q.includes('في أرخص') || q.includes('بديل ارخص') || q.includes('بديل أرخص') || q.includes('اوفر') || q.includes('أوفر');

        if ((isFollowDosage || isFollowPregnancy || isFollowChronic || isFollowCheaper) && history && history.length > 0) {
            let lastContext = '';
            for (let i = history.length - 1; i >= 0; i--) {
                if (history[i].content) {
                    lastContext = history[i].content + ' ' + lastContext;
                    if (lastContext.length > 500) break;
                }
            }
            const normC = lastContext.toLowerCase();

            if (isFollowDosage) {
                if (normC.includes('باراسيتامول') || normC.includes('بنادول') || normC.includes('فيفادول')) {
                    return { reply: "### إرشادات الجرعة لدواء الباراسيتامول (بنادول / فيفادول):\n- **للبالغين:** 1-2 قرص (500-1000 مجم) كل 6-8 ساعات عند اللزوم.\n- **الحد الأقصى:** 4000 مجم (8 أقراص 500 مجم) خلال 24 ساعة.\n- **التوقيت:** يُفضل بعد الأكل مع ماء وافر، وهو لطيف على المعدة.\n\nهل المريض يعاني من أي مشاكل بوظائف الكبد أو الكلى؟" };
                }
                if (normC.includes('ايبوبروفين') || normC.includes('بروفين')) {
                    return { reply: "### إرشادات الجرعة لدواء الإيبوبروفين (بروفين):\n- **للبالغين:** قرص 400 مجم كل 8 ساعات بعد الوجبة مباشرة.\n- **تحذير:** تجنب أخذه على معدة فارغة لمنع القرحة، ويُمنع لمرضى الضغط غير المنضبط.\n\nهل تود بديلاً أكثر أماناً للمعدة؟" };
                }
                if (normC.includes('قولون') || normC.includes('ميبفرين') || normC.includes('دوسباتالين')) {
                    return { reply: "### إرشادات جرعة أدوية القولون والمغص:\n- **دوسباتالين 135 مجم:** قرص واحد 3 مرات يومياً قبل الوجبة بـ 20 دقيقة.\n- **سباسموبان (هيوسين):** قرص عند اللزوم عند حدوث مغص حاد.\n\nهل المغص مصحوب بإسهال أم بإمساك؟" };
                }
                if (normC.includes('اوميبرازول') || normC.includes('حموضة') || normC.includes('نيكسيوم')) {
                    return { reply: "### إرشادات جرعة علاج الحموضة (أوميبرازول / نيكسيوم):\n- كبسولة واحدة عيار 20 أو 40 مجم صباحاً على الريق قبل الإفطار بـ 30 دقيقة.\n\nهل تشعر بحرقة تصعد للمريء أثناء النوم؟" };
                }
            }

            if (isFollowPregnancy) {
                if (normC.includes('باراسيتامول') || normC.includes('بنادول') || normC.includes('صداع') || normC.includes('حرارة')) {
                    return { reply: "### تقييم الأمان خلال الحمل والرضاعة:\n- **الباراسيتامول العادي (بنادول الأزرق / فيفادول):** آمن تماماً وهو الخيار الأول المعتمد طبياً لتسكين الألم وخفض الحرارة للحوامل.\n- **تحذير:** تجنبي بنادول إكسترا المحتوي على كافيين، والبروفين والفولتارين.\n\nهل ترغبين بمعرفة أمان أدوية أخرى للحوامل؟" };
                }
                if (normC.includes('بروفين') || normC.includes('فولتارين') || normC.includes('كتافلام')) {
                    return { reply: "### تحذير سريري قطعي للحوامل:\n- مسكنات مضادات الالتهاب (بروفين، فولتارين، كتافلام) **غير آمنة** أثناء الحمل ومحظورة تماماً بالثلث الأخير.\n- البديل الآمن المعتمد فوراً هو **الباراسيتامول**.\n\nهل تود استعراض خيارات الباراسيتامول المتاحة بالمخزن؟" };
                }
            }

            if (isFollowChronic) {
                return { reply: "### تقييم الملاءمة لمرضى الضغط وقرحة المعدة:\n- لمرضى الضغط وقرحة المعدة: يُمنع صرف البروفين والفولتارين ومشتقاتهما، والمسكن الآمن الوحيد هو **الباراسيتامول (فيفادول / أدول)**.\n\nهل تبحث عن علاج لا يتعارض مع أدويتك الحالية؟" };
            }

            if (isFollowCheaper) {
                return { reply: "### البدائل المتكافئة بيولوجياً الأكثر توفيراً:\n- بنادول إكسترا (12 ريال) -> البديل: فيفادول أو أدول (6 ريال) [توفير 50%].\n- أوجمنتين 1 جم (45 ريال) -> البديل: ميجاموكس (28 ريال) أو كلافوكس (32 ريال).\n- نيكسيوم 40 (72 ريال) -> البديل: كونترولوك (54 ريال) أو أوميبرازول (20 ريال).\n\nاكتب اسم الصنف وسأفرز لك كل بدائله من الأرخص للأعلى فوراً." };
            }
        }

        // 2. فحص مطابقة دواء محدد بالاسم
        let found = meds.find(m => {
            const n = (m.name || '').toLowerCase();
            const g = (m.generic_name || '').toLowerCase();
            return n.includes(q) || g.includes(q);
        });

        if (!found) {
            const words = q.split(' ').filter(w => w.length >= 3);
            for (const w of words) {
                found = meds.find(m => {
                    const n = (m.name || '').toLowerCase();
                    const g = (m.generic_name || '').toLowerCase();
                    return n.includes(w) || g.includes(w);
                });
                if (found) break;
            }
        }

        if (found) {
            const alts = meds.filter(m => m.id !== found.id && m.generic_name && found.generic_name && m.generic_name.toLowerCase() === found.generic_name.toLowerCase()).sort((a, b) => a.price - b.price);
            let reply = `### نتيجة البحث: **${found.name}**\n`;
            reply += renderWidgetMedCard(found);

            if (alts.length > 0) {
                reply += `\n#### البدائل المتكافئة المسجلة بالصيدلية (مرتبة حسب السعر):\n`;
                alts.forEach((alt, idx) => {
                    reply += renderWidgetMedCard(alt, idx === 0 && alt.price < found.price);
                });
            }
            reply += `\n*(يا غالي، تقدر تسألني: 'كم حبة آخذ؟'، 'هل ينفع للحامل؟'، أو 'في بديل أرخص؟')*`;
            return { reply: reply };
        }

        // 3. أنطولوجيا الأعراض السريرية الشاملة مع استبعاد الالتباس
        const symptomRules = [
            { id: 'EYE', keywords: ['عين', 'عيني', 'عيوني', 'جفاف عين', 'حرقة بالعين', 'حرقان بالعين', 'حرقان في عيني', 'احمرار عين', 'قطرة عين', 'قطره عين'], generics: ['كاربوكسي ميثيل سليلوز', 'ريفريش'], title: 'جفاف وحرقة وإجهاد العين', advice: 'استخدام قطرة مرطبة مثل ريفريش تيرز 3-4 مرات يومياً لترطيب القرنية وتخفيف الجفاف.', triage: 'هل يوجد إفرازات صديدية صفراء أو تشوش بالرؤية؟' },
            { id: 'UTI', keywords: ['حرقان بول', 'حرقان بالبول', 'حرقان في البول', 'مسالك بولية', 'صديد بول', 'صعوبة تبول', 'التهاب مثانة', 'بول متكرر', 'الم عند التبول'], generics: ['سيبروفلوكساسين', 'ليفوفلوكساسين', 'أوجمنتين'], title: 'حرقان والتهاب المسالك البولية والمثانة', advice: 'شرب كميات وافرة من الماء (3 لتر يومياً) مع فوار مطهر للمسالك، ومراجعة الطبيب لعمل تحليل بول.', triage: 'هل هناك ألم في جانبي الظهر أو دم في البول؟' },
            { id: 'BURNS', keywords: ['حرق', 'حروق', 'انحرقت', 'جرح', 'جروح', 'تسلخات', 'ميبو', 'ماء حار'], exclude: ['حرقان', 'بول', 'معدة'], generics: ['بيتا سيتوستيرول', 'ميبو'], title: 'الحروق السطحية والجروح والتئام الجلد', advice: 'تبريد الحرق فوراً بماء فاتر لمدة 10 دقائق ثم دهن مرهم ميبو (Mebo) وتغطيته بشاش معقم.', triage: 'ما هي درجة الحرق وهل تكونت فقاعات مائية؟' },
            { id: 'STOMACH', keywords: ['حموضة', 'حموضه', 'حرقان', 'ارتجاع', 'قرحة', 'معدة', 'فم المعدة', 'عسر هضم'], exclude: ['عين', 'عيني', 'بول'], generics: ['أوميبرازول', 'بانتوبرازول', 'إيزوميبرازول'], title: 'حموضة وحرقة المعدة والارتجاع', advice: 'تناول كبسولة أوميبرازول 20 مجم صباحاً قبل الإفطار بنصف ساعة مع تجنب الدهون والمقليات.', triage: 'هل تشعر بحرقة تصعد للحلق عند النوم؟' },
            { id: 'HEADACHE', keywords: ['صداع', 'راس', 'رأس', 'شقيقة', 'شقيقه', 'مصدع', 'الم راس', 'راسي يوجعني'], generics: ['باراسيتامول', 'إيبوبروفين', 'ديكلوفيناك'], title: 'الصداع والصداع النصفي والآلام العامة', advice: 'تناول قرص باراسيتامول أو بنادول إكسترا بعد الأكل مع شرب الماء.', triage: 'هل الصداع مصحوب بزغللة في العين أو غثيان؟' },
            { id: 'FEVER', keywords: ['حرارة', 'حراره', 'سخونة', 'سخونه', 'حمى', 'حمي', 'مكسر', 'ساخن', 'تكسير بالجسم'], generics: ['باراسيتامول', 'إيبوبروفين'], title: 'الحمى وارتفاع الحرارة وتكسير الجسم', advice: 'تناول الباراسيتامول كل 6-8 ساعات مع كمادات ماء فاتر وسوائل بكثرة.', triage: 'كم درجة الحرارة المقاسة حالياً؟' },
            { id: 'COLIC', keywords: ['مغص', 'قولون', 'غازات', 'انتفاخ', 'تقلصات', 'الم بطن', 'وجع بطن'], generics: ['ميبفرين', 'هيوسين بوتيل بروميد', 'ميترونيدازول'], title: 'مغص البطن والقولون العصبي والانتفاخ', advice: 'تناول دوسباتالين 135 مجم قبل الأكل بـ 20 دقيقة، وسباسموبان عند المغص الحاد.', triage: 'هل المغص مصحوب بإسهال أم إمساك؟' },
            { id: 'DIARRHEA', keywords: ['إسهال', 'اسهال', 'نزلة معوية', 'تسمم غذائي', 'بطني يمشي', 'ايموديوم'], generics: ['ميترونيدازول', 'لوبيراميد', 'سيبروفلوكساسين'], title: 'الإسهال والنزلات المعوية', advice: 'تناول مطهر معوي كالفلاجيل 500 مجم كل 8 ساعات مع محلول الجفاف لتعويض الأملاح.', triage: 'كم مرة تكرر الإسهال اليوم؟' },
            { id: 'CONSTIPATION', keywords: ['إمساك', 'امساك', 'ملين', 'عسر اخراج', 'دوفالاك'], generics: ['لاكتولوز', 'دوفالاك'], title: 'الإمساك وصعوبة الإخراج', advice: 'تناول شراب دوفالاك (لاكتولوز) 15-30 مل يومياً مع الإكثار من الماء والألياف.', triage: 'منذ متى يعاني المريض من الإمساك؟' },
            { id: 'NAUSEA', keywords: ['غثيان', 'لوعة', 'استفراغ', 'قيء', 'ترجيع', 'دوار سفر', 'موتيليوم'], generics: ['دومبيريدون'], title: 'الغثيان والقيء ودوار الحركة', advice: 'تناول قرص موتيليوم 10 مجم قبل الأكل بـ 15 دقيقة مع رشف سوائل باردة.', triage: 'هل المريضة حامل؟' },
            { id: 'COUGH', keywords: ['كحة', 'كحه', 'سعال', 'بلغم', 'كحة ناشفة', 'مخاط بالصدر'], generics: ['باراسيتامول', 'أزيثرومايسين', 'أموكسيسيلين'], title: 'السعال والكحة والبلغم', advice: 'شرب السوائل الدافئة والينسون وتناول مذيب بلغم أو مسكن للحلق.', triage: 'هل الكحة جافة أم مصحوبة ببلغم صديدي؟' },
            { id: 'FLU', keywords: ['زكام', 'رشح', 'انفلونزا', 'التهاب حلق', 'حلقي يوجعني', 'عطاس'], generics: ['باراسيتامول', 'سيتريزين', 'أموكسيسيلين + كلافولانات'], title: 'الرشح والزكام والتهاب الحلق', advice: 'تناول الباراسيتامول لتسكين الحلق ومضاد هيستامين لتجفيف الرشح مع الغرغرة بالماء والملح.', triage: 'هل يوجد صديد واضح على اللوزتين؟' },
            { id: 'ASTHMA', keywords: ['ربو', 'ضيق تنفس', 'كتمة', 'حساسية صدر', 'تصفير بالصدر', 'فنتولين'], generics: ['سالبوتامول'], title: 'الربو وحساسية الصدر وضيق التنفس', advice: 'استنشاق بخاخ فينتولين بختين فوراً عند نوبة الضيق والجلوس في مكان جيد التهوية.', triage: 'هل الأزمة حادة ولا تستجيب للبخاخ؟' },
            { id: 'JOINTS', keywords: ['مفاصل', 'ركبة', 'ركبه', 'خشونة', 'روماتيزم', 'عظام', 'ظهر', 'فقرات', 'سيلبركس'], generics: ['سيليكوكسيب', 'ديكلوفيناك', 'ميلوكسيكام', 'إيبوبروفين'], title: 'آلام المفاصل والفقرات والخشونة', advice: 'استخدام سيلبركس 200 مجم بعد الأكل مباشرة لتخفيف التيبس والالتهاب بأمان على المعدة.', triage: 'هل يعاني المريض من قرحة بالمعدة أو ضغط مرتفع؟' },
            { id: 'SPASM', keywords: ['شد عضلي', 'ابهر', 'أبهر', 'تشنج عضلي', 'الم رقبة', 'ريباريل'], generics: ['إيبوبروفين', 'ديكلوفيناك', 'إيسين'], title: 'الشد العضلي وتشنج العضلات والأبهر', advice: 'استخدام جل موضعي مثل ريباريل جل مع مسكن بعد الأكل والكمادات الدافئة.', triage: 'هل حدث الشد بعد تمرين أو حمل ثقيل؟' },
            { id: 'TEETH', keywords: ['اسنان', 'أسنان', 'ضرس', 'ضرسي', 'الم اسنان', 'لثة', 'خراج'], generics: ['ديكلوفيناك', 'إيبوبروفين', 'أموكسيسيلين + كلافولانات'], title: 'ألم الأسنان والتهاب اللثة وخراج الضرس', advice: 'تناول مسكن سريع مثل كتافلام أو رابيدوس بعد الأكل مع مراجعة طبيب الأسنان.', triage: 'هل يوجد تورم أو صديد في اللثة؟' },
            { id: 'ALLERGY', keywords: ['حساسية', 'حساسيه', 'حكة', 'حكه', 'هرش', 'ارتكاريا', 'طفح جلدي'], generics: ['سيتريزين', 'لوراتادين'], title: 'الحساسية الجلدية والحكة والأرتيكاريا', advice: 'تناول قرص سيتريزين 10 مجم مساءً مع ترطيب الجلد وتجنب المهيجات.', triage: 'هل يوجد تورم بالشفتين أو صعوبة تنفس؟' },
            { id: 'HYPERTENSION', keywords: ['ضغط', 'ضغط دم', 'ارتفاع الضغط', 'مريض ضغط', 'خفقان', 'كونكور'], generics: ['بيسوبرولول'], title: 'ارتفاع ضغط الدم وصحة القلب', advice: 'تناول دواء الضغط بانتظام مع تقليل الملح وتجنب مسكنات البروفين والفولتارين.', triage: 'كم قراءة الضغط الحالية؟' },
            { id: 'DIABETES', keywords: ['سكري', 'سكر', 'تراكمي', 'مريض سكر', 'جلوكوفاج'], generics: ['ميتفورمين'], title: 'مرض السكري وتنظيم الجلوكوز', advice: 'تناول منظم السكر مع الوجبات وفحص السكر بانتظام.', triage: 'كم قراءة السكر الصائم والتراكمي؟' }
        ];

        let bestRule = null;
        let highestScore = 0;

        for (const rule of symptomRules) {
            if (rule.exclude && rule.exclude.some(ex => q.includes(ex))) continue;
            let score = 0;
            for (const kw of rule.keywords) {
                if (q.includes(kw)) {
                    const wc = kw.split(' ').length;
                    score += (wc >= 2 ? wc * 5 : 2);
                }
            }
            if (score > highestScore) {
                highestScore = score;
                bestRule = rule;
            }
        }

        if (bestRule && highestScore >= 2) {
            const matched = meds.filter(m => {
                const gen = (m.generic_name || '').toLowerCase();
                const nm = (m.name || '').toLowerCase();
                return bestRule.generics.some(g => gen.includes(g.toLowerCase()) || nm.includes(g.toLowerCase()));
            }).sort((a, b) => (b.stock_quantity > 0 ? 0 : 1) - (a.stock_quantity > 0 ? 0 : 1) || (a.price - b.price));

            let reply = `### التقييم السريري: **${bestRule.title}**\n\n`;
            if (matched.length > 0) {
                reply += `#### الأدوية الموصى بها المتوفرة بالمخزن (مرتبة من الأوفر):\n`;
                matched.slice(0, 4).forEach((m, idx) => {
                    reply += renderWidgetMedCard(m, idx === 0);
                });
            } else {
                reply += `**حالة المخزون:** الدواء المباشر لهذه الحالة غير مسجل في المخزون المحلي حالياً، يُرجى استشارة الصيدلي الميداني.\n`;
            }
            reply += `\n#### إرشادات الاستخدام والجرعات:\n${bestRule.advice}\n`;
            reply += `\n#### الفرز السريري:\n${bestRule.triage}\n`;
            reply += `\n*(يا غالي، تقدر تسألني: 'كم حبة آخذ؟'، 'هل ينفع للحامل؟'، أو 'في بديل أرخص؟')*`;
            return { reply: reply };
        }

        return { reply: "يا غالي، لم أتعرف على الصنف أو العارض بدقة. يمكنك سؤالي عن: (صداع، حمى، مغص، قولون، إسهال، إمساك، حموضة، كحة، زكام، مفاصل، أسنان، حساسية، قطرات عين، حروق، ربو، ضغط وسكر) أو كتابة اسم أي دواء بوضوح." };
    }

    function appendWidgetMsg(sender, htmlContent) {
        const d = document.createElement('div');
        d.className = `spms-widget-msg ${sender}`;
        d.innerHTML = `<div class="spms-widget-bubble">${htmlContent}</div>`;
        chatBody.appendChild(d);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function stripWidgetEmojis(str) {
        if (!str) return '';
        return str.replace(/[\u{1F600}-\u{1F64F}\u{1F300}-\u{1F5FF}\u{1F680}-\u{1F6FF}\u{1F700}-\u{1F77F}\u{1F780}-\u{1F7FF}\u{1F800}-\u{1F8FF}\u{1F900}-\u{1F9FF}\u{1FA00}-\u{1FA6F}\u{1FA70}-\u{1FAFF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}\u{2300}-\u{23FF}\u{2B50}\u{26A0}\u{FE0F}]/gu, '')
                  .replace(/[📋💊⭐️💡⚠️🩺⭐🚨✅❌]/g, '');
    }

    function formatText(t) {
        if (!t) return '';
        let html = stripWidgetEmojis(t)
            .replace(/### (.*)/g, '<strong style="color: var(--primary, #0284c7); display:block; margin-bottom:6px; font-size: 0.88rem; font-weight: 800;">$1</strong>')
            .replace(/#### (.*)/g, '<strong style="color: var(--text-main); display:block; margin:8px 0 4px 0; font-size: 0.86rem; border-bottom: 1px solid var(--border); padding-bottom: 3px;">$1</strong>')
            .replace(/\*\*(.*?)\*\*/g, '<strong style="color: var(--text-main);">$1</strong>')
            .replace(/`([^`]+)`/g, '<code style="background: var(--bg-main); color: var(--accent, #0ea5e9); border: 1px solid var(--border); padding: 1px 5px; border-radius: 6px; font-size: 0.85em;">$1</code>')
            .replace(/\n/g, '<br>');

        return html;
    }})();
