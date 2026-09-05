<?php
namespace Patterns\Structural;

use Core\Database;
use PDO;
use Exception;

class Facade {
 private PDO $db;

 public function __construct() {
 $this->db = Database::getInstance()->getConnection();
 }

 /**
 * Process order placement safely using database transaction
 */
    public function placeOrder(int $userId, array $items, array $orderData = []): array {
        $this->db->beginTransaction();

        try {
            $totalAmount = 0.0;

            // 1. Verify stock and calculate total
            foreach ($items as $item) {
                $stmt = $this->db->prepare("SELECT price, stock_quantity FROM medicines WHERE id = :id FOR UPDATE");
                $stmt->execute([':id' => $item['medicine_id']]);
                $med = $stmt->fetch();

                if (!$med) {
                    throw new Exception("Medicine ID {$item['medicine_id']} not found.");
                }

                if ($med['stock_quantity'] < $item['quantity']) {
                    throw new Exception("Insufficient stock for medicine ID {$item['medicine_id']}.");
                }

                $itemPrice = isset($item['unit_price']) ? floatval($item['unit_price']) : floatval($med['price']);
                $totalAmount += $itemPrice * intval($item['quantity']);
            }

            if (isset($orderData['total_amount']) && floatval($orderData['total_amount']) > 0) {
                $totalAmount = floatval($orderData['total_amount']);
            }

            $orderType = !empty($orderData['order_type']) ? $orderData['order_type'] : 'patient_sale';
            $customerName = !empty($orderData['customer_name']) ? $orderData['customer_name'] : 'عميل نقدي / مريض';
            $invNum = !empty($orderData['invoice_number']) ? $orderData['invoice_number'] : ('INV-' . date('Ym') . '-' . rand(100, 999));

            // 2. Insert Order
            $orderStmt = $this->db->prepare("INSERT INTO orders (user_id, order_type, total_amount, status, payment_status) VALUES (:user_id, :order_type, :total, 'completed', 'paid')");
            $orderStmt->execute([
                ':user_id' => $userId,
                ':order_type' => $orderType,
                ':total' => $totalAmount
            ]);
            $orderId = (int)$this->db->lastInsertId();

            // 3. Insert Items and update stock
            $itemStmt = $this->db->prepare("INSERT INTO order_items (order_id, medicine_id, quantity, unit_price) VALUES (:order_id, :med_id, :qty, :price)");
            $updateStockStmt = $this->db->prepare("UPDATE medicines SET stock_quantity = stock_quantity - :qty WHERE id = :med_id");

            foreach ($items as $item) {
                $stmt = $this->db->prepare("SELECT price FROM medicines WHERE id = :id");
                $stmt->execute([':id' => $item['medicine_id']]);
                $med = $stmt->fetch();
                $price = isset($item['unit_price']) ? floatval($item['unit_price']) : floatval($med['price']);

                $itemStmt->execute([
                    ':order_id' => $orderId,
                    ':med_id' => $item['medicine_id'],
                    ':qty' => $item['quantity'],
                    ':price' => $price
                ]);

                $updateStockStmt->execute([
                    ':qty' => $item['quantity'],
                    ':med_id' => $item['medicine_id']
                ]);
            }

            $this->db->commit();
            return [
                'order_id' => $orderId,
                'id' => $orderId,
                'invoice_number' => $invNum,
                'order_type' => $orderType,
                'customer_name' => $customerName,
                'total_amount' => $totalAmount,
                'status' => 'completed',
                'created_at' => date('Y-m-d H:i:s')
            ];
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
