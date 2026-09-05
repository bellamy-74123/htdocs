class ModelTrainer:
    """
    كلاس كائني التوجه (OOP) مسؤول عن تهيئة وتدريب مؤشرات التنبؤ
    """
    def __init__(self, data_path: str = None):
        self.data_path = data_path

    def load_data(self) -> list:
        return [
            {'medicine_id': 1, 'monthly_sales': 150, 'avg_daily_sales': 5.0},
            {'medicine_id': 2, 'monthly_sales': 60, 'avg_daily_sales': 2.0},
            {'medicine_id': 3, 'monthly_sales': 30, 'avg_daily_sales': 1.0}
        ]

    def train_baseline_model(self) -> dict:
        data = self.load_data()
        total_sales = sum(item['monthly_sales'] for item in data)
        avg_sales = total_sales / len(data) if data else 0

        metrics = {
            'total_medicines_indexed': len(data),
            'avg_monthly_sales': float(avg_sales),
            'status': 'TRAINED'
        }
        return metrics
