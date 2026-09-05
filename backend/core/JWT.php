<?php
namespace Core;

class JWT {
 public static function generate(array $payload): string {
 $config = require __DIR__ . '/../config/jwt.php';
 
 $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
 $issuedAt = time();
 $expire = $issuedAt + $config['expiry'];

 $payload['iat'] = $issuedAt;
 $payload['exp'] = $expire;
 
 $base64UrlHeader = self::base64UrlEncode($header);
 $base64UrlPayload = self::base64UrlEncode(json_encode($payload));
 
 $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $config['secret'], true);
 $base64UrlSignature = self::base64UrlEncode($signature);
 
 return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
 }

 public static function validate(string $jwt): ?array {
 $config = require __DIR__ . '/../config/jwt.php';
 $tokenParts = explode('.', $jwt);

 if (count($tokenParts) !== 3) {
 return null;
 }

 $header = self::base64UrlDecode($tokenParts[0]);
 $payload = self::base64UrlDecode($tokenParts[1]);
 $signatureProvided = $tokenParts[2];

 // Verify expiration
 $payloadData = json_decode($payload, true);
 if (!$payloadData || !isset($payloadData['exp']) || $payloadData['exp'] < time()) {
 return null; // Token expired or invalid
 }

 // Verify signature
 $base64UrlHeader = self::base64UrlEncode($header);
 $base64UrlPayload = self::base64UrlEncode($payload);
 $expectedSignature = self::base64UrlEncode(
 hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $config['secret'], true)
 );

 if (hash_equals($expectedSignature, $signatureProvided)) {
 return $payloadData;
 }

 return null;
 }

 private static function base64UrlEncode(string $data): string {
 return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
 }

 private static function base64UrlDecode(string $data): string {
 return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', (4 - strlen($data) % 4) % 4));
 }
}
