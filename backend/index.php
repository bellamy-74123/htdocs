<?php
// نقطة الدخول الرئيسية والموجه للواجهة الخلفية (Backend REST API)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Accept");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
 http_response_code(200);
 exit(0);
}

require_once __DIR__ . '/core/Response.php';

use Core\Response;

// التحقق من حالة الخادم ونقاط النهاية المتاحة
Response::send(200, true, "الخادم المركزي لنظام إدارة الصيدلية الذكي يعمل بكفاءة.", [
 'version' => '1.0.0',
 'status' => 'نشط ويعمل (Healthy)',
 'available_endpoints' => [
 'المصادقة والمستخدمين' => '/api/auth.php',
 'إدارة الأدوية (CRUD)' => '/api/medicines.php',
 'إدارة فواتير المبيعات' => '/api/orders.php',
 'إدارة الشركات الموردة' => '/api/suppliers.php',
 'المساعد الدوائي الذكي' => '/api/chatbot.php'
 ]
]);
