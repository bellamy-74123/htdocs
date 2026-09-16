# AI tests

هذه الاختبارات مبنية على محرك Python الموجود فعلياً في:
`ai-engine/`

تشمل:
- `DemandForecastingEngine`
- `AprioriEngine`
- `PharmacyChatbot`
- `ModelPredictor` إن أمكن تحميله

التشغيل من جذر المشروع:

```bash
pytest tests/AI/test_ai_engine.py -q
```

إذا كانت مكتبات المشروع ناقصة، ثبت `ai-engine/requirements.txt` أولاً.
