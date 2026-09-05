<?php
namespace Patterns\Behavioral;

interface PredictionStrategyInterface {
    public function predict(int $stockQuantity, float $dailyConsumptionRate, array $history = []): array;
    public function getAlgorithmName(): string;
}

/**
 * استراتيجية المتوسط المتحرك (Moving Average Demand Forecasting Strategy)
 */
class MovingAverageStrategy implements PredictionStrategyInterface {
    public function predict(int $stockQuantity, float $dailyConsumptionRate, array $history = []): array {
        if ($dailyConsumptionRate <= 0) $dailyConsumptionRate = 1.0;
        
        $daysRemaining = round($stockQuantity / $dailyConsumptionRate, 1);
        $recommendedPurchase = max(0, (int)ceil(($dailyConsumptionRate * 30) - $stockQuantity));

        return [
            'strategy' => $this->getAlgorithmName(),
            'days_remaining' => $daysRemaining,
            'recommended_purchase_quantity' => $recommendedPurchase,
            'risk_level' => $daysRemaining <= 7 ? 'حرج (Critical)' : ($daysRemaining <= 15 ? 'تحذير (Warning)' : 'آمن (Safe)')
        ];
    }

    public function getAlgorithmName(): string {
        return 'خوارزمية المتوسط المتحرك لمعدل الاستهلاك (Moving Average)';
    }
}

/**
 * استراتيجية الانحدار الخطي للاتجاه الموسمي (Linear Trend & Burn-Rate Strategy)
 */
class LinearTrendRegressionStrategy implements PredictionStrategyInterface {
    public function predict(int $stockQuantity, float $dailyConsumptionRate, array $history = []): array {
        // تطبيق معامل نمو الطلب في حالات الطوارئ (+15%)
        $adjustedRate = ($dailyConsumptionRate > 0 ? $dailyConsumptionRate : 1.0) * 1.15;
        
        $daysRemaining = round($stockQuantity / $adjustedRate, 1);
        $recommendedPurchase = max(0, (int)ceil(($adjustedRate * 30) - $stockQuantity));

        return [
            'strategy' => $this->getAlgorithmName(),
            'days_remaining' => $daysRemaining,
            'recommended_purchase_quantity' => $recommendedPurchase,
            'risk_level' => $daysRemaining <= 7 ? 'حرج جداً (High Urgency)' : 'مستقر'
        ];
    }

    public function getAlgorithmName(): string {
        return 'خوارزمية الانحدار الخطي وتتبع الاتجاه (Linear Trend Regression)';
    }
}

class PredictionContext {
    private PredictionStrategyInterface $strategy;

    public function __construct(PredictionStrategyInterface $strategy) {
        $this->strategy = $strategy;
    }

    public function setStrategy(PredictionStrategyInterface $strategy): void {
        $this->strategy = $strategy;
    }

    public function executePrediction(int $stock, float $rate, array $history = []): array {
        return $this->strategy->predict($stock, $rate, $history);
    }
}

class Strategy {
    public static function getStrategy(string $type = 'moving_average'): PredictionStrategyInterface {
        if ($type === 'trend' || $type === 'regression') {
            return new LinearTrendRegressionStrategy();
        }
        return new MovingAverageStrategy();
    }
}
