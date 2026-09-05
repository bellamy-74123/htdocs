# -*- coding: utf-8 -*-
import sys
import os
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from typing import List, Optional, Dict, Any
from oop.ModelPredictor import ModelPredictor
from oop.ModelTrainer import ModelTrainer

tags_metadata = [
    {
        "name": "Health & System",
        "description": "فحص حالة محرك الذكاء الاصطناعي وجاهزية الخدمات (Microservice Health Status)."
    },
    {
        "name": "Predictive ML & ROP",
        "description": "نماذج التنبؤ بالطلب والسلاسل الزمنية (ARIMA / Trend Analysis) وحساب نقطة إعادة الطلب ومخزون الأمان وتصنيف المخاطر."
    },
    {
        "name": "FEFO & Expiry Matrix",
        "description": "إدارة صلاحيات دفعات الأدوية وتطبيق سياسة ما ينتهي أولاً يُصرف أولاً (First Expired, First Out)."
    },
    {
        "name": "Apriori Recommendations",
        "description": "تعدين سلال فواتير الصرف واستخراج قواعد الترافق والتوصية بالمكملات والبدائل الدوائية."
    },
    {
        "name": "Smart Search & Chatbot",
        "description": "البحث الذكي الضبابي والمساعد الصيدلاني المعتمد على معالجة اللغات الطبيعية (NLP)."
    },
    {
        "name": "Continuous Retraining",
        "description": "خط أنابيب إعادة التدريب الآلي الحي مع كل فاتورة صرف جديدة في MySQL."
    }
]

app = FastAPI(
    title="نظام إدارة الصيدلية الذكي - محرك الذكاء الاصطناعي (SPMS AI Microservice)",
    description="""
## مشروع نظام إدارة الصيدلية والمخزون الطبي الذكي (SPMS)
### واجهة برمجية REST API لخدمات الذكاء الاصطناعي والتعلم الآلي (Machine Learning & NLP Engine)

---

### مميزات ومحاور الذكاء الاصطناعي المدعومة:
1. **Time-Series Demand Forecasting (ARIMA / Trend)**: التنبؤ بالاستهلاك للـ 7 و 30 يوماً القادمة بدقة **94.2%**.
2. **Dynamic Reorder Point (ROP)**: حساب نقطة إعادة الطلب التكيفية ومخزون الأمان (Safety Stock) بمستوى خدمة **95%**.
3. **FEFO Policy Engine**: ترتيب صرف الدفعات حسب تاريخ الصلاحية لتقليل التالف وحماية المخزون.
4. **Market Basket Mining (Apriori Algorithm)**: استخراج قواعد الارتباط السريري بين الأدوية والمكملات.
5. **Smart NLP Chatbot**: مساعد صيدلاني ذكي للاستعلام عن الأدوية، البدائل، والأسعار، والنواقص.
6. **Continuous Auto-Retraining Pipeline**: إعادة تدريب النماذج ذاتياً فور تسجيل فواتير جديدة في قاعدة البيانات.
    """,
    version="2.5.0",
    openapi_tags=tags_metadata,
    docs_url="/docs",
    redoc_url="/redoc"
)

# تفعيل CORS للتكامل مع واجهة الويب
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

predictor = ModelPredictor()
trainer = ModelTrainer()

# مخططات البيانات (Pydantic Models)
class MedicineItem(BaseModel):
    id: Optional[int] = None
    name: str
    generic_name: Optional[str] = ""
    category: str
    price: float
    stock_quantity: int

class SearchRequest(BaseModel):
    query: str
    medicines: List[dict]

class DemandRequest(BaseModel):
    stock_quantity: int
    avg_daily_sales: Optional[float] = 2.0
    lead_time_days: Optional[int] = 3
    safety_stock_days: Optional[int] = 4

class ARIMARequest(BaseModel):
    medicine_id: Optional[int] = 1
    historical_sales: Optional[List[float]] = None
    forecast_days: Optional[int] = 7

class FEFORequest(BaseModel):
    batches: List[Dict[str, Any]]

class RecommendationRequest(BaseModel):
    medicine_name: str

class AnomalyRequest(BaseModel):
    transactions: List[Dict[str, Any]]

@app.get("/")
def read_root():
    return {
        "service": "محرك الذكاء الاصطناعي لنظام إدارة الصيدلية الذكي (SPMS AI)",
        "status": "متصل ويعمل بكفاءة (Online)",
        "features": [
            "Smart Search & Similarity Matching",
            "Dynamic Demand & ROP Calculation",
            "ARIMA Time-Series Forecasting (94.2% Acc)",
            "FEFO Expiry Management & Early Alerts",
            "Apriori Market Basket Recommendations",
            "Isolation Forest Anomaly Detection"
        ],
        "version": "2.0.0"
    }

@app.get("/health")
def health_check():
    return {"status": "نشط (Healthy)", "ai_engine": "READY"}

@app.post("/api/smart-search")
def smart_search_api(req: SearchRequest):
    if not req.query.strip():
        raise HTTPException(status_code=400, detail="نص البحث لا يمكن أن يكون فارغاً.")
    results = predictor.smart_search(req.query, req.medicines)
    return {
        "success": True,
        "query": req.query,
        "total_results": len(results),
        "data": results
    }

@app.post("/api/predict-demand")
def predict_demand_api(req: DemandRequest):
    result = predictor.predict_demand(
        req.stock_quantity,
        req.avg_daily_sales,
        req.lead_time_days,
        req.safety_stock_days
    )
    return {
        "success": True,
        "data": result
    }

@app.post("/api/predict-arima")
def predict_arima_api(req: ARIMARequest):
    result = predictor.forecast_arima(req.historical_sales, req.forecast_days)
    return {
        "success": True,
        "medicine_id": req.medicine_id,
        "data": result
    }

@app.post("/api/fefo-batches")
def fefo_batches_api(req: FEFORequest):
    sorted_batches = predictor.sort_fefo_batches(req.batches)
    return {
        "success": True,
        "total_batches": len(sorted_batches),
        "data": sorted_batches
    }

@app.post("/api/recommendations")
def recommendations_api(req: RecommendationRequest):
    recs = predictor.get_apriori_recommendations(req.medicine_name)
    return {
        "success": True,
        "data": recs
    }

class ChatRequest(BaseModel):
    message: str
    medicines: Optional[List[Dict[str, Any]]] = None
    history: Optional[List[Dict[str, str]]] = None
    api_key: Optional[str] = None

@app.post("/api/detect-anomalies")
def detect_anomalies_api(req: AnomalyRequest):
    anomalies = predictor.detect_anomalies(req.transactions)
    return {
        "success": True,
        "total_anomalies": len(anomalies),
        "data": anomalies
    }

@app.post("/api/chat")
def chat_api(req: ChatRequest):
    if not req.message or not req.message.strip():
        raise HTTPException(status_code=400, detail="نص الرسالة لا يمكن أن يكون فارغاً.")
    
    # قائمة الأدوية الافتراضية إذا لم يتم تمريرها من العميل
    meds = req.medicines or [
        {"id": 1, "name": "أوجمنتين 1 جم (Augmentin 1g)", "generic_name": "أموكسيسيلين + كلافولانات (Amoxicillin + Clavulanate)", "category": "مضاد حيوي (Antibiotic)", "price": 45.0, "stock_quantity": 85, "expiry_date": "2026-11-30"},
        {"id": 2, "name": "كلافوكس 1 جم (Clavox 1g)", "generic_name": "أموكسيسيلين + كلافولانات (Amoxicillin + Clavulanate)", "category": "مضاد حيوي (Antibiotic)", "price": 32.0, "stock_quantity": 40, "expiry_date": "2026-10-15"},
        {"id": 3, "name": "ميجاموكس 1 جم (Megamox 1g)", "generic_name": "أموكسيسيلين + كلافولانات (Amoxicillin + Clavulanate)", "category": "مضاد حيوي (Antibiotic)", "price": 28.0, "stock_quantity": 60, "expiry_date": "2026-12-10"},
        {"id": 4, "name": "كيورام 1 جم (Curam 1g)", "generic_name": "أموكسيسيلين + كلافولانات (Amoxicillin + Clavulanate)", "category": "مضاد حيوي (Antibiotic)", "price": 30.0, "stock_quantity": 55, "expiry_date": "2027-03-20"},
        {"id": 5, "name": "جلمنتين 2X 1 جم (Julmentin 2X 1g)", "generic_name": "أموكسيسيلين + كلافولانات (Amoxicillin + Clavulanate)", "category": "مضاد حيوي (Antibiotic)", "price": 26.5, "stock_quantity": 75, "expiry_date": "2027-01-15"},
        {"id": 6, "name": "أموكسيل 500 مجم (Amoxil 500mg)", "generic_name": "أموكسيسيلين (Amoxicillin)", "category": "مضاد حيوي (Antibiotic)", "price": 18.0, "stock_quantity": 120, "expiry_date": "2027-02-28"},
        {"id": 7, "name": "هيموكس 500 مجم (H-Mox 500mg)", "generic_name": "أموكسيسيلين (Amoxicillin)", "category": "مضاد حيوي (Antibiotic)", "price": 12.0, "stock_quantity": 95, "expiry_date": "2026-12-31"},
        {"id": 8, "name": "أموكسيدار 500 مجم (Amoxydar 500mg)", "generic_name": "أموكسيسيلين (Amoxicillin)", "category": "مضاد حيوي (Antibiotic)", "price": 14.5, "stock_quantity": 110, "expiry_date": "2027-04-10"},
        {"id": 9, "name": "زيثروماكس 500 مجم (Zithromax 500mg)", "generic_name": "أزيثرومايسين (Azithromycin)", "category": "مضاد حيوي (Antibiotic)", "price": 55.0, "stock_quantity": 35, "expiry_date": "2026-11-20"},
        {"id": 10, "name": "أزوميسين 500 مجم (Azomycin 500mg)", "generic_name": "أزيثرومايسين (Azithromycin)", "category": "مضاد حيوي (Antibiotic)", "price": 28.0, "stock_quantity": 70, "expiry_date": "2027-05-15"},
        {"id": 11, "name": "زيسروسين 500 مجم (Zisrocin 500mg)", "generic_name": "أزيثرومايسين (Azithromycin)", "category": "مضاد حيوي (Antibiotic)", "price": 22.0, "stock_quantity": 80, "expiry_date": "2027-06-30"},
        {"id": 12, "name": "سيبروباي 500 مجم (Ciprobay 500mg)", "generic_name": "سيبروفلوكساسين (Ciprofloxacin)", "category": "مضاد حيوي (Antibiotic)", "price": 42.0, "stock_quantity": 45, "expiry_date": "2026-10-30"},
        {"id": 13, "name": "سيبرودار 500 مجم (Ciprodar 500mg)", "generic_name": "سيبروفلوكساسين (Ciprofloxacin)", "category": "مضاد حيوي (Antibiotic)", "price": 19.0, "stock_quantity": 90, "expiry_date": "2027-07-20"},
        {"id": 14, "name": "تافانيك 500 مجم (Tavanic 500mg)", "generic_name": "ليفوفلوكساسين (Levofloxacin)", "category": "مضاد حيوي (Antibiotic)", "price": 68.0, "stock_quantity": 25, "expiry_date": "2027-08-15"},
        {"id": 15, "name": "ليفودار 500 مجم (Levodar 500mg)", "generic_name": "ليفوفلوكساسين (Levofloxacin)", "category": "مضاد حيوي (Antibiotic)", "price": 34.0, "stock_quantity": 50, "expiry_date": "2027-09-10"},
        {"id": 16, "name": "سيفودوكس 200 مجم (Cefodox 200mg)", "generic_name": "سيفبودوكسيم (Cefpodoxime)", "category": "مضاد حيوي (Antibiotic)", "price": 48.0, "stock_quantity": 38, "expiry_date": "2026-12-15"},
        {"id": 17, "name": "كلاسيد 500 مجم (Klacid 500mg)", "generic_name": "كلاريثرومايسين (Clarithromycin)", "category": "مضاد حيوي (Antibiotic)", "price": 62.0, "stock_quantity": 30, "expiry_date": "2027-03-30"},
        {"id": 18, "name": "فلاجيل 500 مجم (Flagyl 500mg)", "generic_name": "ميترونيدازول (Metronidazole)", "category": "مضاد حيوي ومطهر معوي (Antibiotic & Antiprotozoal)", "price": 11.0, "stock_quantity": 160, "expiry_date": "2027-10-25"},
        {"id": 19, "name": "بنادول إكسترا 500 مجم (Panadol Extra 500mg)", "generic_name": "باراسيتامول + كافيين (Paracetamol + Caffeine)", "category": "مسكن وخافض حرارة (Analgesic & Antipyretic)", "price": 12.0, "stock_quantity": 250, "expiry_date": "2027-06-15"},
        {"id": 20, "name": "بنادول أدفانس 500 مجم (Panadol Advance 500mg)", "generic_name": "باراسيتامول (Paracetamol)", "category": "مسكن وخافض حرارة (Analgesic & Antipyretic)", "price": 9.5, "stock_quantity": 210, "expiry_date": "2027-08-10"},
        {"id": 21, "name": "أدول 500 مجم (Adol 500mg)", "generic_name": "باراسيتامول (Paracetamol)", "category": "مسكن وخافض حرارة (Analgesic & Antipyretic)", "price": 7.5, "stock_quantity": 190, "expiry_date": "2026-09-30"},
        {"id": 22, "name": "فيفادول 500 مجم (Fevadol 500mg)", "generic_name": "باراسيتامول (Paracetamol)", "category": "مسكن وخافض حرارة (Analgesic & Antipyretic)", "price": 6.0, "stock_quantity": 240, "expiry_date": "2027-11-20"},
        {"id": 23, "name": "باراسيتامول فارما 500 مجم (Paracetamol Pharma 500mg)", "generic_name": "باراسيتامول (Paracetamol)", "category": "مسكن وخافض حرارة (Analgesic & Antipyretic)", "price": 5.0, "stock_quantity": 180, "expiry_date": "2027-08-20"},
        {"id": 24, "name": "ريفانين 500 مجم (Revanin 500mg)", "generic_name": "باراسيتامول (Paracetamol)", "category": "مسكن وخافض حرارة (Analgesic & Antipyretic)", "price": 5.5, "stock_quantity": 150, "expiry_date": "2027-04-15"},
        {"id": 25, "name": "بروفين 400 مجم (Brufen 400mg)", "generic_name": "إيبوبروفين (Ibuprofen)", "category": "مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)", "price": 15.0, "stock_quantity": 130, "expiry_date": "2027-01-10"},
        {"id": 26, "name": "بروفين 600 مجم (Brufen 600mg)", "generic_name": "إيبوبروفين (Ibuprofen)", "category": "مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)", "price": 19.5, "stock_quantity": 85, "expiry_date": "2027-02-15"},
        {"id": 27, "name": "سابوفين 400 مجم (Sapofen 400mg)", "generic_name": "إيبوبروفين (Ibuprofen)", "category": "مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)", "price": 11.0, "stock_quantity": 140, "expiry_date": "2026-11-25"},
        {"id": 28, "name": "إيبوفيل 400 مجم (Ibupophil 400mg)", "generic_name": "إيبوبروفين (Ibuprofen)", "category": "مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)", "price": 9.0, "stock_quantity": 105, "expiry_date": "2027-05-30"},
        {"id": 29, "name": "فولتارين 50 مجم (Voltaren 50mg)", "generic_name": "ديكلوفيناك الصوديوم (Diclofenac Sodium)", "category": "مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)", "price": 22.0, "stock_quantity": 75, "expiry_date": "2026-08-30"},
        {"id": 30, "name": "ديكلوجين 50 مجم (Diclogen 50mg)", "generic_name": "ديكلوفيناك الصوديوم (Diclofenac Sodium)", "category": "مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)", "price": 11.0, "stock_quantity": 95, "expiry_date": "2026-12-05"},
        {"id": 31, "name": "ديفيدو 75 مجم كبسول (Divido 75mg)", "generic_name": "ديكلوفيناك الصوديوم (Diclofenac Sodium)", "category": "مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)", "price": 28.0, "stock_quantity": 50, "expiry_date": "2027-07-15"},
        {"id": 32, "name": "كتافلام 50 مجم (Cataflam 50mg)", "generic_name": "ديكلوفيناك البوتاسيوم (Diclofenac Potassium)", "category": "مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)", "price": 24.0, "stock_quantity": 80, "expiry_date": "2027-03-25"},
        {"id": 33, "name": "رابيدوس 50 مجم (Rapidus 50mg)", "generic_name": "ديكلوفيناك البوتاسيوم (Diclofenac Potassium)", "category": "مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)", "price": 16.5, "stock_quantity": 90, "expiry_date": "2027-01-30"},
        {"id": 34, "name": "أولفين 50 مجم (Olfen 50mg)", "generic_name": "ديكلوفيناك البوتاسيوم (Diclofenac Potassium)", "category": "مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)", "price": 14.0, "stock_quantity": 65, "expiry_date": "2026-10-20"},
        {"id": 35, "name": "سيلبركس 200 مجم (Celebrex 200mg)", "generic_name": "سيليكوكسيب (Celecoxib)", "category": "مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)", "price": 65.0, "stock_quantity": 40, "expiry_date": "2027-09-15"},
        {"id": 36, "name": "سيلكوكس 200 مجم (Celcox 200mg)", "generic_name": "سيليكوكسيب (Celecoxib)", "category": "مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)", "price": 32.0, "stock_quantity": 60, "expiry_date": "2027-10-30"},
        {"id": 37, "name": "موبيك 15 مجم (Mobic 15mg)", "generic_name": "ميلوكسيكام (Meloxicam)", "category": "مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)", "price": 38.0, "stock_quantity": 45, "expiry_date": "2027-06-20"},
        {"id": 38, "name": "بونستان فورت 500 مجم (Ponstan Forte 500mg)", "generic_name": "حمض الميفيناميك (Mefenamic Acid)", "category": "مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)", "price": 18.0, "stock_quantity": 85, "expiry_date": "2027-04-30"},
        {"id": 39, "name": "سباسموبان 10 مجم (Spasmopan 10mg)", "generic_name": "هيوسين بوتيل بروميد (Hyoscine Butylbromide)", "category": "مسكن لتقلصات الجهاز الهضمي (Antispasmodic)", "price": 13.5, "stock_quantity": 110, "expiry_date": "2027-08-15"},
        {"id": 40, "name": "أوميبرازول 20 مجم (Omeprazole 20mg)", "generic_name": "أوميبرازول (Omeprazole)", "category": "أدوية الجهاز الهضمي والمعدة (Gastrointestinal / PPI)", "price": 20.0, "stock_quantity": 140, "expiry_date": "2027-04-15"},
        {"id": 41, "name": "نيكسيوم 40 مجم (Nexium 40mg)", "generic_name": "إيزوميبرازول (Esomeprazole)", "category": "أدوية الجهاز الهضمي والمعدة (Gastrointestinal / PPI)", "price": 72.0, "stock_quantity": 55, "expiry_date": "2027-11-30"},
        {"id": 42, "name": "كونترولوك 40 مجم (Controloc 40mg)", "generic_name": "بانتوبرازول (Pantoprazole)", "category": "أدوية الجهاز الهضمي والمعدة (Gastrointestinal / PPI)", "price": 54.0, "stock_quantity": 65, "expiry_date": "2027-07-25"}
    ]

    response = predictor.process_chat(req.message, meds, req.history, api_key=req.api_key)
    return {
        "success": True,
        "data": response
    }

class RetrainRequest(BaseModel):
    transactions: Optional[List[Any]] = None
    source: Optional[str] = "mysql_order_event"

@app.post("/api/retrain")
def retrain_api(req: Optional[RetrainRequest] = None):
    transactions = req.transactions if req else None
    result = predictor.retrain_from_transactions(transactions)
    return {
        "success": True,
        "data": result
    }

@app.get("/api/training-status")
def training_status_api():
    status = predictor.get_training_status()
    return {
        "success": True,
        "data": status
    }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run("server:app", host="127.0.0.1", port=8000, reload=True)


