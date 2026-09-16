import sys
from pathlib import Path
import pytest

PROJECT = Path(__file__).resolve().parents[2]
AI_ENGINE = PROJECT / "ai-engine"
sys.path.insert(0, str(AI_ENGINE))


def test_demand_forecasting_real_engine():
    from algorithms.demand_forecasting import DemandForecastingEngine

    engine = DemandForecastingEngine()
    result = engine.forecast_demand(16, 100, custom_daily_rate=5)

    assert isinstance(result, dict)
    assert "next_7_days_forecast" in result
    assert "next_30_days_forecast" in result
    assert "reorder_point" in result
    assert "safety_stock" in result
    assert result["medicine_id"] == 16


def test_demand_record_dispensing_updates_history():
    from algorithms.demand_forecasting import DemandForecastingEngine

    engine = DemandForecastingEngine()
    before = len(engine.history_records.get(999, []))
    engine.record_dispensing(999, 4)
    after = len(engine.history_records[999])

    assert after == before + 1
    assert engine.history_records[999][-1] == 4


def test_apriori_real_engine_accepts_transactions():
    from algorithms.association_apriori import AprioriEngine

    engine = AprioriEngine(min_support=0.01, min_confidence=0.1)
    assert isinstance(engine.rules, list)
    assert isinstance(engine.item_support, dict)


def test_server_has_real_routes():
    from api.server import app

    paths = {route.path for route in app.routes}
    expected = {
        "/",
        "/health",
        "/api/smart-search",
        "/api/predict-demand",
        "/api/predict-arima",
        "/api/fefo-batches",
        "/api/recommendations",
        "/api/detect-anomalies",
        "/api/chat",
        "/api/retrain",
        "/api/training-status",
    }
    assert expected.issubset(paths)


def test_fastapi_health_endpoint():
    from fastapi.testclient import TestClient
    from api.server import app

    client = TestClient(app)
    response = client.get("/health")

    assert response.status_code == 200
    body = response.json()
    assert isinstance(body, dict)
    assert "status" in body
