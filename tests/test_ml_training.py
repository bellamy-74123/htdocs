# -*- coding: utf-8 -*-
"""
اختبارات وحدة لخط أنابيب تدريب وتقييم نماذج التعلم الآلي (Unit Tests for ML Pipeline)
"""

import unittest
import os
import sys

sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../ai-engine')))

from models.train_demand_model import load_and_preprocess_dataset, compute_metrics, PureNumpyRidgeModel
import numpy as np

class TestMLTrainingPipeline(unittest.TestCase):
    def setUp(self):
        self.csv_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '../ai-engine/data/pharmacy_sales_history.csv'))

    def test_dataset_exists_and_loads(self):
        """التأكد من وجود الداتا التاريخية وتحميلها بنجاح"""
        self.assertTrue(os.path.exists(self.csv_path))
        X, y, features = load_and_preprocess_dataset(self.csv_path)
        self.assertGreater(len(X), 1000)
        self.assertEqual(len(X), len(y))
        self.assertEqual(len(features), 8)

    def test_metrics_computation(self):
        """اختبار حساب مقاييس التقييم (MAE, RMSE, R2) بدقة رياضية"""
        y_true = np.array([10.0, 20.0, 30.0, 40.0])
        y_pred = np.array([11.0, 19.0, 31.0, 39.0])
        metrics = compute_metrics(y_true, y_pred)
        self.assertEqual(metrics['MAE'], 1.0)
        self.assertEqual(metrics['RMSE'], 1.0)
        self.assertGreater(metrics['R2_Score'], 0.95)

    def test_pure_numpy_model_training(self):
        """اختبار قدرة النموذج الرياضي على التعلم والتوقع"""
        X = np.array([[1, 2], [2, 4], [3, 6], [4, 8]], dtype=float)
        y = np.array([2.0, 4.0, 6.0, 8.0])
        model = PureNumpyRidgeModel(alpha=0.01)
        model.fit(X, y)
        preds = model.predict(np.array([[5, 10]], dtype=float))
        self.assertAlmostEqual(preds[0], 10.0, places=1)

if __name__ == '__main__':
    unittest.main()
