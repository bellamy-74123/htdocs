<?php
namespace Core;

use PDO;
use PDOException;

class Database {
 private static ?Database $instance = null;
 private ?PDO $conn = null;

    private function __construct() {
        $config = require __DIR__ . '/../config/database.php';
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
        
        try {
            $this->conn = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            // محاولة إنشاء قاعدة البيانات تلقائياً إن لم تكن موجودة
            try {
                $rootDsn = "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}";
                $rootConn = new PDO($rootDsn, $config['username'], $config['password']);
                $rootConn->exec("CREATE DATABASE IF NOT EXISTS `{$config['dbname']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                
                // إعادة الاتصال بعد الإنشاء
                $this->conn = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);

                // تشغيل ملف المخطط الأولي تلقائياً
                $schemaFile = __DIR__ . '/../../database/schema.sql';
                if (file_exists($schemaFile)) {
                    $sql = file_get_contents($schemaFile);
                    $this->conn->exec($sql);
                }
            } catch (PDOException $ex) {
                error_log("خطأ في الاتصال بقاعدة البيانات: " . $ex->getMessage());
                throw new \Exception("فشل الاتصال بقاعدة البيانات المركزية MySQL: " . $ex->getMessage());
            }
        }
    }

 public static function getInstance(): Database {
 if (self::$instance === null) {
 self::$instance = new Database();
 }
 return self::$instance;
 }

 public function getConnection(): ?PDO {
 return $this->conn;
 }

 private function __clone() {}
}
