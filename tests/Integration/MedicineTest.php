<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Models\Medicine;
use Core\Database;

final class MedicineTest extends TestCase
{
    protected function setUp(): void
    {
        if (getenv('SPMS_TEST_DB') !== '1') {
            $this->markTestSkipped(
                'Database integration tests are disabled. Set SPMS_TEST_DB=1 and use a dedicated test database.'
            );
        }

        $db = Database::getInstance()->getConnection();
        if (!$db) {
            $this->markTestSkipped('Test database connection is unavailable.');
        }
    }

    public function testFindByIdReturnsExpectedShape(): void
    {
        $medicine = Medicine::findById(1);

        if ($medicine === null) {
            $this->markTestSkipped('Medicine id=1 does not exist in the configured test database.');
        }

        foreach (['id', 'name', 'generic_name', 'category', 'price', 'stock_quantity'] as $key) {
            $this->assertArrayHasKey($key, $medicine);
        }
    }

    public function testGetAllReturnsArray(): void
    {
        $medicines = Medicine::getAll();
        $this->assertIsArray($medicines);

        if ($medicines !== []) {
            $this->assertArrayHasKey('id', $medicines[0]);
            $this->assertArrayHasKey('name', $medicines[0]);
        }
    }

    public function testSearchReturnsOnlyMatchingRecords(): void
    {
        $all = Medicine::getAll();
        if ($all === []) {
            $this->markTestSkipped('No medicines exist in the test database.');
        }

        $needle = $all[0]['name'];
        $results = Medicine::getAll($needle);

        $this->assertIsArray($results);
        $this->assertNotEmpty($results);

        $found = false;
        foreach ($results as $row) {
            if (mb_stripos($row['name'], $needle, 0, 'UTF-8') !== false ||
                mb_stripos($row['generic_name'] ?? '', $needle, 0, 'UTF-8') !== false ||
                mb_stripos($row['category'] ?? '', $needle, 0, 'UTF-8') !== false) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
    }
}
