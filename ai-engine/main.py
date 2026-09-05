"""
Pharmacy AI Engine Main Entrypoint
Exposes FastAPI server for Smart Search and Demand Forecasting
"""

import uvicorn

if __name__ == "__main__":
 print("Starting Pharmacy AI Service Engine on http://127.0.0.1:8000 ...")
 uvicorn.run("api.server:app", host="127.0.0.1", port=8000, reload=True)
