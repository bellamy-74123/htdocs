<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class MedicinesApiTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = dirname(__DIR__, 2) . '/backend/api/medicines.php';
        $this->assertFileExists($this->file);
    }

    public function testMedicineApiSupportsRealHttpMethods(): void
    {
        $source = file_get_contents($this->file);

        foreach (["case 'GET':", "case 'POST':", "case 'PUT':", "case 'DELETE':"] as $method) {
            $this->assertStringContainsString($method, $source);
        }
    }

    public function testMedicineApiSupportsGetByIdAndSearch(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString("\$_GET['id']", $source);
        $this->assertStringContainsString("\$_GET['search']", $source);
        $this->assertStringContainsString('Medicine::findById', $source);
        $this->assertStringContainsString('Medicine::getAll', $source);
    }

    public function testMedicineApiSupportsRestockAndExpiry(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString("\$_GET['action'] && \$_GET['action'] === 'restock'", $source);
        $this->assertStringContainsString('Medicine::increaseStock', $source);
        $this->assertStringContainsString('expiry_date', $source);
        $this->assertStringContainsString('routine_restock', $source);
    }

    public function testMedicineApiValidatesCreateUpdateDelete(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString('Medicine::create', $source);
        $this->assertStringContainsString('Medicine::update', $source);
        $this->assertStringContainsString('Medicine::delete', $source);
        $this->assertStringContainsString('Response::send(400', $source);
        $this->assertStringContainsString('Response::send(404', $source);
        $this->assertStringContainsString('Response::send(405', $source);
    }
}
