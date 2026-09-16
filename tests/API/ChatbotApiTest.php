<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ChatbotApiTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = dirname(__DIR__, 2) . '/backend/api/chatbot.php';
        $this->assertFileExists($this->file);
    }

    public function testChatbotSupportsGetAndPost(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString("if (\$method === 'GET')", $source);
        $this->assertStringContainsString("elseif (\$method === 'POST')", $source);
    }

    public function testChatbotRequiresNonEmptyMessage(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString("\$message = trim(\$input['message'] ?? '')", $source);
        $this->assertStringContainsString('empty($message)', $source);
        $this->assertStringContainsString('Response::send(400', $source);
    }

    public function testChatbotUsesPythonFastApiAndLocalFallback(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString('http://127.0.0.1:8000/api/chat', $source);
        $this->assertStringContainsString('localFallbackChat', $source);
        $this->assertStringContainsString('Medicine::getAll', $source);
    }

    public function testChatbotContainsRealIntents(): void
    {
        $source = file_get_contents($this->file);

        foreach ([
            'GREETING',
            'SYMPTOM_DIAGNOSIS_RECOMMENDATION',
            'PRICE_COMPARISON',
            'UNKNOWN'
        ] as $intent) {
            $this->assertStringContainsString($intent, $source);
        }
    }

    public function testChatbotHasUnsupportedMethodResponse(): void
    {
        $source = file_get_contents($this->file);
        $this->assertStringContainsString('Response::send(405', $source);
    }
}
