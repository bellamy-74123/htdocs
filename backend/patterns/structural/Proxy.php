<?php
namespace Patterns\Structural;

use Core\JWT;

class Proxy {
 // Security proxy to filter and verify permissions before executing sensitive actions
 public static function checkAccess(?string $token, array $allowedRoles): bool {
 if (!$token) return false;
 $user = JWT::validate($token);
 if (!$user) return false;
 return in_array($user['role'], $allowedRoles);
 }
}
