<?php
namespace Models;

use Core\Database;
use PDO;

class User {
 public ?int $id;
 public string $username;
 public string $email;
 public string $role;

 public function __construct(?int $id = null, string $username = '', string $email = '', string $role = 'customer') {
 $this->id = $id;
 $this->username = $username;
 $this->email = $email;
 $this->role = $role;
 }

 public static function findByEmail(string $email): ?array {
 $db = Database::getInstance()->getConnection();
 $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
 $stmt->execute([':email' => $email]);
 $user = $stmt->fetch();
 return $user ?: null;
 }

 public static function create(string $username, string $email, string $passwordHash, string $role = 'customer'): int {
 $db = Database::getInstance()->getConnection();
 $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :hash, :role)");
 $stmt->execute([
 ':username' => $username,
 ':email' => $email,
 ':hash' => $passwordHash,
 ':role' => $role
 ]);
 return (int)$db->lastInsertId();
 }

 public static function getAll(): array {
 $db = Database::getInstance()->getConnection();
 $stmt = $db->query("SELECT id, username, email, role, created_at FROM users ORDER BY id DESC");
 return $stmt->fetchAll();
 }
}
