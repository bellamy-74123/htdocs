# -*- coding: utf-8 -*-
"""
خوارزمية تحليل سلة المشتريات والتوصيات الدوائية (Apriori Algorithm)
تقوم بتعدين قواعد الترافق واستخراج العلاقات الدوائية التلقائية من فواتير الصرف:
- حساب الدعم (Support)
- حساب الثقة (Confidence)
- حساب الرفع (Lift)
"""

from collections import defaultdict
from itertools import combinations
from typing import List, Dict, Tuple, Any

class AprioriEngine:
    """
    محرك خوارزمية Apriori للتعلم الآلي غير الخاضع للإشراف (Unsupervised Rule Mining)
    يستخرج الأنماط المتكررة بين الأدوية والمكملات من سجلات الصرف والفواتير.
    """

    def __init__(self, min_support: float = 0.03, min_confidence: float = 0.40):
        self.min_support = min_support
        self.min_confidence = min_confidence
        self.rules: List[Dict[str, Any]] = []
        self.item_support: Dict[str, float] = {}
        self.transaction_count: int = 0
        
        # المعجم الإرشادي للعلاقات الطبية السريرية المتقدمة لكافة الأنظمة العلاجية
        self.clinical_explanations = {
            ("أوجمنتين", "بروبيوتيك"): "حماية الجهاز الهضمي والفلورا المعوية من الاضطرابات الناتجة عن المضادات الحيوية.",
            ("أموكسيل", "فيتامين سي"): "تعزيز المناعة وتسريع الشفاء المصاحب لعلاج العدوى البكتيرية.",
            ("أموكسيسيلين", "بروبيوتيك"): "استعادة توازن البكتيريا المعوية النافعة والوقاية من الإسهال المصاحب للمضاد.",
            ("زيثروماكس", "بروبيوتيك"): "حماية جدار المعدة والفلورا أثناء كورس الماكروليد.",
            ("فولتارين", "أوميبرازول"): "حماية جدار المعدة من القرحة والآثار الجانبية لمضادات الالتهاب غير الستيرويدية.",
            ("بروفين", "أوميبرازول"): "واقي للمعدة لمنع القرحة والحموضة أثناء استخدام المسكنات.",
            ("سيلبركس", "إيزوميبرازول"): "حماية إضافية للجهاز الهضمي لدى المرضى المعرضين لمخاطر القرحة الهضمية.",
            ("نابروكسين", "بانتوبرازول"): "تقليل إفراز الحمض المعدي وتجنب التهاب الغشاء المخاطي أثناء تسكين المفاصل.",
            ("ميتفورمين", "فيتامين ب12"): "تعويض النقص المتوقع في فيتامين B12 الناتج عن الاستخدام المزمن لمنظم السكر.",
            ("جلوكوفاج", "نيوروبيون"): "حماية الأعصاب الطرفية من الاعتلال العصبي السكري ودعم خلايا الدم.",
            ("ليبيتور", "إنزيم كيو 10"): "تخفيف آلام وتيبس العضلات المصاحبة لأدوية الستاتين الخافضة للكوليسترول.",
            ("كريستور", "إنزيم كيو 10"): "تعويض مستويات CoQ10 في عضلة القلب والعضلات الهيكلية أثناء خفض الدهون.",
            ("أتورفاستاتين", "إنزيم كيو 10"): "دعم وظائف الميتوكوندريا العضلية وتقليل آلام الأطراف.",
            ("بنادول", "فيتامين سي"): "تخفيف أعراض نزلات البرد والزكام ودعم الاستجابة المناعية وسرعة التعافي.",
            ("فيفادول", "فيتامين سي"): "تخفيف الحرارة وتسكين الصداع المصاحب للإنفلونزا والرشح.",
            ("حديد", "حمض الفوليك"): "تكامل بناء الهيموجلوبين وخلايا الدم الحمراء لعلاج فقر الدم وسوء التغذية.",
            ("فيروجلوبين", "فيتامين سي"): "زيادة معدل امتصاص عنصر الحديد في الأمعاء الدقيقة.",
            ("كالسيوم", "فيتامين د3"): "تحسين الامتصاص المعوي للكالسيوم وبناء كثافة العظام والوقاية من الهشاشة.",
            ("كالترات", "فيدروب"): "دعم صحة الهيكل العظمي والمفاصل لدى كبار السن والنساء بعد انقطاع الطمث.",
            ("سيتريزين", "أوتريفين"): "علاج مشترك لتخفيف الرشح والحكة مع إزالة فورية لاحتقان وانسداد الأنف.",
            ("لوراتادين", "زايلوميتازولين"): "تهدئة أعراض الحساسية الموسمية مع فتح المجاري التنفسية.",
            ("لوسارتان", "هيدروكلوروثيازيد"): "تأثير تآزري مزدوج لخفض ضغط الدم المرتفع وتخليص الجسم من السوائل الزائدة.",
            ("كونكور", "أملوديبين"): "تنظيم ضربات القلب وتوسيع الشرايين لتحقيق السيطرة المثلى على ضغط الدم والذبحة.",
            ("باكلوفين", "باراسيتامول"): "إرخاء الشد والتشنج العضلي مع تسكين الآلام الحادة لأسفل الظهر والرقبة.",
            ("بارافون", "فولتارين"): "علاج تآزري قوي للتقلصات العضلية الحادة والتهابات الفقرات والمفاصل.",
            ("سينجولير", "فنتولين"): "السيطرة الوقائية طويلة الأمد مع الإغاثة السريعة لنوبات ضيق التنفس والربو.",
            ("دوسباتالين", "ديفلاتيل"): "تخفيف تقلصات وتشنجات القولون العصبي مع طرد الغازات والانتفاخ المعوي."
        }

        # بذر أولي بسجلات صرف واقعية تغطي مختلف التخصصات الطبية
        self._seed_default_transactions()

    def _seed_default_transactions(self):
        """قاعدة بيانات أولية واسعة لفواتير الصرف لتدريب النموذج الأساسي على كافة العائلات الدوائية."""
        default_baskets = [
            # 1. مضادات حيوية + مكملات
            ["أوجمنتين 1 جم", "بروبيوتيك (Probiotic Lactobacillus)", "بنادول إكسترا 500 مجم"],
            ["أوجمنتين 1 جم", "بروبيوتيك (Probiotic Lactobacillus)"],
            ["أوجمنتين 1 جم", "بروبيوتيك (Probiotic Lactobacillus)", "فيتامين سي 1000 مجم"],
            ["أموكسيسيلين 500 مجم", "بروبيوتيك (Probiotic Lactobacillus)"],
            ["أموكسيل 500 مجم", "فيتامين سي 1000 مجم"],
            ["أموكسيل 500 مجم", "فيتامين سي 1000 مجم", "بنادول إكسترا 500 مجم"],
            ["زيثروماكس 500 مجم", "بروبيوتيك (Probiotic Lactobacillus)", "بنادول إكسترا 500 مجم"],
            ["كلاريثروميسين 500 مجم", "أوميبرازول 20 مجم", "أموكسيسيلين 500 مجم"], # الخطة الثلاثية لجرثومة المعدة

            # 2. مسكنات ومضادات التهاب + واقي للمعدة
            ["فولتارين 50 مجم", "أوميبرازول 20 مجم"],
            ["فولتارين 50 مجم", "أوميبرازول 20 مجم", "بنادول إكسترا 500 مجم"],
            ["بروفين 400 مجم", "أوميبرازول 20 مجم"],
            ["بروفين 400 مجم", "بانتوبرازول 40 مجم"],
            ["نابروكسين 500 مجم", "إيزوميبرازول 40 مجم (Nexium)"],
            ["سيليكوكسيب 200 مجم (Celebrex)", "بانتوبرازول 40 مجم"],
            ["كاتافلام 50 مجم", "أوميبرازول 20 مجم"],

            # 3. سكري + فيتامينات أعصاب
            ["ميتفورمين 500 مجم", "فيتامين ب12 (1000mcg)", "جلوكوفاج 500 مجم"],
            ["ميتفورمين 500 مجم", "فيتامين ب12 (1000mcg)"],
            ["جلوكوفاج 1000 مجم", "فيتامين ب12 (1000mcg) (Neurobion)"],
            ["جانيوفيا 100 مجم (Sitagliptin)", "ميتفورمين 500 مجم", "فيتامين ب12 (1000mcg)"],
            ["جارديانس 10 مجم (Empagliflozin)", "ميتفورمين 500 مجم"],

            # 4. كوليسترول وقلب + CoQ10
            ["ليبيتور 20 مجم", "إنزيم كيو 10 (CoQ10)"],
            ["أتورفاستاتين 40 مجم (Lipitor)", "إنزيم كيو 10 (CoQ10)", "أسبرين 81 مجم"],
            ["روزوڤاستاتين 10 مجم (Crestor)", "إنزيم كيو 10 (CoQ10)"],
            ["روزوڤاستاتين 20 مجم (Crestor)", "أسبرين 81 مجم"],
            ["كونكور 5 مجم (Bisoprolol)", "أملوديبين 5 مجم (Norvasc)"],
            ["لوسارتان 50 مجم (Cozaar)", "هيدروكلوروثيازيد 25 مجم (Esidrex)"],
            ["فالسارتان 80 مجم (Diovan)", "هيدروكلوروثيازيد 25 مجم (Esidrex)"],
            ["بلافيكس 75 مجم (Clopidogrel)", "أسبرين 81 مجم (Aspirin Protect)"],

            # 5. تنفسي وحساسية
            ["بنادول إكسترا 500 مجم", "فيتامين سي 1000 مجم"],
            ["سيتريزين 10 مجم (Zyrtec)", "أوتريفين بخاخ أنف (Otrivin)"],
            ["لوراتادين 10 مجم (Claritine)", "أوتريفين بخاخ أنف (Otrivin)"],
            ["فنتولين بخاخ (Ventolin)", "سينجولير 10 مجم (Singulair)"],
            ["سيمبيكورت توربوهيلر (Symbicort)", "فنتولين بخاخ (Ventolin)"],
            ["فلويموسيل 600 مجم (Fluimucil)", "بنادول إكسترا 500 مجم"],

            # 6. معادن وعظام ومفاصل
            ["فيروجلوبين ب12 (Feroglobin)", "فيتامين سي 1000 مجم"],
            ["مركبات الحديد (Ferrous Sulfate)", "حمض الفوليك 5 مجم (Folic Acid)"],
            ["كالترات 600 مجم (Caltrate)", "فيدروب فيتامين د3 (Vidrop D3)"],
            ["كالسيوم وفيتامين د", "كوليكالسيفيرول (فيتامين د3)"],

            # 7. عضلات ومغص وقولون
            ["بارافون كبسول (Parafon)", "فولتارين 50 مجم"],
            ["باكلوفين 10 مجم (Lioresal)", "بنادول إكسترا 500 مجم"],
            ["دوسباتالين 200 مجم (Duspatalin)", "ديفلاتيل مضغ (Disflatyl)"],
            ["بسكوبان 10 مجم (Buscopan)", "بنادول 500 مجم"],
            ["موتيليوم 10 مجم (Motilium)", "أوميبرازول 20 مجم"]
        ]
        self.fit(default_baskets)

    def fit(self, transactions: List[List[str]]) -> List[Dict[str, Any]]:
        """
        تدريب وتعدين القواعد من قائمة سلال المشتريات.
        """
        if not transactions:
            return self.rules

        self.transaction_count = len(transactions)
        item_counts = defaultdict(int)
        pair_counts = defaultdict(int)

        # 1. حساب تكرار الأصناف الفردية (L1)
        for basket in transactions:
            unique_items = set(basket)
            for item in unique_items:
                item_counts[item] += 1
            # 2. حساب تكرار الأزواج (L2)
            for item1, item2 in combinations(sorted(unique_items), 2):
                pair_counts[(item1, item2)] += 1

        self.item_support = {item: count / self.transaction_count for item, count in item_counts.items()}

        # 3. توليد قواعد الترافق وحساب المقاييس (Support, Confidence, Lift)
        min_count = max(1, int(self.min_support * self.transaction_count))
        generated_rules = []

        for (item1, item2), pair_count in pair_counts.items():
            if pair_count < min_count:
                continue

            pair_support = pair_count / self.transaction_count
            supp1 = self.item_support.get(item1, 0.0001)
            supp2 = self.item_support.get(item2, 0.0001)

            # قاعدة 1: item1 -> item2
            conf1 = pair_support / supp1
            lift1 = conf1 / supp2 if supp2 > 0 else 1.0

            if conf1 >= self.min_confidence:
                explanation = self._get_explanation(item1, item2)
                generated_rules.append({
                    "antecedent": item1,
                    "consequent": item2,
                    "support": round(pair_support, 3),
                    "confidence": round(conf1, 3),
                    "lift": round(lift1, 2),
                    "explanation": explanation
                })

            # قاعدة 2: item2 -> item1
            conf2 = pair_support / supp2
            lift2 = conf2 / supp1 if supp1 > 0 else 1.0

            if conf2 >= self.min_confidence:
                explanation = self._get_explanation(item2, item1)
                generated_rules.append({
                    "antecedent": item2,
                    "consequent": item1,
                    "support": round(pair_support, 3),
                    "confidence": round(conf2, 3),
                    "lift": round(lift2, 2),
                    "explanation": explanation
                })

        # ترتيب القواعد تنازلياً حسب درجة الرفع (Lift) ثم الثقة (Confidence)
        generated_rules.sort(key=lambda r: (r["lift"], r["confidence"]), reverse=True)
        self.rules = generated_rules
        return self.rules

    def get_recommendations_for_item(self, item_name: str) -> List[Dict[str, Any]]:
        """الحصول على التوصيات المقترنة بدواء معين بدعم كامل للأسماء العربية والإنجليزية والأسماء التجارية."""
        item_lower = item_name.lower().strip()
        aliases = {
            "augmentin": "أوجمنتين",
            "amoxil": "أموكسيل",
            "amoxicillin": "أموكسيسيلين",
            "zithromax": "زيثروماكس",
            "panadol": "بنادول",
            "fevadol": "فيفادول",
            "voltaren": "فولتارين",
            "brufen": "بروفين",
            "ibuprofen": "إيبوبروفين",
            "celebrex": "سيليكوكسيب",
            "naproxen": "نابروكسين",
            "metformin": "ميتفورمين",
            "glucophage": "جلوكوفاج",
            "januvia": "جانيوفيا",
            "jardiance": "جارديانس",
            "lipitor": "ليبيتور",
            "atorvastatin": "أتورفاستاتين",
            "crestor": "روزوڤاستاتين",
            "concor": "كونكور",
            "norvasc": "أملوديبين",
            "cozaar": "لوسارتان",
            "nexium": "إيزوميبرازول",
            "losec": "أوميبرازول",
            "omeprazole": "أوميبرازول",
            "ventolin": "فنتولين",
            "singulair": "سينجولير",
            "zyrtec": "سيتريزين",
            "claritine": "لوراتادين",
            "otrivin": "أوتريفين",
            "caltrate": "كالترات",
            "feroglobin": "فيروجلوبين",
            "duspatalin": "دوسباتالين",
            "buscopan": "بسكوبان"
        }
        
        target_terms = [item_lower]
        for en, ar in aliases.items():
            if en in item_lower:
                target_terms.append(ar.lower())
            elif ar in item_lower:
                target_terms.append(en.lower())

        matched = []
        for r in self.rules:
            ant_lower = r["antecedent"].lower()
            if any(t in ant_lower or ant_lower in t for t in target_terms):
                matched.append(r)
        return matched

    def _get_explanation(self, antecedent: str, consequent: str) -> str:
        """استخراج التفسير الطبي السريري المناسب للعلاقة."""
        for (k1, k2), exp in self.clinical_explanations.items():
            if (k1.lower() in antecedent.lower() and k2.lower() in consequent.lower()) or                (k2.lower() in antecedent.lower() and k1.lower() in consequent.lower()):
                return exp
        return f"ترافق متكرر في فواتير الصرف السريرية مع زيادة طلب بنسبة {int(100 * self.item_support.get(consequent, 0.25))}%."
