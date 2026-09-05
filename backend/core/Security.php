<?php
namespace Core;

class Security {
 public static function sanitizeInput($data) {
 if (is_array($data)) {
 foreach ($data as $key => $value) {
 $data[$key] = self::sanitizeInput($value);
 }
 return $data;
 }
 if (is_string($data)) {
 return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
 }
 return $data;
 }

 public static function hashPassword(string $password): string {
 return password_hash($password, PASSWORD_BCRYPT);
 }

 public static function verifyPassword(string $password, string $hash): bool {
 return password_verify($password, $hash);
 }

 public static function getBearerToken(): ?string {
 $headers = null;
 if (isset($_SERVER['Authorization'])) {
 $headers = trim($_SERVER["Authorization"]);
 } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
 $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
 } else if (function_exists('apache_request_headers')) {
 $requestHeaders = apache_request_headers();
 $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
 if (isset($requestHeaders['Authorization'])) {
 $headers = trim($requestHeaders['Authorization']);
 }
 }

 if (!empty($headers)) {
 if (preg_match('/Bearer\s(\S+)/i', $headers, $matches)) {
 return $matches[1];
 }
 }
 return null;
 }
}
