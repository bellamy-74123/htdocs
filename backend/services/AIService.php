<?php
namespace Services;

class AIService {
    private string $aiServiceUrl;

    public function __construct(string $aiServiceUrl = "http://127.0.0.1:8000/api") {
        $this->aiServiceUrl = rtrim($aiServiceUrl, '/');
    }

    public function smartSearch(string $query, array $medicines): ?array {
        $url = "{$this->aiServiceUrl}/smart-search";
        $payload = json_encode(['query' => $query, 'medicines' => $medicines]);
        return $this->postRequest($url, $payload);
    }

    public function predictDemand(int $stockQuantity, float $avgDailySales = 2.0, int $leadTime = 3, int $safetyStock = 4): ?array {
        $url = "{$this->aiServiceUrl}/predict-demand";
        $payload = json_encode([
            'stock_quantity' => $stockQuantity,
            'avg_daily_sales' => $avgDailySales,
            'lead_time_days' => $leadTime,
            'safety_stock_days' => $safetyStock
        ]);
        return $this->postRequest($url, $payload);
    }

    public function predictARIMA(int $medicineId, ?array $historicalSales = null, int $forecastDays = 7): ?array {
        $url = "{$this->aiServiceUrl}/predict-arima";
        $payload = json_encode([
            'medicine_id' => $medicineId,
            'historical_sales' => $historicalSales,
            'forecast_days' => $forecastDays
        ]);
        return $this->postRequest($url, $payload);
    }

    public function getFEFOBatches(array $batches): ?array {
        $url = "{$this->aiServiceUrl}/fefo-batches";
        $payload = json_encode(['batches' => $batches]);
        return $this->postRequest($url, $payload);
    }

    public function getRecommendations(string $medicineName): ?array {
        $url = "{$this->aiServiceUrl}/recommendations";
        $payload = json_encode(['medicine_name' => $medicineName]);
        return $this->postRequest($url, $payload);
    }

    public function detectAnomalies(array $transactions): ?array {
        $url = "{$this->aiServiceUrl}/detect-anomalies";
        $payload = json_encode(['transactions' => $transactions]);
        return $this->postRequest($url, $payload);
    }

    private function postRequest(string $url, string $payload): ?array {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $result) {
            return json_decode($result, true);
        }

        return null;
    }
}
