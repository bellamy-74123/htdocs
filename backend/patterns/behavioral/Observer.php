<?php
namespace Patterns\Behavioral;

interface ObserverInterface {
    public function update(string $medicineName, int $currentStock, int $minAlert): void;
}

class PharmacistStockAlertObserver implements ObserverInterface {
    public function update(string $medicineName, int $currentStock, int $minAlert): void {
        error_log("[تنبيه النقص - صيدلية المستشفى] الدواء: {$medicineName} وصل إلى كمية حرجة ({$currentStock} علبة). حد التنبيه: {$minAlert}.");
    }
}

class DepartmentSupervisorAlertObserver implements ObserverInterface {
    public function update(string $medicineName, int $currentStock, int $minAlert): void {
        error_log("[إشعار المشرف - المستشفى] تم إرسال إشعار للمشرف لجدولة طلب شراء عاجل للدواء: {$medicineName}.");
    }
}

class StockSubject {
    private array $observers = [];

    public function attach(ObserverInterface $observer): void {
        $this->observers[] = $observer;
    }

    public function notify(string $medicineName, int $currentStock, int $minAlert): void {
        foreach ($this->observers as $observer) {
            $observer->update($medicineName, $currentStock, $minAlert);
        }
    }

    public function checkStockThreshold(string $medicineName, int $currentStock, int $minAlert): bool {
        if ($currentStock <= $minAlert) {
            $this->notify($medicineName, $currentStock, $minAlert);
            return true;
        }
        return false;
    }
}

class Observer {
    public static function createStockNotifier(): StockSubject {
        $subject = new StockSubject();
        $subject->attach(new PharmacistStockAlertObserver());
        $subject->attach(new DepartmentSupervisorAlertObserver());
        return $subject;
    }
}
