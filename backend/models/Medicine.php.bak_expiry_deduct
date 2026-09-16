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

 public static function getAll(?string $search = null): array {
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
