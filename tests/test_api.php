<?php
// PHP Backend Unit & Integration Tests

require_once __DIR__ . '/../backend/core/JWT.php';
require_once __DIR__ . '/../backend/core/Security.php';
require_once __DIR__ . '/../backend/patterns/creational/Factory.php';

use Core\JWT;
use Core\Security;
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
 $med = Factory::createMedicine([
 'id' => 10,
 'name' => 'Test Drug',
 'category' => 'Test',
 'price' => 15.5,
 'stock_quantity' => 50
 ]);

 if ($med->name === 'Test Drug' && $med->price === 15.5) {
 $this->pass("Factory Pattern Medicine Creation Test");
 } else {
 $this->fail("Factory Pattern Medicine Creation Test");
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
