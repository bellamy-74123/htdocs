<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Accept");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/JWT.php';
require_once __DIR__ . '/../core/Security.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../models/Medicine.php';

use Core\Response;
use Core\Security;
use Core\JWT;
use Models\Medicine;

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$search = $_GET['search'] ?? null;

switch ($method) {
    case 'GET':
        if ($id) {
            $med = Medicine::findById($id);
            if ($med) {
                Response::send(200, true, "تم استرجاع بيانات الدواء بنجاح.", $med);
            } else {
                Response::send(404, false, "الدواء المطلوب غير موجود في قاعدة البيانات.");
            }
        } else {
            $list = Medicine::getAll($search);
            Response::send(200, true, "تم استرجاع قائمة الأدوية بنجاح.", $list);
        }
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true) ?? Security::sanitizeInput($_POST);

        // مسار التوريد وزيادة المخزون الفعلي وإصدار فاتورة التوريد
        if (isset($_GET['action']) && $_GET['action'] === 'restock') {
            $medId = isset($input['medicine_id']) ? intval($input['medicine_id']) : null;
            $medName = $input['medicine_name'] ?? '';
            $qty = isset($input['quantity']) ? intval($input['quantity']) : 0;
            $unitPrice = isset($input['unit_price']) ? floatval($input['unit_price']) : 20.0;
            $supplierName = $input['supplier_name'] ?? 'الشركة الوطنية للتموين الطبي';

            if ($qty <= 0) {
                Response::send(400, false, "يجب تحديد كمية توريد أكبر من صفر.");
            }

            $med = null;
            if ($medId) {
                $med = Medicine::findById($medId);
                if ($med) {
                    Medicine::increaseStock($medId, $qty);
                    $med['stock_quantity'] = (int)$med['stock_quantity'] + $qty;
                    if (!empty($input['expiry_date'])) {
                        $dbExp = \Core\Database::getInstance()->getConnection();
                        $upExp = $dbExp->prepare("UPDATE medicines SET expiry_date = :exp WHERE id = :id");
                        $upExp->execute([':exp' => $input['expiry_date'], ':id' => $medId]);
                        $med['expiry_date'] = $input['expiry_date'];
                    }
                }
            } else if (!empty($medName)) {
                $med = Medicine::increaseStockByName($medName, $qty);
                if ($med && !empty($input['expiry_date'])) {
                    $dbExp = \Core\Database::getInstance()->getConnection();
                    $upExp = $dbExp->prepare("UPDATE medicines SET expiry_date = :exp WHERE id = :id");
                    $upExp->execute([':exp' => $input['expiry_date'], ':id' => $med['id']]);
                    $med['expiry_date'] = $input['expiry_date'];
                }
            }

            if (!$med) {
                Response::send(404, false, "لم يتم العثور على الصنف المطلوب لتحديث رصيد مخزونه.");
            }

            // إنشاء فاتورة توريد رسمية وربطها بالمورد والأصناف
            $totalAmount = $qty * $unitPrice;
            $db = \Core\Database::getInstance()->getConnection();
            $invNum = !empty($input['invoice_number']) ? $input['invoice_number'] : ('RESTOCK-' . date('Ym') . '-' . rand(100, 999));
            $orderStmt = $db->prepare("INSERT INTO orders (user_id, order_type, customer_name, total_amount, status, payment_status, notes) VALUES (1, 'routine_restock', :supplier, :total, 'completed', 'paid', :notes)");
            $orderStmt->execute([
                ':supplier' => $supplierName,
                ':total' => $totalAmount,
                ':notes' => "توريد وتحديث رصيد الصنف [{$med['name']}] بإضافة {$qty} علبة"
            ]);
            $orderId = (int)$db->lastInsertId();

            $itemStmt = $db->prepare("INSERT INTO order_items (order_id, medicine_id, quantity, unit_price) VALUES (:order_id, :med_id, :qty, :price)");
            $itemStmt->execute([
                ':order_id' => $orderId,
                ':med_id' => $med['id'],
                ':qty' => $qty,
                ':price' => $unitPrice
            ]);

            $createdInvoice = [
                'id' => $orderId,
                'invoice_number' => $invNum,
                'order_type' => 'routine_restock',
                'customer_name' => $supplierName,
                'total_amount' => $totalAmount,
                'created_at' => date('Y-m-d H:i:s'),
                'items' => [
                    [
                        'medicine_id' => $med['id'],
                        'name' => $med['name'],
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalAmount
                    ]
                ]
            ];

            Response::send(200, true, "تم توريد الصنف وزيادة رصيد المخزون الفعلي بمقدار {$qty} علبة وإصدار فاتورة التوريد بنجاح.", [
                'medicine' => $med,
                'invoice' => $createdInvoice
            ]);
            break;
        }

        if (empty($input['name']) || empty($input['price']) || !isset($input['stock_quantity'])) {
            Response::send(400, false, "حقول الاسم والسعر وكمية المخزون مطلوبة لإضافة الدواء.");
        }

        $newId = Medicine::create($input);

        // إنشاء فاتورة توريد أولي إن كان الرصيد الأولي أكبر من صفر
        $initialStock = intval($input['stock_quantity'] ?? 0);
        if ($initialStock > 0) {
            $unitPrice = floatval($input['price'] ?? 20.0);
            $totalAmount = $initialStock * $unitPrice;
            $db = \Core\Database::getInstance()->getConnection();
            $invNum = 'RESTOCK-' . date('Ym') . '-' . rand(100, 999);
            $supplierName = $input['supplier_name'] ?? 'الشركة الوطنية للتموين الطبي';
            $orderStmt = $db->prepare("INSERT INTO orders (user_id, order_type, customer_name, total_amount, status, payment_status, notes) VALUES (1, 'routine_restock', :supplier, :total, 'completed', 'paid', :notes)");
            $orderStmt->execute([
                ':supplier' => $supplierName,
                ':total' => $totalAmount,
                ':notes' => "إدراج وتوريد أولي للصنف [{$input['name']}] برصيد {$initialStock} علبة"
            ]);
            $orderId = (int)$db->lastInsertId();

            $itemStmt = $db->prepare("INSERT INTO order_items (order_id, medicine_id, quantity, unit_price) VALUES (:order_id, :med_id, :qty, :price)");
            $itemStmt->execute([
                ':order_id' => $orderId,
                ':med_id' => $newId,
                ':qty' => $initialStock,
                ':price' => $unitPrice
            ]);
        }

        Response::send(201, true, "تمت إضافة الدواء إلى المخزون وإصدار فاتورة التوريد بنجاح.", ['id' => $newId]);
        break;

    case 'PUT':
        if (!$id) {
            Response::send(400, false, "يجب تحديد رقم الدواء المراد تعديل بياناته.");
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            Response::send(400, false, "تنسيق البيانات المدخل غير صالح (JSON غير صحيح).");
        }

        $updated = Medicine::update($id, $input);
        if ($updated) {
            Response::send(200, true, "تم تعديل بيانات الدواء بنجاح.");
        } else {
            Response::send(500, false, "فشل تعديل بيانات الدواء في قاعدة البيانات.");
        }
        break;

    case 'DELETE':
        if (!$id) {
            Response::send(400, false, "يجب تحديد رقم الدواء المراد حذفه.");
        }

        $deleted = Medicine::delete($id);
        if ($deleted) {
            Response::send(200, true, "تم حذف الدواء من المخزون بنجاح.");
        } else {
            Response::send(500, false, "فشل حذف الدواء من قاعدة البيانات.");
        }
        break;

    default:
        Response::send(405, false, "نوع الطلب (HTTP Method) غير مدعوم.");
        break;
}
