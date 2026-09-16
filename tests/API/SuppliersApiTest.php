<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SuppliersApiTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = dirname(__DIR__, 2) . '/backend/api/suppliers.php';
        $this->assertFileExists($this->file);
    }

    public function testSuppliersApiSupportsGetAndPost(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString("if (\$method === 'GET')", $source);
        $this->assertStringContainsString("else if (\$method === 'POST')", $source);
        $this->assertStringContainsString('Supplier::getAll', $source);
        $this->assertStringContainsString('Supplier::create', $source);
    }

    public function testSupplierNameIsRequired(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString("empty(\$input['name'])", $source);
        $this->assertStringContainsString('Response::send(400', $source);
    }

    public function testUnsupportedMethodReturns405(): void
    {
        $source = file_get_contents($this->file);
        $this->assertStringContainsString('Response::send(405', $source);
    }
}
