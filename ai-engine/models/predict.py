import sys
import os
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

from oop.ModelPredictor import ModelPredictor

def run_prediction_demo():
 predictor = ModelPredictor()
 sample_medicines = [
 {"id": 1, "name": "Panadol Extra", "generic_name": "Paracetamol / Caffeine", "category": "Analgesic"},
 {"id": 2, "name": "Amoxil 500mg", "generic_name": "Amoxicillin", "category": "Antibiotic"},
 {"id": 3, "name": "Augmentin 1g", "generic_name": "Amoxicillin / Clavulanic Acid", "category": "Antibiotic"}
 ]

 print("--- 1. Testing Smart Search ---")
 search_res = predictor.smart_search("amox", sample_medicines)
 print("Search query 'amox' results:", search_res)

 print("\n--- 2. Testing Demand Forecasting ---")
 demand_res = predictor.predict_demand(stock_quantity=10, avg_daily_sales=2.5)
 print("Demand prediction for 10 stock with 2.5 daily sales:", demand_res)

if __name__ == "__main__":
 run_prediction_demo()
