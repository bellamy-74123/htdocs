<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Core\Security;

final class SecurityTest extends TestCase
{
    public function testHashAndVerifyPassword(): void
    {
        $hash = Security::hashPassword('StrongPassword123!');

        $this->assertNotSame('StrongPassword123!', $hash);
        $this->assertTrue(Security::verifyPassword('StrongPassword123!', $hash));
        $this->assertFalse(Security::verifyPassword('WrongPassword!', $hash));
    }

    public function testHashUsesBcrypt(): void
    {
        $hash = Security::hashPassword('abc123');
        $this->assertSame('2y', substr($hash, 0, 2));
    }

    public function testSanitizeString(): void
    {
        $value = Security::sanitizeInput('  <script>alert("x")</script>  ');
        $this->assertSame('&lt;script&gt;alert(&quot;x&quot;)&lt;/script&gt;', $value);
    }

    public function testSanitizeArrayRecursively(): void
    {
        $value = Security::sanitizeInput([
            'name' => '  <b>Ali</b> ',
            'nested' => ['x' => '<script>x</script>']
        ]);

        $this->assertSame('&lt;b&gt;Ali&lt;/b&gt;', $value['name']);
        $this->assertSame('&lt;script&gt;x&lt;/script&gt;', $value['nested']['x']);
    }

    public function testBearerTokenFromAuthorizationServerVariable(): void
    {
        $old = $_SERVER['Authorization'] ?? null;
        $_SERVER['Authorization'] = 'Bearer test-token-123';

        try {
            $this->assertSame('test-token-123', Security::getBearerToken());
        } finally {
            if ($old === null) {
                unset($_SERVER['Authorization']);
            } else {
                $_SERVER['Authorization'] = $old;
            }
        }
    }

    public function testMissingBearerTokenReturnsNull(): void
    {
        $old = $_SERVER['Authorization'] ?? null;
        unset($_SERVER['Authorization']);

        try {
            $this->assertNull(Security::getBearerToken());
        } finally {
            if ($old !== null) {
                $_SERVER['Authorization'] = $old;
            }
        }
    }
}
