<?php
namespace Patterns\Creational;

use Core\Database;
use PDO;

class Singleton {
    private static ?Singleton $instance = null;
    private ?PDO $dbConnection = null;

    private function __construct() {
        $this->dbConnection = Database::getInstance()->getConnection();
    }

    public static function getInstance(): Singleton {
        if (self::$instance === null) {
            self::$instance = new Singleton();
        }
        return self::$instance;
    }

    public function getConnection(): ?PDO {
        return $this->dbConnection;
    }

    private function __clone() {}
}
