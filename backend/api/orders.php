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
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../patterns/structural/Facade.php';

use Core\Response;
use Models\Order;
use Patterns\Structural\Facade as OrderFacade;

$method = $_SERVER['REQUEST_METHOD'];
$userId = 1;

if ($method === 'GET') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    if ($id) {
        $order = Order::getById($id);
        if ($order) {
            Response::send(200, true, "تم استرجاع تفاصيل الفاتورة بنجاح.", $order);
        } else {
            Response::send(404, false, "الفاتورة المطلوبة غير موجودة.");
        }
    } else {
        $orders = Order::getAll();
        Response::send(200, true, "تم استرجاع قائمة فواتير المبيعات بنجاح.", $orders);
    }
} else if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (empty($input['items']) || !is_array($input['items'])) {
        Response::send(400, false, "يجب تحديد قائمة عناصر الفاتورة والكميات المطلوبة.");
    }

    try {
        $facade = new OrderFacade();
        $result = $facade->placeOrder($userId, $input['items'], $input);

        // إشعار محرك الذكاء الاصطناعي لإعادة التدريب الفوري على سلة المشتريات
        triggerAiRetraining($input['items']);

        Response::send(201, true, "تم إصدار وحفظ الفاتورة وتحديث المخزون بنجاح.", $result);
    } catch (\Throwable $e) {
        Response::send(400, false, $e->getMessage());
    }
} else {
    Response::send(405, false, "نوع الطلب (HTTP Method) غير مدعوم.");
}

function triggerAiRetraining(array $items): void {
    $url = "http://127.0.0.1:8000/api/retrain";
    $payload = json_encode([
        "transactions" => [
            ["items" => $items]
        ],
        "source" => "mysql_order_dispense"
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 500); // إرسال سريع غير مانع لسرعة الاستجابة
    @curl_exec($ch);
    @curl_close($ch);
}
