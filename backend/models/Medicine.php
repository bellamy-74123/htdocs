<?php
namespace Models;

use Core\Database;
use PDO;

class Medicine {
    public ?int $id;
    public string $name;
    public string $generic_name;
    public string $category;
    public float $price;
    public int $stock_quantity;
    public ?int $supplier_id;
    public ?string $expiry_date;

    public function __construct(?int $id = null, string $name = '', string $generic_name = '', string $category = '', float $price = 0.0, int $stock_quantity = 0, ?int $supplier_id = null, ?string $expiry_date = null) {
        $this->id = $id;
        $this->name = $name;
        $this->generic_name = $generic_name;
        $this->category = $category;
        $this->price = $price;
        $this->stock_quantity = $stock_quantity;
        $this->supplier_id = $supplier_id;
        $this->expiry_date = $expiry_date;
    }

    /**
     * تسوية وخصم الأدوية منتهية الصلاحية فعلياً من رصيد المخزون
     * وإصدار محضر إتلاف رسمي وتوثيقه في النظام
     */
    public static function reconcileExpiredMedicines(): array {
        try {
            $db = Database::getInstance()->getConnection();
            $today = date('Y-m-d');
            
            // استرجاع كافة الأدوية التي انتهت صلاحيتها وما زال رصيدها أكبر من صفر
            $stmt = $db->prepare("SELECT * FROM medicines WHERE expiry_date <= :today AND stock_quantity > 0");
            $stmt->execute([':today' => $today]);
            $expiredList = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $processed = [];
            foreach ($expiredList as $med) {
                $qty = (int)$med['stock_quantity'];
                $unitPrice = (float)($med['price'] ?? 20.0);
                $totalLoss = $qty * $unitPrice;
                $invNum = 'DISPOSAL-' . date('Ymd') . '-' . str_pad($med['id'], 3, '0', STR_PAD_LEFT);

                // 1. خصم وتصفير رصيد المخزون الفعلي للدواء منتهي الصلاحية في قاعدة البيانات
                $upStmt = $db->prepare("UPDATE medicines SET stock_quantity = 0 WHERE id = :id");
                $upStmt->execute([':id' => $med['id']]);

                // 2. إصدار محضر إتلاف وتسوية مخزون رسمي وتوثيقه في جدول الفواتير
                $orderStmt = $db->prepare("INSERT INTO orders (user_id, order_type, customer_name, total_amount, status, payment_status, notes) 
                                           VALUES (1, 'expired_disposal', 'لجنة إتلاف الأدوية المنتهية الصلاحية', :total, 'completed', 'written_off', :notes)");
                $orderStmt->execute([
                    ':total' => $totalLoss,
                    ':notes' => "محضر شطب وإتلاف صنف منتهي الصلاحية [{$med['name']}] - خصم كامل الرصيد ({$qty} علبة) لانتهاء الصلاحية في {$med['expiry_date']}"
                ]);
                $orderId = (int)$db->lastInsertId();

                // 3. ربط بنود محضر الإتلاف
                $itemStmt = $db->prepare("INSERT INTO order_items (order_id, medicine_id, quantity, unit_price) VALUES (:order_id, :med_id, :qty, :price)");
                $itemStmt->execute([
                    ':order_id' => $orderId,
                    ':med_id' => $med['id'],
                    ':qty' => $qty,
                    ':price' => $unitPrice
                ]);

                $processed[] = [
                    'medicine_id' => $med['id'],
                    'name' => $med['name'],
                    'deducted_quantity' => $qty,
                    'expiry_date' => $med['expiry_date'],
                    'order_id' => $orderId,
                    'invoice_number' => $invNum
                ];
            }
            return $processed;
        } catch (\Exception $e) {
            return [];
        }
    }

    public static function getAll(?string $search = null): array {
        self::reconcileExpiredMedicines();
        $db = Database::getInstance()->getConnection();
        if ($search) {
            $stmt = $db->prepare("SELECT m.*, s.name as supplier_name FROM medicines m LEFT JOIN suppliers s ON m.supplier_id = s.id WHERE m.name LIKE :s OR m.generic_name LIKE :s OR m.category LIKE :s ORDER BY m.name ASC");
            $stmt->execute([':s' => "%{$search}%"]);
        } else {
            $stmt = $db->query("SELECT m.*, s.name as supplier_name FROM medicines m LEFT JOIN suppliers s ON m.supplier_id = s.id ORDER BY m.name ASC");
        }
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        self::reconcileExpiredMedicines();
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT m.*, s.name as supplier_name FROM medicines m LEFT JOIN suppliers s ON m.supplier_id = s.id WHERE m.id = :id");
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function create(array $data): int {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO medicines (name, generic_name, category, price, stock_quantity, supplier_id, expiry_date, description) VALUES (:name, :generic_name, :category, :price, :stock_quantity, :supplier_id, :expiry_date, :description)");
        $stmt->execute([
            ':name' => $data['name'],
            ':generic_name' => $data['generic_name'] ?? '',
            ':category' => $data['category'],
            ':price' => $data['price'],
            ':stock_quantity' => $data['stock_quantity'],
            ':supplier_id' => $data['supplier_id'] ?? null,
            ':expiry_date' => $data['expiry_date'] ?? null,
            ':description' => $data['description'] ?? ''
        ]);
        return (int)$db->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE medicines SET name = :name, generic_name = :generic_name, category = :category, price = :price, stock_quantity = :stock_quantity, expiry_date = :expiry_date WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':generic_name' => $data['generic_name'] ?? '',
            ':category' => $data['category'],
            ':price' => $data['price'],
            ':stock_quantity' => $data['stock_quantity'],
            ':expiry_date' => $data['expiry_date'] ?? null
        ]);
    }

    public static function increaseStock(int $id, int $quantity): bool {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE medicines SET stock_quantity = stock_quantity + :qty WHERE id = :id");
        return $stmt->execute([':qty' => $quantity, ':id' => $id]);
    }

    public static function increaseStockByName(string $name, int $quantity): ?array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM medicines WHERE name = :name OR name LIKE :name_like LIMIT 1");
        $stmt->execute([':name' => $name, ':name_like' => "%{$name}%"]);
        $med = $stmt->fetch();
        if ($med) {
            $up = $db->prepare("UPDATE medicines SET stock_quantity = stock_quantity + :qty WHERE id = :id");
            $up->execute([':qty' => $quantity, ':id' => $med['id']]);
            $med['stock_quantity'] = (int)$med['stock_quantity'] + $quantity;
            return $med;
        }
        return null;
    }

    public static function delete(int $id): bool {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM medicines WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
