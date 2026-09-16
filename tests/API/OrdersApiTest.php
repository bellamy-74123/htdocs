<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class OrdersApiTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = dirname(__DIR__, 2) . '/backend/api/orders.php';
        $this->assertFileExists($this->file);
    }

    public function testOrdersApiSupportsGetAndPost(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString("if (\$method === 'GET')", $source);
        $this->assertStringContainsString("else if (\$method === 'POST')", $source);
    }

    public function testOrdersApiUsesOrderModelAndFacade(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString('Order::getAll', $source);
        $this->assertStringContainsString('Order::getById', $source);
        $this->assertStringContainsString('new OrderFacade()', $source);
        $this->assertStringContainsString('placeOrder', $source);
    }

    public function testOrdersApiTriggersAiRetraining(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString('triggerAiRetraining', $source);
        $this->assertStringContainsString('http://127.0.0.1:8000/api/retrain', $source);
    }

    public function testOrdersApiHasValidationAndUnsupportedMethodResponse(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString("empty(\$input['items'])", $source);
        $this->assertStringContainsString('Response::send(400', $source);
        $this->assertStringContainsString('Response::send(201', $source);
        $this->assertStringContainsString('Response::send(405', $source);
    }
}
