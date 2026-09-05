<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Accept");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
 exit(0);
}

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/JWT.php';
require_once __DIR__ . '/../core/Security.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../models/User.php';

use Core\Response;
use Core\Security;
use Core\JWT;
use Models\User;

$action = $_GET['action'] ?? 'login';
$input = json_decode(file_get_contents('php://input'), true) ?? Security::sanitizeInput($_POST);

if ($action === 'register') {
 $username = trim($input['username'] ?? '');
 $email = trim($input['email'] ?? '');
 $password = $input['password'] ?? '';
 $role = $input['role'] ?? 'customer';

 if (empty($username) || empty($email) || empty($password)) {
 Response::send(400, false, "جميع الحقول مطلوبة (اسم المستخدم، البريد الإلكتروني، وكلمة المرور).");
 }

 if (User::findByEmail($email)) {
 Response::send(409, false, "البريد الإلكتروني المدخل مسجل مسبقاً في النظام.");
 }

 $hash = Security::hashPassword($password);
 $userId = User::create($username, $email, $hash, $role);

 $token = JWT::generate([
 'user_id' => $userId,
 'username' => $username,
 'email' => $email,
 'role' => $role
 ]);

 Response::send(201, true, "تم إنشاء الحساب بنجاح وتوليد مفتاح المصادقة.", [
 'token' => $token,
 'user' => [
 'id' => $userId,
 'username' => $username,
 'email' => $email,
 'role' => $role
 ]
 ]);
} else if ($action === 'login') {
 $email = trim($input['email'] ?? '');
 $password = $input['password'] ?? '';

 if (empty($email) || empty($password)) {
 Response::send(400, false, "البريد الإلكتروني وكلمة المرور مطلوبان لتسجيل الدخول.");
 }

 $user = User::findByEmail($email);
 if (!$user || !Security::verifyPassword($password, $user['password_hash'])) {
 Response::send(401, false, "البريد الإلكتروني أو كلمة المرور غير صحيحة.");
 }

 $token = JWT::generate([
 'user_id' => $user['id'],
 'username' => $user['username'],
 'email' => $user['email'],
 'role' => $user['role']
 ]);

 Response::send(200, true, "تم تسجيل الدخول بنجاح.", [
 'token' => $token,
 'user' => [
 'id' => $user['id'],
 'username' => $user['username'],
 'email' => $user['email'],
 'role' => $user['role']
 ]
 ]);
} else if ($action === 'profile') {
 $token = Security::getBearerToken();
 if (!$token) {
 Response::send(401, false, "غير مصرح. يجب إرفاق رمز المصادقة (Bearer Token).");
 }

 $userData = JWT::validate($token);
 if (!$userData) {
 Response::send(401, false, "رمز المصادقة غير صالح أو منتهي الصلاحية.");
 }

 Response::send(200, true, "تم جلب بيانات الملف الشخصي بنجاح.", $userData);
} else {
 Response::send(400, false, "إجراء المصادقة المطلوب غير معروف.");
}
