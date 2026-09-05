const MASTER_FORMULARY = [
    {
        "id": 1,
        "name": "Losec / Gasec (أوميبرازول)",
        "generic_name": "Omeprazole / أوميبرازول",
        "category": "مثبط مضخة البروتون (PPI)",
        "indications": "علاج حموضة المعدة، الارتجاع المريئي، وقرحة المعدة. يؤخذ قبل الإفطار.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 2,
        "name": "Nexium (إيزوميبرازول)",
        "generic_name": "Esomeprazole / إيزوميبرازول",
        "category": "مثبط مضخة البروتون (PPI)",
        "indications": "علاج الارتجاع المعدي المريئي والوقاية من قرحة المعدة الناتجة عن المسكنات.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 3,
        "name": "Controloc / Protonix (بانتوبرازول)",
        "generic_name": "Pantoprazole / بانتوبرازول",
        "category": "مثبط مضخة البروتون (PPI)",
        "indications": "علاج التهاب المريء التآكلي وقرحة المعدة والاثني عشر.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 4,
        "name": "Takepron / Prevacid (لانسوبرازول)",
        "generic_name": "Lansoprazole / لانسوبرازول",
        "category": "مثبط مضخة البروتون (PPI)",
        "indications": "علاج قرحة المعدة وارتجاع المريء وجرثومة المعدة مع المضادات.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 5,
        "name": "Pepcid / Famodar (فاموتيدين)",
        "generic_name": "Famotidine / فاموتيدين",
        "category": "مضاد مستقبلات H2",
        "indications": "تقليل إفراز حمض المعدة وتخفيف الحرقة وسوء الهضم.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 6,
        "name": "Imodium (لوبيراميد)",
        "generic_name": "Loperamide / لوبيراميد",
        "category": "مضاد للإسهال",
        "indications": "السيطرة على الإسهال الحاد غير البكتيري وعلاج الإسهال المزمن.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 7,
        "name": "Buscopan (هيوسين بوتيل بروميد)",
        "generic_name": "Hyoscine Butylbromide / هيوسين بوتيل بروميد",
        "category": "مضاد للتقلصات والمغص",
        "indications": "تسكين تقلصات البطن ومغص الجهاز الهضمي والمسالك البولية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 8,
        "name": "Duspatalin / Colospasmin (ميبفرين)",
        "generic_name": "Mebeverine / ميبفرين",
        "category": "مضاد لتشنجات القولون",
        "indications": "تخفيف أعراض متلازمة القولون العصبي والانتفاخ والتقلصات.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 9,
        "name": "Motilium / Motilat (دومبيريدون)",
        "generic_name": "Domperidone / دومبيريدون",
        "category": "منظم لحركة المعدة ومضاد للغثيان",
        "indications": "علاج الغثيان والقيء والشعور بالامتلاء وثقل المعدة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 10,
        "name": "Plasil / Primperan (ميتوكلوبراميد)",
        "generic_name": "Metoclopramide / ميتوكلوبراميد",
        "category": "مضاد للقيء ومنشط للحركة",
        "indications": "علاج الغثيان والقيء وتسريع تفريغ المعدة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 11,
        "name": "Disflatyl / Gas-X (سيميثيكون)",
        "generic_name": "Simethicone / سيميثيكون",
        "category": "طارد للغازات",
        "indications": "تخفيف الانتفاخ والشعور بالضغط والغازات في الأمعاء.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 12,
        "name": "Duphalac (لاكتولوز)",
        "generic_name": "Lactulose / لاكتولوز",
        "category": "ملين أسموزي",
        "indications": "علاج الإمساك المزمن والوقاية من الاعتلال الدماغي الكبدي.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 13,
        "name": "Dulcolax (بيساكوديل)",
        "generic_name": "Bisacodyl / بيساكوديل",
        "category": "ملين منبه للأمعاء",
        "indications": "تفريغ الأمعاء لعلاج الإمساك المؤقت أو قبل الفحوصات الطبية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 14,
        "name": "Ursofalk (حمض الأورسوديوكسيكوليك)",
        "generic_name": "Ursodeoxycholic Acid / حمض الأورسوديوكسيكوليك",
        "category": "مذيب لحصوات المرارة",
        "indications": "إذابة حصوات المرارة الكولسترولية وعلاج أمراض الكبد الصفراوية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 15,
        "name": "Pentasa / Asacol (ميسالازين)",
        "generic_name": "Mesalazine / ميسالازين",
        "category": "مضاد للالتهاب المعوي",
        "indications": "علاج والسيطرة على التهاب القولون التقرحي وداء كرون.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 16,
        "name": "Panadol / Fevadol (باراسيتامول)",
        "generic_name": "Paracetamol / باراسيتامول",
        "category": "مسكن للألم وخافض حرارة",
        "indications": "تسكين الآلام البسيطة إلى المتوسطة مثل الصداع وخفض الحرارة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 17,
        "name": "Brufen / Advil (إيبوبروفين)",
        "generic_name": "Ibuprofen / إيبوبروفين",
        "category": "مضاد التهاب غير ستيرويدي (NSAID)",
        "indications": "تسكين آلام الأسنان، المفاصل، الصداع، وتخفيض الحرارة والالتهاب.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 18,
        "name": "Voltaren (ديكلوفيناك الصوديوم)",
        "generic_name": "Diclofenac Sodium / ديكلوفيناك الصوديوم",
        "category": "مضاد التهاب غير ستيرويدي (NSAID)",
        "indications": "علاج التهابات المفاصل الروماتيزمية وآلام العظام الحادة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 19,
        "name": "Cataflam / Rapidus (ديكلوفيناك البوتاسيوم)",
        "generic_name": "Diclofenac Potassium / ديكلوفيناك البوتاسيوم",
        "category": "مسكن سريع ومضاد التهاب",
        "indications": "تسكين سريع لآلام الأسنان، الصداع النصفي، وآلام الدورة الشهرية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 20,
        "name": "Proxen / Aleve (نابروكسين)",
        "generic_name": "Naproxen / نابروكسين",
        "category": "مضاد التهاب غير ستيرويدي (NSAID)",
        "indications": "تسكين طويل المفعول لآلام المفاصل، النقرس، والآلام العضلية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 21,
        "name": "Celebrex (سيليكوكسيب)",
        "generic_name": "Celecoxib / سيليكوكسيب",
        "category": "مثبط انتقائي لـ COX-2",
        "indications": "تسكين التهاب المفاصل العظمي والروماتويدي مع تأثير أقل على المعدة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 22,
        "name": "Mobic (ميلوكسيكام)",
        "generic_name": "Meloxicam / ميلوكسيكام",
        "category": "مضاد التهاب غير ستيرويدي (NSAID)",
        "indications": "علاج آلام وتيبس المفاصل في التهاب المفاصل الروماتويدي.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 23,
        "name": "Ketofan / Profenid (كيتوبروفين)",
        "generic_name": "Ketoprofen / كيتوبروفين",
        "category": "مسكن ومضاد التهاب",
        "indications": "علاج التهاب المفاصل الحاد والآلام المتوسطة إلى الشديدة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 24,
        "name": "Ponstan (حمض الميفيناميك)",
        "generic_name": "Mefenamic Acid / حمض الميفيناميك",
        "category": "مسكن ومضاد للالتهاب",
        "indications": "تسكين آلام الدورة الشهرية وآلام الأسنان والالتهابات الخفيفة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 25,
        "name": "Tramal (ترامادول)",
        "generic_name": "Tramadol / ترامادول",
        "category": "مسكن أفيوني مركزي (خاضع للرقابة)",
        "indications": "تسكين الآلام الشديدة والمتوسطة تحت إشراف طبي دقيق.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 26,
        "name": "Colchicine (كولشيسين)",
        "generic_name": "Colchicine / كولشيسين",
        "category": "مضاد لنوبات النقرس",
        "indications": "علاج ومنع نوبات النقرس الحادة وحمى البحر الأبيض المتوسط.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 27,
        "name": "Zyloric / No-Uric (ألوبيورينول)",
        "generic_name": "Allopurinol / ألوبيورينول",
        "category": "خافض لحمض اليوريك",
        "indications": "الوقاية من نوبات النقرس المزمنة وتكون حصوات الكلى اليوراتية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 28,
        "name": "Adenuric / Feburic (فيبوكسوستات)",
        "generic_name": "Febuxostat / فيبوكسوستات",
        "category": "مثبط إنزيم زانثين أوكسيديز",
        "indications": "علاج فرط حمض اليوريك في الدم لمرضى النقرس المزمن.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 29,
        "name": "Lioresal (باكلوفين)",
        "generic_name": "Baclofen / باكلوفين",
        "category": "مرخي عضلات مركزي",
        "indications": "علاج التشنجات والشد العضلي الناتج عن التصلب اللويحي أو إصابات الحبل الشوكي.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 30,
        "name": "Parafon / Myogesic (كلورزوكسازون)",
        "generic_name": "Chlorzoxazone / كلورزوكسازون",
        "category": "مرخي عضلات هيكلية",
        "indications": "علاج التقلصات العضلية المؤلمة وآلام أسفل الظهر.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 31,
        "name": "Norvasc / Amlor (أملوديبين)",
        "generic_name": "Amlodipine / أملوديبين",
        "category": "حاصر قنوات الكالسيوم",
        "indications": "علاج ارتفاع ضغط الدم والوقاية من نوبات الذبحة الصدرية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 32,
        "name": "Concor (بيسوبرولول)",
        "generic_name": "Bisoprolol / بيسوبرولول",
        "category": "حاصر مستقبلات بيتا الانتقائي",
        "indications": "علاج ارتفاع ضغط الدم، قصور القلب المزمن، وتنظيم ضربات القلب.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 33,
        "name": "Tenormin (أتينولول)",
        "generic_name": "Atenolol / أتينولول",
        "category": "حاصر مستقبلات بيتا",
        "indications": "خفض ضغط الدم المرتفع وتنظيم عدم انتظام ضربات القلب.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 34,
        "name": "Betaloc / Lopressor (ميتوبرولول)",
        "generic_name": "Metoprolol / ميتوبرولول",
        "category": "حاصر مستقبلات بيتا",
        "indications": "السيطرة على ضغط الدم والذبحة الصدرية والوقاية بعد الجلطات.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 35,
        "name": "Cozaar (لوسارتان)",
        "generic_name": "Losartan / لوسارتان",
        "category": "مضاد مستقبلات الأنجيوتنسين 2 (ARB)",
        "indications": "علاج ارتفاع ضغط الدم وحماية الكلى لمرضى السكري.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 36,
        "name": "Diovan / Tareg (فالسارتان)",
        "generic_name": "Valsartan / فالسارتان",
        "category": "مضاد مستقبلات الأنجيوتنسين 2 (ARB)",
        "indications": "علاج ضغط الدم المرتفع وقصور القلب وتخفيف العبء على عضلة القلب.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 37,
        "name": "Atacand (كانديسارتان)",
        "generic_name": "Candesartan / كانديسارتان",
        "category": "مضاد مستقبلات الأنجيوتنسين 2 (ARB)",
        "indications": "خفض ضغط الدم المرتفع وعلاج قصور عضلة القلب.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 38,
        "name": "Renitec / Ezapril (إنالابريل)",
        "generic_name": "Enalapril / إنالابريل",
        "category": "مثبط الإنزيم المحول للأنجيوتنسين (ACEI)",
        "indications": "علاج ضغط الدم المرتفع وحالات فشل القلب الاحتقاني.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 39,
        "name": "Zestril (ليسينوبريل)",
        "generic_name": "Lisinopril / ليسينوبريل",
        "category": "مثبط الإنزيم المحول للأنجيوتنسين (ACEI)",
        "indications": "علاج ارتفاع ضغط الدم وفشل القلب وتحسين البقاء بعد النوبات القلبية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 40,
        "name": "Tritace (راميبريل)",
        "generic_name": "Ramipril / راميبريل",
        "category": "مثبط الإنزيم المحول للأنجيوتنسين (ACEI)",
        "indications": "السيطرة على ضغط الدم والوقاية من المضاعفات القلبية الوعائية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 41,
        "name": "Esidrex (هيدروكلوروثيازيد)",
        "generic_name": "Hydrochlorothiazide / هيدروكلوروثيازيد",
        "category": "مدر بول ثيازيدي",
        "indications": "خفض ضغط الدم وتخليص الجسم من السوائل الزائدة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 42,
        "name": "Lasix (فوروسيميد)",
        "generic_name": "Furosemide / فوروسيميد",
        "category": "مدر بول عروي قوي",
        "indications": "علاج تجمع السوائل (الوذمة) المرتبطة بقصور القلب أو الكلى والكبد.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 43,
        "name": "Aldactone (سبيرونولاكتون)",
        "generic_name": "Spironolactone / سبيرونولاكتون",
        "category": "مدر بول حافظ للبوتاسيوم",
        "indications": "علاج ارتفاع ضغط الدم، قصور القلب، وتراكم السوائل وفرط الألدوستيرون.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 44,
        "name": "Lipitor / Atorlip (أتورفاستاتين)",
        "generic_name": "Atorvastatin / أتورفاستاتين",
        "category": "خافض للدهون (ستاتين)",
        "indications": "تقليل الكوليسترول الضار والدهون الثلاثية والوقاية من الجلطات.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 45,
        "name": "Crestor (روزوڤاستاتين)",
        "generic_name": "Rosuvastatin / روزوڤاستاتين",
        "category": "خافض للدهون (ستاتين)",
        "indications": "علاج فرط كوليسترول الدم ورفع الكوليسترول النافع (HDL).",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 46,
        "name": "Zocor (سيمفاستاتين)",
        "generic_name": "Simvastatin / سيمفاستاتين",
        "category": "خافض للدهون (ستاتين)",
        "indications": "تقليل مستويات الكوليسترول الكلي والضار في مجرى الدم.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 47,
        "name": "Lipanthyl (فينوفيبرات)",
        "generic_name": "Fenofibrate / فينوفيبرات",
        "category": "خافض للدهون (فيبرات)",
        "indications": "خفض المستويات المرتفعة جداً من الدهون الثلاثية (Triglycerides).",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 48,
        "name": "Plavix (كلوبيدوجريل)",
        "generic_name": "Clopidogrel / كلوبيدوجريل",
        "category": "مضاد لتكدس الصفائح الدموية",
        "indications": "منع تكون الجلطات الدموية بعد القسطرة أو النوبات القلبية والسكتات.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 49,
        "name": "Aspirin Protect / Aspocid (حمض أسيتيل الساليسيليك)",
        "generic_name": "Aspirin (Low Dose) / حمض أسيتيل الساليسيليك",
        "category": "مانع للتجلط بجرعة منخفضة",
        "indications": "الوقاية الأولية والثانوية من النوبات القلبية والسكتات الدماغية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 50,
        "name": "Coumadin / Marevan (وارفارين)",
        "generic_name": "Warfarin / وارفارين",
        "category": "مضاد لتخثر الدم (فيتامين K)",
        "indications": "الوقاية من الجلطات الوريدية والانسداد الرئوي والرجفان الأذيني.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 51,
        "name": "Glucophage / Cidophage (ميتفورمين)",
        "generic_name": "Metformin / ميتفورمين",
        "category": "منظم سكر (بايجوانيد)",
        "indications": "علاج السكري من النوع الثاني وتحسين حساسية الإنسولين وتكيس المبايض.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 52,
        "name": "Diamicron MR (جليكلازيد)",
        "generic_name": "Gliclazide / جليكلازيد",
        "category": "محفز إفراز الإنسولين (سلفونيل يوريا)",
        "indications": "خفض السكر لمرضى النوع الثاني عبر تحفيز خلايا بيتا في البنكرياس.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 53,
        "name": "Amaryl (جليمبيريد)",
        "generic_name": "Glimepiride / جليمبيريد",
        "category": "محفز إفراز الإنسولين (سلفونيل يوريا)",
        "indications": "السيطرة على مستويات الجلوكوز لمرضى السكري من النوع الثاني.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 54,
        "name": "Januvia (سيتاجليبتين)",
        "generic_name": "Sitagliptin / سيتاجليبتين",
        "category": "مثبط إنزيم DPP-4",
        "indications": "تحسين السيطرة على سكر الدم بعد الوجبات لمرضى السكري.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 55,
        "name": "Galvus (فيلداجليبتين)",
        "generic_name": "Vildagliptin / فيلداجليبتين",
        "category": "مثبط إنزيم DPP-4",
        "indications": "تنظيم مستويات السكر بالدم بتحفيز الإنسولين وتثبيط الجلوكاجون.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 56,
        "name": "Jardiance (إمباجليفلوزين)",
        "generic_name": "Empagliflozin / إمباجليفلوزين",
        "category": "مثبط SGLT2",
        "indications": "طرد السكر الزائد عبر البول وحماية القلب والكلى لمرضى السكري.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 57,
        "name": "Forxiga (داباجليفلوزين)",
        "generic_name": "Dapagliflozin / داباجليفلوزين",
        "category": "مثبط SGLT2",
        "indications": "علاج السكري النوع الثاني وتخفيف قصور القلب وأمراض الكلى المزمنة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 58,
        "name": "Lantus / Toujeo (إنسولين جلارجين)",
        "generic_name": "Insulin Glargine / إنسولين جلارجين",
        "category": "إنسولين طويل المفعول (قاعدي)",
        "indications": "توفير مستوى ثابت من الإنسولين على مدار 24 ساعة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 59,
        "name": "NovoRapid (إنسولين أسبارت)",
        "generic_name": "Insulin Aspart / إنسولين أسبارت",
        "category": "إنسولين سريع المفعول",
        "indications": "تغطية ارتفاع سكر الدم بعد تناول الوجبات مباشرة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 60,
        "name": "Euthyrox / Synthroid (ليفوثيروكسين)",
        "generic_name": "Levothyroxine / ليفوثيروكسين",
        "category": "هرمون الغدة الدرقية البديل",
        "indications": "علاج قصور وخمول الغدة الدرقية. يؤخذ صباحاً على معدة فارغة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 61,
        "name": "Neo-Mercazole (كاربيمازول)",
        "generic_name": "Carbimazole / كاربيمازول",
        "category": "مضاد لإفراز هرمون الدرقية",
        "indications": "علاج فرط نشاط الغدة الدرقية والتسمم الدرقي.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 62,
        "name": "Ventolin (سالبوتامول)",
        "generic_name": "Salbutamol / سالبوتامول",
        "category": "موسع قصبات سريع المفعول",
        "indications": "الإغاثة السريعة لضيق التنفس ونوبات الربو الحادة والانسداد الرئوي.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 63,
        "name": "Symbicort (فورموتيرول وبوديسونيد)",
        "generic_name": "Formoterol + Budesonide / فورموتيرول وبوديسونيد",
        "category": "موسع طويل المفعول مع كورتيزون",
        "indications": "الوقاية والسيطرة اليومية على نوبات الربو والانسداد الرئوي المزمن.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 64,
        "name": "Seretide (فلوتيكازون وسالميتيرول)",
        "generic_name": "Fluticasone + Salmeterol / فلوتيكازون وسالميتيرول",
        "category": "موسع قصبات مع كورتيزون مستنشق",
        "indications": "العلاج الوقائي طويل الأمد للربو والتهاب الشعب الهوائية المزمن.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 65,
        "name": "Spiriva (تيوتروبيوم)",
        "generic_name": "Tiotropium / تيوتروبيوم",
        "category": "موسع قصبات مضاد للكولين",
        "indications": "توسيع الشعب الهوائية لمرضى الانسداد الرئوي المزمن (COPD).",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 66,
        "name": "Singulair / Airfast (مونتيلوكاست)",
        "generic_name": "Montelukast / مونتيلوكاست",
        "category": "مضاد مستقبلات الليكوترين",
        "indications": "الوقاية من أزمات الربو الليلية والتحكم في حساسية الأنف الموسمية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 67,
        "name": "Zyrtec / Finistil (سيتريزين)",
        "generic_name": "Cetirizine / سيتريزين",
        "category": "مضاد هيستامين (جيل ثاني)",
        "indications": "تخفيف أعراض الحساسية وسيلان الأنف وحكة الجلد والأرتيكاريا.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 68,
        "name": "Claritine (لوراتادين)",
        "generic_name": "Loratadine / لوراتادين",
        "category": "مضاد هيستامين غير مسبب للنعاس",
        "indications": "علاج حساسية الأنف، العطس، وحكة العيون والحساسية الجلدية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 69,
        "name": "Aerius (ديسلوراتادين)",
        "generic_name": "Desloratadine / ديسلوراتادين",
        "category": "مضاد هيستامين ممتد المفعول",
        "indications": "تخفيف أعراض التهاب الأنف التحسسي والشرى الجلدي بدون خمول.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 70,
        "name": "Telfast (فيكسوفينادين)",
        "generic_name": "Fexofenadine / فيكسوفينادين",
        "category": "مضاد هيستامين لا يسبب النعاس",
        "indications": "علاج الحساسية الموسمية وحكة الجلد دون التأثير على التركيز.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 71,
        "name": "Histop / Anaplex (كلورفينيرامين)",
        "generic_name": "Chlorpheniramine / كلورفينيرامين",
        "category": "مضاد هيستامين (جيل أول)",
        "indications": "تخفيف أعراض نزلات البرد والحساسية الشديدة (يسبب النعاس).",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 72,
        "name": "Otrivin (زايلوميتازولين)",
        "generic_name": "Xylometazoline / زايلوميتازولين",
        "category": "مزيل لاحتقان الأنف",
        "indications": "تخفيف انسداد واحتقان الأنف (لا يستخدم أكثر من 5 أيام متتالية).",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 73,
        "name": "ACC / Fluimucil (أسيتيل سيستئين)",
        "generic_name": "Acetylcysteine / أسيتيل سيستئين",
        "category": "مذيب وطارد للبلغم",
        "indications": "إذابة الإفرازات المخاطية الكثيفة في الجهاز التنفسي والكحة الرطبة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 74,
        "name": "Mucosolvan (أمبروكسول)",
        "generic_name": "Ambroxol / أمبروكسول",
        "category": "مذيب للمخاط",
        "indications": "تسهيل خروج البلغم وتهدئة الكحة المصحوبة بمخاط.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 75,
        "name": "Amoxil (أموكسيسيلين)",
        "generic_name": "Amoxicillin / أموكسيسيلين",
        "category": "مضاد حيوي بنسليني",
        "indications": "علاج الالتهابات البكتيرية في الحلق، الصدر، الأذن الوسطى، والمسالك.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 76,
        "name": "Augmentin / Curam (أموكسيسيلين وكلافولانيك)",
        "generic_name": "Amoxicillin + Clavulanic Acid / أموكسيسيلين وكلافولانيك",
        "category": "مضاد حيوي واسع المجال مع مثبط بيتا لاكتاماز",
        "indications": "علاج التهابات الجهاز التنفسي الحادة، الجيوب، والتهابات الأسنان.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 77,
        "name": "Zithromax / Azimycin (أزيثروميسين)",
        "generic_name": "Azithromycin / أزيثروميسين",
        "category": "مضاد حيوي ماكروليدي",
        "indications": "علاج التهابات الصدر والحلق والجلد وبعض العدوى التناسلية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 78,
        "name": "Klacid (كلاريثروميسين)",
        "generic_name": "Clarithromycin / كلاريثروميسين",
        "category": "مضاد حيوي ماكروليدي",
        "indications": "علاج التهابات الجهاز التنفسي وقرحة المعدة ضمن الخطة الثلاثية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 79,
        "name": "Cipro / Ciprobay (سيبروفلوكساسين)",
        "generic_name": "Ciprofloxacin / سيبروفلوكساسين",
        "category": "مضاد حيوي فلوروكينولون",
        "indications": "علاج التهابات المسالك البولية، النزلات المعوية، والتهابات العظام.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 80,
        "name": "Tavanic (ليفوفلوكساسين)",
        "generic_name": "Levofloxacin / ليفوفلوكساسين",
        "category": "مضاد حيوي فلوروكينولون",
        "indications": "علاج الالتهاب الرئوي الحاد والتهاب الجيوب والمسالك المعقدة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 81,
        "name": "Rocephin (سيفترياكسون)",
        "generic_name": "Ceftriaxone / سيفترياكسون",
        "category": "مضاد حيوي سيفالوسبورين (جيل ثالث)",
        "indications": "علاج العدوى البكتيرية الشديدة وحالات التسمم الدموي والتهاب السحايا.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 82,
        "name": "Zinnat (سيفوروكسيم)",
        "generic_name": "Cefuroxime / سيفوروكسيم",
        "category": "مضاد حيوي سيفالوسبورين (جيل ثانٍ)",
        "indications": "علاج التهابات الحلق والأذن والمسالك والالتهاب الرئوي الخفيف.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 83,
        "name": "Suprax (سيفيكسيم)",
        "generic_name": "Cefixime / سيفيكسيم",
        "category": "مضاد حيوي سيفالوسبورين (جيل ثالث فموي)",
        "indications": "علاج التهابات المسالك والتهاب الأذن الوسطى واللوزتين.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 84,
        "name": "Vibramycin / Doxymycin (دوكسيسيكلين)",
        "generic_name": "Doxycycline / دوكسيسيكلين",
        "category": "مضاد حيوي تتراسيكلين",
        "indications": "علاج حب الشباب الشديد، عدوى الصدر، والملاريا الوقائية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 85,
        "name": "Flagyl (ميترونيدازول)",
        "generic_name": "Metronidazole / ميترونيدازول",
        "category": "مضاد للطفيليات والبكتيريا اللاهوائية",
        "indications": "علاج الإسهال الطفيلي (الأميبا والجيارديا) والتهابات اللثة والأسنان.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 86,
        "name": "Diflucan (فلوكونازول)",
        "generic_name": "Fluconazole / فلوكونازول",
        "category": "مضاد للفطريات جهازياً",
        "indications": "علاج عدوى المبيضات وفطريات الفم والمهبل والأعضاء التناسلية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 87,
        "name": "Zovirax (أسيكلوفير)",
        "generic_name": "Acyclovir / أسيكلوفير",
        "category": "مضاد للفيروسات",
        "indications": "علاج فيروس الهربس البسيط، الحزام الناري، وجدري الماء.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 88,
        "name": "Cipralex / Lexapro (إسيتالوبرام)",
        "generic_name": "Escitalopram / إسيتالوبرام",
        "category": "مضاد اكتئاب (SSRI)",
        "indications": "علاج الاكتئاب العام، نوبات الهلع، والقلق والتوتر المزمن.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 89,
        "name": "Zoloft / Lustral (سيرترالين)",
        "generic_name": "Sertraline / سيرترالين",
        "category": "مضاد اكتئاب (SSRI)",
        "indications": "علاج الاكتئاب والوسواس القهري (OCD) ونوبات الفزع والصدمة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 90,
        "name": "Prozac (فلوكسيتين)",
        "generic_name": "Fluoxetine / فلوكسيتين",
        "category": "مضاد اكتئاب (SSRI)",
        "indications": "علاج الاكتئاب واضطراب نهم الطعام والوسواس القهري.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 91,
        "name": "Seroxat / Paxil (باروكستين)",
        "generic_name": "Paroxetine / باروكستين",
        "category": "مضاد اكتئاب وقلق (SSRI)",
        "indications": "علاج القلق الاجتماعي ونوبات الهلع الحادة والاكتئاب.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 92,
        "name": "Efexor XR (فينلافاكسين)",
        "generic_name": "Venlafaxine / فينلافاكسين",
        "category": "مضاد اكتئاب وقلق (SNRI)",
        "indications": "علاج نوبات الاكتئاب الشديدة واضطرابات القلق المعمم والرهاب.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 93,
        "name": "Cymbalta (دولوكستين)",
        "generic_name": "Duloxetine / دولوكستين",
        "category": "مضاد اكتئاب ومسكن للألم العصبي (SNRI)",
        "indications": "علاج الاكتئاب، آلام الأعصاب السكرية، وآلام الفيبروميالجيا.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 94,
        "name": "Lyrica (بريجابالين)",
        "generic_name": "Pregabalin / بريجابالين",
        "category": "مضاد لآلام الأعصاب والتشنجات (خاضع للرقابة)",
        "indications": "تسكين ألم الأعصاب والاعتلال العصبي السكري واضطراب القلق العام.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 95,
        "name": "Neurontin / Gaptin (جابابنتين)",
        "generic_name": "Gabapentin / جابابنتين",
        "category": "مضاد لألم الأعصاب والاختلاج",
        "indications": "تخفيف آلام الأعصاب الناتجة عن الحزام الناري والسكري.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 96,
        "name": "Keppra (ليفيتيراسيتام)",
        "generic_name": "Levetiracetam / ليفيتيراسيتام",
        "category": "مضاد للصرع والتشنجات",
        "indications": "السيطرة على نوبات الصرع الجزئية والعامة لدى المرضى.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 97,
        "name": "Depakine Chrono (فالبروات الصوديوم)",
        "generic_name": "Sodium Valproate / فالبروات الصوديوم",
        "category": "مثبت للمزاج ومضاد للصرع",
        "indications": "علاج الصرع واضطراب ثنائي القطب والوقاية من الشقيقة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 98,
        "name": "Tegretol (كاربامازيبين)",
        "generic_name": "Carbamazepine / كاربامازيبين",
        "category": "مضاد للصرع وألم العصب الخامس",
        "indications": "السيطرة على نوبات الصرع وعلاج آلام العصب الخامس الحادة.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 99,
        "name": "Seroquel (كويتيابين)",
        "generic_name": "Quetiapine / كويتيابين",
        "category": "مضاد للذهان غير نمطي",
        "indications": "علاج الفصام واضطراب ثنائي القطب والمساعدة في حالات الاكتئاب المقاوم.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 100,
        "name": "Zyprexa (أولانزابين)",
        "generic_name": "Olanzapine / أولانزابين",
        "category": "مضاد للذهان غير نمطي",
        "indications": "علاج نوبات الهوس الحاد والفصام واضطرابات المزاج.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 101,
        "name": "Strattera (أتوموكسيتين)",
        "generic_name": "Atomoxetine / أتوموكسيتين",
        "category": "علاج اضطراب فرط الحركة (غير منبه)",
        "indications": "تحسين التركيز وعلاج اضطراب نقص الانتباه وفرط الحركة (ADHD).",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 102,
        "name": "Aricept (دونيبيزيل)",
        "generic_name": "Donepezil / دونيبيزيل",
        "category": "مثبط إنزيم أسيتيل كولين إستراز",
        "indications": "إبطاء تدهور الذاكرة وتحسين الوظائف الإدراكية لمرضى الزهايمر.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 103,
        "name": "Vidrop / D-3 Stada (كوليكالسيفيرول (فيتامين د3))",
        "generic_name": "Cholecalciferol / كوليكالسيفيرول (فيتامين د3)",
        "category": "فيتامين د3 أساسي",
        "indications": "علاج نقص فيتامين د وهشاشة العظام وتحسين امتصاص الكالسيوم.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 104,
        "name": "Feroglobin / Fefol (مركبات الحديد)",
        "generic_name": "Ferrous Fumarate / Sulfate / مركبات الحديد",
        "category": "مكمل عنصر الحديد",
        "indications": "علاج والوقاية من فقر الدم (الأنيميا) الناتج عن نقص الحديد.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 105,
        "name": "Folic Acid 5mg (حمض الفوليك (فيتامين ب9))",
        "generic_name": "Folic Acid / حمض الفوليك (فيتامين ب9)",
        "category": "فيتامين أساسي لصنع الدم",
        "indications": "الوقاية من تشوهات الأجنة للحوامل وعلاج بعض أنواع فقر الدم.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 106,
        "name": "Neurobion / Methycobal (فيتامين ب12)",
        "generic_name": "Cyanocobalamin / Methylcobalamin / فيتامين ب12",
        "category": "فيتامين ب12 المغذي للأعصاب",
        "indications": "دعم صحة الأعصاب وتكوين خلايا الدم الحمراء لمرضى السكري وكبار السن.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 107,
        "name": "Caltrate (كالسيوم وفيتامين د)",
        "generic_name": "Calcium Carbonate + Vit D / كالسيوم وفيتامين د",
        "category": "مكمل غذائي للعظام",
        "indications": "تقوية كثافة العظام والوقاية من الهشاشة والكسور لدى كبار السن.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 108,
        "name": "Roaccutane / Curacne (إيزوتريتينوين)",
        "generic_name": "Isotretinoin / إيزوتريتينوين",
        "category": "مشتق ريتينويد قوي (تحت إشراف طبي)",
        "indications": "علاج حب الشباب العقدي الشديد والمستعصي على المضادات.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 109,
        "name": "Betnovate / Diprosone (بيتاميثازون)",
        "generic_name": "Betamethasone / بيتاميثازون",
        "category": "كورتيزون موضعي قوي",
        "indications": "علاج الالتهابات الجلدية الشديدة، الإكزيما، والصدفية.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 110,
        "name": "Locoid / Cortiderm (هيدروكورتيزون)",
        "generic_name": "Hydrocortisone / هيدروكورتيزون",
        "category": "كورتيزون موضعي خفيف",
        "indications": "علاج التحسس الجلدي الخفيف ولدغات الحشرات والتهاب الجلد.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 111,
        "name": "Bactroban (موبيروسين)",
        "generic_name": "Mupirocin / موبيروسين",
        "category": "مضاد حيوي موضعي",
        "indications": "علاج العدوى البكتيرية الجلدية مثل القوباء والدمامل وجروح الجلد.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 112,
        "name": "Fucidin (حمض الفوسيديك)",
        "generic_name": "Fusidic Acid / حمض الفوسيديك",
        "category": "مضاد حيوي موضعي",
        "indications": "علاج الالتهابات الجلدية البكتيرية وحب الشباب والتهاب بصيلات الشعر.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 113,
        "name": "Canesten (كلوتريمازول)",
        "generic_name": "Clotrimazole / كلوتريمازول",
        "category": "مضاد فطريات موضعي",
        "indications": "علاج الفطريات الجلدية مثل تينيا القدم وفطريات الثنايا والمهبل.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 114,
        "name": "Acretin / Retin-A (تريتينوين)",
        "generic_name": "Tretinoin / تريتينوين",
        "category": "ريتينويد موضعي مقشر",
        "indications": "تجديد خلايا البشرة، علاج حب الشباب، وتحسين مظهر الندبات.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 115,
        "name": "Xalatan (لاتانوبروست)",
        "generic_name": "Latanoprost / لاتانوبروست",
        "category": "نظير البروستاغلاندين للعين",
        "indications": "خفض ضغط العين المرتفع لمرضى الجلوكوما (الماء الأزرق).",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 116,
        "name": "Alphagan P (بريمونيدين)",
        "generic_name": "Brimonidine / بريمونيدين",
        "category": "قطرة لضغط العين",
        "indications": "تقليل إنتاج السائل داخل العين وتخفيف ضغط الجلوكوما.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 117,
        "name": "Refresh Tears / Optive (كاربوكسي ميثيل سليلوز)",
        "generic_name": "Carboxymethylcellulose / كاربوكسي ميثيل سليلوز",
        "category": "بديل الدموع المرطب",
        "indications": "تخفيف جفاف العين والحرقة والإجهاد الناتج عن الشاشات.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 118,
        "name": "Viagra (سيلدينافيل)",
        "generic_name": "Sildenafil / سيلدينافيل",
        "category": "مثبط إنزيم PDE5",
        "indications": "علاج ضعف الانتصاب وارتفاع ضغط الشريان الرئوي.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 119,
        "name": "Cialis (تادالافيل)",
        "generic_name": "Tadalafil / تادالافيل",
        "category": "مثبط إنزيم PDE5 طويل المفعول",
        "indications": "علاج ضعف الانتصاب وأعراض تضخم البروستاتا الحميد.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 120,
        "name": "Omnic / Flomax (تامسولوسين)",
        "generic_name": "Tamsulosin / تامسولوسين",
        "category": "حاصر مستقبلات ألفا-1 الانتقائي",
        "indications": "تسهيل تدفق البول وتخفيف أعراض تضخم البروستاتا الحميد.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    },
    {
        "id": 121,
        "name": "Proscar / Propecia (فيناستيرايد)",
        "generic_name": "Finasteride / فيناستيرايد",
        "category": "مثبط إنزيم 5-ألفا ريدوكتاز",
        "indications": "علاج تضخم البروستاتا الحميد ووقف تساقط الشعر الوراثي للرجال.",
        "dosage_form": "أقراص فموية / كبسولات",
        "verified": true
    }
];

// API Client for Pharmacy System (Resilient Multi-Port REST Architecture)
const CANDIDATE_API_URLS = [
    'http://localhost:8080/api',
    'http://127.0.0.1:8080/api',
    'http://localhost/charming-planck/backend/api',
    'http://localhost/backend/api'
];

let ACTIVE_API_URL = localStorage.getItem('active_api_url') || CANDIDATE_API_URLS[0];
const AI_ENGINE_URL = 'http://127.0.0.1:8000/api';

// Demo in-memory fallback dataset
const DEMO_MEDICINES = [
    {
        "id": 1,
        "name": "Losec (أوميبرازول)",
        "generic_name": "Omeprazole / أوميبرازول",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 13.35,
        "stock_quantity": 22,
        "expiry_date": "2027-02-15",
        "barcode": "628100000001",
        "location": "رف B2",
        "requires_prescription": false
    },
    {
        "id": 2,
        "name": "Nexium (إيزوميبرازول)",
        "generic_name": "Esomeprazole / إيزوميبرازول",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 14.7,
        "stock_quantity": 29,
        "expiry_date": "2027-03-15",
        "barcode": "628100000002",
        "location": "رف C3",
        "requires_prescription": false
    },
    {
        "id": 3,
        "name": "Controloc (بانتوبرازول)",
        "generic_name": "Pantoprazole / بانتوبرازول",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 16.05,
        "stock_quantity": 36,
        "expiry_date": "2027-04-15",
        "barcode": "628100000003",
        "location": "رف D4",
        "requires_prescription": false
    },
    {
        "id": 4,
        "name": "Takepron (لانسوبرازول)",
        "generic_name": "Lansoprazole / لانسوبرازول",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 17.4,
        "stock_quantity": 43,
        "expiry_date": "2027-05-15",
        "barcode": "628100000004",
        "location": "رف E5",
        "requires_prescription": false
    },
    {
        "id": 5,
        "name": "Pepcid (فاموتيدين)",
        "generic_name": "Famotidine / فاموتيدين",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 18.75,
        "stock_quantity": 50,
        "expiry_date": "2027-06-15",
        "barcode": "628100000005",
        "location": "رف F6",
        "requires_prescription": false
    },
    {
        "id": 6,
        "name": "Imodium (لوبيراميد)",
        "generic_name": "Loperamide / لوبيراميد",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 20.1,
        "stock_quantity": 57,
        "expiry_date": "2027-07-15",
        "barcode": "628100000006",
        "location": "رف A7",
        "requires_prescription": false
    },
    {
        "id": 7,
        "name": "Buscopan (هيوسين بوتيل بروميد)",
        "generic_name": "Hyoscine Butylbromide / هيوسين بوتيل بروميد",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 21.45,
        "stock_quantity": 64,
        "expiry_date": "2027-08-15",
        "barcode": "628100000007",
        "location": "رف B8",
        "requires_prescription": false
    },
    {
        "id": 8,
        "name": "Duspatalin (ميبفرين)",
        "generic_name": "Mebeverine / ميبفرين",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 22.8,
        "stock_quantity": 71,
        "expiry_date": "2027-09-15",
        "barcode": "628100000008",
        "location": "رف C1",
        "requires_prescription": false
    },
    {
        "id": 9,
        "name": "Motilium (دومبيريدون)",
        "generic_name": "Domperidone / دومبيريدون",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 24.15,
        "stock_quantity": 78,
        "expiry_date": "2027-10-15",
        "barcode": "628100000009",
        "location": "رف D2",
        "requires_prescription": false
    },
    {
        "id": 10,
        "name": "Plasil (ميتوكلوبراميد)",
        "generic_name": "Metoclopramide / ميتوكلوبراميد",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 25.5,
        "stock_quantity": 85,
        "expiry_date": "2027-11-15",
        "barcode": "628100000010",
        "location": "رف E3",
        "requires_prescription": false
    },
    {
        "id": 11,
        "name": "Disflatyl (سيميثيكون)",
        "generic_name": "Simethicone / سيميثيكون",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 26.85,
        "stock_quantity": 92,
        "expiry_date": "2027-12-15",
        "barcode": "628100000011",
        "location": "رف F4",
        "requires_prescription": false
    },
    {
        "id": 12,
        "name": "Duphalac (لاكتولوز)",
        "generic_name": "Lactulose / لاكتولوز",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 28.2,
        "stock_quantity": 99,
        "expiry_date": "2027-01-15",
        "barcode": "628100000012",
        "location": "رف A5",
        "requires_prescription": false
    },
    {
        "id": 13,
        "name": "Dulcolax (بيساكوديل)",
        "generic_name": "Bisacodyl / بيساكوديل",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 29.55,
        "stock_quantity": 21,
        "expiry_date": "2027-02-15",
        "barcode": "628100000013",
        "location": "رف B6",
        "requires_prescription": false
    },
    {
        "id": 14,
        "name": "Ursofalk (حمض الأورسوديوكسيكوليك)",
        "generic_name": "Ursodeoxycholic Acid / حمض الأورسوديوكسيكوليك",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 30.9,
        "stock_quantity": 28,
        "expiry_date": "2027-03-15",
        "barcode": "628100000014",
        "location": "رف C7",
        "requires_prescription": false
    },
    {
        "id": 15,
        "name": "Pentasa (ميسالازين)",
        "generic_name": "Mesalazine / ميسالازين",
        "category": "أدوية الجهاز الهضمي والمعدة",
        "price": 32.25,
        "stock_quantity": 35,
        "expiry_date": "2027-04-15",
        "barcode": "628100000015",
        "location": "رف D8",
        "requires_prescription": false
    },
    {
        "id": 16,
        "name": "Panadol (باراسيتامول)",
        "generic_name": "Paracetamol / باراسيتامول",
        "category": "مسكنات وخافضات حرارة",
        "price": 33.6,
        "stock_quantity": 42,
        "expiry_date": "2027-05-15",
        "barcode": "628100000016",
        "location": "رف E1",
        "requires_prescription": false
    },
    {
        "id": 17,
        "name": "Brufen (إيبوبروفين)",
        "generic_name": "Ibuprofen / إيبوبروفين",
        "category": "مسكنات ومضادات التهاب",
        "price": 34.95,
        "stock_quantity": 49,
        "expiry_date": "2027-06-15",
        "barcode": "628100000017",
        "location": "رف F2",
        "requires_prescription": false
    },
    {
        "id": 18,
        "name": "Voltaren (ديكلوفيناك الصوديوم)",
        "generic_name": "Diclofenac Sodium / ديكلوفيناك الصوديوم",
        "category": "مسكنات ومضادات التهاب",
        "price": 36.3,
        "stock_quantity": 56,
        "expiry_date": "2027-07-15",
        "barcode": "628100000018",
        "location": "رف A3",
        "requires_prescription": false
    },
    {
        "id": 19,
        "name": "Cataflam (ديكلوفيناك البوتاسيوم)",
        "generic_name": "Diclofenac Potassium / ديكلوفيناك البوتاسيوم",
        "category": "مسكنات ومضادات التهاب",
        "price": 37.65,
        "stock_quantity": 63,
        "expiry_date": "2027-08-15",
        "barcode": "628100000019",
        "location": "رف B4",
        "requires_prescription": false
    },
    {
        "id": 20,
        "name": "Proxen (نابروكسين)",
        "generic_name": "Naproxen / نابروكسين",
        "category": "مسكنات ومضادات التهاب",
        "price": 39.0,
        "stock_quantity": 70,
        "expiry_date": "2027-09-15",
        "barcode": "628100000020",
        "location": "رف C5",
        "requires_prescription": false
    },
    {
        "id": 21,
        "name": "Celebrex (سيليكوكسيب)",
        "generic_name": "Celecoxib / سيليكوكسيب",
        "category": "مسكنات ومضادات التهاب",
        "price": 40.35,
        "stock_quantity": 77,
        "expiry_date": "2027-10-15",
        "barcode": "628100000021",
        "location": "رف D6",
        "requires_prescription": false
    },
    {
        "id": 22,
        "name": "Mobic (ميلوكسيكام)",
        "generic_name": "Meloxicam / ميلوكسيكام",
        "category": "مسكنات ومضادات التهاب",
        "price": 41.7,
        "stock_quantity": 84,
        "expiry_date": "2027-11-15",
        "barcode": "628100000022",
        "location": "رف E7",
        "requires_prescription": false
    },
    {
        "id": 23,
        "name": "Ketofan (كيتوبروفين)",
        "generic_name": "Ketoprofen / كيتوبروفين",
        "category": "مسكنات ومضادات التهاب",
        "price": 43.05,
        "stock_quantity": 91,
        "expiry_date": "2027-12-15",
        "barcode": "628100000023",
        "location": "رف F8",
        "requires_prescription": false
    },
    {
        "id": 24,
        "name": "Ponstan (حمض الميفيناميك)",
        "generic_name": "Mefenamic Acid / حمض الميفيناميك",
        "category": "مسكنات ومضادات التهاب",
        "price": 44.4,
        "stock_quantity": 98,
        "expiry_date": "2027-01-15",
        "barcode": "628100000024",
        "location": "رف A1",
        "requires_prescription": false
    },
    {
        "id": 25,
        "name": "Tramal (ترامادول)",
        "generic_name": "Tramadol / ترامادول",
        "category": "أدوية مقيدة ومسكنات خاصة",
        "price": 45.75,
        "stock_quantity": 20,
        "expiry_date": "2027-02-15",
        "barcode": "628100000025",
        "location": "رف B2",
        "requires_prescription": false
    },
    {
        "id": 26,
        "name": "Colchicine (كولشيسين)",
        "generic_name": "Colchicine / كولشيسين",
        "category": "أدوية العظام والمفاصل والنقرس",
        "price": 47.1,
        "stock_quantity": 27,
        "expiry_date": "2027-03-15",
        "barcode": "628100000026",
        "location": "رف C3",
        "requires_prescription": true
    },
    {
        "id": 27,
        "name": "Zyloric (ألوبيورينول)",
        "generic_name": "Allopurinol / ألوبيورينول",
        "category": "أدوية العظام والمفاصل والنقرس",
        "price": 48.45,
        "stock_quantity": 34,
        "expiry_date": "2027-04-15",
        "barcode": "628100000027",
        "location": "رف D4",
        "requires_prescription": true
    },
    {
        "id": 28,
        "name": "Adenuric (فيبوكسوستات)",
        "generic_name": "Febuxostat / فيبوكسوستات",
        "category": "أدوية العظام والمفاصل والنقرس",
        "price": 49.8,
        "stock_quantity": 41,
        "expiry_date": "2027-05-15",
        "barcode": "628100000028",
        "location": "رف E5",
        "requires_prescription": true
    },
    {
        "id": 29,
        "name": "Lioresal (باكلوفين)",
        "generic_name": "Baclofen / باكلوفين",
        "category": "مرخيات العضلات",
        "price": 51.15,
        "stock_quantity": 48,
        "expiry_date": "2027-06-15",
        "barcode": "628100000029",
        "location": "رف F6",
        "requires_prescription": true
    },
    {
        "id": 30,
        "name": "Parafon (كلورزوكسازون)",
        "generic_name": "Chlorzoxazone / كلورزوكسازون",
        "category": "مرخيات العضلات",
        "price": 52.5,
        "stock_quantity": 55,
        "expiry_date": "2027-07-15",
        "barcode": "628100000030",
        "location": "رف A7",
        "requires_prescription": true
    },
    {
        "id": 31,
        "name": "Norvasc (أملوديبين)",
        "generic_name": "Amlodipine / أملوديبين",
        "category": "أدوية القلب وضغط الدم",
        "price": 53.85,
        "stock_quantity": 62,
        "expiry_date": "2027-08-15",
        "barcode": "628100000031",
        "location": "رف B8",
        "requires_prescription": true
    },
    {
        "id": 32,
        "name": "Concor (بيسوبرولول)",
        "generic_name": "Bisoprolol / بيسوبرولول",
        "category": "أدوية القلب وضغط الدم",
        "price": 55.2,
        "stock_quantity": 69,
        "expiry_date": "2027-09-15",
        "barcode": "628100000032",
        "location": "رف C1",
        "requires_prescription": true
    },
    {
        "id": 33,
        "name": "Tenormin (أتينولول)",
        "generic_name": "Atenolol / أتينولول",
        "category": "أدوية القلب وضغط الدم",
        "price": 56.55,
        "stock_quantity": 76,
        "expiry_date": "2027-10-15",
        "barcode": "628100000033",
        "location": "رف D2",
        "requires_prescription": true
    },
    {
        "id": 34,
        "name": "Betaloc (ميتوبرولول)",
        "generic_name": "Metoprolol / ميتوبرولول",
        "category": "أدوية القلب وضغط الدم",
        "price": 57.9,
        "stock_quantity": 83,
        "expiry_date": "2027-11-15",
        "barcode": "628100000034",
        "location": "رف E3",
        "requires_prescription": true
    },
    {
        "id": 35,
        "name": "Cozaar (لوسارتان)",
        "generic_name": "Losartan / لوسارتان",
        "category": "أدوية القلب وضغط الدم",
        "price": 59.25,
        "stock_quantity": 90,
        "expiry_date": "2027-12-15",
        "barcode": "628100000035",
        "location": "رف F4",
        "requires_prescription": true
    },
    {
        "id": 36,
        "name": "Diovan (فالسارتان)",
        "generic_name": "Valsartan / فالسارتان",
        "category": "أدوية القلب وضغط الدم",
        "price": 60.6,
        "stock_quantity": 97,
        "expiry_date": "2027-01-15",
        "barcode": "628100000036",
        "location": "رف A5",
        "requires_prescription": true
    },
    {
        "id": 37,
        "name": "Atacand (كانديسارتان)",
        "generic_name": "Candesartan / كانديسارتان",
        "category": "أدوية القلب وضغط الدم",
        "price": 61.95,
        "stock_quantity": 19,
        "expiry_date": "2027-02-15",
        "barcode": "628100000037",
        "location": "رف B6",
        "requires_prescription": true
    },
    {
        "id": 38,
        "name": "Renitec (إنالابريل)",
        "generic_name": "Enalapril / إنالابريل",
        "category": "أدوية القلب وضغط الدم",
        "price": 63.3,
        "stock_quantity": 26,
        "expiry_date": "2027-03-15",
        "barcode": "628100000038",
        "location": "رف C7",
        "requires_prescription": true
    },
    {
        "id": 39,
        "name": "Zestril (ليسينوبريل)",
        "generic_name": "Lisinopril / ليسينوبريل",
        "category": "أدوية القلب وضغط الدم",
        "price": 64.65,
        "stock_quantity": 33,
        "expiry_date": "2027-04-15",
        "barcode": "628100000039",
        "location": "رف D8",
        "requires_prescription": true
    },
    {
        "id": 40,
        "name": "Tritace (راميبريل)",
        "generic_name": "Ramipril / راميبريل",
        "category": "أدوية القلب وضغط الدم",
        "price": 66.0,
        "stock_quantity": 40,
        "expiry_date": "2027-05-15",
        "barcode": "628100000040",
        "location": "رف E1",
        "requires_prescription": true
    },
    {
        "id": 41,
        "name": "Esidrex (هيدروكلوروثيازيد)",
        "generic_name": "Hydrochlorothiazide / هيدروكلوروثيازيد",
        "category": "أدوية القلب وضغط الدم ومدرات البول",
        "price": 67.35,
        "stock_quantity": 47,
        "expiry_date": "2027-06-15",
        "barcode": "628100000041",
        "location": "رف F2",
        "requires_prescription": true
    },
    {
        "id": 42,
        "name": "Lasix (فوروسيميد)",
        "generic_name": "Furosemide / فوروسيميد",
        "category": "أدوية القلب وضغط الدم ومدرات البول",
        "price": 68.7,
        "stock_quantity": 54,
        "expiry_date": "2027-07-15",
        "barcode": "628100000042",
        "location": "رف A3",
        "requires_prescription": true
    },
    {
        "id": 43,
        "name": "Aldactone (سبيرونولاكتون)",
        "generic_name": "Spironolactone / سبيرونولاكتون",
        "category": "أدوية القلب وضغط الدم ومدرات البول",
        "price": 70.05,
        "stock_quantity": 61,
        "expiry_date": "2027-08-15",
        "barcode": "628100000043",
        "location": "رف B4",
        "requires_prescription": true
    },
    {
        "id": 44,
        "name": "Lipitor (أتورفاستاتين)",
        "generic_name": "Atorvastatin / أتورفاستاتين",
        "category": "أدوية الكوليسترول والدهون",
        "price": 71.4,
        "stock_quantity": 68,
        "expiry_date": "2027-09-15",
        "barcode": "628100000044",
        "location": "رف C5",
        "requires_prescription": true
    },
    {
        "id": 45,
        "name": "Crestor (روزوڤاستاتين)",
        "generic_name": "Rosuvastatin / روزوڤاستاتين",
        "category": "أدوية الكوليسترول والدهون",
        "price": 72.75,
        "stock_quantity": 75,
        "expiry_date": "2027-10-15",
        "barcode": "628100000045",
        "location": "رف D6",
        "requires_prescription": true
    },
    {
        "id": 46,
        "name": "Zocor (سيمفاستاتين)",
        "generic_name": "Simvastatin / سيمفاستاتين",
        "category": "أدوية الكوليسترول والدهون",
        "price": 74.1,
        "stock_quantity": 82,
        "expiry_date": "2027-11-15",
        "barcode": "628100000046",
        "location": "رف E7",
        "requires_prescription": true
    },
    {
        "id": 47,
        "name": "Lipanthyl (فينوفيبرات)",
        "generic_name": "Fenofibrate / فينوفيبرات",
        "category": "أدوية الكوليسترول والدهون",
        "price": 75.45,
        "stock_quantity": 89,
        "expiry_date": "2027-12-15",
        "barcode": "628100000047",
        "location": "رف F8",
        "requires_prescription": true
    },
    {
        "id": 48,
        "name": "Plavix (كلوبيدوجريل)",
        "generic_name": "Clopidogrel / كلوبيدوجريل",
        "category": "أدوية السيولة والجلطات",
        "price": 76.8,
        "stock_quantity": 96,
        "expiry_date": "2027-01-15",
        "barcode": "628100000048",
        "location": "رف A1",
        "requires_prescription": true
    },
    {
        "id": 49,
        "name": "Aspirin Protect (حمض أسيتيل الساليسيليك)",
        "generic_name": "Aspirin (Low Dose) / حمض أسيتيل الساليسيليك",
        "category": "أدوية السيولة والجلطات",
        "price": 13.15,
        "stock_quantity": 18,
        "expiry_date": "2027-02-15",
        "barcode": "628100000049",
        "location": "رف B2",
        "requires_prescription": true
    },
    {
        "id": 50,
        "name": "Coumadin (وارفارين)",
        "generic_name": "Warfarin / وارفارين",
        "category": "أدوية السيولة والجلطات",
        "price": 14.5,
        "stock_quantity": 25,
        "expiry_date": "2027-03-15",
        "barcode": "628100000050",
        "location": "رف C3",
        "requires_prescription": true
    },
    {
        "id": 51,
        "name": "Glucophage (ميتفورمين)",
        "generic_name": "Metformin / ميتفورمين",
        "category": "أدوية السكري والغدد",
        "price": 15.85,
        "stock_quantity": 32,
        "expiry_date": "2027-04-15",
        "barcode": "628100000051",
        "location": "رف D4",
        "requires_prescription": true
    },
    {
        "id": 52,
        "name": "Diamicron MR (جليكلازيد)",
        "generic_name": "Gliclazide / جليكلازيد",
        "category": "أدوية السكري والغدد",
        "price": 17.2,
        "stock_quantity": 39,
        "expiry_date": "2027-05-15",
        "barcode": "628100000052",
        "location": "رف E5",
        "requires_prescription": true
    },
    {
        "id": 53,
        "name": "Amaryl (جليمبيريد)",
        "generic_name": "Glimepiride / جليمبيريد",
        "category": "أدوية السكري والغدد",
        "price": 18.55,
        "stock_quantity": 46,
        "expiry_date": "2027-06-15",
        "barcode": "628100000053",
        "location": "رف F6",
        "requires_prescription": true
    },
    {
        "id": 54,
        "name": "Januvia (سيتاجليبتين)",
        "generic_name": "Sitagliptin / سيتاجليبتين",
        "category": "أدوية السكري والغدد",
        "price": 19.9,
        "stock_quantity": 53,
        "expiry_date": "2027-07-15",
        "barcode": "628100000054",
        "location": "رف A7",
        "requires_prescription": true
    },
    {
        "id": 55,
        "name": "Galvus (فيلداجليبتين)",
        "generic_name": "Vildagliptin / فيلداجليبتين",
        "category": "أدوية السكري والغدد",
        "price": 21.25,
        "stock_quantity": 60,
        "expiry_date": "2027-08-15",
        "barcode": "628100000055",
        "location": "رف B8",
        "requires_prescription": true
    },
    {
        "id": 56,
        "name": "Jardiance (إمباجليفلوزين)",
        "generic_name": "Empagliflozin / إمباجليفلوزين",
        "category": "أدوية السكري والغدد",
        "price": 22.6,
        "stock_quantity": 67,
        "expiry_date": "2027-09-15",
        "barcode": "628100000056",
        "location": "رف C1",
        "requires_prescription": true
    },
    {
        "id": 57,
        "name": "Forxiga (داباجليفلوزين)",
        "generic_name": "Dapagliflozin / داباجليفلوزين",
        "category": "أدوية السكري والغدد",
        "price": 23.95,
        "stock_quantity": 74,
        "expiry_date": "2027-10-15",
        "barcode": "628100000057",
        "location": "رف D2",
        "requires_prescription": true
    },
    {
        "id": 58,
        "name": "Lantus (إنسولين جلارجين)",
        "generic_name": "Insulin Glargine / إنسولين جلارجين",
        "category": "أدوية السكري والغدد",
        "price": 25.3,
        "stock_quantity": 81,
        "expiry_date": "2027-11-15",
        "barcode": "628100000058",
        "location": "رف E3",
        "requires_prescription": true
    },
    {
        "id": 59,
        "name": "NovoRapid (إنسولين أسبارت)",
        "generic_name": "Insulin Aspart / إنسولين أسبارت",
        "category": "أدوية السكري والغدد",
        "price": 26.65,
        "stock_quantity": 88,
        "expiry_date": "2027-12-15",
        "barcode": "628100000059",
        "location": "رف F4",
        "requires_prescription": true
    },
    {
        "id": 60,
        "name": "Euthyrox (ليفوثيروكسين)",
        "generic_name": "Levothyroxine / ليفوثيروكسين",
        "category": "أدوية السكري والغدد",
        "price": 28.0,
        "stock_quantity": 95,
        "expiry_date": "2027-01-15",
        "barcode": "628100000060",
        "location": "رف A5",
        "requires_prescription": true
    },
    {
        "id": 61,
        "name": "Neo-Mercazole (كاربيمازول)",
        "generic_name": "Carbimazole / كاربيمازول",
        "category": "أدوية السكري والغدد",
        "price": 29.35,
        "stock_quantity": 17,
        "expiry_date": "2027-02-15",
        "barcode": "628100000061",
        "location": "رف B6",
        "requires_prescription": true
    },
    {
        "id": 62,
        "name": "Ventolin (سالبوتامول)",
        "generic_name": "Salbutamol / سالبوتامول",
        "category": "أدوية الجهاز التنفسي والحساسية",
        "price": 30.7,
        "stock_quantity": 24,
        "expiry_date": "2027-03-15",
        "barcode": "628100000062",
        "location": "رف C7",
        "requires_prescription": true
    },
    {
        "id": 63,
        "name": "Symbicort (فورموتيرول وبوديسونيد)",
        "generic_name": "Formoterol + Budesonide / فورموتيرول وبوديسونيد",
        "category": "أدوية الجهاز التنفسي والحساسية",
        "price": 32.05,
        "stock_quantity": 31,
        "expiry_date": "2027-04-15",
        "barcode": "628100000063",
        "location": "رف D8",
        "requires_prescription": true
    },
    {
        "id": 64,
        "name": "Seretide (فلوتيكازون وسالميتيرول)",
        "generic_name": "Fluticasone + Salmeterol / فلوتيكازون وسالميتيرول",
        "category": "أدوية الجهاز التنفسي والحساسية",
        "price": 33.4,
        "stock_quantity": 38,
        "expiry_date": "2027-05-15",
        "barcode": "628100000064",
        "location": "رف E1",
        "requires_prescription": true
    },
    {
        "id": 65,
        "name": "Spiriva (تيوتروبيوم)",
        "generic_name": "Tiotropium / تيوتروبيوم",
        "category": "أدوية الجهاز التنفسي والحساسية",
        "price": 34.75,
        "stock_quantity": 45,
        "expiry_date": "2027-06-15",
        "barcode": "628100000065",
        "location": "رف F2",
        "requires_prescription": true
    },
    {
        "id": 66,
        "name": "Singulair (مونتيلوكاست)",
        "generic_name": "Montelukast / مونتيلوكاست",
        "category": "أدوية الجهاز التنفسي والحساسية",
        "price": 36.1,
        "stock_quantity": 52,
        "expiry_date": "2027-07-15",
        "barcode": "628100000066",
        "location": "رف A3",
        "requires_prescription": true
    },
    {
        "id": 67,
        "name": "Zyrtec (سيتريزين)",
        "generic_name": "Cetirizine / سيتريزين",
        "category": "أدوية الجهاز التنفسي والحساسية",
        "price": 37.45,
        "stock_quantity": 59,
        "expiry_date": "2027-08-15",
        "barcode": "628100000067",
        "location": "رف B4",
        "requires_prescription": true
    },
    {
        "id": 68,
        "name": "Claritine (لوراتادين)",
        "generic_name": "Loratadine / لوراتادين",
        "category": "أدوية الجهاز التنفسي والحساسية",
        "price": 38.8,
        "stock_quantity": 66,
        "expiry_date": "2027-09-15",
        "barcode": "628100000068",
        "location": "رف C5",
        "requires_prescription": true
    },
    {
        "id": 69,
        "name": "Aerius (ديسلوراتادين)",
        "generic_name": "Desloratadine / ديسلوراتادين",
        "category": "أدوية الجهاز التنفسي والحساسية",
        "price": 40.15,
        "stock_quantity": 73,
        "expiry_date": "2027-10-15",
        "barcode": "628100000069",
        "location": "رف D6",
        "requires_prescription": true
    },
    {
        "id": 70,
        "name": "Telfast (فيكسوفينادين)",
        "generic_name": "Fexofenadine / فيكسوفينادين",
        "category": "أدوية الجهاز التنفسي والحساسية",
        "price": 41.5,
        "stock_quantity": 80,
        "expiry_date": "2027-11-15",
        "barcode": "628100000070",
        "location": "رف E7",
        "requires_prescription": true
    },
    {
        "id": 71,
        "name": "Histop (كلورفينيرامين)",
        "generic_name": "Chlorpheniramine / كلورفينيرامين",
        "category": "أدوية الجهاز التنفسي والحساسية",
        "price": 42.85,
        "stock_quantity": 87,
        "expiry_date": "2027-12-15",
        "barcode": "628100000071",
        "location": "رف F8",
        "requires_prescription": true
    },
    {
        "id": 72,
        "name": "Otrivin (زايلوميتازولين)",
        "generic_name": "Xylometazoline / زايلوميتازولين",
        "category": "أدوية الجهاز التنفسي والحساسية",
        "price": 44.2,
        "stock_quantity": 94,
        "expiry_date": "2027-01-15",
        "barcode": "628100000072",
        "location": "رف A1",
        "requires_prescription": true
    },
    {
        "id": 73,
        "name": "ACC (أسيتيل سيستئين)",
        "generic_name": "Acetylcysteine / أسيتيل سيستئين",
        "category": "أدوية الجهاز التنفسي والحساسية",
        "price": 45.55,
        "stock_quantity": 16,
        "expiry_date": "2027-02-15",
        "barcode": "628100000073",
        "location": "رف B2",
        "requires_prescription": true
    },
    {
        "id": 74,
        "name": "Mucosolvan (أمبروكسول)",
        "generic_name": "Ambroxol / أمبروكسول",
        "category": "أدوية الجهاز التنفسي والحساسية",
        "price": 46.9,
        "stock_quantity": 23,
        "expiry_date": "2027-03-15",
        "barcode": "628100000074",
        "location": "رف C3",
        "requires_prescription": true
    },
    {
        "id": 75,
        "name": "Amoxil (أموكسيسيلين)",
        "generic_name": "Amoxicillin / أموكسيسيلين",
        "category": "مضادات حيوية وميكروبية",
        "price": 48.25,
        "stock_quantity": 30,
        "expiry_date": "2027-04-15",
        "barcode": "628100000075",
        "location": "رف D4",
        "requires_prescription": true
    },
    {
        "id": 76,
        "name": "Augmentin (أموكسيسيلين وكلافولانيك)",
        "generic_name": "Amoxicillin + Clavulanic Acid / أموكسيسيلين وكلافولانيك",
        "category": "مضادات حيوية وميكروبية",
        "price": 49.6,
        "stock_quantity": 37,
        "expiry_date": "2027-05-15",
        "barcode": "628100000076",
        "location": "رف E5",
        "requires_prescription": true
    },
    {
        "id": 77,
        "name": "Zithromax (أزيثروميسين)",
        "generic_name": "Azithromycin / أزيثروميسين",
        "category": "مضادات حيوية وميكروبية",
        "price": 50.95,
        "stock_quantity": 44,
        "expiry_date": "2027-06-15",
        "barcode": "628100000077",
        "location": "رف F6",
        "requires_prescription": true
    },
    {
        "id": 78,
        "name": "Klacid (كلاريثروميسين)",
        "generic_name": "Clarithromycin / كلاريثروميسين",
        "category": "مضادات حيوية وميكروبية",
        "price": 52.3,
        "stock_quantity": 51,
        "expiry_date": "2027-07-15",
        "barcode": "628100000078",
        "location": "رف A7",
        "requires_prescription": true
    },
    {
        "id": 79,
        "name": "Cipro (سيبروفلوكساسين)",
        "generic_name": "Ciprofloxacin / سيبروفلوكساسين",
        "category": "مضادات حيوية وميكروبية",
        "price": 53.65,
        "stock_quantity": 58,
        "expiry_date": "2027-08-15",
        "barcode": "628100000079",
        "location": "رف B8",
        "requires_prescription": true
    },
    {
        "id": 80,
        "name": "Tavanic (ليفوفلوكساسين)",
        "generic_name": "Levofloxacin / ليفوفلوكساسين",
        "category": "مضادات حيوية وميكروبية",
        "price": 55.0,
        "stock_quantity": 65,
        "expiry_date": "2027-09-15",
        "barcode": "628100000080",
        "location": "رف C1",
        "requires_prescription": true
    },
    {
        "id": 81,
        "name": "Rocephin (سيفترياكسون)",
        "generic_name": "Ceftriaxone / سيفترياكسون",
        "category": "مضادات حيوية وميكروبية",
        "price": 56.35,
        "stock_quantity": 72,
        "expiry_date": "2027-10-15",
        "barcode": "628100000081",
        "location": "رف D2",
        "requires_prescription": true
    },
    {
        "id": 82,
        "name": "Zinnat (سيفوروكسيم)",
        "generic_name": "Cefuroxime / سيفوروكسيم",
        "category": "مضادات حيوية وميكروبية",
        "price": 57.7,
        "stock_quantity": 79,
        "expiry_date": "2027-11-15",
        "barcode": "628100000082",
        "location": "رف E3",
        "requires_prescription": true
    },
    {
        "id": 83,
        "name": "Suprax (سيفيكسيم)",
        "generic_name": "Cefixime / سيفيكسيم",
        "category": "مضادات حيوية وميكروبية",
        "price": 59.05,
        "stock_quantity": 86,
        "expiry_date": "2027-12-15",
        "barcode": "628100000083",
        "location": "رف F4",
        "requires_prescription": true
    },
    {
        "id": 84,
        "name": "Vibramycin (دوكسيسيكلين)",
        "generic_name": "Doxycycline / دوكسيسيكلين",
        "category": "مضادات حيوية وميكروبية",
        "price": 60.4,
        "stock_quantity": 93,
        "expiry_date": "2027-01-15",
        "barcode": "628100000084",
        "location": "رف A5",
        "requires_prescription": true
    },
    {
        "id": 85,
        "name": "Flagyl (ميترونيدازول)",
        "generic_name": "Metronidazole / ميترونيدازول",
        "category": "مضادات حيوية وميكروبية",
        "price": 61.75,
        "stock_quantity": 15,
        "expiry_date": "2027-02-15",
        "barcode": "628100000085",
        "location": "رف B6",
        "requires_prescription": true
    },
    {
        "id": 86,
        "name": "Diflucan (فلوكونازول)",
        "generic_name": "Fluconazole / فلوكونازول",
        "category": "مضادات حيوية وميكروبية",
        "price": 63.1,
        "stock_quantity": 22,
        "expiry_date": "2027-03-15",
        "barcode": "628100000086",
        "location": "رف C7",
        "requires_prescription": true
    },
    {
        "id": 87,
        "name": "Zovirax (أسيكلوفير)",
        "generic_name": "Acyclovir / أسيكلوفير",
        "category": "مضادات حيوية وميكروبية",
        "price": 64.45,
        "stock_quantity": 29,
        "expiry_date": "2027-04-15",
        "barcode": "628100000087",
        "location": "رف D8",
        "requires_prescription": true
    },
    {
        "id": 88,
        "name": "Cipralex (إسيتالوبرام)",
        "generic_name": "Escitalopram / إسيتالوبرام",
        "category": "أدوية الجهاز العصبي والنفسية",
        "price": 65.8,
        "stock_quantity": 36,
        "expiry_date": "2027-05-15",
        "barcode": "628100000088",
        "location": "رف E1",
        "requires_prescription": true
    },
    {
        "id": 89,
        "name": "Zoloft (سيرترالين)",
        "generic_name": "Sertraline / سيرترالين",
        "category": "أدوية الجهاز العصبي والنفسية",
        "price": 67.15,
        "stock_quantity": 43,
        "expiry_date": "2027-06-15",
        "barcode": "628100000089",
        "location": "رف F2",
        "requires_prescription": true
    },
    {
        "id": 90,
        "name": "Prozac (فلوكسيتين)",
        "generic_name": "Fluoxetine / فلوكسيتين",
        "category": "أدوية الجهاز العصبي والنفسية",
        "price": 68.5,
        "stock_quantity": 50,
        "expiry_date": "2027-07-15",
        "barcode": "628100000090",
        "location": "رف A3",
        "requires_prescription": true
    },
    {
        "id": 91,
        "name": "Seroxat (باروكستين)",
        "generic_name": "Paroxetine / باروكستين",
        "category": "أدوية الجهاز العصبي والنفسية",
        "price": 69.85,
        "stock_quantity": 57,
        "expiry_date": "2027-08-15",
        "barcode": "628100000091",
        "location": "رف B4",
        "requires_prescription": true
    },
    {
        "id": 92,
        "name": "Efexor XR (فينلافاكسين)",
        "generic_name": "Venlafaxine / فينلافاكسين",
        "category": "أدوية الجهاز العصبي والنفسية",
        "price": 71.2,
        "stock_quantity": 64,
        "expiry_date": "2027-09-15",
        "barcode": "628100000092",
        "location": "رف C5",
        "requires_prescription": true
    },
    {
        "id": 93,
        "name": "Cymbalta (دولوكستين)",
        "generic_name": "Duloxetine / دولوكستين",
        "category": "أدوية الجهاز العصبي والنفسية",
        "price": 72.55,
        "stock_quantity": 71,
        "expiry_date": "2027-10-15",
        "barcode": "628100000093",
        "location": "رف D6",
        "requires_prescription": true
    },
    {
        "id": 94,
        "name": "Lyrica (بريجابالين)",
        "generic_name": "Pregabalin / بريجابالين",
        "category": "أدوية عامة",
        "price": 73.9,
        "stock_quantity": 78,
        "expiry_date": "2027-11-15",
        "barcode": "628100000094",
        "location": "رف E7",
        "requires_prescription": true
    },
    {
        "id": 95,
        "name": "Neurontin (جابابنتين)",
        "generic_name": "Gabapentin / جابابنتين",
        "category": "أدوية الجهاز العصبي والنفسية",
        "price": 75.25,
        "stock_quantity": 85,
        "expiry_date": "2027-12-15",
        "barcode": "628100000095",
        "location": "رف F8",
        "requires_prescription": true
    },
    {
        "id": 96,
        "name": "Keppra (ليفيتيراسيتام)",
        "generic_name": "Levetiracetam / ليفيتيراسيتام",
        "category": "أدوية الجهاز العصبي والنفسية",
        "price": 76.6,
        "stock_quantity": 92,
        "expiry_date": "2027-01-15",
        "barcode": "628100000096",
        "location": "رف A1",
        "requires_prescription": true
    },
    {
        "id": 97,
        "name": "Depakine Chrono (فالبروات الصوديوم)",
        "generic_name": "Sodium Valproate / فالبروات الصوديوم",
        "category": "أدوية الجهاز العصبي والنفسية",
        "price": 12.95,
        "stock_quantity": 99,
        "expiry_date": "2027-02-15",
        "barcode": "628100000097",
        "location": "رف B2",
        "requires_prescription": true
    },
    {
        "id": 98,
        "name": "Tegretol (كاربامازيبين)",
        "generic_name": "Carbamazepine / كاربامازيبين",
        "category": "أدوية الجهاز العصبي والنفسية",
        "price": 14.3,
        "stock_quantity": 21,
        "expiry_date": "2027-03-15",
        "barcode": "628100000098",
        "location": "رف C3",
        "requires_prescription": true
    },
    {
        "id": 99,
        "name": "Seroquel (كويتيابين)",
        "generic_name": "Quetiapine / كويتيابين",
        "category": "أدوية الجهاز العصبي والنفسية",
        "price": 15.65,
        "stock_quantity": 28,
        "expiry_date": "2027-04-15",
        "barcode": "628100000099",
        "location": "رف D4",
        "requires_prescription": true
    },
    {
        "id": 100,
        "name": "Zyprexa (أولانزابين)",
        "generic_name": "Olanzapine / أولانزابين",
        "category": "أدوية الجهاز العصبي والنفسية",
        "price": 17.0,
        "stock_quantity": 35,
        "expiry_date": "2027-05-15",
        "barcode": "628100000100",
        "location": "رف E5",
        "requires_prescription": true
    },
    {
        "id": 101,
        "name": "Strattera (أتوموكسيتين)",
        "generic_name": "Atomoxetine / أتوموكسيتين",
        "category": "أدوية الجهاز العصبي والنفسية",
        "price": 18.35,
        "stock_quantity": 42,
        "expiry_date": "2027-06-15",
        "barcode": "628100000101",
        "location": "رف F6",
        "requires_prescription": true
    },
    {
        "id": 102,
        "name": "Aricept (دونيبيزيل)",
        "generic_name": "Donepezil / دونيبيزيل",
        "category": "أدوية الجهاز العصبي والنفسية",
        "price": 19.7,
        "stock_quantity": 49,
        "expiry_date": "2027-07-15",
        "barcode": "628100000102",
        "location": "رف A7",
        "requires_prescription": true
    },
    {
        "id": 103,
        "name": "Vidrop (كوليكالسيفيرول (فيتامين د3))",
        "generic_name": "Cholecalciferol / كوليكالسيفيرول (فيتامين د3)",
        "category": "الفيتامينات والمكملات الغذائية",
        "price": 21.05,
        "stock_quantity": 56,
        "expiry_date": "2027-08-15",
        "barcode": "628100000103",
        "location": "رف B8",
        "requires_prescription": true
    },
    {
        "id": 104,
        "name": "Feroglobin (مركبات الحديد)",
        "generic_name": "Ferrous Fumarate / Sulfate / مركبات الحديد",
        "category": "الفيتامينات والمكملات الغذائية",
        "price": 22.4,
        "stock_quantity": 63,
        "expiry_date": "2027-09-15",
        "barcode": "628100000104",
        "location": "رف C1",
        "requires_prescription": true
    },
    {
        "id": 105,
        "name": "Folic Acid 5mg (حمض الفوليك (فيتامين ب9))",
        "generic_name": "Folic Acid / حمض الفوليك (فيتامين ب9)",
        "category": "الفيتامينات والمكملات الغذائية",
        "price": 23.75,
        "stock_quantity": 70,
        "expiry_date": "2027-10-15",
        "barcode": "628100000105",
        "location": "رف D2",
        "requires_prescription": true
    },
    {
        "id": 106,
        "name": "Neurobion (فيتامين ب12)",
        "generic_name": "Cyanocobalamin / Methylcobalamin / فيتامين ب12",
        "category": "الفيتامينات والمكملات الغذائية",
        "price": 25.1,
        "stock_quantity": 77,
        "expiry_date": "2027-11-15",
        "barcode": "628100000106",
        "location": "رف E3",
        "requires_prescription": true
    },
    {
        "id": 107,
        "name": "Caltrate (كالسيوم وفيتامين د)",
        "generic_name": "Calcium Carbonate + Vit D / كالسيوم وفيتامين د",
        "category": "الفيتامينات والمكملات الغذائية",
        "price": 26.45,
        "stock_quantity": 84,
        "expiry_date": "2027-12-15",
        "barcode": "628100000107",
        "location": "رف F4",
        "requires_prescription": true
    },
    {
        "id": 108,
        "name": "Roaccutane (إيزوتريتينوين)",
        "generic_name": "Isotretinoin / إيزوتريتينوين",
        "category": "أدوية الجلدية والعيون والمسالك",
        "price": 27.8,
        "stock_quantity": 91,
        "expiry_date": "2027-01-15",
        "barcode": "628100000108",
        "location": "رف A5",
        "requires_prescription": true
    },
    {
        "id": 109,
        "name": "Betnovate (بيتاميثازون)",
        "generic_name": "Betamethasone / بيتاميثازون",
        "category": "أدوية الجلدية والعيون والمسالك",
        "price": 29.15,
        "stock_quantity": 98,
        "expiry_date": "2027-02-15",
        "barcode": "628100000109",
        "location": "رف B6",
        "requires_prescription": true
    },
    {
        "id": 110,
        "name": "Locoid (هيدروكورتيزون)",
        "generic_name": "Hydrocortisone / هيدروكورتيزون",
        "category": "أدوية الجلدية والعيون والمسالك",
        "price": 30.5,
        "stock_quantity": 20,
        "expiry_date": "2027-03-15",
        "barcode": "628100000110",
        "location": "رف C7",
        "requires_prescription": true
    },
    {
        "id": 111,
        "name": "Bactroban (موبيروسين)",
        "generic_name": "Mupirocin / موبيروسين",
        "category": "أدوية الجلدية والعيون والمسالك",
        "price": 31.85,
        "stock_quantity": 27,
        "expiry_date": "2027-04-15",
        "barcode": "628100000111",
        "location": "رف D8",
        "requires_prescription": true
    },
    {
        "id": 112,
        "name": "Fucidin (حمض الفوسيديك)",
        "generic_name": "Fusidic Acid / حمض الفوسيديك",
        "category": "أدوية الجلدية والعيون والمسالك",
        "price": 33.2,
        "stock_quantity": 34,
        "expiry_date": "2027-05-15",
        "barcode": "628100000112",
        "location": "رف E1",
        "requires_prescription": true
    },
    {
        "id": 113,
        "name": "Canesten (كلوتريمازول)",
        "generic_name": "Clotrimazole / كلوتريمازول",
        "category": "أدوية الجلدية والعيون والمسالك",
        "price": 34.55,
        "stock_quantity": 41,
        "expiry_date": "2027-06-15",
        "barcode": "628100000113",
        "location": "رف F2",
        "requires_prescription": true
    },
    {
        "id": 114,
        "name": "Acretin (تريتينوين)",
        "generic_name": "Tretinoin / تريتينوين",
        "category": "أدوية الجلدية والعيون والمسالك",
        "price": 35.9,
        "stock_quantity": 48,
        "expiry_date": "2027-07-15",
        "barcode": "628100000114",
        "location": "رف A3",
        "requires_prescription": true
    },
    {
        "id": 115,
        "name": "Xalatan (لاتانوبروست)",
        "generic_name": "Latanoprost / لاتانوبروست",
        "category": "أدوية الجلدية والعيون والمسالك",
        "price": 37.25,
        "stock_quantity": 55,
        "expiry_date": "2027-08-15",
        "barcode": "628100000115",
        "location": "رف B4",
        "requires_prescription": true
    },
    {
        "id": 116,
        "name": "Alphagan P (بريمونيدين)",
        "generic_name": "Brimonidine / بريمونيدين",
        "category": "أدوية الجلدية والعيون والمسالك",
        "price": 38.6,
        "stock_quantity": 62,
        "expiry_date": "2027-09-15",
        "barcode": "628100000116",
        "location": "رف C5",
        "requires_prescription": true
    },
    {
        "id": 117,
        "name": "Refresh Tears (كاربوكسي ميثيل سليلوز)",
        "generic_name": "Carboxymethylcellulose / كاربوكسي ميثيل سليلوز",
        "category": "أدوية الجلدية والعيون والمسالك",
        "price": 39.95,
        "stock_quantity": 69,
        "expiry_date": "2027-10-15",
        "barcode": "628100000117",
        "location": "رف D6",
        "requires_prescription": true
    },
    {
        "id": 118,
        "name": "Viagra (سيلدينافيل)",
        "generic_name": "Sildenafil / سيلدينافيل",
        "category": "أدوية الجلدية والعيون والمسالك",
        "price": 41.3,
        "stock_quantity": 76,
        "expiry_date": "2027-11-15",
        "barcode": "628100000118",
        "location": "رف E7",
        "requires_prescription": true
    },
    {
        "id": 119,
        "name": "Cialis (تادالافيل)",
        "generic_name": "Tadalafil / تادالافيل",
        "category": "أدوية الجلدية والعيون والمسالك",
        "price": 42.65,
        "stock_quantity": 83,
        "expiry_date": "2027-12-15",
        "barcode": "628100000119",
        "location": "رف F8",
        "requires_prescription": true
    },
    {
        "id": 120,
        "name": "Omnic (تامسولوسين)",
        "generic_name": "Tamsulosin / تامسولوسين",
        "category": "أدوية الجلدية والعيون والمسالك",
        "price": 44.0,
        "stock_quantity": 90,
        "expiry_date": "2027-01-15",
        "barcode": "628100000120",
        "location": "رف A1",
        "requires_prescription": true
    },
    {
        "id": 121,
        "name": "Proscar (فيناستيرايد)",
        "generic_name": "Finasteride / فيناستيرايد",
        "category": "أدوية الجلدية والعيون والمسالك",
        "price": 45.35,
        "stock_quantity": 97,
        "expiry_date": "2027-02-15",
        "barcode": "628100000121",
        "location": "رف B2",
        "requires_prescription": true
    }
];

const DEMO_SUPPLIERS = [
    { id: 1, name: 'شركة فارما العالمية للتوريدات الطبية', contact_person: 'د. أحمد', phone: '+967-770000001', email: 'info@globalpharma.com', address: 'صنعاء - شارع الزبيري' },
    { id: 2, name: 'مؤسسة الحياة لتوزيع أدوية المستشفيات', contact_person: 'سارة علي', phone: '+967-770000002', email: 'contact@medlife.com', address: 'عدن - المنطقة الحرة' },
    { id: 3, name: 'شركة الصحة والتقنية الحيوية', contact_person: 'م. خالد', phone: '+967-770000003', email: 'sales@biotech.com', address: 'تعز - شارع الجامعة' }
];

const DEMO_ORDERS = [
    {
        id: 101,
        invoice_number: 'INV-202608-001',
        order_type: 'department_dispense',
        customer_name: 'قسم الطوارئ والعناية المركزة',
        payment_method: 'credit',
        payment_status: 'مسدد',
        status: 'completed',
        subtotal: 135.00,
        discount: 0.00,
        tax_amount: 0.00,
        total_amount: 135.00,
        created_at: '2026-08-24 10:30:00',
        items: [
            { medicine_id: 1, name: 'أوجمنتين 1 جم (Augmentin 1g)', quantity: 2, unit_price: 45.00, total_price: 90.00 },
            { medicine_id: 29, name: 'فولتارين 50 مجم (Voltaren 50mg)', quantity: 2, unit_price: 22.00, total_price: 45.00 }
        ]
    },
    {
        id: 102,
        invoice_number: 'INV-202608-002',
        order_type: 'patient_sale',
        customer_name: 'عميل نقدي / مريض عيادات خارجية',
        payment_method: 'cash',
        payment_status: 'مسدد',
        status: 'completed',
        subtotal: 76.50,
        discount: 5.00,
        tax_amount: 0.00,
        total_amount: 71.50,
        created_at: '2026-08-23 15:45:00',
        items: [
            { medicine_id: 19, name: 'بنادول إكسترا 500 مجم (Panadol Extra 500mg)', quantity: 3, unit_price: 12.00, total_price: 36.00 },
            { medicine_id: 40, name: 'أوميبرازول 20 مجم (Omeprazole 20mg)', quantity: 2, unit_price: 20.00, total_price: 40.50 }
        ]
    },
    {
        id: 103,
        invoice_number: 'INV-202608-003',
        order_type: 'routine_restock',
        customer_name: 'شركة فارما العالمية للتوريدات الطبية',
        payment_method: 'card',
        payment_status: 'مسدد',
        status: 'completed',
        subtotal: 1250.00,
        discount: 0.00,
        tax_amount: 0.00,
        total_amount: 1250.00,
        created_at: '2026-08-22 09:15:00',
        items: [
            { medicine_id: 6, name: 'أموكسيل 500 مجم (Amoxil 500mg)', quantity: 50, unit_price: 18.00, total_price: 900.00 },
            { medicine_id: 35, name: 'سيلبركس 200 مجم (Celebrex 200mg)', quantity: 10, unit_price: 65.00, total_price: 350.00 }
        ]
    }
];

// LocalStorage Persistence Layer for Orders and Inventory
function getPersistentOrders() {
    try {
        const stored = localStorage.getItem('spms_persisted_orders');
        if (stored) {
            const parsed = JSON.parse(stored);
            if (Array.isArray(parsed) && parsed.length > 0) return parsed;
        }
    } catch (e) {}
    return DEMO_ORDERS;
}

function savePersistentOrders(orders) {
    try {
        localStorage.setItem('spms_persisted_orders', JSON.stringify(orders));
    } catch (e) {}
}

function getPersistentMedicines() {
    let meds = null;
    try {
        const stored = localStorage.getItem('spms_persisted_medicines');
        if (stored) {
            const parsed = JSON.parse(stored);
            if (Array.isArray(parsed) && parsed.length > 0) meds = parsed;
        }
    } catch (e) {}
    if (!meds) {
        meds = (typeof DEMO_MEDICINES !== 'undefined') ? [...DEMO_MEDICINES] : [];
    }

    // تسوية شطب الأدوية منتهية الصلاحية تلقائياً وخصمها من الرصيد الفعلي
    const todayStr = (typeof AppDate !== 'undefined' && AppDate.getTodayYMD) ? AppDate.getTodayYMD() : new Date().toISOString().substring(0, 10);
    let changed = false;

    meds.forEach(m => {
        if (m.expiry_date && String(m.expiry_date).substring(0, 10) <= todayStr && (parseInt(m.stock_quantity) || 0) > 0) {
            const expiredQty = parseInt(m.stock_quantity);
            m.stock_quantity = 0;
            m.is_expired = true;
            changed = true;

            try {
                const currentOrders = getPersistentOrders();
                const now = new Date();
                const totalLoss = parseFloat((expiredQty * (parseFloat(m.price) || 20)).toFixed(2));
                const disposalOrder = {
                    id: currentOrders.length > 0 ? Math.max(...currentOrders.map(o => o.id || 0)) + 1 : 101,
                    invoice_number: `DISPOSAL-${now.getFullYear()}${String(now.getMonth()+1).padStart(2,'0')}${String(now.getDate()).padStart(2,'0')}-${m.id}`,
                    order_type: 'expired_disposal',
                    customer_name: 'لجنة إتلاف الأدوية المنتهية الصلاحية',
                    payment_method: 'loss',
                    payment_status: 'معدوم / تالف',
                    status: 'completed',
                    subtotal: totalLoss,
                    total_amount: totalLoss,
                    created_at: now.toISOString().replace('T', ' ').substring(0, 19),
                    notes: `محضر شطب وإتلاف صنف منتهي الصلاحية [${m.name}] - خصم كامل الرصيد (${expiredQty} علبة) لانتهاء الصلاحية في ${m.expiry_date}`,
                    items: [{
                        medicine_id: m.id,
                        name: m.name,
                        generic_name: m.generic_name || '',
                        quantity: expiredQty,
                        unit_price: parseFloat(m.price) || 20,
                        total_price: totalLoss
                    }]
                };
                currentOrders.unshift(disposalOrder);
                savePersistentOrders(currentOrders);
            } catch (err) {}
        }
    });

    if (changed) {
        savePersistentMedicines(meds);
    }
    return meds;
}

function savePersistentMedicines(meds) {
    try {
        localStorage.setItem('spms_persisted_medicines', JSON.stringify(meds));
    } catch (e) {}
}

class APIClient {
    static getToken() {
        return localStorage.getItem('jwt_token');
    }

    static setToken(token) {
        localStorage.setItem('jwt_token', token);
    }

    static clearToken() {
        localStorage.removeItem('jwt_token');
        localStorage.removeItem('user_data');
    }

    static async request(endpoint, method = 'GET', data = null) {
        const headers = {
            'Accept': 'application/json'
        };

        const token = this.getToken();
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        let bodyData = null;
        if (data && !(data instanceof FormData)) {
            headers['Content-Type'] = 'application/json';
            bodyData = JSON.stringify(data);
        } else if (data instanceof FormData) {
            bodyData = data;
        }

        const urlsToTry = [ACTIVE_API_URL, ...CANDIDATE_API_URLS.filter(u => u !== ACTIVE_API_URL)];

        for (const baseUrl of urlsToTry) {
            try {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 2000);

                const response = await fetch(`${baseUrl}/${endpoint}`, {
                    method,
                    headers,
                    body: bodyData,
                    signal: controller.signal
                });
                clearTimeout(timeoutId);

                if (response.ok || response.status === 400 || response.status === 404 || response.status === 409) {
                    ACTIVE_API_URL = baseUrl;
                    localStorage.setItem('active_api_url', baseUrl);
                    return await response.json();
                }
            } catch (err) {
                // Try next URL
            }
        }

        // Offline Simulation Fallback with Persistent Storage
        return this.handleOfflineFallback(endpoint, method, data);
    }

    static handleOfflineFallback(endpoint, method, data) {
        if (endpoint.includes('auth.php')) {
            if (endpoint.includes('action=register')) {
                const requestedRole = (data && data.role) ? data.role.toLowerCase().trim() : 'staff';
                const normalizedRole = (requestedRole === 'supervisor' || requestedRole === 'admin') ? 'supervisor' : 'staff';
                
                const mockUser = {
                    id: Math.floor(Math.random() * 1000) + 10,
                    username: data.username || 'د. كادر جديد',
                    email: data.email || 'user@pharmacy.com',
                    role: normalizedRole
                };
                return {
                    status: 201,
                    success: true,
                    message: 'تم إنشاء الحساب بنجاح وتوليد مفتاح المصادقة.',
                    data: {
                        token: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.demo_token',
                        user: mockUser
                    }
                };
            }

            // Login action with accurate role resolution (ONLY 2 Roles: Pharmacy Manager & Pharmacist/Cashier)
            const email = (data && data.email) ? data.email.toLowerCase().trim() : '';
            let resolvedUser = {
                id: 1,
                username: 'مدير الصيدلية',
                email: 'manager@hospital.local',
                role: 'supervisor'
            };

            if (email.includes('staff') || email.includes('pharmacist') || (data && data.role === 'staff')) {
                resolvedUser = {
                    id: 2,
                    username: 'الصيدلي (كاشير)',
                    email: 'pharmacist@hospital.local',
                    role: 'staff'
                };
            } else {
                resolvedUser = {
                    id: 1,
                    username: 'مدير الصيدلية',
                    email: email || 'manager@hospital.local',
                    role: 'supervisor'
                };
            }

            return {
                status: 200,
                success: true,
                message: 'تم تسجيل الدخول بنجاح.',
                data: {
                    token: `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.${resolvedUser.role}_demo_token`,
                    user: resolvedUser
                }
            };
        }

        if (endpoint.includes('medicines.php')) {
            const currentMeds = getPersistentMedicines();
            if (method === 'POST' && endpoint.includes('action=restock') && data) {
                const medId = parseInt(data.medicine_id);
                const medName = data.medicine_name || '';
                const qty = parseInt(data.quantity) || 0;
                const unitPrice = parseFloat(data.unit_price) || 20.0;
                const supplierName = data.supplier_name || 'الشركة الوطنية للتموين الطبي';

                let med = currentMeds.find(m => m.id === medId || m.name === medName);
                if (med) {
                    med.stock_quantity = (parseInt(med.stock_quantity) || 0) + qty;
                    savePersistentMedicines(currentMeds);
                }

                // إنشاء فاتورة توريد شحنة دائمة
                const currentOrders = getPersistentOrders();
                const now = new Date();
                const orderId = currentOrders.length > 0 ? Math.max(...currentOrders.map(o => o.id || 0)) + 1 : 101;
                const yyyy = now.getFullYear();
                const mm = String(now.getMonth() + 1).padStart(2, '0');
                const dd = String(now.getDate()).padStart(2, '0');
                const hh = String(now.getHours()).padStart(2, '0');
                const min = String(now.getMinutes()).padStart(2, '0');
                const ss = String(now.getSeconds()).padStart(2, '0');
                const invNum = data.invoice_number || `RESTOCK-${yyyy}${mm}${dd}-${hh}${min}${ss}`;
                const totalAmount = parseFloat((qty * unitPrice).toFixed(2));

                const restockOrder = {
                    id: orderId,
                    invoice_number: invNum,
                    order_type: 'routine_restock',
                    customer_name: supplierName,
                    payment_method: 'card',
                    payment_status: 'مسدد',
                    status: 'completed',
                    subtotal: totalAmount,
                    total_amount: totalAmount,
                    created_at: now.toISOString().replace('T', ' ').substring(0, 19),
                    notes: `توريد وتحديث رصيد الصنف [${med ? med.name : medName}] بإضافة ${qty} علبة`,
                    items: [
                        {
                            medicine_id: med ? med.id : (medId || 1),
                            name: med ? med.name : medName,
                            generic_name: med ? med.generic_name : '',
                            quantity: qty,
                            unit_price: unitPrice,
                            total_price: totalAmount
                        }
                    ]
                };

                currentOrders.unshift(restockOrder);
                savePersistentOrders(currentOrders);

                return {
                    status: 200,
                    success: true,
                    message: `تم توريد الصنف وزيادة رصيد المخزون الفعلي بمقدار ${qty} علبة وإصدار فاتورة التوريد بنجاح.`,
                    data: {
                        medicine: med,
                        invoice: restockOrder
                    }
                };
            }

            if (method === 'POST' && data) {
                const newMed = {
                    id: currentMeds.length + 1,
                    ...data
                };
                currentMeds.unshift(newMed);
                savePersistentMedicines(currentMeds);

                // إنشاء فاتورة توريد أولي إن كان الرصيد أكبر من صفر
                const initialStock = parseInt(data.stock_quantity) || 0;
                if (initialStock > 0) {
                    const currentOrders = getPersistentOrders();
                    const now = new Date();
                    const orderId = currentOrders.length > 0 ? Math.max(...currentOrders.map(o => o.id || 0)) + 1 : 101;
                    const yyyy = now.getFullYear();
                    const mm = String(now.getMonth() + 1).padStart(2, '0');
                    const invNum = `RESTOCK-${yyyy}${mm}-${String(orderId).padStart(3, '0')}`;
                    const price = parseFloat(data.price) || 20.0;
                    const totalAmount = parseFloat((initialStock * price).toFixed(2));
                    
                    const initialOrder = {
                        id: orderId,
                        invoice_number: invNum,
                        order_type: 'routine_restock',
                        customer_name: data.supplier_name || 'الشركة الوطنية للتموين الطبي',
                        payment_method: 'card',
                        payment_status: 'مسدد',
                        status: 'completed',
                        subtotal: totalAmount,
                        total_amount: totalAmount,
                        created_at: now.toISOString().replace('T', ' ').substring(0, 19),
                        notes: `إدراج وتوريد أولي للصنف [${data.name}] برصيد ${initialStock} علبة`,
                        items: [
                            {
                                medicine_id: newMed.id,
                                name: newMed.name,
                                generic_name: newMed.generic_name || '',
                                quantity: initialStock,
                                unit_price: price,
                                total_price: totalAmount
                            }
                        ]
                    };
                    currentOrders.unshift(initialOrder);
                    savePersistentOrders(currentOrders);
                }

                return {
                    status: 201,
                    success: true,
                    message: 'تم حفظ الدواء وإصدار فاتورة التوريد بنجاح.',
                    data: newMed
                };
            }
            return {
                status: 200,
                success: true,
                message: 'تم استرجاع قائمة الأدوية بنجاح.',
                data: currentMeds
            };
        }

        if (endpoint.includes('suppliers.php')) {
            if (method === 'POST' && data) {
                const newSup = {
                    id: DEMO_SUPPLIERS.length + 1,
                    ...data
                };
                DEMO_SUPPLIERS.push(newSup);
                return {
                    status: 201,
                    success: true,
                    message: 'تم حفظ المورد بنجاح.',
                    data: newSup
                };
            }
            return {
                status: 200,
                success: true,
                message: 'تم استرجاع بيانات الموردين بنجاح.',
                data: DEMO_SUPPLIERS
            };
        }

        if (endpoint.includes('orders.php')) {
            const currentOrders = getPersistentOrders();
            const currentMeds = getPersistentMedicines();

            if (method === 'POST' && data) {
                const now = new Date();
                const dateStr = now.toISOString().replace('T', ' ').substring(0, 19);
                const orderId = currentOrders.length > 0 ? Math.max(...currentOrders.map(o => o.id || 0)) + 1 : 101;
                const yyyy = now.getFullYear();
                const mm = String(now.getMonth() + 1).padStart(2, '0');
                const dd = String(now.getDate()).padStart(2, '0');
                const hh = String(now.getHours()).padStart(2, '0');
                const min = String(now.getMinutes()).padStart(2, '0');
                const ss = String(now.getSeconds()).padStart(2, '0');
                const invNum = data.invoice_number || `INV-${yyyy}${mm}${dd}-${hh}${min}${ss}`;

                let subtotal = 0;
                const enrichedItems = (data.items || []).map(item => {
                    const med = currentMeds.find(m => m.id === parseInt(item.medicine_id));
                    const unitPrice = parseFloat(item.unit_price || (med ? med.price : 0));
                    const qty = parseInt(item.quantity || 1);
                    const lineTotal = unitPrice * qty;
                    subtotal += lineTotal;

                    // تحديث رصيد المخزون الدائم
                    if (med) {
                        if (data.order_type === 'routine_restock') {
                            med.stock_quantity = (med.stock_quantity || 0) + qty;
                        } else {
                            med.stock_quantity = Math.max(0, (med.stock_quantity || 0) - qty);
                        }
                    }

                    return {
                        medicine_id: item.medicine_id,
                        name: med ? med.name : `دواء #${item.medicine_id}`,
                        generic_name: med ? med.generic_name : '',
                        quantity: qty,
                        unit_price: unitPrice,
                        total_price: lineTotal
                    };
                });

                savePersistentMedicines(currentMeds);

                const discount = parseFloat(data.discount || 0);
                const taxRate = parseFloat(data.tax_rate || 0);
                const taxableBase = Math.max(0, subtotal - discount);
                const taxAmount = parseFloat((taxableBase * (taxRate / 100)).toFixed(2));
                const totalAmount = parseFloat((taxableBase + taxAmount).toFixed(2));

                const newOrder = {
                    id: orderId,
                    invoice_number: invNum,
                    order_type: data.order_type || 'patient_sale',
                    customer_name: data.customer_name || 'عميل نقدي / صيدلية',
                    payment_method: data.payment_method || 'cash',
                    payment_status: 'مسدد',
                    status: 'completed',
                    subtotal: subtotal,
                    discount: discount,
                    tax_rate: taxRate,
                    tax_amount: taxAmount,
                    total_amount: totalAmount,
                    created_at: dateStr,
                    items: enrichedItems,
                    notes: data.notes || ''
                };

                currentOrders.unshift(newOrder);
                savePersistentOrders(currentOrders);

                // مزامنة سحابية مع Supabase Cloud
                if (window.SupabaseDB && typeof window.SupabaseDB.saveOrder === 'function') {
                    window.SupabaseDB.saveOrder(newOrder, enrichedItems).catch(() => {});
                }

                // إشعار غير مانع لمحرك الذكاء الاصطناعي لإعادة التدريب الفوري
                try {
                    fetch(`${AI_ENGINE_URL}/retrain`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            transactions: [{ items: enrichedItems.map(i => ({ medicine_id: i.medicine_id, quantity: i.quantity })) }],
                            source: "frontend_pos_dispense"
                        })
                    }).catch(() => {});
                } catch(e) {}

                return {
                    status: 201,
                    success: true,
                    message: 'تم إصدار الفاتورة وحفظها وتحديث المخزون بنجاح (محلياً وسحابياً عبر Supabase).',
                    data: newOrder
                };
            }

            // فحص طلب تفاصيل فاتورة واحدة
            const urlParams = new URLSearchParams(endpoint.split('?')[1] || '');
            const targetId = urlParams.get('id');
            if (targetId) {
                const single = currentOrders.find(o => o.id === parseInt(targetId));
                if (single) {
                    return { status: 200, success: true, message: 'تم استرجاع تفاصيل الفاتورة.', data: single };
                }
            }

            return {
                status: 200,
                success: true,
                message: 'تم استرجاع قائمة فواتير المبيعات بنجاح.',
                data: currentOrders
            };
        }

        return {
            status: 200,
            success: true,
            message: 'تمت المعالجة بنجاح.',
            data: []
        };
    }
}

class AIEngine {
    // Comprehensive Arabic & English Medical Synonyms & Indications Dictionary
    static MEDICAL_SYNONYMS = {
        'صداع': ['panadol', 'paracetamol', 'brufen', 'voltaren', 'cataflam', 'solpadeine', 'aspirin', 'adol', 'fevadol'],
        'مسكن': ['panadol', 'paracetamol', 'brufen', 'voltaren', 'cataflam', 'tramadol', 'ketorolac', 'celebrex', 'profenid'],
        'الم': ['panadol', 'paracetamol', 'brufen', 'voltaren', 'cataflam', 'tramadol', 'ketorolac', 'celebrex'],
        'وجع': ['panadol', 'paracetamol', 'brufen', 'voltaren', 'cataflam'],
        'حرارة': ['panadol', 'paracetamol', 'brufen', 'tempra', 'fever', 'adol', 'fevadol'],
        'سخونة': ['panadol', 'paracetamol', 'brufen', 'tempra', 'adol', 'fevadol'],
        'حمى': ['panadol', 'paracetamol', 'brufen', 'tempra', 'adol', 'fevadol'],
        'سكر': ['metformin', 'glucophage', 'januvia', 'diamicron', 'insulin', 'jardiance', 'forxiga', 'lantus', 'novorapid', 'amaryl', 'galvus'],
        'سكري': ['metformin', 'glucophage', 'januvia', 'diamicron', 'insulin', 'jardiance', 'forxiga', 'lantus', 'novorapid', 'amaryl', 'galvus'],
        'ضغط': ['concor', 'amlodipine', 'valsartan', 'lisinopril', 'capoten', 'losartan', 'exforge', 'norvasc', 'inderal', 'atacand', 'coversyl'],
        'قلب': ['concor', 'amlodipine', 'valsartan', 'digoxin', 'plavix', 'aspirin', 'isordil', 'nitroglycerin', 'cordarone'],
        'مضاد': ['augmentin', 'ciprofloxacin', 'amoxicillin', 'zithromax', 'klacid', 'rocephin', 'ceftriaxone', 'flagyl', 'tavanic', 'curam'],
        'بكتيريا': ['augmentin', 'ciprofloxacin', 'amoxicillin', 'zithromax', 'klacid', 'rocephin', 'ceftriaxone', 'flagyl', 'tavanic'],
        'التهاب': ['augmentin', 'amoxicillin', 'brufen', 'voltaren', 'celebrex', 'cataflam', 'rocephin', 'ciprofloxacin'],
        'معدة': ['nexium', 'controloc', 'omeprazole', 'pantoprazole', 'gaviscon', 'librax', 'duspatalin', 'motilium', 'ranitidine', 'famotidine', 'losec'],
        'حموضة': ['nexium', 'controloc', 'omeprazole', 'pantoprazole', 'gaviscon', 'rennie', 'maalox', 'tums', 'losec'],
        'حرقة': ['nexium', 'controloc', 'omeprazole', 'pantoprazole', 'gaviscon', 'rennie', 'maalox'],
        'ارتجاع': ['nexium', 'controloc', 'omeprazole', 'pantoprazole', 'gaviscon'],
        'قولون': ['duspatalin', 'librax', 'colona', 'spasmomen', 'mebeverine', 'colofac'],
        'مغص': ['buscopan', 'duspatalin', 'spasmocanulase', 'visceralgine'],
        'حساسية': ['claritine', 'zyrtec', 'telfast', 'aerius', 'histop', 'loratadine', 'cetirizine', 'fexofenadine'],
        'زكام': ['panadol cold', 'fludrex', 'comtrex', 'clarinase', 'otrivin', 'actifed'],
        'رشح': ['panadol cold', 'fludrex', 'comtrex', 'clarinase', 'otrivin', 'actifed'],
        'انفلونزا': ['panadol cold', 'fludrex', 'tamiflu', 'comtrex'],
        'كحة': ['prospan', 'bronchicum', 'sinecod', 'cough', 'notuss', 'codipront', 'tussivan'],
        'سعال': ['prospan', 'bronchicum', 'sinecod', 'notuss', 'codipront'],
        'بلغم': ['mucosolvan', 'bisolvon', 'solmucol', 'prospan', 'ambroxol'],
        'دهون': ['lipitor', 'atorvastatin', 'crestor', 'rosuvastatin', 'ezetrol', 'zocor', 'simvastatin'],
        'كولسترول': ['lipitor', 'atorvastatin', 'crestor', 'rosuvastatin', 'ezetrol', 'zocor', 'simvastatin'],
        'ربو': ['ventolin', 'symbicort', 'seretide', 'salbutamol', 'pulmicort', 'singulair', 'montelukast'],
        'ضيق تنفس': ['ventolin', 'symbicort', 'seretide', 'salbutamol', 'pulmicort', 'atrovent'],
        'فيتامين': ['vitamin d', 'vitamin c', 'neurobion', 'b12', 'feroglobin', 'zinc', 'omega 3', 'centrum', 'vidrop'],
        'حديد': ['feroglobin', 'ferose', 'fe-fol', 'ferrous', 'iron'],
        'انيميا': ['feroglobin', 'ferose', 'fe-fol', 'iron'],
        'ارق': ['panadol night', 'melatonin', 'dormicum', 'stilnox'],
        'نوم': ['panadol night', 'melatonin', 'dormicum', 'stilnox']
    };

    // Arabic to English phonetic mappings
    static ARABIC_PHONETICS = {
        'بانادول': 'panadol', 'بندول': 'panadol', 'بنادول': 'panadol', 'باراسيتامول': 'paracetamol',
        'فيفادول': 'fevadol', 'ادول': 'adol',
        'كونكور': 'concor', 'نيكسيوم': 'nexium', 'اوغمنتين': 'augmentin', 'اوجمنتين': 'augmentin',
        'أوجمنتين': 'augmentin', 'فولتارين': 'voltaren', 'فلترين': 'voltaren', 'فلتارين': 'voltaren',
        'بروفين': 'brufen', 'كتافلام': 'cataflam', 'سيليبريكس': 'celebrex',
        'ميتفورمين': 'metformin', 'جلوكوفاج': 'glucophage', 'جانوفيا': 'januvia',
        'ليبوتور': 'lipitor', 'ليبيتور': 'lipitor', 'اتورفاستاتين': 'atorvastatin',
        'كريستور': 'crestor', 'روزوفاستاتين': 'rosuvastatin',
        'فنتولين': 'ventolin', 'فينتولين': 'ventolin', 'سيمبيكورت': 'symbicort',
        'زيرتك': 'zyrtec', 'كلاريتين': 'claritine', 'تيلفاست': 'telfast', 'ايريوس': 'aerius',
        'سيبروفلوكساسين': 'ciprofloxacin', 'اموكسيسيلين': 'amoxicillin', 'فلاجيل': 'flagyl',
        'بسكوبان': 'buscopan', 'دوسباتالين': 'duspatalin', 'ليبراكس': 'librax',
        'اميلوديبين': 'amlodipine', 'فالسارتان': 'valsartan', 'كابوتن': 'capoten',
        'بلافيكس': 'plavix', 'اسبرين': 'aspirin', 'سولبادين': 'solpadeine'
    };

    static normalizeArabic(text) {
        if (!text) return '';
        return String(text)
            .trim()
            .toLowerCase()
            .replace(/[أإآٱ]/g, 'ا')
            .replace(/[ة]/g, 'ه')
            .replace(/[ى]/g, 'ي')
            .replace(/[ؤ]/g, 'و')
            .replace(/[ئ]/g, 'ي')
            .replace(/[ً-ٰٟـ]/g, '')
            .replace(/[^\w\s\u0600-\u06FF]/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();
    }

    static stringSimilarity(s1, s2) {
        if (s1 === s2) return 1.0;
        if (!s1 || !s2) return 0.0;
        s1 = String(s1).toLowerCase();
        s2 = String(s2).toLowerCase();
        let longer = s1.length > s2.length ? s1 : s2;
        let shorter = s1.length > s2.length ? s2 : s1;
        let longerLength = longer.length;
        if (longerLength === 0) return 1.0;
        
        let costs = [];
        for (let i = 0; i <= s1.length; i++) {
            let lastValue = i;
            for (let j = 0; j <= s2.length; j++) {
                if (i === 0) costs[j] = j;
                else if (j > 0) {
                    let newValue = costs[j - 1];
                    if (s1.charAt(i - 1) !== s2.charAt(j - 1))
                        newValue = Math.min(Math.min(newValue, lastValue), costs[j]) + 1;
                    costs[j - 1] = lastValue;
                    lastValue = newValue;
                }
            }
            if (i > 0) costs[s2.length] = lastValue;
        }
        return (longerLength - costs[s2.length]) / longerLength;
    }

    static calculateArabicSimilarity(query, target) {
        if (!query || !target) return 0;
        const qNorm = AIEngine.normalizeArabic(query);
        const tNorm = AIEngine.normalizeArabic(target);

        if (qNorm === tNorm) return 1.0;
        if (tNorm.includes(qNorm)) return 0.98;
        if (qNorm.includes(tNorm) && tNorm.length >= 3) return 0.95;

        // Check phonetic transliteration
        for (const [ar, en] of Object.entries(AIEngine.ARABIC_PHONETICS)) {
            const arNorm = AIEngine.normalizeArabic(ar);
            if (qNorm.includes(arNorm) || arNorm.includes(qNorm)) {
                if (tNorm.includes(en) || tNorm.includes(arNorm)) return 0.96;
            }
        }

        // Check therapeutic & symptom synonym matches
        for (const [sym, meds] of Object.entries(AIEngine.MEDICAL_SYNONYMS)) {
            const symNorm = AIEngine.normalizeArabic(sym);
            if (qNorm.includes(symNorm) || symNorm.includes(qNorm)) {
                for (const med of meds) {
                    if (tNorm.includes(med) || tNorm.includes(AIEngine.normalizeArabic(med))) {
                        return 0.92;
                    }
                }
            }
        }

        const delimiters = ['(', ')', '/', '-', '_', ',', '+', '[', ']', '•', '،'];
        let cleanTarget = tNorm;
        for (const d of delimiters) {
            cleanTarget = cleanTarget.replaceAll(d, ' ');
        }
        const tokens = cleanTarget.split(/\s+/).filter(Boolean);
        let bestRatio = 0;

        for (const tok of tokens) {
            if (tok === qNorm) return 1.0;
            if (tok.includes(qNorm) || qNorm.includes(tok)) {
                bestRatio = Math.max(bestRatio, 0.88);
            }
            const sim = AIEngine.stringSimilarity(qNorm, tok);
            if (sim > bestRatio) bestRatio = sim;
        }

        // Skeleton comparison (remove Arabic vowels)
        const skel = (s) => s.replace(/[اوي]/g, '');
        const qSkel = skel(qNorm);
        if (qSkel.length >= 2) {
            for (const tok of tokens) {
                const tSkel = skel(tok);
                if (tSkel.length >= 2) {
                    if (qSkel === tSkel) {
                        bestRatio = Math.max(bestRatio, 0.92);
                    } else if (tSkel.includes(qSkel) || qSkel.includes(tSkel)) {
                        bestRatio = Math.max(bestRatio, 0.80);
                    }
                }
            }
        }

        return Math.round(bestRatio * 1000) / 1000;
    }

    static async smartSearch(query, medicines) {
        query = (query || '').trim();
        const list = (medicines && medicines.length > 0) ? medicines : (typeof DEMO_MEDICINES !== 'undefined' ? DEMO_MEDICINES : []);
        
        if (!query) {
            return { success: true, query: '', total_results: list.length, data: list };
        }

        // Client-side high-precision multi-attribute fuzzy search
        const results = list.map(m => {
            const nameScore = AIEngine.calculateArabicSimilarity(query, m.name || '');
            const genericScore = AIEngine.calculateArabicSimilarity(query, m.generic_name || m.generic_name_ar || '');
            const catScore = AIEngine.calculateArabicSimilarity(query, m.category || '');
            const indScore = AIEngine.calculateArabicSimilarity(query, m.indications || '');
            const altScore = AIEngine.calculateArabicSimilarity(query, (m.alternatives || []).join(' '));

            const maxScore = Math.max(
                nameScore * 1.0,
                genericScore * 0.96,
                catScore * 0.75,
                indScore * 0.85,
                altScore * 0.80
            );

            return {
                ...m,
                relevance_score: Math.round(Math.min(maxScore, 1.0) * 1000) / 1000
            };
        }).sort((a, b) => b.relevance_score - a.relevance_score);

        const topScore = results.length > 0 ? results[0].relevance_score : 0;
        const minThreshold = topScore >= 0.80 ? Math.max(0.55, topScore - 0.35) : 0.40;
        const filteredResults = results.filter(m => m.relevance_score >= minThreshold);

        return {
            success: true,
            query: query,
            total_results: filteredResults.length,
            data: filteredResults
        };
    }
}


// Bind methods and export globals for universal availability
APIClient.smartSearch = AIEngine.smartSearch;
APIClient.calculateArabicSimilarity = AIEngine.calculateArabicSimilarity;
APIClient.normalizeArabic = AIEngine.normalizeArabic;

if (typeof window !== 'undefined') {
    window.APIClient = APIClient;
    window.AIEngine = AIEngine;
    window.MASTER_FORMULARY = MASTER_FORMULARY;
    window.DEMO_MEDICINES = DEMO_MEDICINES;
    window.DEMO_ORDERS = DEMO_ORDERS;
    window.DEMO_SUPPLIERS = DEMO_SUPPLIERS;
    window.getPersistentOrders = getPersistentOrders;
    window.savePersistentOrders = savePersistentOrders;
}

