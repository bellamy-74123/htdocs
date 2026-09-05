<?php
namespace Patterns\Creational;

interface HospitalOrderInterface {
    public function processOrder(int $userId, array $items): array;
    public function getOrderType(): string;
    public function getPriority(): string;
}

class RoutineRestockOrder implements HospitalOrderInterface {
    public function processOrder(int $userId, array $items): array {
        return [
            'type' => 'routine_restock',
            'priority' => 'عادية (Standard)',
            'status' => 'تم التوجيه لقسم التوريد الروتيني بالمستشفى',
            'user_id' => $userId,
            'items_count' => count($items)
        ];
    }
    public function getOrderType(): string { return 'routine_restock'; }
    public function getPriority(): string { return 'عادية'; }
}

class EmergencyOrder implements HospitalOrderInterface {
    public function processOrder(int $userId, array $items): array {
        return [
            'type' => 'emergency_order',
            'priority' => 'حرجة وفورية (Urgent - Emergency)',
            'status' => 'تم إرسال إشعار فوري لطلب الشحنة العاجلة من المورد',
            'user_id' => $userId,
            'items_count' => count($items)
        ];
    }
    public function getOrderType(): string { return 'emergency_order'; }
    public function getPriority(): string { return 'حرجة وعاجلة'; }
}

class DepartmentDispenseOrder implements HospitalOrderInterface {
    public function processOrder(int $userId, array $items): array {
        return [
            'type' => 'department_dispense',
            'priority' => 'صرف داخلي (Internal Department)',
            'status' => 'تم صرف الأدوية وتحديث مخزون قسم العناية / العمليات',
            'user_id' => $userId,
            'items_count' => count($items)
        ];
    }
    public function getOrderType(): string { return 'department_dispense'; }
    public function getPriority(): string { return 'صرف داخلي'; }
}

class Factory {
    public static function createOrder(string $type): HospitalOrderInterface {
        switch (strtolower($type)) {
            case 'emergency':
            case 'emergency_order':
                return new EmergencyOrder();
            case 'department':
            case 'department_dispense':
                return new DepartmentDispenseOrder();
            case 'routine':
            case 'routine_restock':
            default:
                return new RoutineRestockOrder();
        }
    }
}
