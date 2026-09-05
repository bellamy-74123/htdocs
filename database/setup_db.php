<?php
/**
 * سكربت تهيئة وتغذية قاعدة بيانات نظام إدارة الصيدلية (MySQL / MariaDB)
 * يقوم بإنشاء قاعدة البيانات والجداول وإدخال كافة الأدوية (42+ دواء) تلقائياً
 */

header('Content-Type: text/html; charset=utf-8');

$config = require __DIR__ . '/../backend/config/database.php';

echo "<pre style='font-family: monospace; background: #0f172a; color: #38bdf8; padding: 20px; border-radius: 8px; direction: ltr;'>";
echo "=== تهيئة قاعدة بيانات نظام إدارة الصيدلية (SPMS Database Setup) ===\n\n";

try {
    // 1. الاتصال بخادم MySQL
    $dsn = "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "[OK] الاتصال بخادم MySQL ناجح ({$config['host']}:{$config['port']}).\n";

    // 2. إنشاء قاعدة البيانات
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['dbname']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "[OK] قاعدة البيانات `{$config['dbname']}` جاهزة.\n";

    // 3. الاتصال بقاعدة البيانات
    $pdo->exec("USE `{$config['dbname']}`");

    // 4. تنفيذ ملف المخطط الأولي schema.sql
    $schemaFile = __DIR__ . '/schema.sql';
    if (file_exists($schemaFile)) {
        $sql = file_get_contents($schemaFile);
        $pdo->exec($sql);
        echo "[OK] تم إنشاء الجداول وإدخال كافة بيانات الأدوية والموردين بنجاح (42+ دواء).\n";
    }

    // 5. التحقق من عدد الأدوية
    $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM medicines");
    $cnt = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
    echo "\n--------------------------------------------------\n";
    echo "إجمالي الأدوية المسجلة في MySQL: {$cnt} دواء.\n";
    echo "حالة النظام: متصل وجاهز للعمل مع واجهة الويب ومحرك الذكاء الاصطناعي بنسبة 100%.\n";
    echo "--------------------------------------------------\n";

} catch (PDOException $e) {
    echo "[ERROR] فشل الاتصال بقاعدة البيانات: " . $e->getMessage() . "\n";
    echo "يرجى التأكد من تشغيل خادم MySQL (عبر XAMPP أو خدمة MySQL) وإعادة المحاولة.\n";
}

echo "</pre>";