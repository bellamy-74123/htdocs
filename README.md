# Smart Pharmacy & AI Management System
> **مشروع مادة البرمجة المتقدمة والذكاء الاصطناعي (Advanced Programming Project)** 
> **جامعة صنعاء - كلية الحاسوب وتكنولوجيا المعلومات**

---

## نبذة عن المشروع (Overview)
نظام متكامل لإدارة الصيدليات ومستودعات الأدوية يعتمد على **معمارية هجينة متعددة المنصات (Multi-Platform REST Architecture)**:
1. **الواجهة الأمامية (Web Frontend)**: واجهة مستخدم متجاوبة تدعم الوضعين الليلي والفتحي (Dark/Light Mode) واللغة العربية بالكامل.
2. **محرك الذكاء الاصطناعي (Python AI Service)**: خادم مستقل مبني بـ FastAPI يوفر خدمات البحث الذكي وتوقع استهلاك المخزون.
3. **الواجهة الخلفية المركزية (PHP REST Backend)**: مبنية على معمارية كائنية OOP / MVC مع تطبيق أنماط التصميم (Singleton, Factory, Facade) ومؤمنة برموز JWT و PDO ضد حقن SQL.
4. **قاعدة البيانات المركزية (MySQL Database)**: قاعدة بيانات مطبعة (3NF) من 5 جداول رئيسية.

---

## فريق العمل
* **عبدالله البوص**
* **محمد قحري**
* **ابراهيم ابراهيم**
* **محمد الصايدي**

---

## طريقة التشغيل السريع (Quick Start)

### 1. التشغيل التلقائي بنقرة واحدة (Windows)
اضغط مرتين على الملف:
```cmd
run_project.bat
```
أو عبر PowerShell:
```powershell
.\run_project.ps1
```

### 2. التشغيل اليدوي للخدمات
- **محرك الذكاء الاصطناعي (Python FastAPI)**:
 ```bash
 cd ai-engine
 py -m uvicorn api.server:app --host 127.0.0.1 --port 8000 --reload
 ```
- **الواجهة الخلفية (PHP Backend)**:
 ```bash
 php -S localhost:8080 -t backend
 ```
- **تشغيل الاختبارات الآلية (Testing)**:
 ```bash
 py tests/test_prediction.py
 ```

---

## هيكلية المشروع (Project Tree)
```text
charming-planck/
 ai-engine/ # Python FastAPI AI Microservice
 api/server.py # REST AI Endpoints
 data/ # Historical data for training
 models/ # Train & Predict modules
 oop/ # OOP Classes (ModelPredictor, ModelTrainer)
 backend/ # Central PHP REST API Backend
 api/ # (auth.php, medicines.php, orders.php, suppliers.php)
 config/ # (database.php, jwt.php)
 core/ # (Database, JWT, Security, Response)
 models/ # Domain Models (User, Medicine, Order, Supplier)
 patterns/ # Creational, Structural & Behavioral Design Patterns
 services/ # Internal API Service Integrations
 database/ # schema.sql & seed.sql
 docs/ # Comprehensive Reports & Documentation (PDF & DOCX)
 frontend/ # Responsive Web Application (HTML5 / CSS3 / JS ES6)
 tests/ # Automated Unit & Integration Test Suites
 run_project.bat / .ps1 # 1-Click Launchers
```
