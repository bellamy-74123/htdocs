<?php
require_once __DIR__ . '/backend/core/Database.php';

try {
    $db = Core\Database::getInstance()->getConnection();
    $stmt = $db->query("SHOW CREATE TABLE orders");
    header("Content-Type: application/json");
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
