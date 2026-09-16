<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Core\JWT;

final class JWTTest extends TestCase
{
    public function testGenerateAndValidateToken(): void
    {
        $payload = [
            'user_id' => 10,
            'username' => 'tester',
            'role' => 'manager'
        ];

        $token = JWT::generate($payload);
        $decoded = JWT::validate($token);

        $this->assertIsString($token);
        $this->assertSame(3, count(explode('.', $token)));
        $this->assertIsArray($decoded);
        $this->assertSame(10, $decoded['user_id']);
        $this->assertSame('manager', $decoded['role']);
        $this->assertArrayHasKey('iat', $decoded);
        $this->assertArrayHasKey('exp', $decoded);
        $this->assertGreaterThan($decoded['iat'], $decoded['exp'] - 86400 - 1);
    }

    public function testTamperedTokenIsRejected(): void
    {
        $token = JWT::generate(['user_id' => 1, 'role' => 'customer']);
        $parts = explode('.', $token);
        $parts[1] = rtrim(strtr(base64_encode(json_encode([
            'user_id' => 1,
            'role' => 'manager',
            'iat' => time(),
            'exp' => time() + 86400
        ])), '+/', '-_'), '=');

        $tampered = implode('.', $parts);
        $this->assertNull(JWT::validate($tampered));
    }

    public function testMalformedTokenIsRejected(): void
    {
        $this->assertNull(JWT::validate('not.a.valid.jwt.token'));
        $this->assertNull(JWT::validate(''));
    }

    public function testExpiredTokenIsRejected(): void
    {
        $header = rtrim(strtr(base64_encode(json_encode(['typ' => 'JWT', 'alg' => 'HS256'])), '+/', '-_'), '=');
        $payload = rtrim(strtr(base64_encode(json_encode([
            'user_id' => 1,
            'role' => 'customer',
            'iat' => time() - 100,
            'exp' => time() - 1
        ])), '+/', '-_'), '=');

        $config = require dirname(__DIR__, 2) . '/backend/config/jwt.php';
        $signature = hash_hmac('sha256', "$header.$payload", $config['secret'], true);
        $signature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        $this->assertNull(JWT::validate("$header.$payload.$signature"));
    }
}
