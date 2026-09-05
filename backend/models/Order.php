<?php
namespace Models;

use Core\Database;
use PDO;

class Order {
    public static function getAll(): array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.order_date DESC");
        $orders = $stmt->fetchAll();

        if (!empty($orders)) {
            $itemStmt = $db->prepare("SELECT oi.*, m.name as medicine_name, m.generic_name FROM order_items oi JOIN medicines m ON oi.medicine_id = m.id WHERE oi.order_id = :order_id");
            foreach ($orders as &$ord) {
                $itemStmt->execute([':order_id' => $ord['id']]);
                $ord['items'] = $itemStmt->fetchAll();
                $ord['created_at'] = $ord['order_date'] ?? date('Y-m-d H:i:s');
                if (empty($ord['invoice_number'])) {
                    $yearMonth = date('Ym', strtotime($ord['created_at']));
                    $ord['invoice_number'] = 'INV-' . $yearMonth . '-' . str_pad($ord['id'], 3, '0', STR_PAD_LEFT);
                }
                if (empty($ord['customer_name'])) {
                    $ord['customer_name'] = ($ord['order_type'] === 'department_dispense') ? 'قسم الطوارئ / المستشفى' : (($ord['order_type'] === 'routine_restock') ? 'الشركة الوطنية للتموين الطبي' : 'عميل نقدي / مريض');
                }
                if (empty($ord['payment_method'])) {
                    $ord['payment_method'] = ($ord['order_type'] === 'department_dispense') ? 'credit' : 'cash';
                }
            }
        }

        return $orders;
    }

    public static function getById(int $id): ?array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = :id");
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch();

        if (!$order) return null;

        $itemStmt = $db->prepare("SELECT oi.*, m.name as medicine_name, m.generic_name FROM order_items oi JOIN medicines m ON oi.medicine_id = m.id WHERE oi.order_id = :order_id");
        $itemStmt->execute([':order_id' => $id]);
        $order['items'] = $itemStmt->fetchAll();
        $order['created_at'] = $order['order_date'] ?? date('Y-m-d H:i:s');
        if (empty($order['invoice_number'])) {
            $yearMonth = date('Ym', strtotime($order['created_at']));
            $order['invoice_number'] = 'INV-' . $yearMonth . '-' . str_pad($order['id'], 3, '0', STR_PAD_LEFT);
        }
        if (empty($order['customer_name'])) {
            $order['customer_name'] = ($order['order_type'] === 'department_dispense') ? 'قسم الطوارئ / المستشفى' : (($order['order_type'] === 'routine_restock') ? 'الشركة الوطنية للتموين الطبي' : 'عميل نقدي / مريض');
        }

        return $order;
    }
}
