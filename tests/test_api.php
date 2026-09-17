<?php
// PHP Backend Unit & Integration Tests

require_once __DIR__ . '/../backend/core/JWT.php';
require_once __DIR__ . '/../backend/core/Security.php';
require_once __DIR__ . '/../backend/core/Response.php';
require_once __DIR__ . '/../backend/patterns/creational/Factory.php';

use Core\JWT;
use Core\Security;
use Core\Response;
use Patterns\Creational\Factory;

class APITestSuite {
    private int $passed = 0;
    private int $failed = 0;

    public function run() {
        echo "=========================================\n";
        echo " Running Pharmacy API Integration Tests \n";
        echo "=========================================\n\n";

        $this->testJWTCreationAndValidation();
        $this->testInputSanitization();
        $this->testFactoryPattern();
        $this->testDynamicXmlResponseFormat();

        echo "\n-----------------------------------------\n";
        echo " Test Summary: Passed {$this->passed}, Failed {$this->failed}\n";
        echo "-----------------------------------------\n";
    }

    private function testJWTCreationAndValidation() {
        $payload = ['user_id' => 1, 'username' => 'testuser', 'role' => 'admin'];
        $token = JWT::generate($payload);
        
        $decoded = JWT::validate($token);

        if ($decoded && $decoded['username'] === 'testuser') {
            $this->pass("JWT Generation & Validation Test");
        } else {
            $this->fail("JWT Generation & Validation Test");
        }
    }

    private function testInputSanitization() {
        $dirty = "<script>alert('xss')</script>";
        $clean = Security::sanitizeInput($dirty);

        if (strpos($clean, '<script>') === false && strpos($clean, '&lt;script&gt;') !== false) {
            $this->pass("XSS Input Sanitization Test");
        } else {
            $this->fail("XSS Input Sanitization Test");
        }
    }

    private function testFactoryPattern() {
        $emergencyOrder = Factory::createOrder('emergency');
        $routineOrder = Factory::createOrder('routine');

        if ($emergencyOrder instanceof \Patterns\Creational\EmergencyOrder && 
            $emergencyOrder->getOrderType() === 'emergency_order' &&
            $routineOrder->getOrderType() === 'routine_restock') {
            $this->pass("Factory Pattern Order Creation Test");
        } else {
            $this->fail("Factory Pattern Order Creation Test");
        }
    }

    private function testDynamicXmlResponseFormat() {
        $phpBin = PHP_BINARY;
        $responseFile = addslashes(realpath(__DIR__ . '/../backend/core/Response.php'));
        $code = "require_once '{$responseFile}'; \$_GET['format'] = 'xml'; \\Core\\Response::send(200, true, 'XML Verification Success', ['drug_name' => 'Amoxil', 'stock' => 120]);";
        
        $cmd = "\"{$phpBin}\" -r \"{$code}\"";
        $output = shell_exec($cmd);

        $hasXmlHeader = ($output && strpos($output, '<?xml') !== false);
        $hasStatus = ($output && strpos($output, '<status>200</status>') !== false);
        $hasPayload = ($output && strpos($output, '<drug_name>Amoxil</drug_name>') !== false && strpos($output, '<stock>120</stock>') !== false);

        if ($hasXmlHeader && $hasStatus && $hasPayload) {
            $this->pass("Dynamic XML Response & Serialization Test (?format=xml)");
        } else {
            $this->fail("Dynamic XML Response & Serialization Test (?format=xml)");
        }
    }

    private function pass(string $name) {
        echo " [PASS] {$name}\n";
        $this->passed++;
    }

    private function fail(string $name) {
        echo " [FAIL] {$name}\n";
        $this->failed++;
    }
}

$suite = new APITestSuite();
$suite->run();
