========================================================================
COLLEGE OF COMPUTER & INFORMATION TECHNOLOGY (2026 / 2027)
SANA'A UNIVERSITY - FACULTY OF COMPUTER & INFORMATION TECHNOLOGY
========================================================================

Project Title:
Smart Pharmacy & AI-Powered Inventory Management System (SPMS)

Courses:
1. Artificial Intelligence (Chief Supervisor: Dr. Ayham Al-Akhali)
2. Advanced Programming (Supervisor: Eng. Waleed Al-Doais)

------------------------------------------------------------------------
MATRIX OF TEAM ROLES & TECHNICAL CONTRIBUTIONS (مصفوفة توزيع الأدوار):
------------------------------------------------------------------------

1. Abdullah Al-Bous (ID: 25164359) - Team Lead & Software Architect
   - Roles: Project Manager, Multi-Platform Architect, Lead NLP Engineer
   - Technical Contributions:
     * Designed decoupled multi-platform architecture (PHP OOP + Python FastAPI).
     * Implemented JWT HMAC-SHA256 authentication and token expiration handling.
     * Developed the NLP Fuzzy Similarity Matcher & Intent Recognition Chatbot.
     * Engineered the central facade and structural integration patterns.

2. Mohammed Qahri (ID: 25164065) - Lead Machine Learning Engineer
   - Roles: ML Engineer, Time-Series & Demand Forecasting Specialist
   - Technical Contributions:
     * Developed data preprocessing pipeline and 8 lag/rolling features.
     * Trained and fine-tuned Random Forest Regressor & Ridge Regression models.
     * Achieved and verified academic metrics: MAE (0.87), RMSE (1.119), R^2 (0.8763).
     * Built local offline model training and serialization pipeline (demand_model.joblib).

3. Ahmed Al-Saidi (ID: 25164067) - Operations & Inventory Algorithm Engineer
   - Roles: Database Architect, Inventory Optimization Engineer
   - Technical Contributions:
     * Formulated dynamic Reorder Point (ROP) & Safety Stock (95% service level).
     * Implemented the Dynamic FEFO (First Expired, First Out) batch heuristic.
     * Designed normalized 3NF MySQL/PostgreSQL schema and PDO SQL immunity.
     * Built automated early warning alerts for batches near expiration (30/60 days).

4. Ibrahim Ibrahim (ID: 25164587) - Data Mining & QA Engineer
   - Roles: Data Mining Engineer, Automated Testing & Quality Lead
   - Technical Contributions:
     * Implemented unsupervised Apriori Association Rule Mining algorithm.
     * Evaluated clinical co-prescription metrics: Support >= 3%, Conf >= 40%, Lift > 2.0x.
     * Built the continuous auto-retraining pipeline (/api/retrain) on order events.
     * Authored comprehensive test suites (test_api.php, test_prediction.py, test_ml_training.py).

------------------------------------------------------------------------
Core AI Algorithms Implemented:
------------------------------------------------------------------------
1. Supervised Machine Learning: Random Forest Regressor & Ridge Regression
   - Task: Daily Medicine Demand Forecasting & Dynamic Reorder Point (ROP)
   - Evaluation Metrics: MAE (0.87 units), RMSE (1.119), R^2 Score (0.8763)
2. Unsupervised Machine Learning: Apriori Association Rule Mining
   - Task: Market Basket Co-Prescription Analysis & Clinical Recommendations
   - Evaluation Metrics: Support (>= 3%), Confidence (>= 40%), Lift (> 2.0x)
3. Natural Language Processing (NLP):
   - Task: Symptom Extraction, Similarity Matching & Arabic Pharmacy Chatbot (< 15ms)
4. Heuristic Search & Sorting:
   - Task: Dynamic First Expired, First Out (FEFO) Batch Allocation

------------------------------------------------------------------------
How to Run the Project:
------------------------------------------------------------------------
1. Run Fast AI Evaluation CLI (Academic Defense):
   py ai-engine/evaluate_academic_metrics.py

2. Run Automated Test Suites (100% Pass):
   php tests/test_api.php               (Tests JWT, XSS, Factory Pattern, and Dynamic XML)
   py tests/test_prediction.py          (Tests Demand Forecasting & API endpoints)
   py tests/test_ml_training.py         (Tests local ML Training & Weights)

3. Run 1-Click Complete System:
   run_project.bat
   or via PowerShell:
   .\run_project.ps1

========================================================================
