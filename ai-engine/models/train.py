import sys
import os
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

from oop.ModelTrainer import ModelTrainer

def run_training():
 data_csv = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))), "data", "consumption_data.csv")
 trainer = ModelTrainer(data_path=data_csv)
 metrics = trainer.train_baseline_model()
 print("Training Completed Successfully:", metrics)
 return metrics

if __name__ == "__main__":
 run_training()
