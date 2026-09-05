<?php
namespace Models;

use Core\Database;
use PDO;

class Supplier {
 public static function getAll(): array {
 $db = Database::getInstance()->getConnection();
 $stmt = $db->query("SELECT * FROM suppliers ORDER BY name ASC");
 return $stmt->fetchAll();
 }

 public static function create(array $data): int {
 $db = Database::getInstance()->getConnection();
 $stmt = $db->prepare("INSERT INTO suppliers (name, contact_person, phone, email, address) VALUES (:name, :contact_person, :phone, :email, :address)");
 $stmt->execute([
 ':name' => $data['name'],
 ':contact_person' => $data['contact_person'] ?? '',
 ':phone' => $data['phone'] ?? '',
 ':email' => $data['email'] ?? '',
 ':address' => $data['address'] ?? ''
 ]);
 return (int)$db->lastInsertId();
 }
}
