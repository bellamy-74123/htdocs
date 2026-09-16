<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class AuthApiTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = dirname(__DIR__, 2) . '/backend/api/auth.php';
        $this->assertFileExists($this->file);
    }

    public function testAuthApiHasRealActions(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString("$action = \$_GET['action'] ?? 'login';", $source);
        $this->assertStringContainsString("if (\$action === 'register')", $source);
        $this->assertStringContainsString("else if (\$action === 'login')", $source);
        $this->assertStringContainsString("else if (\$action === 'profile')", $source);
    }

    public function testRegisterAndLoginUseSecurityAndJwt(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString('Security::hashPassword', $source);
        $this->assertStringContainsString('Security::verifyPassword', $source);
        $this->assertStringContainsString('JWT::generate', $source);
        $this->assertStringContainsString('JWT::validate', $source);
    }

    public function testAuthValidationStatusCodesExist(): void
    {
        $source = file_get_contents($this->file);

        $this->assertStringContainsString('Response::send(400', $source);
        $this->assertStringContainsString('Response::send(401', $source);
        $this->assertStringContainsString('Response::send(409', $source);
        $this->assertStringContainsString('Response::send(201', $source);
        $this->assertStringContainsString('Response::send(200', $source);
    }
}
