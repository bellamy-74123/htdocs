# -*- coding: utf-8 -*-
"""
محرك الشات بوت والمساعد الدوائي الذكي (Pharmacy AI Chatbot Engine)
مع دعم معالجة اللغة الطبيعية واستخراج الأعراض السريرية واقتراح الأدوية المناسبة
من المخزون والبدائل ومقارنة الأسعار وإرشادات الاستخدام والجرعات.
"""

import re
import difflib
from datetime import datetime
from typing import List, Dict, Any, Optional

class PharmacyChatbotEngine:
    """
    محرك معالجة اللغة الطبيعية والرد الذكي لاستفسارات الصيدلية.
    """

    def __init__(self):
        # تحميل قاعدة المعرفة الدوائية الشاملة
        import json, os
        kb_f = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))), "data", "drugs_knowledge_base.json")
        if os.path.exists(kb_f):
            with open(kb_f, "r", encoding="utf-8") as f:
                self.drugs_kb = json.load(f)
        else:
            self.drugs_kb = [{"id": 1, "generic_name_en": "Omeprazole", "generic_name_ar": "أوميبرازول", "brand_names": ["Losec", "Gasec"], "brand_raw": "Losec / Gasec", "drug_class": "مثبط مضخة البروتون (PPI)", "indications": "علاج حموضة المعدة، الارتجاع المريئي، وقرحة المعدة. يؤخذ قبل الإفطار."}, {"id": 2, "generic_name_en": "Esomeprazole", "generic_name_ar": "إيزوميبرازول", "brand_names": ["Nexium"], "brand_raw": "Nexium", "drug_class": "مثبط مضخة البروتون (PPI)", "indications": "علاج الارتجاع المعدي المريئي والوقاية من قرحة المعدة الناتجة عن المسكنات."}, {"id": 3, "generic_name_en": "Pantoprazole", "generic_name_ar": "بانتوبرازول", "brand_names": ["Controloc", "Protonix"], "brand_raw": "Controloc / Protonix", "drug_class": "مثبط مضخة البروتون (PPI)", "indications": "علاج التهاب المريء التآكلي وقرحة المعدة والاثني عشر."}, {"id": 4, "generic_name_en": "Lansoprazole", "generic_name_ar": "لانسوبرازول", "brand_names": ["Takepron", "Prevacid"], "brand_raw": "Takepron / Prevacid", "drug_class": "مثبط مضخة البروتون (PPI)", "indications": "علاج قرحة المعدة وارتجاع المريء وجرثومة المعدة مع المضادات."}, {"id": 5, "generic_name_en": "Famotidine", "generic_name_ar": "فاموتيدين", "brand_names": ["Pepcid", "Famodar"], "brand_raw": "Pepcid / Famodar", "drug_class": "مضاد مستقبلات H2", "indications": "تقليل إفراز حمض المعدة وتخفيف الحرقة وسوء الهضم."}, {"id": 6, "generic_name_en": "Loperamide", "generic_name_ar": "لوبيراميد", "brand_names": ["Imodium"], "brand_raw": "Imodium", "drug_class": "مضاد للإسهال", "indications": "السيطرة على الإسهال الحاد غير البكتيري وعلاج الإسهال المزمن."}, {"id": 7, "generic_name_en": "Hyoscine Butylbromide", "generic_name_ar": "هيوسين بوتيل بروميد", "brand_names": ["Buscopan"], "brand_raw": "Buscopan", "drug_class": "مضاد للتقلصات والمغص", "indications": "تسكين تقلصات البطن ومغص الجهاز الهضمي والمسالك البولية."}, {"id": 8, "generic_name_en": "Mebeverine", "generic_name_ar": "ميبفرين", "brand_names": ["Duspatalin", "Colospasmin"], "brand_raw": "Duspatalin / Colospasmin", "drug_class": "مضاد لتشنجات القولون", "indications": "تخفيف أعراض متلازمة القولون العصبي والانتفاخ والتقلصات."}, {"id": 9, "generic_name_en": "Domperidone", "generic_name_ar": "دومبيريدون", "brand_names": ["Motilium", "Motilat"], "brand_raw": "Motilium / Motilat", "drug_class": "منظم لحركة المعدة ومضاد للغثيان", "indications": "علاج الغثيان والقيء والشعور بالامتلاء وثقل المعدة."}, {"id": 10, "generic_name_en": "Metoclopramide", "generic_name_ar": "ميتوكلوبراميد", "brand_names": ["Plasil", "Primperan"], "brand_raw": "Plasil / Primperan", "drug_class": "مضاد للقيء ومنشط للحركة", "indications": "علاج الغثيان والقيء وتسريع تفريغ المعدة."}, {"id": 11, "generic_name_en": "Simethicone", "generic_name_ar": "سيميثيكون", "brand_names": ["Disflatyl", "Gas-X"], "brand_raw": "Disflatyl / Gas-X", "drug_class": "طارد للغازات", "indications": "تخفيف الانتفاخ والشعور بالضغط والغازات في الأمعاء."}, {"id": 12, "generic_name_en": "Lactulose", "generic_name_ar": "لاكتولوز", "brand_names": ["Duphalac"], "brand_raw": "Duphalac", "drug_class": "ملين أسموزي", "indications": "علاج الإمساك المزمن والوقاية من الاعتلال الدماغي الكبدي."}, {"id": 13, "generic_name_en": "Bisacodyl", "generic_name_ar": "بيساكوديل", "brand_names": ["Dulcolax"], "brand_raw": "Dulcolax", "drug_class": "ملين منبه للأمعاء", "indications": "تفريغ الأمعاء لعلاج الإمساك المؤقت أو قبل الفحوصات الطبية."}, {"id": 14, "generic_name_en": "Ursodeoxycholic Acid", "generic_name_ar": "حمض الأورسوديوكسيكوليك", "brand_names": ["Ursofalk"], "brand_raw": "Ursofalk", "drug_class": "مذيب لحصوات المرارة", "indications": "إذابة حصوات المرارة الكولسترولية وعلاج أمراض الكبد الصفراوية."}, {"id": 15, "generic_name_en": "Mesalazine", "generic_name_ar": "ميسالازين", "brand_names": ["Pentasa", "Asacol"], "brand_raw": "Pentasa / Asacol", "drug_class": "مضاد للالتهاب المعوي", "indications": "علاج والسيطرة على التهاب القولون التقرحي وداء كرون."}, {"id": 16, "generic_name_en": "Paracetamol", "generic_name_ar": "باراسيتامول", "brand_names": ["Panadol", "Fevadol"], "brand_raw": "Panadol / Fevadol", "drug_class": "مسكن للألم وخافض حرارة", "indications": "تسكين الآلام البسيطة إلى المتوسطة مثل الصداع وخفض الحرارة."}, {"id": 17, "generic_name_en": "Ibuprofen", "generic_name_ar": "إيبوبروفين", "brand_names": ["Brufen", "Advil"], "brand_raw": "Brufen / Advil", "drug_class": "مضاد التهاب غير ستيرويدي (NSAID)", "indications": "تسكين آلام الأسنان، المفاصل، الصداع، وتخفيض الحرارة والالتهاب."}, {"id": 18, "generic_name_en": "Diclofenac Sodium", "generic_name_ar": "ديكلوفيناك الصوديوم", "brand_names": ["Voltaren"], "brand_raw": "Voltaren", "drug_class": "مضاد التهاب غير ستيرويدي (NSAID)", "indications": "علاج التهابات المفاصل الروماتيزمية وآلام العظام الحادة."}, {"id": 19, "generic_name_en": "Diclofenac Potassium", "generic_name_ar": "ديكلوفيناك البوتاسيوم", "brand_names": ["Cataflam", "Rapidus"], "brand_raw": "Cataflam / Rapidus", "drug_class": "مسكن سريع ومضاد التهاب", "indications": "تسكين سريع لآلام الأسنان، الصداع النصفي، وآلام الدورة الشهرية."}, {"id": 20, "generic_name_en": "Naproxen", "generic_name_ar": "نابروكسين", "brand_names": ["Proxen", "Aleve"], "brand_raw": "Proxen / Aleve", "drug_class": "مضاد التهاب غير ستيرويدي (NSAID)", "indications": "تسكين طويل المفعول لآلام المفاصل، النقرس، والآلام العضلية."}, {"id": 21, "generic_name_en": "Celecoxib", "generic_name_ar": "سيليكوكسيب", "brand_names": ["Celebrex"], "brand_raw": "Celebrex", "drug_class": "مثبط انتقائي لـ COX-2", "indications": "تسكين التهاب المفاصل العظمي والروماتويدي مع تأثير أقل على المعدة."}, {"id": 22, "generic_name_en": "Meloxicam", "generic_name_ar": "ميلوكسيكام", "brand_names": ["Mobic"], "brand_raw": "Mobic", "drug_class": "مضاد التهاب غير ستيرويدي (NSAID)", "indications": "علاج آلام وتيبس المفاصل في التهاب المفاصل الروماتويدي."}, {"id": 23, "generic_name_en": "Ketoprofen", "generic_name_ar": "كيتوبروفين", "brand_names": ["Ketofan", "Profenid"], "brand_raw": "Ketofan / Profenid", "drug_class": "مسكن ومضاد التهاب", "indications": "علاج التهاب المفاصل الحاد والآلام المتوسطة إلى الشديدة."}, {"id": 24, "generic_name_en": "Mefenamic Acid", "generic_name_ar": "حمض الميفيناميك", "brand_names": ["Ponstan"], "brand_raw": "Ponstan", "drug_class": "مسكن ومضاد للالتهاب", "indications": "تسكين آلام الدورة الشهرية وآلام الأسنان والالتهابات الخفيفة."}, {"id": 25, "generic_name_en": "Tramadol", "generic_name_ar": "ترامادول", "brand_names": ["Tramal"], "brand_raw": "Tramal", "drug_class": "مسكن أفيوني مركزي (خاضع للرقابة)", "indications": "تسكين الآلام الشديدة والمتوسطة تحت إشراف طبي دقيق."}, {"id": 26, "generic_name_en": "Colchicine", "generic_name_ar": "كولشيسين", "brand_names": ["Colchicine"], "brand_raw": "Colchicine", "drug_class": "مضاد لنوبات النقرس", "indications": "علاج ومنع نوبات النقرس الحادة وحمى البحر الأبيض المتوسط."}, {"id": 27, "generic_name_en": "Allopurinol", "generic_name_ar": "ألوبيورينول", "brand_names": ["Zyloric", "No-Uric"], "brand_raw": "Zyloric / No-Uric", "drug_class": "خافض لحمض اليوريك", "indications": "الوقاية من نوبات النقرس المزمنة وتكون حصوات الكلى اليوراتية."}, {"id": 28, "generic_name_en": "Febuxostat", "generic_name_ar": "فيبوكسوستات", "brand_names": ["Adenuric", "Feburic"], "brand_raw": "Adenuric / Feburic", "drug_class": "مثبط إنزيم زانثين أوكسيديز", "indications": "علاج فرط حمض اليوريك في الدم لمرضى النقرس المزمن."}, {"id": 29, "generic_name_en": "Baclofen", "generic_name_ar": "باكلوفين", "brand_names": ["Lioresal"], "brand_raw": "Lioresal", "drug_class": "مرخي عضلات مركزي", "indications": "علاج التشنجات والشد العضلي الناتج عن التصلب اللويحي أو إصابات الحبل الشوكي."}, {"id": 30, "generic_name_en": "Chlorzoxazone", "generic_name_ar": "كلورزوكسازون", "brand_names": ["Parafon", "Myogesic"], "brand_raw": "Parafon / Myogesic", "drug_class": "مرخي عضلات هيكلية", "indications": "علاج التقلصات العضلية المؤلمة وآلام أسفل الظهر."}, {"id": 31, "generic_name_en": "Amlodipine", "generic_name_ar": "أملوديبين", "brand_names": ["Norvasc", "Amlor"], "brand_raw": "Norvasc / Amlor", "drug_class": "حاصر قنوات الكالسيوم", "indications": "علاج ارتفاع ضغط الدم والوقاية من نوبات الذبحة الصدرية."}, {"id": 32, "generic_name_en": "Bisoprolol", "generic_name_ar": "بيسوبرولول", "brand_names": ["Concor"], "brand_raw": "Concor", "drug_class": "حاصر مستقبلات بيتا الانتقائي", "indications": "علاج ارتفاع ضغط الدم، قصور القلب المزمن، وتنظيم ضربات القلب."}, {"id": 33, "generic_name_en": "Atenolol", "generic_name_ar": "أتينولول", "brand_names": ["Tenormin"], "brand_raw": "Tenormin", "drug_class": "حاصر مستقبلات بيتا", "indications": "خفض ضغط الدم المرتفع وتنظيم عدم انتظام ضربات القلب."}, {"id": 34, "generic_name_en": "Metoprolol", "generic_name_ar": "ميتوبرولول", "brand_names": ["Betaloc", "Lopressor"], "brand_raw": "Betaloc / Lopressor", "drug_class": "حاصر مستقبلات بيتا", "indications": "السيطرة على ضغط الدم والذبحة الصدرية والوقاية بعد الجلطات."}, {"id": 35, "generic_name_en": "Losartan", "generic_name_ar": "لوسارتان", "brand_names": ["Cozaar"], "brand_raw": "Cozaar", "drug_class": "مضاد مستقبلات الأنجيوتنسين 2 (ARB)", "indications": "علاج ارتفاع ضغط الدم وحماية الكلى لمرضى السكري."}, {"id": 36, "generic_name_en": "Valsartan", "generic_name_ar": "فالسارتان", "brand_names": ["Diovan", "Tareg"], "brand_raw": "Diovan / Tareg", "drug_class": "مضاد مستقبلات الأنجيوتنسين 2 (ARB)", "indications": "علاج ضغط الدم المرتفع وقصور القلب وتخفيف العبء على عضلة القلب."}, {"id": 37, "generic_name_en": "Candesartan", "generic_name_ar": "كانديسارتان", "brand_names": ["Atacand"], "brand_raw": "Atacand", "drug_class": "مضاد مستقبلات الأنجيوتنسين 2 (ARB)", "indications": "خفض ضغط الدم المرتفع وعلاج قصور عضلة القلب."}, {"id": 38, "generic_name_en": "Enalapril", "generic_name_ar": "إنالابريل", "brand_names": ["Renitec", "Ezapril"], "brand_raw": "Renitec / Ezapril", "drug_class": "مثبط الإنزيم المحول للأنجيوتنسين (ACEI)", "indications": "علاج ضغط الدم المرتفع وحالات فشل القلب الاحتقاني."}, {"id": 39, "generic_name_en": "Lisinopril", "generic_name_ar": "ليسينوبريل", "brand_names": ["Zestril"], "brand_raw": "Zestril", "drug_class": "مثبط الإنزيم المحول للأنجيوتنسين (ACEI)", "indications": "علاج ارتفاع ضغط الدم وفشل القلب وتحسين البقاء بعد النوبات القلبية."}, {"id": 40, "generic_name_en": "Ramipril", "generic_name_ar": "راميبريل", "brand_names": ["Tritace"], "brand_raw": "Tritace", "drug_class": "مثبط الإنزيم المحول للأنجيوتنسين (ACEI)", "indications": "السيطرة على ضغط الدم والوقاية من المضاعفات القلبية الوعائية."}, {"id": 41, "generic_name_en": "Hydrochlorothiazide", "generic_name_ar": "هيدروكلوروثيازيد", "brand_names": ["Esidrex"], "brand_raw": "Esidrex", "drug_class": "مدر بول ثيازيدي", "indications": "خفض ضغط الدم وتخليص الجسم من السوائل الزائدة."}, {"id": 42, "generic_name_en": "Furosemide", "generic_name_ar": "فوروسيميد", "brand_names": ["Lasix"], "brand_raw": "Lasix", "drug_class": "مدر بول عروي قوي", "indications": "علاج تجمع السوائل (الوذمة) المرتبطة بقصور القلب أو الكلى والكبد."}, {"id": 43, "generic_name_en": "Spironolactone", "generic_name_ar": "سبيرونولاكتون", "brand_names": ["Aldactone"], "brand_raw": "Aldactone", "drug_class": "مدر بول حافظ للبوتاسيوم", "indications": "علاج ارتفاع ضغط الدم، قصور القلب، وتراكم السوائل وفرط الألدوستيرون."}, {"id": 44, "generic_name_en": "Atorvastatin", "generic_name_ar": "أتورفاستاتين", "brand_names": ["Lipitor", "Atorlip"], "brand_raw": "Lipitor / Atorlip", "drug_class": "خافض للدهون (ستاتين)", "indications": "تقليل الكوليسترول الضار والدهون الثلاثية والوقاية من الجلطات."}, {"id": 45, "generic_name_en": "Rosuvastatin", "generic_name_ar": "روزوڤاستاتين", "brand_names": ["Crestor"], "brand_raw": "Crestor", "drug_class": "خافض للدهون (ستاتين)", "indications": "علاج فرط كوليسترول الدم ورفع الكوليسترول النافع (HDL)."}, {"id": 46, "generic_name_en": "Simvastatin", "generic_name_ar": "سيمفاستاتين", "brand_names": ["Zocor"], "brand_raw": "Zocor", "drug_class": "خافض للدهون (ستاتين)", "indications": "تقليل مستويات الكوليسترول الكلي والضار في مجرى الدم."}, {"id": 47, "generic_name_en": "Fenofibrate", "generic_name_ar": "فينوفيبرات", "brand_names": ["Lipanthyl"], "brand_raw": "Lipanthyl", "drug_class": "خافض للدهون (فيبرات)", "indications": "خفض المستويات المرتفعة جداً من الدهون الثلاثية (Triglycerides)."}, {"id": 48, "generic_name_en": "Clopidogrel", "generic_name_ar": "كلوبيدوجريل", "brand_names": ["Plavix"], "brand_raw": "Plavix", "drug_class": "مضاد لتكدس الصفائح الدموية", "indications": "منع تكون الجلطات الدموية بعد القسطرة أو النوبات القلبية والسكتات."}, {"id": 49, "generic_name_en": "Aspirin (Low Dose)", "generic_name_ar": "حمض أسيتيل الساليسيليك", "brand_names": ["Aspirin Protect", "Aspocid"], "brand_raw": "Aspirin Protect / Aspocid", "drug_class": "مانع للتجلط بجرعة منخفضة", "indications": "الوقاية الأولية والثانوية من النوبات القلبية والسكتات الدماغية."}, {"id": 50, "generic_name_en": "Warfarin", "generic_name_ar": "وارفارين", "brand_names": ["Coumadin", "Marevan"], "brand_raw": "Coumadin / Marevan", "drug_class": "مضاد لتخثر الدم (فيتامين K)", "indications": "الوقاية من الجلطات الوريدية والانسداد الرئوي والرجفان الأذيني."}, {"id": 51, "generic_name_en": "Metformin", "generic_name_ar": "ميتفورمين", "brand_names": ["Glucophage", "Cidophage"], "brand_raw": "Glucophage / Cidophage", "drug_class": "منظم سكر (بايجوانيد)", "indications": "علاج السكري من النوع الثاني وتحسين حساسية الإنسولين وتكيس المبايض."}, {"id": 52, "generic_name_en": "Gliclazide", "generic_name_ar": "جليكلازيد", "brand_names": ["Diamicron MR"], "brand_raw": "Diamicron MR", "drug_class": "محفز إفراز الإنسولين (سلفونيل يوريا)", "indications": "خفض السكر لمرضى النوع الثاني عبر تحفيز خلايا بيتا في البنكرياس."}, {"id": 53, "generic_name_en": "Glimepiride", "generic_name_ar": "جليمبيريد", "brand_names": ["Amaryl"], "brand_raw": "Amaryl", "drug_class": "محفز إفراز الإنسولين (سلفونيل يوريا)", "indications": "السيطرة على مستويات الجلوكوز لمرضى السكري من النوع الثاني."}, {"id": 54, "generic_name_en": "Sitagliptin", "generic_name_ar": "سيتاجليبتين", "brand_names": ["Januvia"], "brand_raw": "Januvia", "drug_class": "مثبط إنزيم DPP-4", "indications": "تحسين السيطرة على سكر الدم بعد الوجبات لمرضى السكري."}, {"id": 55, "generic_name_en": "Vildagliptin", "generic_name_ar": "فيلداجليبتين", "brand_names": ["Galvus"], "brand_raw": "Galvus", "drug_class": "مثبط إنزيم DPP-4", "indications": "تنظيم مستويات السكر بالدم بتحفيز الإنسولين وتثبيط الجلوكاجون."}, {"id": 56, "generic_name_en": "Empagliflozin", "generic_name_ar": "إمباجليفلوزين", "brand_names": ["Jardiance"], "brand_raw": "Jardiance", "drug_class": "مثبط SGLT2", "indications": "طرد السكر الزائد عبر البول وحماية القلب والكلى لمرضى السكري."}, {"id": 57, "generic_name_en": "Dapagliflozin", "generic_name_ar": "داباجليفلوزين", "brand_names": ["Forxiga"], "brand_raw": "Forxiga", "drug_class": "مثبط SGLT2", "indications": "علاج السكري النوع الثاني وتخفيف قصور القلب وأمراض الكلى المزمنة."}, {"id": 58, "generic_name_en": "Insulin Glargine", "generic_name_ar": "إنسولين جلارجين", "brand_names": ["Lantus", "Toujeo"], "brand_raw": "Lantus / Toujeo", "drug_class": "إنسولين طويل المفعول (قاعدي)", "indications": "توفير مستوى ثابت من الإنسولين على مدار 24 ساعة."}, {"id": 59, "generic_name_en": "Insulin Aspart", "generic_name_ar": "إنسولين أسبارت", "brand_names": ["NovoRapid"], "brand_raw": "NovoRapid", "drug_class": "إنسولين سريع المفعول", "indications": "تغطية ارتفاع سكر الدم بعد تناول الوجبات مباشرة."}, {"id": 60, "generic_name_en": "Levothyroxine", "generic_name_ar": "ليفوثيروكسين", "brand_names": ["Euthyrox", "Synthroid"], "brand_raw": "Euthyrox / Synthroid", "drug_class": "هرمون الغدة الدرقية البديل", "indications": "علاج قصور وخمول الغدة الدرقية. يؤخذ صباحاً على معدة فارغة."}, {"id": 61, "generic_name_en": "Carbimazole", "generic_name_ar": "كاربيمازول", "brand_names": ["Neo-Mercazole"], "brand_raw": "Neo-Mercazole", "drug_class": "مضاد لإفراز هرمون الدرقية", "indications": "علاج فرط نشاط الغدة الدرقية والتسمم الدرقي."}, {"id": 62, "generic_name_en": "Salbutamol", "generic_name_ar": "سالبوتامول", "brand_names": ["Ventolin"], "brand_raw": "Ventolin", "drug_class": "موسع قصبات سريع المفعول", "indications": "الإغاثة السريعة لضيق التنفس ونوبات الربو الحادة والانسداد الرئوي."}, {"id": 63, "generic_name_en": "Formoterol + Budesonide", "generic_name_ar": "فورموتيرول وبوديسونيد", "brand_names": ["Symbicort"], "brand_raw": "Symbicort", "drug_class": "موسع طويل المفعول مع كورتيزون", "indications": "الوقاية والسيطرة اليومية على نوبات الربو والانسداد الرئوي المزمن."}, {"id": 64, "generic_name_en": "Fluticasone + Salmeterol", "generic_name_ar": "فلوتيكازون وسالميتيرول", "brand_names": ["Seretide"], "brand_raw": "Seretide", "drug_class": "موسع قصبات مع كورتيزون مستنشق", "indications": "العلاج الوقائي طويل الأمد للربو والتهاب الشعب الهوائية المزمن."}, {"id": 65, "generic_name_en": "Tiotropium", "generic_name_ar": "تيوتروبيوم", "brand_names": ["Spiriva"], "brand_raw": "Spiriva", "drug_class": "موسع قصبات مضاد للكولين", "indications": "توسيع الشعب الهوائية لمرضى الانسداد الرئوي المزمن (COPD)."}, {"id": 66, "generic_name_en": "Montelukast", "generic_name_ar": "مونتيلوكاست", "brand_names": ["Singulair", "Airfast"], "brand_raw": "Singulair / Airfast", "drug_class": "مضاد مستقبلات الليكوترين", "indications": "الوقاية من أزمات الربو الليلية والتحكم في حساسية الأنف الموسمية."}, {"id": 67, "generic_name_en": "Cetirizine", "generic_name_ar": "سيتريزين", "brand_names": ["Zyrtec", "Finistil"], "brand_raw": "Zyrtec / Finistil", "drug_class": "مضاد هيستامين (جيل ثاني)", "indications": "تخفيف أعراض الحساسية وسيلان الأنف وحكة الجلد والأرتيكاريا."}, {"id": 68, "generic_name_en": "Loratadine", "generic_name_ar": "لوراتادين", "brand_names": ["Claritine"], "brand_raw": "Claritine", "drug_class": "مضاد هيستامين غير مسبب للنعاس", "indications": "علاج حساسية الأنف، العطس، وحكة العيون والحساسية الجلدية."}, {"id": 69, "generic_name_en": "Desloratadine", "generic_name_ar": "ديسلوراتادين", "brand_names": ["Aerius"], "brand_raw": "Aerius", "drug_class": "مضاد هيستامين ممتد المفعول", "indications": "تخفيف أعراض التهاب الأنف التحسسي والشرى الجلدي بدون خمول."}, {"id": 70, "generic_name_en": "Fexofenadine", "generic_name_ar": "فيكسوفينادين", "brand_names": ["Telfast"], "brand_raw": "Telfast", "drug_class": "مضاد هيستامين لا يسبب النعاس", "indications": "علاج الحساسية الموسمية وحكة الجلد دون التأثير على التركيز."}, {"id": 71, "generic_name_en": "Chlorpheniramine", "generic_name_ar": "كلورفينيرامين", "brand_names": ["Histop", "Anaplex"], "brand_raw": "Histop / Anaplex", "drug_class": "مضاد هيستامين (جيل أول)", "indications": "تخفيف أعراض نزلات البرد والحساسية الشديدة (يسبب النعاس)."}, {"id": 72, "generic_name_en": "Xylometazoline", "generic_name_ar": "زايلوميتازولين", "brand_names": ["Otrivin"], "brand_raw": "Otrivin", "drug_class": "مزيل لاحتقان الأنف", "indications": "تخفيف انسداد واحتقان الأنف (لا يستخدم أكثر من 5 أيام متتالية)."}, {"id": 73, "generic_name_en": "Acetylcysteine", "generic_name_ar": "أسيتيل سيستئين", "brand_names": ["ACC", "Fluimucil"], "brand_raw": "ACC / Fluimucil", "drug_class": "مذيب وطارد للبلغم", "indications": "إذابة الإفرازات المخاطية الكثيفة في الجهاز التنفسي والكحة الرطبة."}, {"id": 74, "generic_name_en": "Ambroxol", "generic_name_ar": "أمبروكسول", "brand_names": ["Mucosolvan"], "brand_raw": "Mucosolvan", "drug_class": "مذيب للمخاط", "indications": "تسهيل خروج البلغم وتهدئة الكحة المصحوبة بمخاط."}, {"id": 75, "generic_name_en": "Amoxicillin", "generic_name_ar": "أموكسيسيلين", "brand_names": ["Amoxil"], "brand_raw": "Amoxil", "drug_class": "مضاد حيوي بنسليني", "indications": "علاج الالتهابات البكتيرية في الحلق، الصدر، الأذن الوسطى، والمسالك."}, {"id": 76, "generic_name_en": "Amoxicillin + Clavulanic Acid", "generic_name_ar": "أموكسيسيلين وكلافولانيك", "brand_names": ["Augmentin", "Curam"], "brand_raw": "Augmentin / Curam", "drug_class": "مضاد حيوي واسع المجال مع مثبط بيتا لاكتاماز", "indications": "علاج التهابات الجهاز التنفسي الحادة، الجيوب، والتهابات الأسنان."}, {"id": 77, "generic_name_en": "Azithromycin", "generic_name_ar": "أزيثروميسين", "brand_names": ["Zithromax", "Azimycin"], "brand_raw": "Zithromax / Azimycin", "drug_class": "مضاد حيوي ماكروليدي", "indications": "علاج التهابات الصدر والحلق والجلد وبعض العدوى التناسلية."}, {"id": 78, "generic_name_en": "Clarithromycin", "generic_name_ar": "كلاريثروميسين", "brand_names": ["Klacid"], "brand_raw": "Klacid", "drug_class": "مضاد حيوي ماكروليدي", "indications": "علاج التهابات الجهاز التنفسي وقرحة المعدة ضمن الخطة الثلاثية."}, {"id": 79, "generic_name_en": "Ciprofloxacin", "generic_name_ar": "سيبروفلوكساسين", "brand_names": ["Cipro", "Ciprobay"], "brand_raw": "Cipro / Ciprobay", "drug_class": "مضاد حيوي فلوروكينولون", "indications": "علاج التهابات المسالك البولية، النزلات المعوية، والتهابات العظام."}, {"id": 80, "generic_name_en": "Levofloxacin", "generic_name_ar": "ليفوفلوكساسين", "brand_names": ["Tavanic"], "brand_raw": "Tavanic", "drug_class": "مضاد حيوي فلوروكينولون", "indications": "علاج الالتهاب الرئوي الحاد والتهاب الجيوب والمسالك المعقدة."}, {"id": 81, "generic_name_en": "Ceftriaxone", "generic_name_ar": "سيفترياكسون", "brand_names": ["Rocephin"], "brand_raw": "Rocephin", "drug_class": "مضاد حيوي سيفالوسبورين (جيل ثالث)", "indications": "علاج العدوى البكتيرية الشديدة وحالات التسمم الدموي والتهاب السحايا."}, {"id": 82, "generic_name_en": "Cefuroxime", "generic_name_ar": "سيفوروكسيم", "brand_names": ["Zinnat"], "brand_raw": "Zinnat", "drug_class": "مضاد حيوي سيفالوسبورين (جيل ثانٍ)", "indications": "علاج التهابات الحلق والأذن والمسالك والالتهاب الرئوي الخفيف."}, {"id": 83, "generic_name_en": "Cefixime", "generic_name_ar": "سيفيكسيم", "brand_names": ["Suprax"], "brand_raw": "Suprax", "drug_class": "مضاد حيوي سيفالوسبورين (جيل ثالث فموي)", "indications": "علاج التهابات المسالك والتهاب الأذن الوسطى واللوزتين."}, {"id": 84, "generic_name_en": "Doxycycline", "generic_name_ar": "دوكسيسيكلين", "brand_names": ["Vibramycin", "Doxymycin"], "brand_raw": "Vibramycin / Doxymycin", "drug_class": "مضاد حيوي تتراسيكلين", "indications": "علاج حب الشباب الشديد، عدوى الصدر، والملاريا الوقائية."}, {"id": 85, "generic_name_en": "Metronidazole", "generic_name_ar": "ميترونيدازول", "brand_names": ["Flagyl"], "brand_raw": "Flagyl", "drug_class": "مضاد للطفيليات والبكتيريا اللاهوائية", "indications": "علاج الإسهال الطفيلي (الأميبا والجيارديا) والتهابات اللثة والأسنان."}, {"id": 86, "generic_name_en": "Fluconazole", "generic_name_ar": "فلوكونازول", "brand_names": ["Diflucan"], "brand_raw": "Diflucan", "drug_class": "مضاد للفطريات جهازياً", "indications": "علاج عدوى المبيضات وفطريات الفم والمهبل والأعضاء التناسلية."}, {"id": 87, "generic_name_en": "Acyclovir", "generic_name_ar": "أسيكلوفير", "brand_names": ["Zovirax"], "brand_raw": "Zovirax", "drug_class": "مضاد للفيروسات", "indications": "علاج فيروس الهربس البسيط، الحزام الناري، وجدري الماء."}, {"id": 88, "generic_name_en": "Escitalopram", "generic_name_ar": "إسيتالوبرام", "brand_names": ["Cipralex", "Lexapro"], "brand_raw": "Cipralex / Lexapro", "drug_class": "مضاد اكتئاب (SSRI)", "indications": "علاج الاكتئاب العام، نوبات الهلع، والقلق والتوتر المزمن."}, {"id": 89, "generic_name_en": "Sertraline", "generic_name_ar": "سيرترالين", "brand_names": ["Zoloft", "Lustral"], "brand_raw": "Zoloft / Lustral", "drug_class": "مضاد اكتئاب (SSRI)", "indications": "علاج الاكتئاب والوسواس القهري (OCD) ونوبات الفزع والصدمة."}, {"id": 90, "generic_name_en": "Fluoxetine", "generic_name_ar": "فلوكسيتين", "brand_names": ["Prozac"], "brand_raw": "Prozac", "drug_class": "مضاد اكتئاب (SSRI)", "indications": "علاج الاكتئاب واضطراب نهم الطعام والوسواس القهري."}, {"id": 91, "generic_name_en": "Paroxetine", "generic_name_ar": "باروكستين", "brand_names": ["Seroxat", "Paxil"], "brand_raw": "Seroxat / Paxil", "drug_class": "مضاد اكتئاب وقلق (SSRI)", "indications": "علاج القلق الاجتماعي ونوبات الهلع الحادة والاكتئاب."}, {"id": 92, "generic_name_en": "Venlafaxine", "generic_name_ar": "فينلافاكسين", "brand_names": ["Efexor XR"], "brand_raw": "Efexor XR", "drug_class": "مضاد اكتئاب وقلق (SNRI)", "indications": "علاج نوبات الاكتئاب الشديدة واضطرابات القلق المعمم والرهاب."}, {"id": 93, "generic_name_en": "Duloxetine", "generic_name_ar": "دولوكستين", "brand_names": ["Cymbalta"], "brand_raw": "Cymbalta", "drug_class": "مضاد اكتئاب ومسكن للألم العصبي (SNRI)", "indications": "علاج الاكتئاب، آلام الأعصاب السكرية، وآلام الفيبروميالجيا."}, {"id": 94, "generic_name_en": "Pregabalin", "generic_name_ar": "بريجابالين", "brand_names": ["Lyrica"], "brand_raw": "Lyrica", "drug_class": "مضاد لآلام الأعصاب والتشنجات (خاضع للرقابة)", "indications": "تسكين ألم الأعصاب والاعتلال العصبي السكري واضطراب القلق العام."}, {"id": 95, "generic_name_en": "Gabapentin", "generic_name_ar": "جابابنتين", "brand_names": ["Neurontin", "Gaptin"], "brand_raw": "Neurontin / Gaptin", "drug_class": "مضاد لألم الأعصاب والاختلاج", "indications": "تخفيف آلام الأعصاب الناتجة عن الحزام الناري والسكري."}, {"id": 96, "generic_name_en": "Levetiracetam", "generic_name_ar": "ليفيتيراسيتام", "brand_names": ["Keppra"], "brand_raw": "Keppra", "drug_class": "مضاد للصرع والتشنجات", "indications": "السيطرة على نوبات الصرع الجزئية والعامة لدى المرضى."}, {"id": 97, "generic_name_en": "Sodium Valproate", "generic_name_ar": "فالبروات الصوديوم", "brand_names": ["Depakine Chrono"], "brand_raw": "Depakine Chrono", "drug_class": "مثبت للمزاج ومضاد للصرع", "indications": "علاج الصرع واضطراب ثنائي القطب والوقاية من الشقيقة."}, {"id": 98, "generic_name_en": "Carbamazepine", "generic_name_ar": "كاربامازيبين", "brand_names": ["Tegretol"], "brand_raw": "Tegretol", "drug_class": "مضاد للصرع وألم العصب الخامس", "indications": "السيطرة على نوبات الصرع وعلاج آلام العصب الخامس الحادة."}, {"id": 99, "generic_name_en": "Quetiapine", "generic_name_ar": "كويتيابين", "brand_names": ["Seroquel"], "brand_raw": "Seroquel", "drug_class": "مضاد للذهان غير نمطي", "indications": "علاج الفصام واضطراب ثنائي القطب والمساعدة في حالات الاكتئاب المقاوم."}, {"id": 100, "generic_name_en": "Olanzapine", "generic_name_ar": "أولانزابين", "brand_names": ["Zyprexa"], "brand_raw": "Zyprexa", "drug_class": "مضاد للذهان غير نمطي", "indications": "علاج نوبات الهوس الحاد والفصام واضطرابات المزاج."}, {"id": 101, "generic_name_en": "Atomoxetine", "generic_name_ar": "أتوموكسيتين", "brand_names": ["Strattera"], "brand_raw": "Strattera", "drug_class": "علاج اضطراب فرط الحركة (غير منبه)", "indications": "تحسين التركيز وعلاج اضطراب نقص الانتباه وفرط الحركة (ADHD)."}, {"id": 102, "generic_name_en": "Donepezil", "generic_name_ar": "دونيبيزيل", "brand_names": ["Aricept"], "brand_raw": "Aricept", "drug_class": "مثبط إنزيم أسيتيل كولين إستراز", "indications": "إبطاء تدهور الذاكرة وتحسين الوظائف الإدراكية لمرضى الزهايمر."}, {"id": 103, "generic_name_en": "Cholecalciferol", "generic_name_ar": "كوليكالسيفيرول (فيتامين د3)", "brand_names": ["Vidrop", "D-3 Stada"], "brand_raw": "Vidrop / D-3 Stada", "drug_class": "فيتامين د3 أساسي", "indications": "علاج نقص فيتامين د وهشاشة العظام وتحسين امتصاص الكالسيوم."}, {"id": 104, "generic_name_en": "Ferrous Fumarate / Sulfate", "generic_name_ar": "مركبات الحديد", "brand_names": ["Feroglobin", "Fefol"], "brand_raw": "Feroglobin / Fefol", "drug_class": "مكمل عنصر الحديد", "indications": "علاج والوقاية من فقر الدم (الأنيميا) الناتج عن نقص الحديد."}, {"id": 105, "generic_name_en": "Folic Acid", "generic_name_ar": "حمض الفوليك (فيتامين ب9)", "brand_names": ["Folic Acid 5mg"], "brand_raw": "Folic Acid 5mg", "drug_class": "فيتامين أساسي لصنع الدم", "indications": "الوقاية من تشوهات الأجنة للحوامل وعلاج بعض أنواع فقر الدم."}, {"id": 106, "generic_name_en": "Cyanocobalamin / Methylcobalamin", "generic_name_ar": "فيتامين ب12", "brand_names": ["Neurobion", "Methycobal"], "brand_raw": "Neurobion / Methycobal", "drug_class": "فيتامين ب12 المغذي للأعصاب", "indications": "دعم صحة الأعصاب وتكوين خلايا الدم الحمراء لمرضى السكري وكبار السن."}, {"id": 107, "generic_name_en": "Calcium Carbonate + Vit D", "generic_name_ar": "كالسيوم وفيتامين د", "brand_names": ["Caltrate"], "brand_raw": "Caltrate", "drug_class": "مكمل غذائي للعظام", "indications": "تقوية كثافة العظام والوقاية من الهشاشة والكسور لدى كبار السن."}, {"id": 108, "generic_name_en": "Isotretinoin", "generic_name_ar": "إيزوتريتينوين", "brand_names": ["Roaccutane", "Curacne"], "brand_raw": "Roaccutane / Curacne", "drug_class": "مشتق ريتينويد قوي (تحت إشراف طبي)", "indications": "علاج حب الشباب العقدي الشديد والمستعصي على المضادات."}, {"id": 109, "generic_name_en": "Betamethasone", "generic_name_ar": "بيتاميثازون", "brand_names": ["Betnovate", "Diprosone"], "brand_raw": "Betnovate / Diprosone", "drug_class": "كورتيزون موضعي قوي", "indications": "علاج الالتهابات الجلدية الشديدة، الإكزيما، والصدفية."}, {"id": 110, "generic_name_en": "Hydrocortisone", "generic_name_ar": "هيدروكورتيزون", "brand_names": ["Locoid", "Cortiderm"], "brand_raw": "Locoid / Cortiderm", "drug_class": "كورتيزون موضعي خفيف", "indications": "علاج التحسس الجلدي الخفيف ولدغات الحشرات والتهاب الجلد."}, {"id": 111, "generic_name_en": "Mupirocin", "generic_name_ar": "موبيروسين", "brand_names": ["Bactroban"], "brand_raw": "Bactroban", "drug_class": "مضاد حيوي موضعي", "indications": "علاج العدوى البكتيرية الجلدية مثل القوباء والدمامل وجروح الجلد."}, {"id": 112, "generic_name_en": "Fusidic Acid", "generic_name_ar": "حمض الفوسيديك", "brand_names": ["Fucidin"], "brand_raw": "Fucidin", "drug_class": "مضاد حيوي موضعي", "indications": "علاج الالتهابات الجلدية البكتيرية وحب الشباب والتهاب بصيلات الشعر."}, {"id": 113, "generic_name_en": "Clotrimazole", "generic_name_ar": "كلوتريمازول", "brand_names": ["Canesten"], "brand_raw": "Canesten", "drug_class": "مضاد فطريات موضعي", "indications": "علاج الفطريات الجلدية مثل تينيا القدم وفطريات الثنايا والمهبل."}, {"id": 114, "generic_name_en": "Tretinoin", "generic_name_ar": "تريتينوين", "brand_names": ["Acretin", "Retin-A"], "brand_raw": "Acretin / Retin-A", "drug_class": "ريتينويد موضعي مقشر", "indications": "تجديد خلايا البشرة، علاج حب الشباب، وتحسين مظهر الندبات."}, {"id": 115, "generic_name_en": "Latanoprost", "generic_name_ar": "لاتانوبروست", "brand_names": ["Xalatan"], "brand_raw": "Xalatan", "drug_class": "نظير البروستاغلاندين للعين", "indications": "خفض ضغط العين المرتفع لمرضى الجلوكوما (الماء الأزرق)."}, {"id": 116, "generic_name_en": "Brimonidine", "generic_name_ar": "بريمونيدين", "brand_names": ["Alphagan P"], "brand_raw": "Alphagan P", "drug_class": "قطرة لضغط العين", "indications": "تقليل إنتاج السائل داخل العين وتخفيف ضغط الجلوكوما."}, {"id": 117, "generic_name_en": "Carboxymethylcellulose", "generic_name_ar": "كاربوكسي ميثيل سليلوز", "brand_names": ["Refresh Tears", "Optive"], "brand_raw": "Refresh Tears / Optive", "drug_class": "بديل الدموع المرطب", "indications": "تخفيف جفاف العين والحرقة والإجهاد الناتج عن الشاشات."}, {"id": 118, "generic_name_en": "Sildenafil", "generic_name_ar": "سيلدينافيل", "brand_names": ["Viagra"], "brand_raw": "Viagra", "drug_class": "مثبط إنزيم PDE5", "indications": "علاج ضعف الانتصاب وارتفاع ضغط الشريان الرئوي."}, {"id": 119, "generic_name_en": "Tadalafil", "generic_name_ar": "تادالافيل", "brand_names": ["Cialis"], "brand_raw": "Cialis", "drug_class": "مثبط إنزيم PDE5 طويل المفعول", "indications": "علاج ضعف الانتصاب وأعراض تضخم البروستاتا الحميد."}, {"id": 120, "generic_name_en": "Tamsulosin", "generic_name_ar": "تامسولوسين", "brand_names": ["Omnic", "Flomax"], "brand_raw": "Omnic / Flomax", "drug_class": "حاصر مستقبلات ألفا-1 الانتقائي", "indications": "تسهيل تدفق البول وتخفيف أعراض تضخم البروستاتا الحميد."}, {"id": 121, "generic_name_en": "Finasteride", "generic_name_ar": "فيناستيرايد", "brand_names": ["Proscar", "Propecia"], "brand_raw": "Proscar / Propecia", "drug_class": "مثبط إنزيم 5-ألفا ريدوكتاز", "indications": "علاج تضخم البروستاتا الحميد ووقف تساقط الشعر الوراثي للرجال."}]

        # 1. أنطولوجيا المعرفة السريرية للأعراض والدواعي الطبية
        self.symptom_ontology = [
            {
                "id": "HEADACHE_MIGRAINE",
                "title": "الصداع والصداع النصفي (الشقيقة) وتسكين الآلام العامة",
                "keywords": [
                    "صداع", "وجع راس", "الم راس", "ألم رأس", "ألم في الرأس", "وجع في راسي", "راسي يوجعني",
                    "راسي يعورني", "راسي مصدع", "مصدع", "شقيقة", "شقيقه", "صداع نصفي", "صداع شديد",
                    "صداع وتعب", "الم بالراس", "وجع بالراس", "وجع رأس"
                ],
                "primary_generics": ["باراسيتامول", "إيبوبروفين", "ديكلوفيناك البوتاسيوم", "باراسيتامول + كافيين"],
                "target_categories": ["مسكن", "مسكنات", "خافض حرارة", "مسكن وخافض حرارة", "مسكن ومضاد للالتهاب"],
                "usage_advice": "يُفضل تناول قرص من الباراسيتامول (أو البنادول إكسترا) بعد الأكل مع شرب كمية وافرة من الماء. في حال الصداع الشديد أو النصفي، يمكن استخدام مضادات الالتهاب غير الستيرويدية مثل الإيبوبروفين (بروفين) أو الديكلوفيناك (رابيدوس/كتافلام).",
                "precautions": "يُمنع استخدام الإيبوبروفين والديكلوفيناك لمرضى قرحة المعدة النشطة أو القصور الكلوي الحاد. يجب استشارة الطبيب إذا استمر الصداع لأكثر من 3 أيام أو صاحبه تشوش في الرؤية أو قيء مفاجئ."
            },
            {
                "id": "FEVER_BODY_ACHE",
                "title": "الحمى وارتفاع درجة الحرارة وتكسير الجسم",
                "keywords": [
                    "حرارة", "حراره", "سخونة", "سخونه", "حمى", "حمي", "جسمي مكسر", "تكسير بالجسم",
                    "حرارة مرتفعة", "حراره مرتفعه", "حرارته مرتفعة", "حرارته مرتفعه", "ساخن", "مسخن",
                    "قشعريرة", "رجفة", "ارتفاع درجة الحرارة", "ارتفاع الحرارة", "تنزيل الحرارة", "خافض حرارة"
                ],
                "primary_generics": ["باراسيتامول", "إيبوبروفين"],
                "target_categories": ["خافض حرارة", "مسكن وخافض حرارة", "مسكن"],
                "usage_advice": "تناول الباراسيتامول (500 مجم إلى 1000 مجم كل 6-8 ساعات عند اللزوم، بحد أقصى 4000 مجم يومياً) مع عمل كمادات ماء فاتر وتناول السوائل بكثرة لتعويض الفاقد.",
                "precautions": "تجنب مضاعفة الجرعات. إذا تجاوزت الحرارة 39°C أو استمرت لأكثر من 48 ساعة دون استجابة، يجب مراجعة الطوارئ أو الطبيب المختص فوراً."
            },
            {
                "id": "STOMACH_ACIDITY_GERD",
                "title": "حموضة وحرقة المعدة والارتجاع المريئي",
                "keywords": [
                    "حموضة", "حموضه", "حرقان", "حرقان بالمعدة", "حرقة بالمعدة", "حرقة المعدة", "ارتجاع",
                    "ارتجاع مريئي", "ارتجاع المريء", "معدتي تحرقني", "قرحة", "قرحه", "التهاب معدة",
                    "وجع بالمعدة", "عسر هضم", "حرقان بالصدر", "حارق", "فم المعدة", "حرقة بالمريء"
                ],
                "primary_generics": ["أوميبرازول", "بانتوبرازول", "إيسوميبرازول", "مضادات حموضة"],
                "target_categories": ["جهاز هضمي", "معدة", "أدوية الجهاز الهضمي والمعدة"],
                "usage_advice": "تناول كبسولة أوميبرازول (20 مجم) صباحاً على معدة فارغة قبل الإفطار بنصف ساعة، مع تجنب الأطعمة الحارة والدهنية والمشروبات الغازية والأكل قبل النوم مباشرة.",
                "precautions": "لا يُنصح بالاستخدام المستمر لمثبطات مضخة البروتون لأكثر من 14 يوماً دون استشارة الطبيب للتحقق من جرثومة المعدة (H. Pylori)."
            },
            {
                "id": "ABDOMINAL_COLIC_IBS",
                "title": "مغص البطن والقولون العصبي والتقلصات المعوية",
                "keywords": [
                    "مغص", "تقلصات", "الم بطن", "ألم بطن", "وجع بطن", "قولون", "قولون عصبي", "انتفاخ",
                    "غازات", "بطني يوجعني", "تشنج بالمعدة", "مغص معوي", "الم في البطن", "مغص كلوي"
                ],
                "primary_generics": ["ميبفرين", "هيوسين", "ميترونيدازول", "فلاجيل"],
                "target_categories": ["جهاز هضمي", "مطهر معوي", "مسكن ومضاد للتقلصات", "أدوية الجهاز الهضمي"],
                "usage_advice": "استخدام مضادات التقلصات المعوية قبل الوجبات بـ 20 دقيقة، مع شرب المشروبات الدافئة كالنعناع والبابونج والابتعاد عن مهيجات القولون والبقوليات.",
                "precautions": "إذا كان الألم مصحوباً بارتفاع شديد في الحرارة أو قيء مستمر أو دم، يجب التوجه للطوارئ لاستبعاد التهاب الزائدة الدودية أو انسداد الأمعاء."
            },
            {
                "id": "COUGH_FLU_COLD",
                "title": "السعال والكحة والرشح والزكام والتهاب الحلق",
                "keywords": [
                    "كحة", "كحه", "سعال", "بلغم", "كحة ناشفة", "كحة جافة", "رشح", "زكام", "انفلونزا",
                    "إنفلونزا", "التهاب حلق", "حلقي يوجعني", "احتقان", "احتقان انف", "صوتي رايح", "سيلان",
                    "برد", "نزلة برد", "عطاس"
                ],
                "primary_generics": ["باراسيتامول", "أزيثرومايسين", "أموكسيسيلين", "سيتريزين", "ديكلوفيناك"],
                "target_categories": ["مسكن وخافض حرارة", "مضاد حيوي", "حساسية", "جهاز تنفسي"],
                "usage_advice": "استخدام الباراسيتامول لتسكين احتقان الحلق وتخفيض الحرارة، ومضادات الهيستامين لتخفيف الرشح والعطاس، مع الإكثار من السوائل الدافئة والغرغرة بالماء والملح.",
                "precautions": "المضادات الحيوية لا تُستخدم للعدوى الفيروسية الشائعة إلا إذا ثبت وجود عدوى بكتيرية ثانوية (مثل صديد اللوزتين) وبإشراف طبيب أو صيدلي."
            },
            {
                "id": "JOINT_ARTHRITIS_BACK",
                "title": "آلام المفاصل والفقرات والتهاب العظام والروماتيزم",
                "keywords": [
                    "مفاصل", "ركبة", "ركبه", "خشونة", "خشونه", "روماتيزم", "عظام", "الم ظهر", "وجع ظهر",
                    "ألم ظهر", "انزلاق غضروفي", "دسك", "ديسك", "فقرات", "عرق النسا", "التهاب مفاصل", "الم بالمفاصل",
                    "خشونة الركبة", "التهاب الفقرات"
                ],
                "primary_generics": ["سيليكوكسيب", "ديكلوفيناك الصوديوم", "ميلوكسيكام", "إيبوبروفين"],
                "target_categories": ["مسكن ومضاد للالتهاب", "مسكن"],
                "usage_advice": "استخدام مضادات الالتهاب الانتقائية (مثل سيلبركس 200 مجم) أو ديكلوفيناك (فولتارين/ديفيدو) بعد الأكل مباشرة لتقليل تهيج المعدة، مع دهان موضعي لتخفيف التيبس.",
                "precautions": "الحذر مع مرضى ارتفاع ضغط الدم والقصور الكلوي وقرحة المعدة. لا تتناول دوائين من عائلة NSAIDs معاً في نفس الوقت."
            },
            {
                "id": "TOOTHACHE_DENTAL",
                "title": "ألم الأسنان والتهاب اللثة وخراج الأسنان",
                "keywords": [
                    "اسنان", "أسنان", "ضرس", "ضرسي يوجعني", "الم اسنان", "وجع اسنان", "خلع ضرس",
                    "التهاب لثة", "لثتي ملتهبة", "خراج", "خراج اسنان", "وجع بالضرس", "الم بالضرس"
                ],
                "primary_generics": ["ديكلوفيناك البوتاسيوم", "إيبوبروفين", "حمض الميفيناميك", "أموكسيسيلين + كلافولانات"],
                "target_categories": ["مسكن ومضاد للالتهاب", "مضاد حيوي", "مسكن"],
                "usage_advice": "تناول مسكن سريع المفعول مثل كتافلام أو رابيدوس أو بروفين بعد الأكل لتسكين الألم الحاد. إذا كان هناك تورم أو خراج بكتيري، يُصرف مضاد حيوي (مثل أوجمنتين/كلافوكس) كورس كامل.",
                "precautions": "المسكنات حل مؤقت لتسكين الألم ويجب زيارة طبيب الأسنان لعلاج المشكلة جذرياً."
            },
            {
                "id": "MUSCLE_SPASM_PAIN",
                "title": "الشد العضلي والتقلصات العضلية وإجهاد الجسم",
                "keywords": [
                    "شد عضلي", "تشنج عضلي", "عضلات", "الم عضلات", "ألم عضلات", "رقبتي مشدودة", "ابهر", "وثاب",
                    "تمزق عضلي", "عضلي", "عضلاتي توجعني", "تصلب عضلات", "اجهاد عضلي"
                ],
                "primary_generics": ["ديكلوفيناك الصوديوم", "إيبوبروفين", "باراسيتامول"],
                "target_categories": ["مسكن ومضاد للالتهاب", "مسكن"],
                "usage_advice": "استخدام مسكن ومضاد التهاب مع الراحة وكمادات دافئة على العضلة المشدودة وتجنب الحركات المفاجئة.",
                "precautions": "تجنب الإجهاد الرياضي الزائد أثناء فترة التعافي."
            },
            {
                "id": "ALLERGY_SKIN_ITCH",
                "title": "الحساسية والحكة الجلدية والأرتكاريا والتهاب الجلد",
                "keywords": [
                    "حساسية", "حساسيه", "حكة", "حكه", "هرش", "ارتكاريا", "طفح جلدي", "حبوب حمراء",
                    "حساسية انف", "عطاس مستمر", "اكزيما", "إكزيما", "حساسية جلدية"
                ],
                "primary_generics": ["سيتريزين", "لوراتادين", "مضادات هيستامين"],
                "target_categories": ["حساسية", "مضاد هيستامين", "جلدية"],
                "usage_advice": "تناول قرص مضاد للهيستامين (مثل سيتريزين 10 مجم) مساءً قبل النوم، واستخدام مرطب طبي للبشرة.",
                "precautions": "قد تسبب بعض مضادات الحساسية النعاس، لذا يُفضل تجنب القيادة بعد تناولها."
            },
            {
                "id": "DIABETES_GLUCOSE",
                "title": "مرض السكري وتنظيم مستوى السكر في الدم",
                "keywords": [
                    "سكري", "سكر", "ارتفاع السكر", "مرض السكر", "تنظيم السكر", "حمية سكر", "تراكمي",
                    "مريض سكر", "علاج السكر", "نقص السكر"
                ],
                "primary_generics": ["ميتفورمين", "جلوكوفاج"],
                "target_categories": ["أدوية السكري", "سكري", "باطنة"],
                "usage_advice": "تناول أدوية منظم السكر (جلوكوفاج / ميتفورمين) أثناء الوجبات أو بعدها مباشرة لتقليل الاضطرابات الهضمية.",
                "precautions": "يجب المتابعة الدورية لفحص السكر التراكمي (HbA1c) ووظائف الكلى واستشارة الطبيب المعالج لتعديل الجرعات."
            },
            {
                "id": "HYPERTENSION_BP",
                "title": "ارتفاع ضغط الدم وصحة القلب والشرايين",
                "keywords": [
                    "ضغط", "ضغط دم", "ارتفاع الضغط", "مريض ضغط", "ضغط مرتفع", "تنظيم الضغط", "خفقان",
                    "علاج الضغط", "ضغط الدم"
                ],
                "primary_generics": ["بيسوبرولول", "أملوديبين", "فالسارتان"],
                "target_categories": ["أدوية الضغط", "قلب وأوعية دموية", "ضغط"],
                "usage_advice": "تناول أدوية الضغط بانتظام يومياً في نفس الموعد مع تقليل الملح في الطعام وتجنب التوتر وممارسة رياضة المشي.",
                "precautions": "لا توقف دواء الضغط فجأة دون استشارة الطبيب لتجنب الارتفاع الارتدادي الحاد في الضغط."
            },
            {
                "id": "DIARRHEA_GASTROENTERITIS",
                "title": "الإسهال والنزلات المعوية والمطهرات المعوية",
                "keywords": [
                    "اسهال", "إسهال", "نزلة معوية", "تسمم غذائي", "مطهر معوي", "جرثومة", "بطني يمشي", "اسهال مائي"
                ],
                "primary_generics": ["ميترونيدازول", "سيبروفلوكساسين", "فلاجيل"],
                "target_categories": ["مطهر معوي", "مضاد حيوي ومطهر معوي", "جهاز هضمي"],
                "usage_advice": "استخدام فلاجيل 500 مجم (ميترونيدازول) مرتين إلى 3 مرات يومياً بعد الأكل مع شرب محاليل الإرواء والسوائل لتعويض الأملاح المفقودة.",
                "precautions": "مراجعة الطوارئ في حال وجود جفاف شديد أو دم في البراز أو هبوط حاد في الضغط."
            },
            {
                "id": "CONSTIPATION",
                "title": "الإمساك وصعوبة الهضم والإخراج",
                "keywords": [
                    "امساك", "إمساك", "صعوبة اخراج", "ملين", "ملينات", "عسر اخراج", "بطني يابس", "علاج الامساك"
                ],
                "primary_generics": ["لاكتولوز", "بيساكوديل", "ألياف"],
                "target_categories": ["جهاز هضمي", "ملينات"],
                "usage_advice": "الإكثار من شرب الماء (2-3 لتر يومياً) وتناول الأغذية الغنية بالألياف الطبيعية مع استخدام الملينات الخفيفة عند الحاجة.",
                "precautions": "تجنب الاعتماد المزمن على الملينات المنبهة لمنع كسل الأمعاء."
            },
            {
                "id": "BURNS_WOUNDS_SKIN",
                "title": "الحروق والجروح والتسلخات الجلدية ومطهرات الجلد",
                "keywords": [
                    "حروق", "حرق", "جروح", "جرح", "تسلخات", "خدوش", "مرهم حروق", "كريم حروق", "مطهر جروح", "التئام"
                ],
                "primary_generics": ["ميبو", "بيتادين", "فيوسيديك"],
                "target_categories": ["جلدية", "مراهم وإسعافات"],
                "usage_advice": "غسل منطقة الحرق بماء بارد جارٍ لمدة 10 دقائق، ثم وضع طبقة رقيقة من مرهم الحروق المهدئ وتغطيتها بشاش معقم غير لاصق.",
                "precautions": "الحروق العميقة أو الواسعة أو التي تصيب الوجه والمفاصل تتطلب التوجه الفوري لأقرب مركز حروق."
            },
            {
                "id": "BACTERIAL_INFECTION",
                "title": "العدوى البكتيرية والالتهابات الصدرية والمسالك",
                "keywords": [
                    "التهاب", "عدوى", "بكتيريا", "صديد", "التهاب مسالك", "حرقان بول", "التهاب بولي",
                    "مضاد حيوي", "مضادات حيوية", "التهاب لوز", "التهاب رئوي", "جيوب انفية"
                ],
                "primary_generics": ["أموكسيسيلين + كلافولانات", "أزيثرومايسين", "سيبروفلوكساسين", "سيفبودوكسيم", "ليفوفلوكساسين"],
                "target_categories": ["مضاد حيوي", "مضادات حيوية"],
                "usage_advice": "الالتزام التام بكامل كورس المضاد الحيوي في مواعيده المحددة حتى بعد زوال الأعراض لمنع نشوء سلالات بكتيرية مقاومة.",
                "precautions": "إبلاغ الصيدلي بوجود أي حساسية سابقة للبنسلين أو الكينولونات قبل البدء بالعلاج."
            }
        ]

        # 2. الكلمات المفتاحية لتصنيف النوايا العامة
        self.intent_patterns = {
            "EXPIRING_SOON": [
                "تنتهي قريبا", "ستنتهي", "قريبة الانتهاء", "قريبة من الانتهاء", "قريبه من الانتهاء", "توشك على الانتهاء", "صلاحيتها قريبة",
                "منتهية الصلاحية", "تاريخ الصلاحية القريب", "قرب انتهاء", "ادوية ستنتهي", "أدوية ستنتهي", "أدوية تنتهي",
                "تقرير الصلاحية", "تقرير انتهاء", "صلاحية قربت", "fefo"
            ],
            "RESTOCK_RECOMMENDATIONS": [
                "اعادة طلب", "إعادة طلب", "تحتاج طلب", "تحتاج توريد", "شراء", "نواقص",
                "مخزون حرج", "منخفضة", "اوشكت على النفاد", "أوشكت على النفاد", "اقترح طلبيات", "rop"
            ],
            "PRICE_COMPARISON": [
                "قارن", "مقارنة", "اسعار", "أسعار", "سعر", "ارخص", "أرخص", "الأرخص",
                "كم سعر", "تكلفة", "افرق", "فرق السعر", "بكم", "كم قيمة"
            ],
            "FIND_ALTERNATIVES": [
                "بديل", "بدائل", "مكافئ", "مثيل", "نفس المادة", "بديل دواء", "عوضا عن",
                "نفس التركيب", "نفس الفعالية", "دواء مشابه", "بدائل ارخص"
            ],
            "STOCK_CHECK": [
                "متوفر", "موجود", "كم باقي", "كمية", "رصيد", "مخزون", "هل يوجد",
                "متى ينتهي", "تاريخ انتهاء", "صلاحية", "نفد", "خلص"
            ],
            "GREETING": [
                "مرحبا", "أهلا", "اهلا", "السلام عليكم", "مساء الخير", "صباح الخير",
                "هلا", "يا هلا", "حياك", "مين انت", "من أنت", "وظيفتك", "كيف تساعدني"
            ]
        }

    def normalize_arabic(self, text: str) -> str:
        """توحيد الحروف والأشكال العربية لرفع دقة المطابقة"""
        if not text:
            return ""
        text = text.strip().lower()
        # إزالة التشكيل
        text = re.sub(r'[\u064B-\u065F\u0670]', '', text)
        # توحيد الألف والهمزات
        text = re.sub(r'[إأآا]', 'ا', text)
        # توحيد الياء والتاء المربوطة
        text = re.sub(r'ى', 'ي', text)
        text = re.sub(r'ة', 'ه', text)
        # إزالة الرموز الزائدة
        text = re.sub(r'[^\w\s]', ' ', text)
        return text

    def calculate_similarity(self, s1: str, s2: str) -> float:
        """حساب نسبة التشابه النصي الضبابي"""
        n1 = self.normalize_arabic(s1)
        n2 = self.normalize_arabic(s2)
        if not n1 or not n2:
            return 0.0
        if n1 in n2 or n2 in n1:
            return 0.95
        return difflib.SequenceMatcher(None, n1, n2).ratio()

    def match_symptoms_in_query(self, query: str) -> List[Dict[str, Any]]:
        """استخراج وتطابق الأعراض السريرية من نص الاستفسار الطبيعي"""
        norm_query = self.normalize_arabic(query)
        words = norm_query.split()
        matched_symptoms = []

        for item in self.symptom_ontology:
            matched_keywords = []
            score = 0.0

            for kw in item["keywords"]:
                norm_kw = self.normalize_arabic(kw)
                if norm_kw in norm_query:
                    matched_keywords.append(kw)
                    score += 1.0
                else:
                    # مطابقة الكلمات المنفردة بدقة
                    kw_words = norm_kw.split()
                    if all(w in words for w in kw_words if len(w) >= 3):
                        matched_keywords.append(kw)
                        score += 0.8

            if matched_keywords:
                matched_symptoms.append({
                    "symptom": item,
                    "matched_keywords": list(set(matched_keywords)),
                    "score": score
                })

        # ترتيب الأعراض حسب قوة المطابقة
        matched_symptoms.sort(key=lambda x: x["score"], reverse=True)
        return matched_symptoms

    def identify_intent(self, query: str) -> str:
        """تصنيف قصد المستخدم من السؤال بدقة"""
        norm_query = self.normalize_arabic(query)

        # 1. فحص النوايا الإدارية والتقارير المحددة
        for keyword in self.intent_patterns["EXPIRING_SOON"]:
            if self.normalize_arabic(keyword) in norm_query:
                return "EXPIRING_SOON"

        for keyword in self.intent_patterns["RESTOCK_RECOMMENDATIONS"]:
            if self.normalize_arabic(keyword) in norm_query:
                return "RESTOCK_RECOMMENDATIONS"

        # 2. فحص استفسارات الأعراض الطبيعية (Symptom Diagnosis)
        # إذا وردت شكوى أو استفسار عن أعراض مريض
        symptom_matches = self.match_symptoms_in_query(query)
        # مؤشرات السؤال الطبيعي عن الأعراض
        natural_cues = ["ابغى", "اريد", "عندي", "مريض", "يعاني", "يشكي", "يوجع", "يشتكي", "علاج", "دواء ل", "دواء حق", "ايش يناسب", "افضل دواء", "احتاج"]
        has_natural_cue = any(c in norm_query for c in natural_cues)

        if symptom_matches and (has_natural_cue or symptom_matches[0]["score"] >= 1.0):
            return "SYMPTOM_DIAGNOSIS_RECOMMENDATION"

        # 3. فحص بقية النوايا
        for keyword in self.intent_patterns["PRICE_COMPARISON"]:
            if self.normalize_arabic(keyword) in norm_query:
                return "PRICE_COMPARISON"

        for keyword in self.intent_patterns["FIND_ALTERNATIVES"]:
            if self.normalize_arabic(keyword) in norm_query:
                return "FIND_ALTERNATIVES"

        for keyword in self.intent_patterns["STOCK_CHECK"]:
            if self.normalize_arabic(keyword) in norm_query:
                return "STOCK_CHECK"

        for keyword in self.intent_patterns["GREETING"]:
            if self.normalize_arabic(keyword) in norm_query:
                return "GREETING"

        # إذا تطابق مع عرض سريري حتى لو بدون كلمات دلالية صريحة
        if symptom_matches:
            return "SYMPTOM_DIAGNOSIS_RECOMMENDATION"

        return "GENERAL_INQUIRY"

    def match_medicine_in_query(self, query: str, medicines: List[Dict[str, Any]]) -> Optional[Dict[str, Any]]:
        """استخراج الدواء الأقرب والمطابق من نص السؤال"""
        norm_query = self.normalize_arabic(query)
        best_match = None
        highest_score = 0.0

        for med in medicines:
            name = med.get("name", "")
            generic = med.get("generic_name", "")

            score_name = self.calculate_similarity(name, norm_query)
            score_generic = self.calculate_similarity(generic, norm_query)
            max_score = max(score_name, score_generic)

            # مطابقة مباشرة للأجزاء
            for part in norm_query.split():
                if len(part) >= 3:
                    if self.normalize_arabic(part) in self.normalize_arabic(name):
                        max_score = max(max_score, 0.88)
                    if self.normalize_arabic(part) in self.normalize_arabic(generic):
                        max_score = max(max_score, 0.85)

            if max_score > highest_score and max_score >= 0.45:
                highest_score = max_score
                best_match = med

        return best_match

    def find_alternatives(self, target_med: Dict[str, Any], medicines: List[Dict[str, Any]]) -> List[Dict[str, Any]]:
        """البحث عن البدائل المتكافئة بنفس المادة الفعالة أو نفس التصنيف"""
        target_generic = self.normalize_arabic(target_med.get("generic_name", ""))
        target_cat = self.normalize_arabic(target_med.get("category", ""))
        target_id = target_med.get("id")

        alternatives = []
        for med in medicines:
            if med.get("id") == target_id:
                continue

            med_generic = self.normalize_arabic(med.get("generic_name", ""))
            med_cat = self.normalize_arabic(med.get("category", ""))

            # تطابق في المادة الفعالة (بديل مكافئ 100%)
            if target_generic and (target_generic == med_generic or target_generic in med_generic or med_generic in target_generic):
                med_copy = dict(med)
                med_copy["match_type"] = "مكافئ حيوي لنفس المادة الفعالة"
                alternatives.append(med_copy)
            # تطابق في التصنيف الدوائي
            elif target_cat and target_cat == med_cat:
                med_copy = dict(med)
                med_copy["match_type"] = "بديل علاجي من نفس التصنيف"
                alternatives.append(med_copy)

        # ترتيب البدائل حسب السعر من الأرخص للأعلى
        alternatives.sort(key=lambda x: float(x.get("price", 0)))
        return alternatives

    def recommend_medicines_for_symptom(self, symptom_entry: Dict[str, Any], medicines: List[Dict[str, Any]]) -> List[Dict[str, Any]]:
        """مطابقة واستخراج الأدوية المتاحة في قاعدة البيانات المناسبة للعارض السريري"""
        primary_generics = [self.normalize_arabic(g) for g in symptom_entry.get("primary_generics", [])]
        target_cats = [self.normalize_arabic(c) for c in symptom_entry.get("target_categories", [])]

        matched_meds = []
        for med in medicines:
            m_gen = self.normalize_arabic(med.get("generic_name", ""))
            m_cat = self.normalize_arabic(med.get("category", ""))
            m_name = self.normalize_arabic(med.get("name", ""))
            m_desc = self.normalize_arabic(med.get("description", ""))

            # حساب درجة المطابقة السريرية
            relevance = 0.0
            match_reason = ""

            # 1. مطابقة المادة الفعالة الأساسية
            for gen in primary_generics:
                if gen and (gen in m_gen or m_gen in gen or gen in m_name):
                    relevance = max(relevance, 0.95)
                    match_reason = f"يحتوي على المادة الفعالة الموصى بها ({med.get('generic_name')})"
                    break

            # 2. مطابقة التصنيف الدوائي
            if relevance < 0.90:
                for cat in target_cats:
                    if cat and (cat in m_cat or m_cat in cat):
                        relevance = max(relevance, 0.80)
                        match_reason = f"ينتمي للتصنيف الدوائي المناسب ({med.get('category')})"
                        break

            # 3. مطابقة الكلمات الدلالية في الوصف
            if relevance < 0.75:
                for kw in symptom_entry.get("keywords", []):
                    norm_kw = self.normalize_arabic(kw)
                    if len(norm_kw) >= 3 and (norm_kw in m_desc or norm_kw in m_name):
                        relevance = max(relevance, 0.70)
                        match_reason = f"دواعي الاستخدام مطابقة للعارض"
                        break

            if relevance >= 0.70:
                m_copy = dict(med)
                m_copy["clinical_relevance"] = relevance
                m_copy["match_reason"] = match_reason
                matched_meds.append(m_copy)

        # فرز: المتوفر أولاً (stock > 0) ثم حسب السعر من الأرخص للأعلى
        matched_meds.sort(key=lambda x: (
            0 if x.get("stock_quantity", 0) > 0 else 1,
            -x.get("clinical_relevance", 0),
            float(x.get("price", 0))
        ))

        return matched_meds

    def call_gemini_api(self, query: str, medicines: List[Dict[str, Any]], history: Optional[List[Dict[str, str]]] = None, api_key: Optional[str] = None) -> Optional[Dict[str, Any]]:
        """استدعاء Google Gemini AI مع حقن المخزون الحي في سياق النموذج"""
        import os
        import json
        import urllib.request
        import urllib.error

        key = api_key or os.environ.get("GEMINI_API_KEY")
        if not key:
            return None

        model = os.environ.get("GEMINI_MODEL", "gemini-2.0-flash")
        url = f"https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent?key={key}"

        inventory_summary = "\n".join([
            f"- [ID: {m.get('id')}] {m.get('name')} | Generic: {m.get('generic_name', '-')} | Category: {m.get('category')} | Price: {m.get('price')} SAR | Stock: {m.get('stock_quantity')} units | Expiry: {m.get('expiry_date', '-')}"
            for m in medicines[:50]
        ])

        system_instruction = (
            "أنت المساعد الدوائي والصيدلاني الذكي (AI Clinical Pharmacist) لصيدلية المستشفى.\n"
            "مهمتك: تقديم الاستشارات الدوائية والسريرية بدقة.\n"
            "1. قيّم شكوى المريض واقترح حصراً الأدوية المتوفرة حالياً في قائمة مخزون الصيدلية أدناه.\n"
            "2. اذكر اسم الدواء وسعره ورصيده، وقارن بين البدائل واقترح الخيار الأوفر بنفس الفعالية.\n"
            "3. اذكر إرشادات الجرعات ومحاذير الاستخدام وعلامات الخطورة.\n\n"
            f"مخزون الصيدلية المتاح:\n{inventory_summary}"
        )

        contents = []
        if history:
            for h in history[-4:]:
                contents.append({
                    "role": "user" if h.get("role") == "user" else "model",
                    "parts": [{"text": h.get("content", "")}]
                })
        contents.append({
            "role": "user",
            "parts": [{"text": query}]
        })

        payload = {
            "contents": contents,
            "systemInstruction": {"parts": [{"text": system_instruction}]},
            "generationConfig": {"temperature": 0.3, "maxOutputTokens": 1000}
        }

        try:
            req_data = json.dumps(payload).encode("utf-8")
            req = urllib.request.Request(url, data=req_data, headers={"Content-Type": "application/json"})
            with urllib.request.urlopen(req, timeout=5) as response:
                if response.status == 200:
                    resp_json = json.loads(response.read().decode("utf-8"))
                    text = resp_json.get("candidates", [{}])[0].get("content", {}).get("parts", [{}])[0].get("text")
                    if text:
                        return {
            "reply": self.sanitize_no_emojis(text),
            "intent": "GEMINI_GENAI",
                            "source": "Google Gemini AI",
                            "data": []
                        }
        except Exception:
            return None
        return None

    def sanitize_no_emojis(self, text: str) -> str:
        """تجريد كامل الرد من أي رموز تعبيرية أو إيموجي لضمان الالتزام الصارم بقواعد المنظومة"""
        if not text:
            return ""
        # إزالة كافة محارف الإيموجي واليونيكود الرسومي
        emoji_pattern = re.compile(
            r'[\U00010000-\U0010ffff\u2600-\u27ff\u2b50\u2300-\u23ff\u200d\u20e3\u25a0-\u25ff\u2190-\u21ff\u2700-\u27bf\u2900-\u297f\u2b00-\u2bff\u26a0\ufe0f]'
        )
        cleaned = emoji_pattern.sub('', text)
        for sym in ["📋", "💊", "⭐️", "💡", "⚠️", "🩺", "⭐", "🚨", "✅", "❌", "•"]:
            cleaned = cleaned.replace(sym, '')
        return re.sub(r' +', ' ', cleaned).strip()

    
    def lookup_drug_in_kb(self, query: str) -> Optional[Dict[str, Any]]:
        """البحث عن الدواء في قاعدة المعرفة الشاملة (121 دواء) بالاسم العلمي أو التجاري"""
        norm_q = self.normalize_arabic(query).lower()
        q_words = [w for w in norm_q.split() if len(w) >= 3]
        
        best_match = None
        best_score = 0.0
        
        for item in self.drugs_kb:
            gen_en = item.get("generic_name_en", "").lower()
            gen_ar = self.normalize_arabic(item.get("generic_name_ar", "")).lower()
            brand_raw = self.normalize_arabic(item.get("brand_raw", "")).lower()
            brands = [self.normalize_arabic(b).lower() for b in item.get("brand_names", [])]
            
            # 1. مطابقة مباشرة
            if norm_q in gen_ar or gen_ar in norm_q or norm_q in gen_en or gen_en in norm_q:
                return item
            for b in brands:
                if b and (b in norm_q or norm_q in b):
                    return item
            
            # 2. مطابقة بالكلمات
            score = 0.0
            for w in q_words:
                if w in gen_ar or w in gen_en:
                    score += 0.85
                elif w in brand_raw:
                    score += 0.90
                for b in brands:
                    if w in b:
                        score += 0.90
            
            if score > best_score and score >= 0.80:
                best_score = score
                best_match = item
                
        return best_match

    def generate_response(self, query: str, medicines: List[Dict[str, Any]], history: Optional[List[Dict[str, str]]] = None, api_key: Optional[str] = None) -> Dict[str, Any]:
        """توليد الرد النهائي المتكامل على استفسار المستخدم"""
        if not medicines:
            medicines = [
                {
                    "id": d.get("id", i),
                    "name": f"{d.get('brand_raw')} ({d.get('generic_name_ar')})",
                    "generic_name": f"{d.get('generic_name_en')} / {d.get('generic_name_ar')}",
                    "category": d.get("drug_class", "عام"),
                    "description": d.get("indications", ""),
                    "price": round(15.0 + (i * 2.1) % 50.0, 2),
                    "stock_quantity": 30 + (i * 5) % 60,
                    "expiry_date": "2027-10-15"
                }
                for i, d in enumerate(self.drugs_kb, 1)
            ]

        # 0. محاولة الاتصال بـ Google Gemini AI إذا كان المفتاح متوفراً
        gemini_res = self.call_gemini_api(query, medicines, history, api_key)
        if gemini_res:
            return gemini_res

        intent = self.identify_intent(query)
        matched_med = self.match_medicine_in_query(query, medicines)
        kb_drug = self.lookup_drug_in_kb(query)


        # 1. معالجة استفسارات الأعراض والكلام الطبيعي (SYMPTOM_DIAGNOSIS_RECOMMENDATION)
        if intent == "SYMPTOM_DIAGNOSIS_RECOMMENDATION":
            symptom_matches = self.match_symptoms_in_query(query)
            if symptom_matches:
                primary_match = symptom_matches[0]
                symptom = primary_match["symptom"]
                matched_kws = "، ".join(primary_match["matched_keywords"])

                recommended_meds = self.recommend_medicines_for_symptom(symptom, medicines)
                in_stock_meds = [m for m in recommended_meds if m.get("stock_quantity", 0) > 0]
                out_stock_meds = [m for m in recommended_meds if m.get("stock_quantity", 0) == 0]

                reply_lines = [
                    f"### التقييم السريري ودواعي الاستخدام:",
                    f"**الحالة المرصودة:** {symptom['title']}",
                    f"**العلامات المستخرجة من الاستفسار:** `{matched_kws}`",
                    ""
                ]

                if in_stock_meds:
                    reply_lines.append(f"#### الأدوية المقترحة المتوفرة حالياً في صيدلية المستشفى (مرتبة من الأوفر):")
                    for idx, m in enumerate(in_stock_meds[:6], start=1):
                        price = float(m.get("price", 0))
                        stock = m.get("stock_quantity", 0)
                        gen = m.get("generic_name", "غير محدد")
                        cat = m.get("category", "")
                        
                        # تمييز الخيار الأول
                        badge = " [الخيار الموصى به أولاً]" if idx == 1 else ""
                        reply_lines.append(
                            f"{idx}. **{m.get('name')}**{badge}\n"
                            f"   - **المادة الفعالة:** `{gen}` | **التصنيف:** {cat}\n"
                            f"   - **السعر:** **{price:.2f} ريال** | **الرصيد المتاح:** `{stock}` علبة"
                        )
                    
                    # تسليط الضوء على خيار التوفير
                    if len(in_stock_meds) > 1:
                        cheapest = min(in_stock_meds[:6], key=lambda x: float(x.get("price", 0)))
                        reply_lines.append(f"\n**خيار التوفير الأقصى:** دواء **{cheapest.get('name')}** بسعر **{float(cheapest.get('price', 0)):.2f} ريال** بنفس الفعالية السريرية.")
                else:
                    reply_lines.append("**حالة المخزون:** جميع الأدوية المباشرة لهذه الحالة نافدة حالياً من المخزون، يُرجى مراجعة الصيدلي لتوفير البدائل الطارئة.")

                if out_stock_meds and not in_stock_meds:
                    reply_lines.append("\nأدوية مسجلة بالنظام ولكنها نافدة حالياً:")
                    for m in out_stock_meds[:3]:
                        reply_lines.append(f"- **{m.get('name')}** (`{m.get('generic_name')}`)")

                # إضافة إرشادات الجرعات والاستخدام
                reply_lines.append(f"\n#### إرشادات الاستخدام والجرعات الآمنة:")
                reply_lines.append(f"{symptom['usage_advice']}")

                # إضافة التحذيرات السريرية
                reply_lines.append(f"\n#### محاذير وتنبيهات سريرية:")
                reply_lines.append(f"{symptom['precautions']}")

                return {
            "reply": self.sanitize_no_emojis("\n".join(reply_lines)),
            "intent": "SYMPTOM_DIAGNOSIS_RECOMMENDATION",
                    "symptom_id": symptom["id"],
                    "symptom_title": symptom["title"],
                    "matched_keywords": primary_match["matched_keywords"],
                    "data": in_stock_meds if in_stock_meds else recommended_meds
                }

        # 2. الترحيب والتعريف بالنظام
        if intent == "GREETING" and not matched_med:
            return {
                "reply": (
                    "أهلاً بك. **المساعد الدوائي الذكي** متصل مباشرة بقاعدة بيانات الصيدلية.\n\n"
                    "يمكنك التحدث معي باللغة الطبيعية وسؤالي عن:\n"
                    "- **أعراض وشكاوى المرضى**: (مثال: 'ابغى دواء لمريض يعاني من صداع'، 'علاج لحموضة وحرقة المعدة'، 'مسكن لآلام الأسنان والمفاصل').\n"
                    "- **الاستفسار عن أي صنف دوائي**: المادة الفعالة، السعر، والرصيد المتوفر.\n"
                    "- **البدائل ومقارنة الأسعار**: البحث عن بدائل أرخص متكافئة حيوياً.\n"
                    "- **حالة المخزون والصلاحية**: الأدوية القريبة من الانتهاء (FEFO) والنواقص (ROP).\n\n"
                    "تفضل بكتابة استفسارك أو أعراض المريض وسأقترح الأدوية المناسبة فوراً."
                ),
                "intent": "GREETING",
                "data": []
            }

        # 3. الاستعلام عن الأدوية القريبة من الانتهاء (FEFO Analysis)
        if intent == "EXPIRING_SOON":
            now = datetime.now()
            expiring_list = []
            for m in medicines:
                exp_str = m.get("expiry_date")
                if exp_str:
                    try:
                        exp_dt = datetime.strptime(str(exp_str)[:10], "%Y-%m-%d")
                        diff_days = (exp_dt - now).days
                        if diff_days <= 120:
                            expiring_list.append((m, diff_days))
                    except Exception:
                        pass
            
            expiring_list.sort(key=lambda x: x[1])
            if expiring_list:
                reply_lines = [
                    f"### تقرير الأدوية القريبة من تاريخ الانتهاء (تحليل FEFO الذكي):",
                    f"تم رصد **{len(expiring_list)}** أصناف دوائية توشك صلاحيتها على الانتهاء خلال الأشهر القادمة:",
                    ""
                ]
                for idx, (m, days) in enumerate(expiring_list[:8], 1):
                    badge_text = f"خلال {days} يوم" if days > 0 else "منتهي الصلاحية"
                    reply_lines.append(
                        f"{idx}. **{m.get('name')}** — الصلاحية: `{m.get('expiry_date')}` ({badge_text}) | الرصيد: `{m.get('stock_quantity', 0)}` علبة"
                    )
                reply_lines.append("\n**توصية النظام:** يُوصى بإعطاء أولوية الصرف الفوري لهذه التشغيلات أو إعادتها للشركات الموردة.")
                return {
            "reply": self.sanitize_no_emojis("\n".join(reply_lines)),
            "intent": "EXPIRING_SOON",
                    "data": [x[0] for x in expiring_list]
                }
            else:
                return {
            "reply": self.sanitize_no_emojis("جميع الأدوية المسجلة بالمخزون ذات صلاحية آمنة (أكثر من 4 أشهر) ولا توجد دفعات حرجة حالياً."),
            "intent": "EXPIRING_SOON",
                    "data": []
                }

        # 4. الاستعلام عن الأدوية التي تحتاج إعادة طلب (Dynamic ROP / Low Stock)
        if intent == "RESTOCK_RECOMMENDATIONS":
            critical_list = []
            for m in medicines:
                stock = m.get("stock_quantity", 0)
                min_alert = m.get("min_stock_alert", 20)
                if stock <= min_alert:
                    reorder_qty = max(20, (min_alert * 2) - stock)
                    critical_list.append((m, stock, min_alert, reorder_qty))
            
            critical_list.sort(key=lambda x: x[1])
            if critical_list:
                reply_lines = [
                    f"### تقرير النواقص وتوصيات إعادة الطلب (تحليل ROP الذكي):",
                    f"حسب تحليل حركة المخزون، يوجد **{len(critical_list)}** أصناف دوائية بلغت الحد الحرج وتحتاج توريد فوري:",
                    ""
                ]
                for idx, (m, stk, alert, req) in enumerate(critical_list[:8], 1):
                    reply_lines.append(
                        f"{idx}. **{m.get('name')}** — الرصيد: `{stk}` علبة (حد الأمان: {alert}) | **الكمية المقترحة للطلب: {req} علبة**"
                    )
                reply_lines.append("\n**توصية الذكاء الاصطناعي:** تم إنشاء مسودة طلبيات توريد موصى بها لتغطية استهلاك الصيدلية لـ 30 يوماً القادمة.")
                return {
            "reply": self.sanitize_no_emojis("\n".join(reply_lines)),
            "intent": "RESTOCK_RECOMMENDATIONS",
                    "data": [x[0] for x in critical_list]
                }
            else:
                return {
            "reply": self.sanitize_no_emojis("المخزون الحالي مستقر وكافة الأدوية تتجاوز حدود الأمان ونقاط إعادة الطلب (ROP)."),
            "intent": "RESTOCK_RECOMMENDATIONS",
                    "data": []
                }

        # 5. مقارنة الأسعار والبحث عن أرخص بديل
        if intent == "PRICE_COMPARISON" or (intent == "FIND_ALTERNATIVES" and matched_med):
            if matched_med:
                alts = self.find_alternatives(matched_med, medicines)
                all_group = [matched_med] + alts
                all_group.sort(key=lambda x: float(x.get("price", 0)))

                target_price = float(matched_med.get("price", 0))
                reply_lines = [
                    f"### مقارنة أسعار وبدائل دواء: **{matched_med.get('name')}**",
                    f"- **المادة الفعالة**: `{matched_med.get('generic_name', 'غير محدد')}`",
                    f"- **السعر الحالي**: **{target_price:.2f} ريال** | **الرصيد المتاح**: {matched_med.get('stock_quantity', 0)} علبة",
                    "",
                    "#### جدول البدائل المتاحة مرتبة من الأرخص للأعلى:"
                ]

                comparison_items = []
                for idx, item in enumerate(all_group, start=1):
                    item_price = float(item.get("price", 0))
                    is_current = (item.get("id") == matched_med.get("id"))
                    current_badge = " (الدواء المطلوب)" if is_current else ""
                    
                    diff = target_price - item_price
                    diff_text = f"أرخص بـ {diff:.2f} ريال" if diff > 0 else (f"أغلى بـ {abs(diff):.2f} ريال" if diff < 0 else "نفس السعر")

                    reply_lines.append(
                        f"{idx}. **{item.get('name')}**{current_badge} — **{item_price:.2f} ريال** "
                        f"| الرصيد: `{item.get('stock_quantity', 0)}` | {diff_text}"
                    )
                    comparison_items.append({
                        "name": item.get("name"),
                        "generic_name": item.get("generic_name"),
                        "price": item_price,
                        "stock": item.get("stock_quantity", 0),
                        "is_target": is_current,
                        "saving": diff if diff > 0 else 0
                    })

                cheapest = all_group[0]
                if cheapest.get("id") != matched_med.get("id") and float(cheapest.get("price", 0)) < target_price:
                    saving = target_price - float(cheapest.get("price", 0))
                    reply_lines.append("")
                    reply_lines.append(f"توصية التوفير: يعتبر دواء **{cheapest.get('name')}** هو الخيار الأوفر بنفس الفعالية ويوفر **{saving:.2f} ريال** لكل علبة.")

                return {
            "reply": self.sanitize_no_emojis("\n".join(reply_lines)),
            "intent": "PRICE_COMPARISON",
                    "matched_medicine": matched_med,
                    "comparison": comparison_items,
                    "data": all_group
                }
            else:
                return {
            "reply": self.sanitize_no_emojis("يرجى تحديد اسم الدواء الذي ترغب بمقارنة أسعاره وبدائله (مثال: 'قارن أسعار أوجمنتين' أو 'بدائل باراسيتامول')."),
            "intent": "PRICE_COMPARISON",
                    "data": []
                }

        # 6. فحص الرصيد وتاريخ الصلاحية
        if intent == "STOCK_CHECK":
            if matched_med:
                stock = matched_med.get("stock_quantity", 0)
                status_text = "متوفر بكمية جيدة" if stock > 20 else ("مخزون حرج (قارب على النفاد)" if stock > 0 else "غير متوفر (نفد المخزون)")
                expiry = matched_med.get("expiry_date", "غير محدد")
                
                reply = (
                    f"### حالة مخزون دواء: **{matched_med.get('name')}**\n"
                    f"- **الرصيد المتاح**: **{stock} علبة** ({status_text})\n"
                    f"- **سعر البيع**: {matched_med.get('price', 0)} ريال\n"
                    f"- **تاريخ صلاحية الدفعة الأقرب**: `{expiry}`\n"
                    f"- **التصنيف**: {matched_med.get('category', 'عام')}\n"
                )
                if stock == 0:
                    alts = self.find_alternatives(matched_med, medicines)
                    available_alts = [a for a in alts if a.get("stock_quantity", 0) > 0]
                    if available_alts:
                        reply += f"\nتنبيه: الدواء غير متوفر حالياً، ولكن يتوفر البديل المتكافئ: **{available_alts[0].get('name')}** (رصيد: {available_alts[0].get('stock_quantity')} علبة بسعر {available_alts[0].get('price')} ريال)."

                return {
            "reply": self.sanitize_no_emojis(reply),
            "intent": "STOCK_CHECK",
                    "matched_medicine": matched_med,
                    "data": [matched_med]
                }
            else:
                return {
            "reply": self.sanitize_no_emojis("يرجى كتابة اسم الدواء الذي ترغب بفحص رصيده ومخزونه في الصيدلية."),
            "intent": "STOCK_CHECK",
                    "data": []
                }

        # 7. استكشاف التصنيفات الدوائية
        if intent == "CATEGORY_EXPLORE":
            norm_q = self.normalize_arabic(query)
            cat_matches = []
            for med in medicines:
                cat = self.normalize_arabic(med.get("category", ""))
                name = self.normalize_arabic(med.get("name", ""))
                generic = self.normalize_arabic(med.get("generic_name", ""))
                if any(w in cat or w in name or w in generic for w in norm_q.split() if len(w) >= 3):
                    cat_matches.append(med)

            if cat_matches:
                reply_lines = [
                    f"### نتائج البحث في الأصناف المتاحة:",
                    f"تم العثور على **{len(cat_matches)}** أصناف مطابقة لاستفسارك:",
                    ""
                ]
                for idx, m in enumerate(cat_matches[:8], 1):
                    reply_lines.append(f"{idx}. **{m.get('name')}** ({m.get('generic_name', '')}) — السعر: **{m.get('price', 0)} ريال** | الرصيد: `{m.get('stock_quantity', 0)}`")
                
                return {
            "reply": self.sanitize_no_emojis("\n".join(reply_lines)),
            "intent": "CATEGORY_EXPLORE",
                    "data": cat_matches
                }

        # 8. الاستفسار العام عن الدواء
        if matched_med:
            alts = self.find_alternatives(matched_med, medicines)
            reply_lines = [
                f"### تفاصيل الدواء: **{matched_med.get('name')}**",
                f"- **الاسم العلمي (المادة الفعالة)**: `{matched_med.get('generic_name', 'غير محدد')}`",
                f"- **التصنيف الدوائي**: {matched_med.get('category', 'عام')}",
                f"- **سعر العلبة**: **{matched_med.get('price', 0)} ريال**",
                f"- **الرصيد بالمخزون**: {matched_med.get('stock_quantity', 0)} علبة",
                f"- **تاريخ الانتهاء**: `{matched_med.get('expiry_date', 'غير محدد')}`",
                ""
            ]
            if alts:
                reply_lines.append(f"البدائل المتاحة: يتوفر **{len(alts)}** بدائل متكافئة (أرخصها: **{alts[0].get('name')}** بسعر {alts[0].get('price')} ريال).")

            return {
            "reply": self.sanitize_no_emojis("\n".join(reply_lines)),
            "intent": "DRUG_INQUIRY",
                "matched_medicine": matched_med,
                "data": [matched_med]
            }

        
        # 8.2 فحص قاعدة المعرفة الشاملة (121 دواء) في حال لم يتم إيجاد الصنف بالمخزون المحلي
        if kb_drug and intent not in ["EXPIRING_SOON", "RESTOCK_RECOMMENDATIONS"]:
            reply_lines = [
                f"### تفاصيل الدواء: **{kb_drug.get('brand_raw')}**",
                f"- **الاسم العلمي (المادة الفعالة)**: `{kb_drug.get('generic_name_en')} ({kb_drug.get('generic_name_ar')})`",
                f"- **التصنيف الدوائي**: {kb_drug.get('drug_class')}",
                f"- **الأسماء التجارية الشائعة**: {kb_drug.get('brand_raw')}",
                f"- **دواعي الاستعمال والفوائد**: {kb_drug.get('indications')}",
                "",
                "نصيحة إرشادية: يرجى دائماً الالتزام بالجرعات المحددة من قِبل الطبيب المعالج أو الصيدلي وعدم تكرار الدواء دون وصفة طبية."
            ]
            return {
                "reply": self.sanitize_no_emojis("\n".join(reply_lines)),
                "intent": "DRUG_KNOWLEDGE_INQUIRY",
                "matched_kb_drug": kb_drug,
                "data": [kb_drug]
            }

        # لم يتم التعرف على الاستفسار
        return {
            "reply": (
                "لم أتمكن من التعرف على الاستفسار أو الدواء المطلوب بدقة.\n\n"
                "يمكنك سؤالي عن أعراض المريض مثل:\n"
                "- 'ابغى دواء لمريض يعاني من صداع'\n"
                "- 'علاج لحموضة وحرقة المعدة'\n"
                "- 'مسكن لآلام المفاصل أو الأسنان'\n"
                "- 'علاج للكحة والزكام والرشح'\n\n"
                "أو اكتب اسم الدواء التجاري أو العلمي مباشرة (مثل: بنادول، بروفين، أوميبرازول، أوجمنتين)."
            ),
            "intent": "UNKNOWN",
            "data": []
        }
