
        const urlParams = new URLSearchParams(window.location.search);
        const editId = urlParams.get('id');
        const actionParam = urlParams.get('action');
        const medNameParam = urlParams.get('med') || urlParams.get('name');
        let registeredMedicines = [];

        document.addEventListener('DOMContentLoaded', async () => {
            if (!AppAuth.requireAuth(['supervisor', 'staff'])) return;

            // استرجاع قائمة الأدوية المسجلة مسبقاً لمطابقة الأرصدة
            try {
                const res = await APIClient.request('medicines.php');
                if (res && res.success && Array.isArray(res.data)) {
                    registeredMedicines = res.data;
                }
            } catch(e) {
                registeredMedicines = (typeof DEMO_MEDICINES !== 'undefined') ? DEMO_MEDICINES : [];
            }

            populateFormularyDropdown();
            populateSuppliersDropdown();

            if (editId) {
                if (actionParam === 'restock') {
                    document.getElementById('formTitle').textContent = `توريد دفعة مخزون جديدة للصنف #${editId}`;
                    const btn = document.getElementById('saveBtn');
                    if (btn) btn.textContent = 'اعتماد التوريد وزيادة المخزون الفعلي وإصدار الفاتورة';
                } else {
                    document.getElementById('formTitle').textContent = `تعديل رصيد وسعر الصنف #${editId}`;
                }
                loadEditMedicineData(editId);
            } else if (medNameParam) {
                autoSelectByMedicineName(medNameParam);
            }
        });

        function populateSuppliersDropdown() {
            const select = document.getElementById('medSupplier');
            if (!select) return;
            const list = (window.getSuppliersList ? window.getSuppliersList() : []) || [];
            if (list.length > 0) {
                select.innerHTML = list.map(s => `<option value="${s.id}">${s.name} (${s.address || 'مورد معتمد'})</option>`).join('');
            } else {
                select.innerHTML = `
                    <option value="1">الشركة الوطنية للتموين الطبي (الرياض)</option>
                    <option value="2">مستودعات الخليج للأدوية (جدة)</option>
                    <option value="3">سقالة للرعاية الصحية المتقدمة</option>
                `;
            }
        }

        function populateFormularyDropdown() {
            const select = document.getElementById('selectFromFormulary');
            if (!select) return;
            select.innerHTML = '<option value="">-- اختر الدواء بالاسم التجاري أو العلمي من المخزون --</option>';

            let list = [];
            if (typeof MASTER_FORMULARY !== 'undefined' && Array.isArray(MASTER_FORMULARY) && MASTER_FORMULARY.length > 0) {
                list = MASTER_FORMULARY;
            } else if (typeof DEMO_MEDICINES !== 'undefined' && Array.isArray(DEMO_MEDICINES) && DEMO_MEDICINES.length > 0) {
                list = DEMO_MEDICINES;
            }

            list.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id;
                const regMed = registeredMedicines.find(m => m.name === d.name || m.id === d.id);
                const stockInfo = regMed ? ` [الرصيد الحالي: ${regMed.stock_quantity} علبة]` : '';
                opt.textContent = `${d.name} -- [${d.generic_name || d.generic_name_ar || ''}] (${d.category || 'عام'})${stockInfo}`;
                select.appendChild(opt);
            });
        }

        function onFormularyDrugSelected(drugId) {
            if (!drugId) {
                document.getElementById('medName').value = '';
                document.getElementById('medGeneric').value = '';
                document.getElementById('medCategory').value = '';
                document.getElementById('selectedDrugInfoBanner').style.display = 'none';
                return;
            }

            let list = (typeof MASTER_FORMULARY !== 'undefined' && Array.isArray(MASTER_FORMULARY)) ? MASTER_FORMULARY : ((typeof DEMO_MEDICINES !== 'undefined') ? DEMO_MEDICINES : []);
            const drug = list.find(d => String(d.id) === String(drugId));
            if (!drug) return;

            document.getElementById('medName').value = drug.name;
            document.getElementById('medGeneric').value = drug.generic_name || drug.generic_name_ar || '';
            document.getElementById('medCategory').value = drug.category || '';
            if (drug.price) {
                document.getElementById('medPrice').value = parseFloat(drug.price).toFixed(2);
            }

            const regMed = registeredMedicines.find(m => m.name === drug.name || String(m.id) === String(drug.id));
            const banner = document.getElementById('selectedDrugInfoBanner');
            banner.style.display = 'block';
            let stockHtml = '';
            if (regMed) {
                stockHtml = `<span style="display: block; margin-top: 0.35rem; color: var(--primary); font-weight: 800;">الرصيد الفعلي الحالي بالمستودع: ${regMed.stock_quantity} علبة. إضافة دفعة ستزيد هذا الرصيد مباشرة وتصدر فاتورة توريد.</span>`;
            } else {
                stockHtml = `<span style="display: block; margin-top: 0.35rem; color: var(--success); font-weight: 700;">صنف جديد سيتم تسجيله لأول مرة في الصيدلية مع إيداع الكمية المدخلة وإصدار فاتورة مشتريات.</span>`;
            }

            banner.innerHTML = `
                <div style="background: rgba(2, 132, 199, 0.08); border: 1px solid var(--border); border-radius: var(--radius); padding: 0.75rem;">
                    <div><strong>دواعي الاستعمال:</strong> ${drug.indications || 'علاج سريري معتمد وموثق'}</div>
                    ${stockHtml}
                </div>
            `;
        }

        async function loadEditMedicineData(id) {
            try {
                let med = null;
                const res = await APIClient.request(`medicines.php?id=${id}`);
                if (res && res.success && res.data) {
                    med = res.data;
                } else {
                    med = registeredMedicines.find(m => String(m.id) === String(id));
                }

                if (!med) return;

                document.getElementById('medName').value = med.name || '';
                document.getElementById('medGeneric').value = med.generic_name || '';
                document.getElementById('medCategory').value = med.category || '';
                document.getElementById('medPrice').value = parseFloat(med.price || 25).toFixed(2);
                document.getElementById('medStock').value = actionParam === 'restock' ? '50' : (med.stock_quantity || '50');
                if (med.location) document.getElementById('medLocation').value = med.location;
                if (med.expiry_date) document.getElementById('medExpiry').value = String(med.expiry_date).substring(0, 10);

                // تحديد العنصر في القائمة المنسدلة
                const select = document.getElementById('selectFromFormulary');
                if (select) {
                    for (let opt of select.options) {
                        if (opt.text.includes(med.name)) {
                            select.value = opt.value;
                            break;
                        }
                    }
                }

                const banner = document.getElementById('selectedDrugInfoBanner');
                banner.style.display = 'block';
                banner.innerHTML = `
                    <div style="background: rgba(2, 132, 199, 0.08); border: 1px solid var(--border); border-radius: var(--radius); padding: 0.75rem;">
                        <span style="color: var(--primary); font-weight: 800;">الرصيد الفعلي الحالي للصنف #${med.id} هو ${med.stock_quantity} علبة.</span>
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">إدخال كمية والتأكيد سيقوم بزيادة الرصيد الفعلي في قاعدة البيانات وإصدار فاتورة توريد جديدة رسمية.</div>
                    </div>
                `;
            } catch (e) {}
        }

        function autoSelectByMedicineName(name) {
            const select = document.getElementById('selectFromFormulary');
            if (!select) return;
            for (let opt of select.options) {
                if (opt.text.toLowerCase().includes(name.toLowerCase())) {
                    select.value = opt.value;
                    onFormularyDrugSelected(opt.value);
                    break;
                }
            }
        }

        async function handleSavePharmacyMedicine(e) {
            e.preventDefault();
            const medName = document.getElementById('medName').value.trim();
            const medGeneric = document.getElementById('medGeneric').value.trim();
            const medCategory = document.getElementById('medCategory').value.trim();
            const stock = parseInt(document.getElementById('medStock').value) || 0;
            const price = parseFloat(document.getElementById('medPrice').value) || 25.0;
            const expiry = document.getElementById('medExpiry').value;
            const location = document.getElementById('medLocation').value.trim();
            const supplierSelect = document.getElementById('medSupplier');
            const supplierName = supplierSelect ? supplierSelect.options[supplierSelect.selectedIndex]?.text.split('(')[0].trim() : 'الشركة الوطنية للتموين الطبي';

            if (!medName) {
                showNotification('يرجى اختيار الدواء من دليل المخزون أولاً.', 'warning');
                return;
            }

            if (stock <= 0) {
                showNotification('يرجى تحديد كمية أكبر من صفر للتوريد.', 'warning');
                return;
            }

            const btn = document.getElementById('saveBtn');
            const originalBtnText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = 'جاري حفظ التوريد وزيادة المخزون الفعلي...';

            try {
                // التحقق هل الصنف مسجل مسبقاً في قاعدة البيانات
                let existing = registeredMedicines.find(m => m.name === medName || (editId && String(m.id) === String(editId)));

                let res = null;
                if (existing) {
                    // توريد وزيادة رصيد المخزون الفعلي وإصدار فاتورة التوريد
                    res = await APIClient.request('medicines.php?action=restock', 'POST', {
                        medicine_id: existing.id,
                        medicine_name: medName,
                        quantity: stock,
                        unit_price: price,
                        supplier_name: supplierName
                    });
                } else {
                    // إضافة صنف جديد مع توريده الأولي وإصدار فاتورة التوريد
                    res = await APIClient.request('medicines.php', 'POST', {
                        name: medName,
                        generic_name: medGeneric,
                        category: medCategory,
                        price: price,
                        stock_quantity: stock,
                        expiry_date: expiry,
                        location: location,
                        supplier_name: supplierName
                    });
                }

                window.dispatchEvent(new Event('spms_stock_updated'));

                const invNumber = res?.data?.invoice?.invoice_number || ('#RESTOCK-' + Date.now().toString().slice(-4));
                const msg = document.getElementById('formMsg');
                msg.innerHTML = `
                    <div class="badge badge-success" style="padding: 12px; font-size: 0.92rem; line-height: 1.6; border-radius: var(--radius);">
                        تم بنجاح توريد وزيادة رصيد الصنف [${medName}] بمقدار (${stock} علبة) وسعر (${price.toFixed(2)} ريال).<br>
                        تم توليد فاتورة توريد رسمية برقم [<strong>${invNumber}</strong>]. جاري توجيهك لقسم الأدوية...
                    </div>
                `;

                showNotification(`تم زيادة رصيد [${medName}] بمقدار ${stock} علبة وتوليد فاتورة التوريد بنجاح!`, 'success');

                setTimeout(() => {
                    window.location.href = 'medicines.html';
                }, 1400);

            } catch (err) {
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
                showNotification('حدث خطأ أثناء حفظ التوريد، يرجى المحاولة ثانية.', 'danger');
            }
        }
    