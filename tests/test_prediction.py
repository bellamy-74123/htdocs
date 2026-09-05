# -*- coding: utf-8 -*-
"""
Unit tests for AI Engine matching all algorithms and features in Documentation:
- Smart fuzzy search and similarity ranking
- Dynamic demand prediction & Reorder Point calculation
- ARIMA time-series demand forecasting
- FEFO expiry batch sorting & alert categorization
- Apriori market basket recommendations
- Anomaly detection for unusual dispensing volumes
"""

import unittest
import sys
import os

sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../ai-engine')))

from oop.ModelPredictor import ModelPredictor
from oop.ModelTrainer import ModelTrainer

class TestAIEngineArabic(unittest.TestCase):
    """
    اختبارات وحدة الذكاء الاصطناعي لنظام إدارة الصيدلية الذكي
    """
    def setUp(self):
        self.predictor = ModelPredictor()
        self.trainer = ModelTrainer()
        self.sample_medicines = [
            {'id': 1, 'name': 'بنادول إكسترا 500 مجم', 'generic_name': 'باراسيتامول / كافيين', 'category': 'مسكنات وخافضات حرارة', 'price': 5.5, 'stock_quantity': 100},
            {'id': 2, 'name': 'أوجمنتين 1 جم', 'generic_name': 'أموكسيسيلين / كلافولانيك', 'category': 'مضادات حيوية', 'price': 22.0, 'stock_quantity': 15},
            {'id': 3, 'name': 'فولتارين 50 مجم', 'generic_name': 'ديكلوفيناك صوديوم', 'category': 'مضادات التهاب ومسكنات', 'price': 14.0, 'stock_quantity': 5}
        ]

    def test_smart_search_ranking_arabic(self):
        """اختبار دقة البحث الذكي والمطابقة التامة والضبابية (بندول -> بنادول)"""
        # 1. مطابقة تامة
        res_exact = self.predictor.smart_search('بنادول', self.sample_medicines)
        self.assertGreater(len(res_exact), 0)
        self.assertEqual(res_exact[0]['name'], 'بنادول إكسترا 500 مجم')
        self.assertGreater(res_exact[0]['relevance_score'], 0.8)

        # 2. مطابقة ضبابية وهيكل صوتي (بندول بدون ألف -> بنادول)
        res_fuzzy = self.predictor.smart_search('بندول', self.sample_medicines)
        self.assertGreater(len(res_fuzzy), 0)
        self.assertEqual(res_fuzzy[0]['name'], 'بنادول إكسترا 500 مجم')
        self.assertGreater(res_fuzzy[0]['relevance_score'], 0.75)

        # 3. مطابقة ضبابية (فلترين -> فولتارين)
        res_volt = self.predictor.smart_search('فلترين', self.sample_medicines)
        self.assertGreater(len(res_volt), 0)
        self.assertEqual(res_volt[0]['name'], 'فولتارين 50 مجم')

        # 4. مطابقة الاسم العلمي (براسيتامول -> باراسيتامول)
        res_para = self.predictor.smart_search('براسيتامول', self.sample_medicines)
        self.assertGreater(len(res_para), 0)
        self.assertEqual(res_para[0]['name'], 'بنادول إكسترا 500 مجم')

    def test_demand_prediction_and_rop(self):
        """اختبار التنبؤ بالطلب وحساب نقطة إعادة الطلب ديناميكياً"""
        res = self.predictor.predict_demand(stock_quantity=5, avg_daily_sales=2.0, lead_time_days=3, safety_stock_days=4)
        self.assertEqual(res['days_remaining'], 2.5)
        self.assertTrue(res['is_reorder_needed'])
        self.assertEqual(res['dynamic_reorder_point'], 14)

    def test_arima_forecasting(self):
        """اختبار التنبؤ بالسلاسل الزمنية ARIMA"""
        history = [12, 14, 15, 13, 16, 18, 20]
        forecast = self.predictor.forecast_arima(history, forecast_days=7)
        self.assertEqual(len(forecast['predictions']), 7)
        self.assertEqual(forecast['confidence_score'], 0.942)
        self.assertGreater(forecast['estimated_weekly_demand'], 50)

    def test_fefo_sorting_and_alerts(self):
        """اختبار ترتيب الدفعات حسب الأقرب انتهاءً (FEFO) وتصنيف التنبيهات"""
        batches = [
            {'batch_no': 'B002', 'expiry_date': '2027-12-31', 'quantity': 50},
            {'batch_no': 'B001', 'expiry_date': '2026-09-15', 'quantity': 20}
        ]
        sorted_b = self.predictor.sort_fefo_batches(batches)
        self.assertEqual(sorted_b[0]['batch_no'], 'B001')
        self.assertIn('EXPIRING_SOON', sorted_b[0]['fefo_status'])

    def test_apriori_recommendations(self):
        """اختبار محرك توصية المكملات والبدائل الدوائية"""
        recs = self.predictor.get_apriori_recommendations('Augmentin 1g')
        self.assertGreater(len(recs), 0)
        self.assertIn('Probiotic', recs[0]['recommended_product'])
        self.assertGreaterEqual(recs[0]['support_confidence'], 0.80)

    def test_anomaly_detection(self):
        """اختبار كشف الشذوذ في كميات الصرف"""
        txs = [
            {'tx_id': 1, 'quantity': 2},
            {'tx_id': 2, 'quantity': 3},
            {'tx_id': 3, 'quantity': 2},
            {'tx_id': 4, 'quantity': 45}  # غير طبيعي
        ]
        anomalies = self.predictor.detect_anomalies(txs)
        self.assertEqual(len(anomalies), 1)
        self.assertEqual(anomalies[0]['tx_id'], 4)

    def test_trainer_metrics(self):
        """اختبار تدريب النموذج الأساسي"""
        metrics = self.trainer.train_baseline_model()
        self.assertEqual(metrics['status'], 'TRAINED')

    def test_chatbot_greeting(self):
        """اختبار رد الشات بوت على التحية والتعريف بالنظام"""
        res = self.predictor.process_chat("السلام عليكم ورحمة الله", self.sample_medicines)
        self.assertEqual(res['intent'], 'GREETING')
        self.assertIn('المساعد الدوائي الذكي', res['reply'])

    def test_chatbot_price_comparison_and_alternatives(self):
        """اختبار مقارنة الأسعار وترتيب البدائل من الأرخص للأعلى"""
        extended_meds = [
            {'id': 1, 'name': 'أوجمنتين 1 جم', 'generic_name': 'أموكسيسيلين + كلافولانات', 'category': 'مضادات حيوية', 'price': 45.0, 'stock_quantity': 15, 'expiry_date': '2026-11-30'},
            {'id': 2, 'name': 'كلافوكس 1 جم', 'generic_name': 'أموكسيسيلين + كلافولانات', 'category': 'مضادات حيوية', 'price': 30.0, 'stock_quantity': 40, 'expiry_date': '2026-10-15'},
            {'id': 3, 'name': 'ميجاموكس 1 جم', 'generic_name': 'أموكسيسيلين + كلافولانات', 'category': 'مضادات حيوية', 'price': 25.0, 'stock_quantity': 60, 'expiry_date': '2026-12-10'}
        ]
        res = self.predictor.process_chat("قارن أسعار وبدائل أوجمنتين", extended_meds)
        self.assertEqual(res['intent'], 'PRICE_COMPARISON')
        self.assertIn('مقارنة أسعار', res['reply'])
        # التحقق من أن البديل الأرخص (ميجاموكس) مقترح
        self.assertIn('ميجاموكس', res['reply'])

    def test_chatbot_stock_check(self):
        """اختبار استعلام الرصيد والمخزون في الشات بوت"""
        res = self.predictor.process_chat("كم باقي مخزون بنادول إكسترا؟", self.sample_medicines)
        self.assertEqual(res['intent'], 'STOCK_CHECK')
        self.assertIn('حالة مخزون دواء', res['reply'])
        self.assertIn('100 علبة', res['reply'])

    def test_continuous_auto_retraining_pipeline(self):
        """اختبار خط أنابيب إعادة التدريب الآلي وتعدين قواعد Apriori حياً"""
        new_transactions = [
            {"items": [{"name": "أوجمنتين 1 جم", "medicine_id": 1, "quantity": 2}, {"name": "بروبيوتيك (Lactobacillus)", "medicine_id": 10, "quantity": 1}]},
            {"items": [{"name": "فولتارين 50 مجم", "medicine_id": 4, "quantity": 1}, {"name": "أوميبرازول 20 مجم", "medicine_id": 11, "quantity": 1}]}
        ]
        res = self.predictor.retrain_from_transactions(new_transactions)
        self.assertEqual(res['status'], 'success')
        self.assertGreater(res['mined_rules_count'], 0)
        self.assertGreater(res['total_cycles'], 1)

        # التحقق من استرجاع حالة التدريب
        status = self.predictor.get_training_status()
        self.assertEqual(status['status'], 'active')
        self.assertGreater(status['active_apriori_rules'], 0)

    def test_chatbot_expiring_soon_fefo_intent(self):
        """اختبار استعلام الأدوية القريبة من الانتهاء في الشات بوت (FEFO Intent)"""
        test_meds = [
            {'id': 1, 'name': 'أوجمنتين 1 جم', 'stock_quantity': 10, 'expiry_date': '2026-09-15'},
            {'id': 2, 'name': 'بنادول إكسترا', 'stock_quantity': 50, 'expiry_date': '2028-12-31'}
        ]
        res = self.predictor.process_chat("ما هي الأدوية التي ستنتهي قريباً؟", test_meds)
        self.assertEqual(res['intent'], 'EXPIRING_SOON')
        self.assertIn('تقرير الأدوية القريبة من تاريخ الانتهاء', res['reply'])
        self.assertIn('أوجمنتين', res['reply'])

    def test_chatbot_restock_recommendations_intent(self):
        """اختبار استعلام النواقص وإعادة الطلب في الشات بوت (ROP Intent)"""
        test_meds = [
            {'id': 1, 'name': 'أوجمنتين 1 جم', 'stock_quantity': 5, 'min_stock_alert': 20},
            {'id': 2, 'name': 'بنادول إكسترا', 'stock_quantity': 200, 'min_stock_alert': 30}
        ]
        res = self.predictor.process_chat("اقترح طلبيات النواقص التي تحتاج إعادة طلب", test_meds)
        self.assertEqual(res['intent'], 'RESTOCK_RECOMMENDATIONS')
        self.assertIn('تقرير النواقص وتوصيات إعادة الطلب', res['reply'])
        self.assertIn('أوجمنتين', res['reply'])

    def test_7_day_forecast_and_risk_classification(self):
        """اختبار دقة نموذج السلاسل الزمنية والتنبؤ بـ 7 أيام وتصنيف المخاطر"""
        engine = self.predictor.forecasting_engine
        res = engine.forecast_demand(medicine_id=1, current_stock=6)
        self.assertEqual(len(res['next_7_days_forecast']), 7)
        self.assertIn(res['risk_badge'], ['CRITICAL', 'MEDIUM', 'LOW'])
        self.assertGreater(res['expected_7_days_demand'], 0)
        self.assertGreater(res['reorder_point_rop'], 0)
        self.assertEqual(res['confidence_interval'], '95.0%')

    def test_chatbot_natural_language_headache_symptom(self):
        """اختبار فهم الكلام الطبيعي واقتراح أدوية الصداع والآلام"""
        extended_meds = [
            {'id': 1, 'name': 'بنادول إكسترا 500 مجم', 'generic_name': 'باراسيتامول + كافيين', 'category': 'مسكن وخافض حرارة', 'price': 12.0, 'stock_quantity': 250},
            {'id': 2, 'name': 'باراسيتامول فارما 500 مجم', 'generic_name': 'باراسيتامول', 'category': 'مسكن وخافض حرارة', 'price': 5.0, 'stock_quantity': 180},
            {'id': 3, 'name': 'بروفين 400 مجم', 'generic_name': 'إيبوبروفين', 'category': 'مسكن ومضاد للالتهاب', 'price': 15.0, 'stock_quantity': 65}
        ]
        res = self.predictor.process_chat("ابغى دواء لمريض يعاني من صداع شديد", extended_meds)
        self.assertEqual(res['intent'], 'SYMPTOM_DIAGNOSIS_RECOMMENDATION')
        self.assertIn('الصداع', res['reply'])
        self.assertIn('التقييم السريري', res['reply'])
        self.assertIn('إرشادات الاستخدام', res['reply'])
        self.assertGreater(len(res['data']), 0)

    def test_chatbot_natural_language_stomach_acidity(self):
        """اختبار فهم استفسار حموضة وحرقة المعدة والارتجاع"""
        stomach_meds = [
            {'id': 11, 'name': 'أوميبرازول 20 مجم', 'generic_name': 'أوميبرازول', 'category': 'أدوية الجهاز الهضمي والمعدة', 'price': 20.0, 'stock_quantity': 110}
        ]
        res = self.predictor.process_chat("ايش افضل علاج لحموضة وحرقة المعدة والارتجاع؟", stomach_meds)
        self.assertEqual(res['intent'], 'SYMPTOM_DIAGNOSIS_RECOMMENDATION')
        self.assertIn('حموضة', res['reply'])
        self.assertIn('أوميبرازول', res['reply'])

    def test_chatbot_natural_language_toothache(self):
        """اختبار فهم استفسارات آلام الأسنان والضروس"""
        dental_meds = [
            {'id': 25, 'name': 'بروفين 400 مجم', 'generic_name': 'إيبوبروفين', 'category': 'مسكن ومضاد للالتهاب', 'price': 15.0, 'stock_quantity': 130},
            {'id': 32, 'name': 'كتافلام 50 مجم', 'generic_name': 'ديكلوفيناك البوتاسيوم', 'category': 'مسكن ومضاد للالتهاب', 'price': 24.0, 'stock_quantity': 80}
        ]
        res = self.predictor.process_chat("مريض يشكي من وجع في الضرس واللثة", dental_meds)
        self.assertEqual(res['intent'], 'SYMPTOM_DIAGNOSIS_RECOMMENDATION')
        self.assertIn('الأسنان', res['reply'])
        self.assertGreater(len(res['data']), 0)

if __name__ == '__main__':
    unittest.main()

