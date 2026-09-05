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
require_once __DIR__ . '/../models/Supplier.php';

use Core\Response;
use Core\Security;
use Core\JWT;
use Models\Supplier;

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
 $suppliers = Supplier::getAll();
 Response::send(200, true, "تم استرجاع قائمة الشركات الموردة بنجاح.", $suppliers);
} else if ($method === 'POST') {
	$input = json_decode(file_get_contents('php://input'), true) ?? Security::sanitizeInput($_POST);
	if (empty($input['name'])) {
		Response::send(400, false, "اسم الشركة الموردة مطلوب.");
	}

	$id = Supplier::create($input);
	Response::send(201, true, "تمت إضافة الشركة الموردة بنجاح إلى النظام.", ['id' => $id]);
} else {
 Response::send(405, false, "نوع الطلب (HTTP Method) غير مدعوم.");
}
