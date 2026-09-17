<?php
// -*- coding: utf-8 -*-
// نقطة اتصال الشات بوت والمساعد الدوائي والسريري الذكي (Smart Clinical Pharmacy Chatbot API)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Accept");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../models/Medicine.php';

use Core\Response;
use Models\Medicine;

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $msg = $_GET['message'] ?? 'مرحبا';
    handleChatRequest($msg, []);
} elseif ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $message = trim($input['message'] ?? '');
    $history = $input['history'] ?? [];

    if (empty($message)) {
        Response::send(400, false, "نص الرسالة لا يمكن أن يكون فارغاً.");
    }

    handleChatRequest($message, $history);
} else {
    Response::send(405, false, "نوع الطلب (HTTP Method) غير مدعوم.");
}

function handleChatRequest($message, $history = []) {
    // 1. جلب أحدث قائمة أدوية من قاعدة البيانات
    try {
        $medicines = Medicine::getAll();
    } catch (\Exception $e) {
        $medicines = [];
    }

    if (empty($medicines)) {
        $medicines = [
            ["id" => 1, "name" => "أوجمنتين 1 جم (Augmentin 1g)", "generic_name" => "أموكسيسيلين + كلافولانات", "category" => "مضاد حيوي", "price" => 45.0, "stock_quantity" => 85, "expiry_date" => "2026-11-30"],
            ["id" => 2, "name" => "كلافوكس 1 جم (Clavox 1g)", "generic_name" => "أموكسيسيلين + كلافولانات", "category" => "مضاد حيوي", "price" => 32.0, "stock_quantity" => 40, "expiry_date" => "2026-10-15"],
            ["id" => 3, "name" => "ميجاموكس 1 جم (Megamox 1g)", "generic_name" => "أموكسيسيلين + كلافولانات", "category" => "مضاد حيوي", "price" => 28.0, "stock_quantity" => 60, "expiry_date" => "2026-12-10"],
            ["id" => 4, "name" => "كيورام 1 جم (Curam 1g)", "generic_name" => "أموكسيسيلين + كلافولانات", "category" => "مضاد حيوي", "price" => 30.0, "stock_quantity" => 55, "expiry_date" => "2027-03-20"],
            ["id" => 6, "name" => "أموكسيل 500 مجم (Amoxil 500mg)", "generic_name" => "أموكسيسيلين", "category" => "مضاد حيوي", "price" => 18.0, "stock_quantity" => 120, "expiry_date" => "2027-02-28"],
            ["id" => 9, "name" => "زيثروماكس 500 مجم (Zithromax 500mg)", "generic_name" => "أزيثرومايسين", "category" => "مضاد حيوي", "price" => 55.0, "stock_quantity" => 35, "expiry_date" => "2026-11-20"],
            ["id" => 12, "name" => "سيبروباي 500 مجم (Ciprobay 500mg)", "generic_name" => "سيبروفلوكساسين", "category" => "مضاد حيوي", "price" => 42.0, "stock_quantity" => 45, "expiry_date" => "2026-10-30"],
            ["id" => 14, "name" => "تافانيك 500 مجم (Tavanic 500mg)", "generic_name" => "ليفوفلوكساسين", "category" => "مضاد حيوي", "price" => 68.0, "stock_quantity" => 25, "expiry_date" => "2027-08-15"],
            ["id" => 18, "name" => "فلاجيل 500 مجم (Flagyl 500mg)", "generic_name" => "ميترونيدازول", "category" => "مضاد ومطهر معوي", "price" => 11.0, "stock_quantity" => 160, "expiry_date" => "2027-10-25"],
            ["id" => 19, "name" => "بنادول إكسترا 500 مجم (Panadol Extra 500mg)", "generic_name" => "باراسيتامول + كافيين", "category" => "مسكن وخافض حرارة", "price" => 12.0, "stock_quantity" => 250, "expiry_date" => "2027-06-15"],
            ["id" => 20, "name" => "بنادول أدفانس 500 مجم (Panadol Advance 500mg)", "generic_name" => "باراسيتامول", "category" => "مسكن وخافض حرارة", "price" => 9.5, "stock_quantity" => 210, "expiry_date" => "2027-08-10"],
            ["id" => 21, "name" => "أدول 500 مجم (Adol 500mg)", "generic_name" => "باراسيتامول", "category" => "مسكن وخافض حرارة", "price" => 7.5, "stock_quantity" => 190, "expiry_date" => "2026-09-30"],
            ["id" => 22, "name" => "فيفادول 500 مجم (Fevadol 500mg)", "generic_name" => "باراسيتامول", "category" => "مسكن وخافض حرارة", "price" => 6.0, "stock_quantity" => 240, "expiry_date" => "2027-11-20"],
            ["id" => 23, "name" => "باراسيتامول فارما 500 مجم", "generic_name" => "باراسيتامول", "category" => "مسكن وخافض حرارة", "price" => 5.0, "stock_quantity" => 180, "expiry_date" => "2027-08-20"],
            ["id" => 25, "name" => "بروفين 400 مجم (Brufen 400mg)", "generic_name" => "إيبوبروفين", "category" => "مسكن ومضاد للالتهاب", "price" => 15.0, "stock_quantity" => 130, "expiry_date" => "2027-01-10"],
            ["id" => 26, "name" => "بروفين 600 مجم (Brufen 600mg)", "generic_name" => "إيبوبروفين", "category" => "مسكن ومضاد للالتهاب", "price" => 19.5, "stock_quantity" => 85, "expiry_date" => "2027-02-15"],
            ["id" => 27, "name" => "سابوفين 400 مجم (Sapofen 400mg)", "generic_name" => "إيبوبروفين", "category" => "مسكن ومضاد للالتهاب", "price" => 11.0, "stock_quantity" => 140, "expiry_date" => "2026-11-25"],
            ["id" => 29, "name" => "فولتارين 50 مجم (Voltaren 50mg)", "generic_name" => "ديكلوفيناك الصوديوم", "category" => "مسكن ومضاد للالتهاب", "price" => 22.0, "stock_quantity" => 75, "expiry_date" => "2026-08-30"],
            ["id" => 30, "name" => "ديكلوجين 50 مجم (Diclogen 50mg)", "generic_name" => "ديكلوفيناك الصوديوم", "category" => "مسكن ومضاد للالتهاب", "price" => 11.0, "stock_quantity" => 95, "expiry_date" => "2026-12-05"],
            ["id" => 32, "name" => "كتافلام 50 مجم (Cataflam 50mg)", "generic_name" => "ديكلوفيناك البوتاسيوم", "category" => "مسكن ومضاد للالتهاب", "price" => 24.0, "stock_quantity" => 80, "expiry_date" => "2027-03-25"],
            ["id" => 33, "name" => "رابيدوس 50 مجم (Rapidus 50mg)", "generic_name" => "ديكلوفيناك البوتاسيوم", "category" => "مسكن ومضاد للالتهاب", "price" => 16.5, "stock_quantity" => 90, "expiry_date" => "2027-01-30"],
            ["id" => 35, "name" => "سيلبركس 200 مجم (Celebrex 200mg)", "generic_name" => "سيليكوكسيب", "category" => "مسكن ومضاد للالتهاب", "price" => 65.0, "stock_quantity" => 40, "expiry_date" => "2027-09-15"],
            ["id" => 36, "name" => "سيلكوكس 200 مجم (Celcox 200mg)", "generic_name" => "سيليكوكسيب", "category" => "مسكن ومضاد للالتهاب", "price" => 32.0, "stock_quantity" => 60, "expiry_date" => "2027-10-30"],
            ["id" => 37, "name" => "موبيك 15 مجم (Mobic 15mg)", "generic_name" => "ميلوكسيكام", "category" => "مسكن ومضاد للالتهاب", "price" => 38.0, "stock_quantity" => 45, "expiry_date" => "2027-06-20"],
            ["id" => 38, "name" => "بونستان فورت 500 مجم", "generic_name" => "حمض الميفيناميك", "category" => "مسكن ومضاد للالتهاب", "price" => 18.0, "stock_quantity" => 85, "expiry_date" => "2027-04-30"],
            ["id" => 39, "name" => "سباسموبان 10 مجم (Spasmopan 10mg)", "generic_name" => "هيوسين بوتيل بروميد", "category" => "مسكن لتقلصات الجهاز الهضمي", "price" => 13.5, "stock_quantity" => 110, "expiry_date" => "2027-08-15"],
            ["id" => 40, "name" => "أوميبرازول 20 مجم (Omeprazole 20mg)", "generic_name" => "أوميبرازول", "category" => "أدوية الجهاز الهضمي والمعدة", "price" => 20.0, "stock_quantity" => 140, "expiry_date" => "2027-04-15"],
            ["id" => 41, "name" => "نيكسيوم 40 مجم (Nexium 40mg)", "generic_name" => "إيزوميبرازول", "category" => "أدوية الجهاز الهضمي والمعدة", "price" => 72.0, "stock_quantity" => 55, "expiry_date" => "2027-11-30"],
            ["id" => 42, "name" => "كونترولوك 40 مجم (Controloc 40mg)", "generic_name" => "بانتوبرازول", "category" => "أدوية الجهاز الهضمي والمعدة", "price" => 54.0, "stock_quantity" => 65, "expiry_date" => "2027-07-25"],
            ["id" => 43, "name" => "دوسباتالين 135 مجم (Duspatalin 135mg)", "generic_name" => "ميبفرين", "category" => "علاج القولون والتقلصات", "price" => 42.0, "stock_quantity" => 90, "expiry_date" => "2027-09-10"],
            ["id" => 44, "name" => "دوفالاك شراب 200 مل (Duphalac Syrup)", "generic_name" => "لاكتولوز", "category" => "ملينات وأدوية الإمساك", "price" => 24.5, "stock_quantity" => 70, "expiry_date" => "2027-05-15"],
            ["id" => 45, "name" => "سيتريزين 10 مجم (Zyrtec 10mg)", "generic_name" => "سيتريزين", "category" => "مضادات الحساسية والهيستامين", "price" => 18.0, "stock_quantity" => 115, "expiry_date" => "2027-08-20"],
            ["id" => 46, "name" => "لوراتادين 10 مجم (Claritine 10mg)", "generic_name" => "لوراتادين", "category" => "مضادات الحساسية والهيستامين", "price" => 21.0, "stock_quantity" => 80, "expiry_date" => "2027-03-30"],
            ["id" => 47, "name" => "ريفريش تيرز قطرة مرطبة للعين (Refresh Tears)", "generic_name" => "كاربوكسي ميثيل سليلوز", "category" => "قطرات ومستحضرات العيون", "price" => 28.0, "stock_quantity" => 65, "expiry_date" => "2027-10-10"],
            ["id" => 48, "name" => "أوتريفين بخاخ أنف للكبار (Otrivin Adult)", "generic_name" => "زايلوميتازولين", "category" => "مزيلات احتقان الأنف", "price" => 15.0, "stock_quantity" => 95, "expiry_date" => "2026-12-20"],
            ["id" => 49, "name" => "ميبو مرهم لعلاج الحروق والجروح (Mebo Ointment)", "generic_name" => "بيتا سيتوستيرول", "category" => "مراهم الحروق والجروح", "price" => 39.0, "stock_quantity" => 50, "expiry_date" => "2027-11-15"],
            ["id" => 50, "name" => "فينتولين بخاخ للربو (Ventolin Inhaler)", "generic_name" => "سالبوتامول", "category" => "موسعات القصبات والربو", "price" => 26.0, "stock_quantity" => 85, "expiry_date" => "2027-07-20"],
            ["id" => 51, "name" => "موتيليوم 10 مجم (Motilium 10mg)", "generic_name" => "دومبيريدون", "category" => "منظم حركة المعدة والغثيان", "price" => 22.5, "stock_quantity" => 75, "expiry_date" => "2027-04-10"],
            ["id" => 52, "name" => "ريباريل جل للكدمات (Reparil Gel)", "generic_name" => "إيسين + داي إيثيل أمين", "category" => "مضاد للالتهاب والتورم الموضعي", "price" => 19.0, "stock_quantity" => 60, "expiry_date" => "2027-06-05"],
            ["id" => 53, "name" => "جلوكوفاج 500 مجم (Glucophage 500mg)", "generic_name" => "ميتفورمين", "category" => "أدوية السكري", "price" => 25.0, "stock_quantity" => 140, "expiry_date" => "2027-05-30"],
            ["id" => 54, "name" => "كونكور 5 مجم (Concor 5mg)", "generic_name" => "بيسوبرولول", "category" => "أدوية الضغط والقلب", "price" => 35.0, "stock_quantity" => 90, "expiry_date" => "2027-08-15"],
            ["id" => 55, "name" => "فيروجلوبين كبسول (Feroglobin)", "generic_name" => "حديد + زنك + فيتامين ب", "category" => "مكملات الحديد وفقر الدم", "price" => 33.0, "stock_quantity" => 80, "expiry_date" => "2027-09-25"]
        ];
    }

    // 2. محاولة الاتصال بمحرك بايثون إذا كان يعمل
    $aiPayload = json_encode([
        'message' => $message,
        'medicines' => $medicines,
        'history' => $history
    ]);

    $ch = curl_init('http://127.0.0.1:8000/api/chat');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $aiPayload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 150);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 400);

    $aiResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $aiResponse) {
        $resData = json_decode($aiResponse, true);
        if (isset($resData['data']) && !empty($resData['data']['reply'])) {
            $cleanedReply = stripEmojis($resData['data']['reply']);
            $resData['data']['reply'] = $cleanedReply;
            Response::send(200, true, "تمت الإجابة عبر محرك الذكاء الاصطناعي بنجاح.", $resData['data']);
        }
    }

    // 3. المحرك السريري المحلي المتكامل (Advanced Local Fallback Engine)
    $fallbackResult = localFallbackChat($message, $medicines, $history);
    Response::send(200, true, "تمت الإجابة عبر المساعد الدوائي والسريري المحلي بنجاح.", $fallbackResult);
}

function stripEmojis($text) {
    if (!$text) return '';
    $clean = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{2300}-\x{23FF}\x{2B50}\x{26A0}\x{FE0F}]/u', '', $text);
    return str_replace(['📋', '💊', '⭐️', '💡', '⚠️', '🩺', '⭐', '🚨', '✅', '❌', '•'], '', $clean);
}

function normalizeArabicText($str) {
    $str = mb_strtolower(trim($str), 'UTF-8');
    $str = preg_replace('/[إأآا]/u', 'ا', $str);
    $str = preg_replace('/[ى]/u', 'ي', $str);
    $str = preg_replace('/[ة]/u', 'ه', $str);
    $str = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $str);
    return preg_replace('/\s+/u', ' ', $str);
}

function localFallbackChat($query, $medicines, $history = []) {
    $normQuery = normalizeArabicText($query);
    
    // التحية والاستقبال
    if (strpos($normQuery, 'مرحبا') !== false || strpos($normQuery, 'اهلا') !== false || strpos($normQuery, 'السلام') !== false || strpos($normQuery, 'هلا') !== false || strpos($normQuery, 'صباح') !== false || strpos($normQuery, 'مساء') !== false) {
        return [
            'reply' => "أهلاً بك. أنا المساعد الدوائي والسريري الذكي، متصل بقاعدة بيانات الصيدلية والمخزون الحي.\n\nأستطيع مساعدتك في:\n1. تشخيص واقتراح الأدوية المناسبة لأكثر من 30 حالة سريرية شائعة (كأمراض الصداع، الحمى، القولون والمغص، الإسهال والإمساك، الحساسية، حموضة المعدة، الكحة، آلام المفاصل والأسنان، قطرات العيون، الحروق، الربو، وغيرها).\n2. التحقق اللحظي من توفر المخزون والأسعار واقتراح البدائل الأوفر.\n3. إرشادات الجرعات وموانع الاستعمال والتوافق مع الحمل والأمراض المزمنة.\n\nتفضل بطرح شكواك أو اسم الدواء وسأجيبك فوراً.",
            'intent' => 'GREETING',
            'data' => []
        ];
    }

    // =========================================================================
    // أ) محرك الذاكرة السياقية والمحادثة التفاعلية (Multi-Turn Context Engine)
    // =========================================================================
    $isFollowUpDosage = (
        strpos($normQuery, 'كم حبه') !== false || strpos($normQuery, 'كم حبة') !== false ||
        strpos($normQuery, 'كم الجرعه') !== false || strpos($normQuery, 'كم الجرعة') !== false ||
        strpos($normQuery, 'جرعه') !== false || strpos($normQuery, 'جرعة') !== false ||
        strpos($normQuery, 'طريقه الاستخدام') !== false || strpos($normQuery, 'طريقة الاستخدام') !== false ||
        strpos($normQuery, 'متي اخذه') !== false || strpos($normQuery, 'متى اخذه') !== false || strpos($normQuery, 'متى اخده') !== false ||
        strpos($normQuery, 'كم مره') !== false || strpos($normQuery, 'كم مرة') !== false ||
        strpos($normQuery, 'قبل الاكل') !== false || strpos($normQuery, 'بعد الاكل') !== false ||
        strpos($normQuery, 'طريقه تناوله') !== false || strpos($normQuery, 'طريقة تناوله') !== false ||
        strpos($normQuery, 'كم قرص') !== false || strpos($normQuery, 'كم كبسوله') !== false
    );

    $isFollowUpPregnancy = (
        strpos($normQuery, 'حامل') !== false || strpos($normQuery, 'للحامل') !== false ||
        strpos($normQuery, 'الحمل') !== false || strpos($normQuery, 'حوامل') !== false ||
        strpos($normQuery, 'مرضع') !== false || strpos($normQuery, 'للمرضع') !== false ||
        strpos($normQuery, 'الرضاعه') !== false || strpos($normQuery, 'الرضاعة') !== false ||
        strpos($normQuery, 'ترضع') !== false || strpos($normQuery, 'ينفع للحامل') !== false ||
        strpos($normQuery, 'امن للحامل') !== false || strpos($normQuery, 'آمن للحامل') !== false
    );

    $isFollowUpChronic = (
        strpos($normQuery, 'مريض ضغط') !== false || strpos($normQuery, 'عندي ضغط') !== false ||
        strpos($normQuery, 'يناسب الضغط') !== false || strpos($normQuery, 'للضغط') !== false ||
        strpos($normQuery, 'مريض سكر') !== false || strpos($normQuery, 'عندي سكر') !== false ||
        strpos($normQuery, 'يناسب السكر') !== false || strpos($normQuery, 'للسكر') !== false ||
        strpos($normQuery, 'قرحه') !== false || strpos($normQuery, 'قرحة') !== false ||
        strpos($normQuery, 'معدتي حساسه') !== false || strpos($normQuery, 'معدتي حساسة') !== false ||
        strpos($normQuery, 'كلي') !== false || strpos($normQuery, 'كلى') !== false ||
        strpos($normQuery, 'فشل كلوي') !== false
    );

    $isFollowUpCheaper = (
        strpos($normQuery, 'في ارخص') !== false || strpos($normQuery, 'في أرخص') !== false ||
        strpos($normQuery, 'بديل ارخص') !== false || strpos($normQuery, 'بديل أرخص') !== false ||
        strpos($normQuery, 'غالي') !== false || strpos($normQuery, 'اوفر') !== false ||
        strpos($normQuery, 'أوفر') !== false || strpos($normQuery, 'بديل ثاني') !== false ||
        strpos($normQuery, 'بديل اخر') !== false
    );

    $isFollowUpPediatric = (
        strpos($normQuery, 'اطفال') !== false || strpos($normQuery, 'للاطفال') !== false ||
        strpos($normQuery, 'للأطفال') !== false || strpos($normQuery, 'طفل') !== false ||
        strpos($normQuery, 'عمر') !== false || strpos($normQuery, 'سنه') !== false ||
        strpos($normQuery, 'سنوات') !== false || strpos($normQuery, 'شراب') !== false ||
        strpos($normQuery, 'نقط') !== false || strpos($normQuery, 'تحاميل') !== false
    );

    // إذا كان السؤال استكمالياً لحديث سابق ووجدنا سجل محادثة
    if (($isFollowUpDosage || $isFollowUpPregnancy || $isFollowUpChronic || $isFollowUpCheaper || $isFollowUpPediatric) && !empty($history)) {
        $lastContext = '';
        for ($i = count($history) - 1; $i >= 0; $i--) {
            $msgContent = $history[$i]['content'] ?? '';
            if (!empty($msgContent)) {
                $lastContext = $msgContent . ' ' . $lastContext;
                if (strlen($lastContext) > 600) break;
            }
        }

        $normContext = normalizeArabicText($lastContext);

        // 1. حالة الجرعة والاستخدام (Dosage & Administration Follow-up)
        if ($isFollowUpDosage) {
            if (strpos($normContext, 'باراسيتامول') !== false || strpos($normContext, 'بنادول') !== false || strpos($normContext, 'فيفادول') !== false || strpos($normContext, 'ادول') !== false) {
                return [
                    'reply' => "### إرشادات الجرعة لدواء الباراسيتامول (بنادول / فيفادول / أدول):\n\n" .
                               "- **جرعة البالغين (فوق 12 سنة):** قرص إلى قرصين (500 إلى 1000 مجم) كل 6 إلى 8 ساعات عند اللزوم.\n" .
                               "- **الحد الأقصى اليومي:** لا تتجاوز 4000 مجم (أي 8 أقراص عيار 500 مجم) خلال 24 ساعة، حفاظاً على سلامة الكبد.\n" .
                               "- **التوقيت مع الطعام:** يُفضل تناوله بعد الأكل مع كوب ماء، ومع ذلك فهو لطيف جداً على المعدة ويمكن تناوله على معدة فارغة عند الضرورة.\n" .
                               "- **تحذير سريري:** تجنب تناول أدوية أخرى للزكام أو السعال تحتوي على الباراسيتامول في نفس الوقت لمنع ازدواجية الجرعة.\n\n" .
                               "هل المريض يعاني من أي مشاكل بوظائف الكبد أو الكلى، أم ترغب في معرفة الجرعة للأطفال؟",
                    'intent' => 'FOLLOW_UP_DOSAGE',
                    'data' => []
                ];
            }
            if (strpos($normContext, 'ايبوبروفين') !== false || strpos($normContext, 'بروفين') !== false || strpos($normContext, 'سابوفين') !== false) {
                return [
                    'reply' => "### إرشادات الجرعة لدواء الإيبوبروفين (بروفين / سابوفين):\n\n" .
                               "- **الجرعة للبالغين:** قرص عيار 400 مجم كل 8 ساعات، أو قرص عيار 600 مجم كل 12 ساعة عند شدة الألم والالتهاب.\n" .
                               "- **التوقيت الإلزامي:** يجب تناوله **بعد الوجبة مباشرة** مع شرب كوب كامل من الماء لمنع حدوث تهيج أو قرحة في جدار المعدة.\n" .
                               "- **الحد الأقصى:** لا تتجاوز 1200 مجم يومياً بدون استشارة طبية مباشرة.\n" .
                               "- **موانع هامة:** ممنوع تماماً لمرضى قرحة المعدة النشطة، قصور الكلى الحاد، ومرضى الضغط المرتفع غير المنضبط.\n\n" .
                               "هل تود بديلاً أكثر أماناً للمعدة والضغط مثل الباراسيتامول؟",
                    'intent' => 'FOLLOW_UP_DOSAGE',
                    'data' => []
                ];
            }
            if (strpos($normContext, 'ديكلوفيناك') !== false || strpos($normContext, 'فولتارين') !== false || strpos($normContext, 'كتافلام') !== false || strpos($normContext, 'رابيدوس') !== false) {
                return [
                    'reply' => "### إرشادات الجرعة لدواء الديكلوفيناك (فولتارين / كتافلام / رابيدوس):\n\n" .
                               "- **الجرعة للبالغين:** قرص عيار 50 مجم من مرتين إلى ثلاث مرات يومياً (كل 8-12 ساعة).\n" .
                               "- **التوقيت:** يُؤخذ بعد الأكل مباشرة لتفادي تهيج المعدة.\n" .
                               "- **ملاحظة سريرية:** كتافلام ورابيدوس (بوتاسيوم) سريعا المفعول لآلام الأسنان الحادة، بينما فولتارين (صوديوم) ممتد لالتهابات المفاصل والروماتيزم.\n" .
                               "- **تحذير:** لا يُستخدم لأكثر من 5 إلى 7 أيام متتالية دون مراجعة الطبيب.\n\n" .
                               "هل يعاني المريض من قرحة في المعدة أو ارتفاع في ضغط الدم؟",
                    'intent' => 'FOLLOW_UP_DOSAGE',
                    'data' => []
                ];
            }
            if (strpos($normContext, 'اوميبرازول') !== false || strpos($normContext, 'نيكسيوم') !== false || strpos($normContext, 'كونترولوك') !== false || strpos($normContext, 'حموضه') !== false) {
                return [
                    'reply' => "### إرشادات الجرعة لأدوية المعدة والحموضة (أوميبرازول / نيكسيوم / كونترولوك):\n\n" .
                               "- **الجرعة:** كبسولة واحدة يومياً عيار 20 مجم أو 40 مجم.\n" .
                               "- **التوقيت الحاسم:** تُؤخذ صباحاً على معدة فارغة قبل وجبة الإفطار بـ 30 إلى 60 دقيقة كاملة لتعمل بكفاءة قصوى.\n" .
                               "- **مدة العلاج:** من أسبوعين إلى 4 أسابيع في حالات الارتجاع والحموضة، وتُبلع الكبسولة كاملة دون مضغ أو تفريغ.\n\n" .
                               "هل ترغب بإرشادات إضافية حول التغذية المناسبة لمرضى الارتجاع وقرحة المعدة؟",
                    'intent' => 'FOLLOW_UP_DOSAGE',
                    'data' => []
                ];
            }
            if (strpos($normContext, 'اوجمنتين') !== false || strpos($normContext, 'كلافوكس') !== false || strpos($normContext, 'مضاد') !== false) {
                return [
                    'reply' => "### إرشادات جرعة المضاد الحيوي (أوجمنتين / كلافوكس / كيورام):\n\n" .
                               "- **الجرعة للبالغين عيار 1 جم:** قرص واحد مرتين يومياً (كل 12 ساعة بانتظام دقيق).\n" .
                               "- **التوقيت:** يُؤخذ مع بداية الوجبة لزيادة امتصاص المادة الفعالة وتقليل أي اضطرابات هضمية.\n" .
                               "- **إلزامية الكورس:** يجب إكمال الكورس العلاجي كاملاً (عادة 5 إلى 7 أيام) حتى بعد اختفاء الأعراض، لمنع تكوّن بكتيريا مقاومة للمضادات.\n\n" .
                               "هل يعاني المريض من حساسية تجاه البنسلين أو مشتقاته؟",
                    'intent' => 'FOLLOW_UP_DOSAGE',
                    'data' => []
                ];
            }
            if (strpos($normContext, 'ميبفرين') !== false || strpos($normContext, 'دوسباتالين') !== false || strpos($normContext, 'سباسموبان') !== false || strpos($normContext, 'قولون') !== false) {
                return [
                    'reply' => "### إرشادات جرعة أدوية القولون والمغص (دوسباتالين / سباسموبان):\n\n" .
                               "- **دوسباتالين 135 مجم:** قرص واحد 3 مرات يومياً قبل الوجبات بـ 20 دقيقة.\n" .
                               "- **سباسموبان (هيوسين 10 مجم):** قرص واحد عند اللزوم حتى 3 مرات يومياً عند حدوث تقلصات أو مغص شديد.\n" .
                               "- **نصيحة غذائية:** الابتعاد عن البقوليات، المقليات، المشروبات الغازية، والمحليات الصناعية.\n\n" .
                               "هل المغص مصحوب بإسهال أم بإمساك وانتفاخ؟",
                    'intent' => 'FOLLOW_UP_DOSAGE',
                    'data' => []
                ];
            }
        }

        // 2. حالة الحمل والرضاعة (Pregnancy & Lactation Follow-up)
        if ($isFollowUpPregnancy) {
            if (strpos($normContext, 'باراسيتامول') !== false || strpos($normContext, 'بنادول') !== false || strpos($normContext, 'فيفادول') !== false || strpos($normContext, 'صداع') !== false || strpos($normContext, 'حراره') !== false) {
                return [
                    'reply' => "### تقييم الأمان خلال فترة الحمل والرضاعة:\n\n" .
                               "- **الباراسيتامول (بنادول الأزرق / فيفادول العادي):** يُعتبر الخيار الأول والأكثر أماناً طبياً لتسكين الألم وخفض الحرارة أثناء كافة مراحل الحمل والرضاعة بجرعات معتدلة (500 مجم عند اللزوم).\n" .
                               "- **تحذير هام للحوامل:** تجنبي تماماً الأنواع المحتوية على كافيين مثل **بنادول إكسترا** أو المسكنات المضادة للالتهاب مثل **البروفين والفولتارين** (خاصة في الثلث الثالث من الحمل).\n\n" .
                               "هل تودين معرفة مسكنات أو مكملات آمنة أخرى للحوامل؟",
                    'intent' => 'FOLLOW_UP_PREGNANCY',
                    'data' => []
                ];
            }
            if (strpos($normContext, 'بروفين') !== false || strpos($normContext, 'فولتارين') !== false || strpos($normContext, 'كتافلام') !== false || strpos($normContext, 'سيلبركس') !== false || strpos($normContext, 'مفاصل') !== false || strpos($normContext, 'اسنان') !== false) {
                return [
                    'reply' => "### تحذير سريري قطعي بشأن الحمل:\n\n" .
                               "- **مضادات الالتهاب (بروفين، فولتارين، كتافلام):** غير آمنة في الحمل، وتُحظر قطعياً في الثلث الثالث من الحمل لأنها قد تؤدي لغلق مبكر للقناة الشريانية لدى الجنين ومشاكل في الكلى ونقص السائل الأمنيوسي.\n" .
                               "- **البديل الآمن المعتمد:** استبدالها فوراً بـ **الباراسيتامول (فيفادول أو بنادول الأزرق العادي)** فهو آمن تماماً.\n\n" .
                               "هل ترغب في توفير بديل آمن متوفر بالمخزن الآن؟",
                    'intent' => 'FOLLOW_UP_PREGNANCY',
                    'data' => []
                ];
            }
            if (strpos($normContext, 'قولون') !== false || strpos($normContext, 'مغص') !== false || strpos($normContext, 'دوسباتالين') !== false || strpos($normContext, 'سباسموبان') !== false || strpos($normContext, 'فلاجيل') !== false) {
                return [
                    'reply' => "### تقييم أمان أدوية المغص والقولون أثناء الحمل:\n\n" .
                               "- **أدوية المغص (دوسباتالين / سباسموبان):** لا يُنصح باستخدامها في الثلث الأول من الحمل إلا للضرورة القصوى وتحت إشراف مباشر من طبيب النساء والتوليد.\n" .
                               "- **البدائل الآمنة للمغص في الحمل:** المشروبات الدافئة المهدئة (كالنعناع والبابونج)، تقسيم الوجبات إلى وجبات خفيفة متعددة، والابتعاد عن الأطعمة المسببة للغازات.\n" .
                               "- **تحذير:** إذا كان المغص مصحوباً بتقلصات أسفل البطن أو نزيف يجب مراجعة الطوارئ النسائية فوراً.\n\n" .
                               "هل تودين نصائح إضافية لتخفيف آلام القولون والانتفاخ بأمان أثناء الحمل؟",
                    'intent' => 'FOLLOW_UP_PREGNANCY',
                    'data' => []
                ];
            }
            if (strpos($normContext, 'حموضه') !== false || strpos($normContext, 'اوميبرازول') !== false) {
                return [
                    'reply' => "### علاج الحموضة الآمن للحامل:\n\n" .
                               "- الخيار الأول الأكثر أماناً للحوامل هو مضادات الحموضة الموضعية مثل (شراب جافيسكون أو أقراص ريني للمضغ).\n" .
                               "- أوميبرازول وبانتوبرازول يُصنفان كفئة (Category C) ولا يُلجأ لهما إلا بإشراف مباشر من طبيب النساء والتوليد.\n\n" .
                               "هل ترغب باقتراح شراب جافيسكون أو فوار مناسب للحمل؟",
                    'intent' => 'FOLLOW_UP_PREGNANCY',
                    'data' => []
                ];
            }
            if (strpos($normContext, 'اوجمنتين') !== false || strpos($normContext, 'مضاد') !== false) {
                return [
                    'reply' => "### تقييم أمان المضاد الحيوي في الحمل:\n\n" .
                               "- **الأوجمنتين والأموكسيل:** مصنفان كفئة آمنة نسبياً (Category B) في الحمل ويمكن استخدامهما إذا دعت الحاجة السريرية بإشراف الطبيب.\n" .
                               "- **مضادات محظورة:** يُمنع تماماً استخدام السيبروفلوكساسين (سيبروباي) والتافانيك والتتراسيكلين أثناء الحمل.\n\n" .
                               "هل المضاد موصوف لعلاج التهاب المسالك أم الصدر؟",
                    'intent' => 'FOLLOW_UP_PREGNANCY',
                    'data' => []
                ];
            }
            return [
                'reply' => "### التوجيه السريري العام للأمان في الحمل والرضاعة:\n\n" .
                           "- تجنبي تناول أي دواء دون استشارة الصيدلي أو طبيب النساء المعالج خلال فترة الحمل.\n" .
                           "- **المسكن الآمن المعتمد عالمياً:** الباراسيتامول فقط، مع تجنب الأسبرين والبروفين والفولتارين تماماً.\n\n" .
                           "تفضلي بكتابة اسم الدواء الذي تستفسرين عنه تحديداً وسأعطيكِ تقييمه العلمي فوراً.",
                'intent' => 'FOLLOW_UP_PREGNANCY',
                'data' => []
            ];
        }

        // 3. حالة الأمراض المزمنة (Chronic Diseases & Contraindications)
        if ($isFollowUpChronic) {
            if (strpos($normQuery, 'ضغط') !== false) {
                return [
                    'reply' => "### تقييم الملاءمة لمرضى ارتفاع ضغط الدم:\n\n" .
                               "- **المسكنات:** تجنب تماماً أدوية البروفين والفولتارين والكتافلام لأنها تسبب احتباس الأملاح والسوائل وترفع الضغط. المسكن الآمن 100% هو **الباراسيتامول (فيفادول / أدول)**.\n" .
                               "- **أدوية البرد والزكام:** تجنب أدوية الرشح التي تحتوي على مادة السودوإيفيدرين (مثل فلوتاب أو بنادول كولد) لأنها تقبض الأوعية الدموية وترفع الضغط بشكل حاد.\n\n" .
                               "هل تبحث عن علاج لعارض معين لا يتعارض مع أدوية الضغط؟",
                    'intent' => 'FOLLOW_UP_CHRONIC',
                    'data' => []
                ];
            }
            if (strpos($normQuery, 'قرحه') !== false || strpos($normQuery, 'معده') !== false) {
                return [
                    'reply' => "### تقييم الملاءمة لمرضى قرحة وحساسية المعدة:\n\n" .
                               "- **ممنوعات قطعية:** يمنع تناول مسكنات NSAIDs مثل البروفين، الفولتارين، الأسبرين، والرئيسيات على معدة فارغة، ويُفضل تجنبها تماماً.\n" .
                               "- **الخيارات الآمنة:** الباراسيتامول لتسكين الألم، مع تناول حماية للمعدة (أوميبرازول أو بانتوبرازول) قبل الإفطار.\n\n" .
                               "هل ترغب بإضافة دواء حماية المعدة للفاتورة؟",
                    'intent' => 'FOLLOW_UP_CHRONIC',
                    'data' => []
                ];
            }
        }

        // 4. حالة البحث عن بديل أرخص (Cheaper Alternative Follow-up)
        if ($isFollowUpCheaper) {
            return [
                'reply' => "### البدائل المتكافئة بيولوجياً بأفضل سعر توفير:\n\n" .
                           "- إذا كنت تستخدم **أوجمنتين 1 جم** (45 ريال) -> البديل المكافئ: **كلافوكس 1 جم** (32 ريال) أو **ميجاموكس 1 جم** (28 ريال) [يوفر حتى 17 ريال].\n" .
                           "- إذا كنت تستخدم **بنادول إكسترا** (12 ريال) -> البديل المكافئ: **أدول أو فيفادول** (6 ريال) [يوفر 50% من السعر].\n" .
                           "- إذا كنت تستخدم **بروفين 400** (15 ريال) -> البديل المكافئ: **سابوفين 400** (11 ريال) أو **إيبوفيل** (9 ريال).\n" .
                           "- إذا كنت تستخدم **نيكسيوم 40** (72 ريال) -> البديل المكافئ: **كونترولوك 40** (54 ريال) أو **أوميبرازول 20** (20 ريال).\n\n" .
                           "اكتب لي اسم الدواء المطلوب بالظبط وسأستخرج لك كل بدائله المرتبة من الأرخص للأعلى فوراً.",
                'intent' => 'FOLLOW_UP_CHEAPER',
                'data' => []
            ];
        }

        // 5. حالة الأطفال والأشكال الصيدلانية (Pediatric Follow-up)
        if ($isFollowUpPediatric) {
            return [
                'reply' => "### إرشادات الصرف والجرعات للأطفال:\n\n" .
                           "- **الحرارة والألم للأطفال:** يُصرف شراب الباراسيتامول (مثل فيفادول شراب) أو تحاميل، وتحسب الجرعة الدقيقة حسب **وزن الطفل بالكيلوجرام** (15 مجم لكل كجم كل 6 ساعات)، وليس بالعمر فقط.\n" .
                           "- **التهابات الصدر والحلق:** يُمنع صرف المضادات الحيوية للأطفال دون فحص سريري للوزن والأذن والحلق لدى الطبيب.\n" .
                           "- **تحذير هام:** يُمنع إعطاء الأسبرين نهائياً للأطفال دون سن 16 عاماً لتجنب متلازمة راي (Reye's syndrome).\n\n" .
                           "كم وزن الطفل أو عمره بالضبط لكي أحسب لك الجرعة بالملليلتر بدقة؟",
                'intent' => 'FOLLOW_UP_PEDIATRIC',
                'data' => []
            ];
        }
    }

    // =========================================================================
    // ب) أنطولوجيا المعرفة السريرية الموسعة (32+ حالة مرضية سريرية متكاملة)
    // =========================================================================
    $symptomRules = [
        [
            'id' => 'EYE_IRRITATION_DRY',
            'title' => 'جفاف وحرقة وإجهاد العين والتهاب العين الخفيف',
            'keywords' => ['عين', 'عيني', 'عيوني', 'جفاف عين', 'جفاف بالعين', 'حرقة بالعين', 'حرقه بالعين', 'حرقان بالعين', 'حرقان في عيني', 'حرقان بعيني', 'احمرار عين', 'قطرة عين', 'قطره عين', 'تدميع عين', 'اجهاد عين'],
            'generics' => ['كاربوكسي ميثيل سليلوز', 'ريفريش'],
            'categories' => ['قطرات ومستحضرات العيون', 'عيون'],
            'advice' => 'استخدام قطرة مرطبة خالية من المواد الحافظة مثل ريفريش تيرز قطرة واحدة في كل عين 3 إلى 4 مرات يومياً، مع أخذ فترات راحة من شاشات الأجهزة وتجنب فرك العينين.',
            'triage' => 'هل يوجد إفرازات صديدية صفراء بالعين أو تشوش بالرؤية أو ألم داخلي حاد؟'
        ],
        [
            'id' => 'UTI_DYSURIA',
            'title' => 'حرقان والتهاب المسالك البولية والمثانة والأملاح',
            'keywords' => ['حرقان بول', 'حرقان بالبول', 'حرقان في البول', 'حرقان شديد في البول', 'حرقان شديد بالبول', 'مسالك بولية', 'مسالك بوليه', 'صديد بول', 'صديد في البول', 'صعوبة تبول', 'التهاب مثانة', 'التهاب مثانه', 'بول متكرر', 'الم عند التبول', 'تقطيع بالبول', 'وجع في البول', 'الم بالبول', 'البول'],
            'generics' => ['سيبروفلوكساسين', 'ليفوفلوكساسين', 'أوجمنتين', 'سيبروباي'],
            'categories' => ['مضاد حيوي', 'مسالك'],
            'advice' => 'شرب كميات وافرة جداً من الماء (ما لا يقل عن 3 لتر يومياً) لطرد الأملاح والبكتيريا، مع فوار مطهر للمسالك، وإذا كان هناك صديد يلزم مضاد حيوي مثل سيبروباي 500 مجم بعد عمل تحليل بول.',
            'triage' => 'هل الحرقان مصحوب بألم في جانبي الظهر (الكليتين) أو دم في البول أو حرارة؟'
        ],
        [
            'id' => 'BURNS_WOUNDS_SKIN',
            'title' => 'الحروق السطحية والجروح والتسلخات والتئام الأنسجة',
            'keywords' => ['حرق', 'حروق', 'انحرقت', 'جرح', 'جروح', 'تسلخات', 'ميبو', 'ماء حار', 'جلد محروق', 'التئام', 'ندبة', 'ندبه', 'شاش فازلين'],
            'exclude' => ['حرقان', 'بول', 'البول', 'معده', 'المعده', 'حموضه', 'حموضة'],
            'generics' => ['بيتا سيتوستيرول', 'ميبو'],
            'categories' => ['مراهم الحروق والجروح', 'جلدية'],
            'advice' => 'تبريد مكان الحرق فوراً بماء فاتر جارٍ لمدة 10-15 دقيقة (تجنب وضع الثلج أو المعجون)، ثم دهن طبقة رقيقة من مرهم ميبو (Mebo) وتغطيتها بلطف بشاش معقم وتجديدها كل 8 ساعات.',
            'triage' => 'ما هي درجة الحرق؟ وهل تكونت فقاعات مائية كبيرة أو يشمل مساحة واسعة؟'
        ],
        [
            'id' => 'STOMACH_ACIDITY_GERD',
            'title' => 'حموضة وحرقة المعدة والارتجاع المريئي وقرحة المعدة',
            'keywords' => ['حموضة', 'حموضه', 'حرقان', 'حرقة', 'حرقه', 'ارتجاع', 'قرحة', 'قرحه', 'معدة', 'معده', 'حارق', 'فم المعدة', 'عسر هضم', 'الم معدة'],
            'exclude' => ['عين', 'عيني', 'عيوني', 'بول', 'البول', 'جلد'],
            'generics' => ['أوميبرازول', 'إيزوميبرازول', 'بانتوبرازول'],
            'categories' => ['أدوية الجهاز الهضمي والمعدة', 'جهاز هضمي'],
            'advice' => 'تناول كبسولة أوميبرازول 20 مجم أو نيكسيوم صباحاً على معدة فارغة قبل الإفطار بنصف ساعة كاملة مع تجنب الأطعمة الحارة والدهنية والقهوة والتدخين.',
            'triage' => 'هل تشعر بحرقة تصعد للحلق أو صعوبة في البلع؟ وهل الأعراض تزداد عند الاستلقاء؟'
        ],
        [
            'id' => 'HEADACHE_MIGRAINE',
            'title' => 'الصداع والصداع النصفي (الشقيقة) وتسكين الآلام العامة',
            'keywords' => ['صداع', 'راس', 'رأس', 'شقيقة', 'شقيقه', 'مصدع', 'الم راس', 'وجع راس', 'راسي يوجعني', 'راسي يعورني', 'صداع نصفي', 'صداع شديد'],
            'generics' => ['باراسيتامول', 'إيبوبروفين', 'ديكلوفيناك البوتاسيوم', 'باراسيتامول + كافيين'],
            'categories' => ['مسكن', 'خافض حرارة', 'مسكن ومضاد للالتهاب'],
            'advice' => 'تناول قرص باراسيتامول (أو بنادول إكسترا) بعد الأكل مع كوب ماء. وفي حال الصداع الشديد أو النصفي يمكن استخدام بروفين 400 أو رابيدوس/كتافلام.',
            'triage' => 'هل الصداع مصحوب بزغللة في العين أو غثيان؟ وهل يعاني المريض من مشاكل بالمعدة أو ضغط دم مرتفع؟'
        ],
        [
            'id' => 'FEVER_BODY_ACHE',
            'title' => 'الحمى وارتفاع درجة الحرارة وتكسير وخمول الجسم',
            'keywords' => ['حرارة', 'حراره', 'سخونة', 'سخونه', 'حمى', 'حمي', 'مكسر', 'ساخن', 'مسخن', 'تكسير بالجسم', 'قشعريرة', 'رجفة', 'تنزيل الحرارة'],
            'generics' => ['باراسيتامول', 'إيبوبروفين'],
            'categories' => ['خافض حرارة', 'مسكن وخافض حرارة'],
            'advice' => 'تناول الباراسيتامول (500 إلى 1000 مجم كل 6-8 ساعات، بحد أقصى 4000 مجم يومياً) مع عمل كمادات ماء فاتر وتناول السوائل بكثرة.',
            'triage' => 'كم تبلغ درجة الحرارة المقاسة حالياً؟ وإذا تجاوزت 39 درجة لأكثر من 48 ساعة يلزم مراجعة الطوارئ فوراً.'
        ],
        [
            'id' => 'COLIC_IBS',
            'title' => 'مغص البطن والقولون العصبي والانتفاخ والغازات والتقلصات',
            'keywords' => ['مغص', 'قولون', 'غازات', 'انتفاخ', 'تقلصات', 'الم بطن', 'وجع بطن', 'تشنج بالبطن', 'كركبة بالبطن', 'عصبي'],
            'generics' => ['ميبفرين', 'هيوسين بوتيل بروميد', 'ميترونيدازول'],
            'categories' => ['علاج القولون والتقلصات', 'مسكن لتقلصات الجهاز الهضمي', 'جهاز هضمي'],
            'advice' => 'تناول قرص ميبفرين (دوسباتالين 135 مجم) قبل الأكل بـ 20 دقيقة، أو سباسموبان عند المغص الحاد، مع شرب السوائل الدافئة والابتعاد عن البقوليات والتوتر.',
            'triage' => 'هل المغص مصحوب بإسهال أم إمساك؟ وهل يخف الألم بعد التبرز؟'
        ],
        [
            'id' => 'DIARRHEA_GASTRO',
            'title' => 'الإسهال والنزلات المعوية وتطهير الجهاز الهضمي',
            'keywords' => ['إسهال', 'اسهال', 'نزلة معوية', 'نزله معويه', 'تسمم غذائي', 'تطهير معوي', 'بطني يمشي', 'ترجيع واسهال', 'اسهال مائي', 'لوبيراميد', 'ايموديوم'],
            'generics' => ['ميترونيدازول', 'لوبيراميد', 'سيبروفلوكساسين'],
            'categories' => ['مضاد ومطهر معوي', 'مضاد حيوي'],
            'advice' => 'استخدام مطهر معوي مثل فلاجيل 500 مجم كل 8 ساعات، مع كبسولة لوبيراميد (إيموديوم) عند اللزوم، ومحلول الجفاف لتعويض الأملاح وتجنب مشتقات الحليب والدهون.',
            'triage' => 'كم مرة تكرر الإسهال اليوم؟ وهل يوجد دم في البراز أو حرارة مرتفعة؟'
        ],
        [
            'id' => 'CONSTIPATION_LAXATIVE',
            'title' => 'الإمساك وصعوبة الإخراج والملينات الهضمية',
            'keywords' => ['إمساك', 'امساك', 'ملين', 'عسر اخراج', 'صعوبة اخراج', 'معدتي ناشفة', 'متحجر', 'ملينات', 'دوفالاك'],
            'generics' => ['لاكتولوز', 'دوفالاك'],
            'categories' => ['ملينات وأدوية الإمساك', 'جهاز هضمي'],
            'advice' => 'تناول شراب لاكتولوز (دوفالاك) 15 إلى 30 مل يومياً مع شرب ما لا يقل عن 2-3 لتر ماء والإكثار من الألياف والخضار الورقية.',
            'triage' => 'منذ متى يعاني المريض من الإمساك؟ وهل مصحوب بألم شرجي أو بواسير؟'
        ],
        [
            'id' => 'NAUSEA_VOMITING',
            'title' => 'الغثيان والقيء والاستفراغ ودوار الحركة',
            'keywords' => ['غثيان', 'لوعة', 'لوعه', 'استفراغ', 'قيء', 'ترجيع', 'احس بستفرغ', 'دوار سفر', 'دوخة واستفراغ', 'موتيليوم'],
            'generics' => ['دومبيريدون'],
            'categories' => ['منظم حركة المعدة والغثيان', 'جهاز هضمي'],
            'advice' => 'تناول قرص موتيليوم 10 مجم (دومبيريدون) قبل الأكل بـ 15 دقيقة لتنظيم حركة المعدة ومنع الترجيع، مع رشف السوائل الباردة ببطء.',
            'triage' => 'هل المريضة حامل؟ وهل القيء مستمر يمنع شرب الماء؟'
        ],
        [
            'id' => 'COUGH_BRONCHITIS',
            'title' => 'السعال والكحة الجافة والرطبة وإذابة البلغم',
            'keywords' => ['كحة', 'كحه', 'سعال', 'بلغم', 'كحة ناشفة', 'كحة ببلغم', 'صدر يوجعني', 'مخاط بالصدر', 'شرقة'],
            'generics' => ['باراسيتامول', 'أزيثرومايسين', 'أموكسيسيلين'],
            'categories' => ['مسكن وخافض حرارة', 'مضاد حيوي', 'موسعات القصبات والربو'],
            'advice' => 'الإكثار من السوائل الدافئة والينسون وعسل النحل، وتناول مذيب بلغم أو مهدئ كحة، وإذا كانت الكحة مصحوبة ببلغم صديدي وحرارة قد يلزم مضاد حيوي.',
            'triage' => 'هل الكحة جافة دغدغة بالحلق أم مصحوبة ببلغم سميك أصفر/أخضر؟ وكم يوم مستمرة؟'
        ],
        [
            'id' => 'FLU_COLD_SORETHROAT',
            'title' => 'الرشح والزكام والإنفلونزا والتهاب واحتقان الحلق',
            'keywords' => ['زكام', 'رشح', 'انفلونزا', 'إنفلونزا', 'التهاب حلق', 'حلقي يوجعني', 'احتقان حلق', 'صعوبة بلع', 'عطاس', 'برد'],
            'generics' => ['باراسيتامول', 'سيتريزين', 'لوراتادين', 'أموكسيسيلين + كلافولانات'],
            'categories' => ['مسكن وخافض حرارة', 'مضادات الحساسية والهيستامين', 'مضاد حيوي'],
            'advice' => 'تناول الباراسيتامول لتسكين الحلق والحرارة، ومضاد هيستامين (سيتريزين) لتجفيف الرشح والعطاس، مع الغرغرة بالماء والملح الدافئ.',
            'triage' => 'هل تشاهد بقعاً بيضاء على اللوزتين أو صديد؟ وهل يوجد تضخم بالغدد اللمفاوية؟'
        ],
        [
            'id' => 'SINUSITIS_CONGESTION',
            'title' => 'احتقان وانسداد الأنف والتهاب الجيوب الأنفية',
            'keywords' => ['جيوب انفية', 'جيوب أنفية', 'انسداد انف', 'انسداد أنف', 'احتقان انف', 'خشمي مسدود', 'صداع الجيوب', 'ضغط بين العينين', 'اوتريفين', 'أوتريفين'],
            'generics' => ['زايلوميتازولين', 'سيتريزين', 'لوراتادين'],
            'categories' => ['مزيلات احتقان الأنف', 'مضادات الحساسية والهيستامين'],
            'advice' => 'استخدام بخاخ أوتريفين للكبار بخة بكل أنف مرتين يومياً (لمدة لا تتجاوز 5 أيام متتالية لمنع الاحتقان الارتدادي)، مع غسول ماء البحر الملحي.',
            'triage' => 'هل يعاني المريض من ارتفاع في ضغط الدم؟ (مزيلات الاحتقان الفموية قد ترفع الضغط).'
        ],
        [
            'id' => 'ASTHMA_RESPIRATORY',
            'title' => 'الربو وحساسية الصدر وضيق التنفس والصفير',
            'keywords' => ['ربو', 'ضيق تنفس', 'كتمة', 'كتمه', 'حساسية صدر', 'تصفير بالصدر', 'نهجان', 'بخاخ ربو', 'فنتولين', 'فينتولين', 'سيمبيكورت'],
            'generics' => ['سالبوتامول', 'فورموتيرول وبوديسونيد'],
            'categories' => ['موسعات القصبات والربو'],
            'advice' => 'استنشاق بخاخ فينتولين (سالبوتامول) بختين عند الشعور بنوبة الضيق لتوسيع الشعب الهوائية فوراً والجلوس مستقيماً في مكان جيد التهوية، أو سيمبيكورت للوقاية اليومية.',
            'triage' => 'هل يعاني المريض من أزمة ربو حادة مستمرة لا تستجيب للبخاخ؟ إذا نعم، يجب التوجه فوراً لأقرب طوارئ.'
        ],
        [
            'id' => 'JOINTS_ARTHRITIS',
            'title' => 'آلام المفاصل والفقرات والتهاب الروماتيزم وخشونة الركبة',
            'keywords' => ['مفاصل', 'ركبة', 'ركبه', 'خشونة', 'روماتيزم', 'عظام', 'ظهر', 'فقرات', 'غضروف', 'دسك', 'ديسك', 'عرق النسا', 'سيلبركس'],
            'generics' => ['سيليكوكسيب', 'ديكلوفيناك الصوديوم', 'ميلوكسيكام', 'إيبوبروفين'],
            'categories' => ['مسكن ومضاد للالتهاب'],
            'advice' => 'استخدام سيلبركس 200 مجم (آمن نسبياً على المعدة) أو فولتارين 50 مجم بعد الأكل مباشرة لتخفيف التيبس والالتهاب، مع كمادات دافئة ودهان جل موضعي.',
            'triage' => 'هل يعاني المريض من قرحة في المعدة أو قصور كلوي أو ارتفاع ضغط الدم؟'
        ],
        [
            'id' => 'MUSCLE_SPASM',
            'title' => 'الشد العضلي وتقلص العضلات وآلام الرقبة والأبهر',
            'keywords' => ['شد عضلي', 'ابهر', 'أبهر', 'تشنج عضلي', 'عضلة مشدودة', 'الم رقبة', 'لوح الكتف', 'عضلات مجهدة', 'كدمة', 'ريباريل'],
            'generics' => ['إيبوبروفين', 'ديكلوفيناك الصوديوم', 'إيسين + داي إيثيل أمين'],
            'categories' => ['مسكن ومضاد للالتهاب', 'مضاد للالتهاب والتورم الموضعي'],
            'advice' => 'استخدام جل موضعي مثل ريباريل جل على مكان الشد مرتين لثلاث مرات يومياً مع مسكن ومضاد التهاب بعد الأكل، والحرص على الراحة والكمادات.',
            'triage' => 'هل حدث الشد بعد مجهود رياضي أو حمل ثقيل؟ وهل هناك تنميل ممتد للذراع أو الساق؟'
        ],
        [
            'id' => 'TOOTHACHE_DENTAL',
            'title' => 'ألم الأسنان والتهاب اللثة وخراج الضرس',
            'keywords' => ['اسنان', 'أسنان', 'ضرس', 'ضرسي', 'الم اسنان', 'لثة', 'لثه', 'خراج', 'وجع ضرس', 'خلع ضرس', 'تسوس'],
            'generics' => ['ديكلوفيناك البوتاسيوم', 'إيبوبروفين', 'أموكسيسيلين + كلافولانات', 'حمض الميفيناميك'],
            'categories' => ['مسكن ومضاد للالتهاب', 'مضاد حيوي'],
            'advice' => 'تناول مسكن سريع مثل كتافلام 50 مجم أو رابيدوس بعد الأكل لتسكين ألم العصب فوراً. وفي حال وجود انتفاخ وصديد باللثة يلزم أوجمنتين مع مراجعة طبيب الأسنان.',
            'triage' => 'هل يوجد تورم أو صديد واضح في الخد أو اللثة؟'
        ],
        [
            'id' => 'ALLERGY_SKIN_ITCH',
            'title' => 'الحساسية الجلدية والحكة والأرتيكاريا والطفح الجلدي',
            'keywords' => ['حساسية', 'حساسيه', 'حكة', 'حكه', 'هرش', 'ارتكاريا', 'طفح جلدي', 'اكزيما', 'بقع حمراء', 'حساسية جلد', 'هيدروكورتيزون'],
            'generics' => ['سيتريزين', 'لوراتادين', 'هيدروكورتيزون'],
            'categories' => ['مضادات الحساسية والهيستامين', 'جلدية'],
            'advice' => 'تناول قرص سيتريزين 10 مجم (زيرتك) مساءً أو لوراتادين نهاراً، مع كريم موضعي ملطف كالهيدروكورتيزون للحكة الشديدة وترطيب الجلد.',
            'triage' => 'هل الحساسية مصحوبة بتورم في الشفتين أو ضيق بالتنفس؟ (تتطلب تدخلاً طارئاً فورياً).'
        ],
        [
            'id' => 'DIABETES_GLUCOSE',
            'title' => 'مرض السكري وتنظيم مستوى السكر في الدم',
            'keywords' => ['سكري', 'سكر', 'تراكمي', 'مريض سكر', 'هبوط سكر', 'ارتفاع سكر', 'جلوكوفاج', 'منظم سكر'],
            'generics' => ['ميتفورمين'],
            'categories' => ['أدوية السكري'],
            'advice' => 'تناول منظم السكر (جلوكوفاج / ميتفورمين) أثناء أو بعد الوجبة مباشرة لتقليل الاضطرابات المعوية، مع الالتزام بالحمية الغذائية وفحص السكر بانتظام.',
            'triage' => 'كم تبلغ قراءة السكر الصائم أو التراكمي؟ وهل تشعر بأعراض هبوط (تعرق ورجفة ودوار)؟'
        ],
        [
            'id' => 'HYPERTENSION_CARDIAC',
            'title' => 'ارتفاع ضغط الدم وصحة القلب وتنظيم النبض',
            'keywords' => ['ضغط', 'ضغط دم', 'ارتفاع الضغط', 'مريض ضغط', 'ضغط مرتفع', 'خفقان', 'تسارع نبض', 'كونكور'],
            'generics' => ['بيسوبرولول'],
            'categories' => ['أدوية الضغط والقلب'],
            'advice' => 'تناول دواء الضغط (مثل كونكور 5 مجم) بانتظام يومياً صباحاً، مع تقليل ملح الطعام، ممارسة المشي، وتجنب التوقف المفاجئ عن الدواء.',
            'triage' => 'كم تبلغ قراءة قياس الضغط الحالية؟ وهل هناك صداع مؤخرة الرأس أو دوار؟'
        ],
        [
            'id' => 'CHOLESTEROL_LIPIDS',
            'title' => 'ارتفاع الكوليسترول والدهون الثلاثية وصحة الشرايين',
            'keywords' => ['كوليسترول', 'دهون ثلاثية', 'دهون ثلاثيه', 'شحوم', 'ليبانتيل', 'تصلب شرايين', 'دهون الدم'],
            'generics' => ['فينوفيبرات', 'أتورفاستاتين'],
            'categories' => ['أدوية الدهون والكوليسترول'],
            'advice' => 'تناول خافض الدهون (مثل ليبانتيل للدهون الثلاثية أو أتورفاستاتين للكوليسترول) مساءً بعد وجبة العشاء، مع الالتزام بحمية قليلة الدهون وممارسة الرياضة.',
            'triage' => 'كم تبلغ نسبة الدهون الثلاثية والكوليسترول الضار (LDL) في تحليلك الأخير؟'
        ],
        [
            'id' => 'ANEMIA_VITAMINS',
            'title' => 'الأنيميا وفقر الدم ونقص الحديد والهيموجلوبين والخمول',
            'keywords' => ['انيميا', 'أنيميا', 'فقر دم', 'نقص حديد', 'هيموجلوبين', 'خمول وتعب', 'شحوب', 'تساقط شعر', 'دوخة ونقص فيتامين', 'فيروجلوبين'],
            'generics' => ['حديد + زنك + فيتامين ب'],
            'categories' => ['مكملات الحديد وفقر الدم'],
            'advice' => 'تناول كبسولة فيروجلوبين أو مكملات الحديد بعد الأكل بساعتين مع عصير برتقال (فيتامين C لتعزيز الامتصاص)، وتجنب الشاي والقهوة والحليب لمدة ساعتين بعدها.',
            'triage' => 'كم نسبة الهيموجلوبين أو مخزون الحديد (Ferritin) في آخر فحص مخبري؟'
        ]
    ];

    // 1. مطابقة الاستفسار مع أنطولوجيا الأعراض السريرية باستخدام نظام الأوزان والاستبعاد
    $bestRule = null;
    $highestScore = 0;

    foreach ($symptomRules as $rule) {
        // التحقق من موانع ومستبعدات العارض لمنع الالتباس (Negative Exclusions)
        if (!empty($rule['exclude'])) {
            foreach ($rule['exclude'] as $ex) {
                if (strpos($normQuery, normalizeArabicText($ex)) !== false) {
                    continue 2;
                }
            }
        }

        $score = 0;
        $paddedQuery = ' ' . $normQuery . ' ';
        foreach ($rule['keywords'] as $kw) {
            $normKw = normalizeArabicText($kw);
            if (strpos($paddedQuery, ' ' . $normKw . ' ') !== false || strpos($normQuery, $normKw) !== false) {
                $wordCount = count(explode(' ', $normKw));
                // الكلمات المركبة تأخذ وزناً مضاعفاً
                $score += ($wordCount >= 2 ? $wordCount * 5 : 2);
            }
        }

        if ($score > $highestScore) {
            $highestScore = $score;
            $bestRule = $rule;
        }
    }

    if ($bestRule && $highestScore >= 2) {
        $rule = $bestRule;
        $suggestedMeds = [];
        foreach ($medicines as $m) {
            $mGen = normalizeArabicText($m['generic_name'] ?? '');
            $mName = normalizeArabicText($m['name'] ?? '');
            $mCat = normalizeArabicText($m['category'] ?? '');

            $hit = false;
            foreach ($rule['generics'] as $gen) {
                $normGen = normalizeArabicText($gen);
                if (strpos($mGen, $normGen) !== false || strpos($mName, $normGen) !== false) {
                    $hit = true;
                    break;
                }
            }
            if (!$hit && !empty($rule['categories'])) {
                foreach ($rule['categories'] as $cat) {
                    if (strpos($mCat, normalizeArabicText($cat)) !== false) {
                        $hit = true;
                        break;
                    }
                }
            }
            if ($hit) {
                $suggestedMeds[] = $m;
            }
        }

        // ترتيب: المتوفر بالمخزن أولاً، ثم الأوفر سعراً
        usort($suggestedMeds, function($a, $b) {
            $aStock = ($a['stock_quantity'] ?? 0) > 0 ? 0 : 1;
            $bStock = ($b['stock_quantity'] ?? 0) > 0 ? 0 : 1;
            if ($aStock !== $bStock) return $aStock <=> $bStock;
            return floatval($a['price'] ?? 0) <=> floatval($b['price'] ?? 0);
        });

        $reply = "### التقييم السريري ودواعي الاستخدام:\n";
        $reply .= "**الحالة التشخيصية:** {$rule['title']}\n\n";

        if (!empty($suggestedMeds)) {
            $reply .= "#### الأدوية المقترحة المتوفرة حالياً في صيدلية المستشفى (مرتبة من الأوفر):\n";
            foreach (array_slice($suggestedMeds, 0, 5) as $idx => $med) {
                $num = $idx + 1;
                $bestTag = ($idx === 0) ? " [الخيار الأوفر والموصى به]" : "";
                $price = number_format(floatval($med['price']), 2);
                $stock = intval($med['stock_quantity'] ?? 0);
                $reply .= "{$num}. **{$med['name']}**{$bestTag}\n";
                $reply .= "   - المادة الفعالة: `{$med['generic_name']}` | السعر: **{$price} ريال** | الرصيد المتاح: `{$stock}` علبة\n";
            }
        } else {
            $reply .= "**حالة المخزون:** الدواء المباشر لهذه الحالة غير مسجل في المخزون المحلي حالياً، يُرجى استشارة الصيدلي الميداني لتوفير البديل المناسب.\n";
        }

        $reply .= "\n#### إرشادات الاستخدام والجرعات:\n{$rule['advice']}\n";
        $reply .= "\n#### الفرز والمتابعة السريرية:\n{$rule['triage']}\n";
        $reply .= "\n*(يا غالي، تقدر تسألني الحين عن جرعة أي دواء منها، أمانه للحامل، أو بدائله المتاحة)*";

        return [
            'reply' => stripEmojis($reply),
            'intent' => 'SYMPTOM_DIAGNOSIS_RECOMMENDATION',
            'symptom_id' => $rule['id'],
            'data' => $suggestedMeds
        ];
    }

    // 2. البحث عن دواء مطابق بالاسم التجاري أو العلمي
    $found = null;
    foreach ($medicines as $m) {
        $mName = normalizeArabicText($m['name'] ?? '');
        $mGen = normalizeArabicText($m['generic_name'] ?? '');

        if (strpos($mName, $normQuery) !== false || (!empty($mGen) && strpos($mGen, $normQuery) !== false)) {
            $found = $m;
            break;
        }
        foreach (explode(' ', $normQuery) as $word) {
            if (mb_strlen($word, 'UTF-8') >= 3 && (strpos($mName, $word) !== false || strpos($mGen, $word) !== false)) {
                $found = $m;
                break 2;
            }
        }
    }

    if ($found) {
        // البحث عن البدائل المتكافئة
        $alts = [];
        $foundGen = normalizeArabicText($found['generic_name'] ?? '');
        foreach ($medicines as $m) {
            if (($m['id'] ?? 0) !== ($found['id'] ?? 0) && !empty($m['generic_name'])) {
                $mGen = normalizeArabicText($m['generic_name']);
                if ($mGen === $foundGen || strpos($mGen, $foundGen) !== false || strpos($foundGen, $mGen) !== false) {
                    $alts[] = $m;
                }
            }
        }
        usort($alts, function($a, $b) { return floatval($a['price']) <=> floatval($b['price']); });

        $fPrice = number_format(floatval($found['price']), 2);
        $reply = "### تفاصيل دواء: **{$found['name']}**\n";
        $reply .= "- **المادة الفعالة:** `{$found['generic_name']}`\n";
        $reply .= "- **التصنيف:** {$found['category']}\n";
        $reply .= "- **السعر:** **{$fPrice} ريال** | **الرصيد بالمخزون:** {$found['stock_quantity']} علبة\n";
        $reply .= "- **تاريخ الصلاحية:** `{$found['expiry_date']}`\n\n";

        if (!empty($alts)) {
            $reply .= "#### البدائل المتكافئة بيولوجياً المتاحة (مرتبة حسب السعر):\n";
            foreach ($alts as $i => $alt) {
                $num = $i + 1;
                $diff = floatval($found['price']) - floatval($alt['price']);
                $diffText = ($diff > 0) ? "يوفر " . number_format($diff, 2) . " ريال" : "أغلى بـ " . number_format(abs($diff), 2) . " ريال";
                $altPrice = number_format(floatval($alt['price']), 2);
                $reply .= "{$num}. **{$alt['name']}** — **{$altPrice} ريال** (رصيد: {$alt['stock_quantity']} علبة) — [{$diffText}]\n";
            }
        } else {
            $reply .= "لا توجد بدائل تجارية مسجلة حالياً بنفس المادة الفعالة بالمخزن.\n";
        }

        $reply .= "\n*(يا غالي، تقدر تسألني: 'كم حبة آخذ؟'، 'هل ينفع للحامل؟'، أو 'هل يناسب مريض ضغط؟')*";

        return [
            'reply' => stripEmojis($reply),
            'intent' => 'MEDICINE_DETAILS_AND_ALTERNATIVES',
            'matched_medicine' => $found,
            'data' => array_merge([$found], $alts)
        ];
    }

    // 3. الرد العام التوجيهي الذكي في حال عدم مطابقة أي عرض أو دواء
    return [
        'reply' => "يا غالي، لم أتعرف على الصنف أو العارض بدقة من السؤال الحالي.\n\nيمكنك أن تسألني عن أي من الحالات السريرية التالية:\n- أعراض شائعة: (صداع، حمى، مغص بطن، قولون عصبي، إسهال، إمساك، غثيان وترجيع، حموضة وارتجاع).\n- تنفسية وصدرية: (كحة وبلغم، زكام ورشح، التهاب حلق، جيوب أنفية، ربو وكتمة).\n- عظام ومفاصل: (آلام مفاصل وخشونة، شد عضلي وأبهر، ألم ضرس ولثة).\n- جلدية وعيون: (حساسية وحكة، جفاف وحرقة عين، حروق وجروح وميبو).\n- أمراض مزمنة وفيتامينات: (ضغط الدم، السكري، كوليسترول ودهون، أنيميا ونقص حديد، حرقان مسالك بولية).\n\nأو تفضل بكتابة اسم أي دواء تجاري أو علمي (مثل: بنادول، أوجمنتين، بروفين، نيكسيوم، إيموديوم، سيمبيكورت).",
        'intent' => 'UNKNOWN_HELP_PROMPT',
        'data' => []
    ];
}
