<?php
// -*- coding: utf-8 -*-
// نقطة اتصال الشات بوت والمساعد الدوائي الذكي (Smart Pharmacy Chatbot API)
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
            ["id" => 1, "name" => "أوجمنتين 1 جم", "generic_name" => "أموكسيسيلين + كلافولانات", "category" => "مضاد حيوي", "price" => 45.0, "stock_quantity" => 85, "expiry_date" => "2026-11-30"],
            ["id" => 2, "name" => "أموكسيل 500 مجم", "generic_name" => "أموكسيسيلين", "category" => "مضاد حيوي", "price" => 18.0, "stock_quantity" => 120, "expiry_date" => "2027-02-28"],
            ["id" => 3, "name" => "كلافوكس 1 جم", "generic_name" => "أموكسيسيلين + كلافولانات", "category" => "مضاد حيوي", "price" => 32.0, "stock_quantity" => 40, "expiry_date" => "2026-10-15"],
            ["id" => 4, "name" => "ميجاموكس 1 جم", "generic_name" => "أموكسيسيلين + كلافولانات", "category" => "مضاد حيوي", "price" => 28.0, "stock_quantity" => 60, "expiry_date" => "2026-12-10"],
            ["id" => 5, "name" => "بنادول إكسترا 500 مجم", "generic_name" => "باراسيتامول + كافيين", "category" => "مسكن وخافض حرارة", "price" => 12.0, "stock_quantity" => 250, "expiry_date" => "2027-06-15"],
            ["id" => 6, "name" => "باراسيتامول فارما 500 مجم", "generic_name" => "باراسيتامول", "category" => "مسكن وخافض حرارة", "price" => 5.0, "stock_quantity" => 180, "expiry_date" => "2027-08-20"],
            ["id" => 7, "name" => "أدول 500 مجم", "generic_name" => "باراسيتامول", "category" => "مسكن وخافض حرارة", "price" => 7.5, "stock_quantity" => 95, "expiry_date" => "2026-09-30"],
            ["id" => 8, "name" => "بروفين 400 مجم", "generic_name" => "إيبوبروفين", "category" => "مسكن ومضاد للالتهاب", "price" => 15.0, "stock_quantity" => 65, "expiry_date" => "2027-01-10"],
            ["id" => 9, "name" => "فولتارين 50 مجم", "generic_name" => "ديكلوفيناك الصوديوم", "category" => "مسكن ومضاد للالتهاب", "price" => 22.0, "stock_quantity" => 30, "expiry_date" => "2026-08-30"],
            ["id" => 10, "name" => "ديكلوجين 50 مجم", "generic_name" => "ديكلوفيناك الصوديوم", "category" => "مسكن ومضاد للالتهاب", "price" => 11.0, "stock_quantity" => 70, "expiry_date" => "2026-12-05"],
            ["id" => 11, "name" => "أوميبرازول 20 مجم", "generic_name" => "أوميبرازول", "category" => "أدوية الجهاز الهضمي والمعدة", "price" => 20.0, "stock_quantity" => 110, "expiry_date" => "2027-04-15"],
            ["id" => 12, "name" => "جلوكوفاج 500 مجم", "generic_name" => "ميتفورمين", "category" => "أدوية السكري", "price" => 25.0, "stock_quantity" => 140, "expiry_date" => "2027-05-30"]
        ];
    }

    // 2. إرسال الطلب لمحرك الذكاء الاصطناعي (FastAPI Microservice)
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
    curl_setopt($ch, CURLOPT_TIMEOUT, 3); // مهلة استجابة سريعة

    $aiResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $aiResponse) {
        $resData = json_decode($aiResponse, true);
        if (isset($resData['data'])) {
            Response::send(200, true, "تمت الإجابة عبر محرك الذكاء الاصطناعي بنجاح.", $resData['data']);
        }
    }

    // 3. محرك الاستجابة الاحتياطي المحلي في حال عدم اتصال بايثون (Offline Fallback Engine)
    $fallbackResult = localFallbackChat($message, $medicines);
    Response::send(200, true, "تمت الإجابة عبر المساعد الدوائي المحلي بنجاح.", $fallbackResult);
}

function stripEmojis($text) {
    if (!$text) return '';
    // حذف نطاقات الإيموجي من السلاسل النصية
    $clean = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{2300}-\x{23FF}\x{2B50}]/u', '', $text);
    return str_replace(['📋', '💊', '⭐️', '💡', '⚠️', '🩺', '⭐', '🚨', '✅', '❌'], '', $clean);
}

function localFallbackChat($query, $medicines) {
    $q = mb_strtolower(trim($query), 'UTF-8');
    
    // فحص التحية
    if (strpos($q, 'مرحبا') !== false || strpos($q, 'اهلا') !== false || strpos($q, 'السلام') !== false) {
        return [
            'reply' => "أهلاً بك. المساعد الدوائي متصل بقاعدة بيانات الصيدلية للإجابة عن أسعار الأدوية، توفر المخزون، والبدائل، واقتراح الأدوية المناسبة لحالات وأعراض المرضى (مثل الصداع، الحمى، حموضة المعدة، الكحة، آلام المفاصل والأسنان).",
            'intent' => 'GREETING',
            'data' => []
        ];
    }

    // 1. فحص الأعراض السريرية الشائعة
    $symptomRules = [
        [
            'keywords' => ['صداع', 'راس', 'رأس', 'شقيقة', 'شقيقه', 'مصدع'],
            'title' => 'الصداع والصداع النصفي والآلام العامة',
            'generics' => ['باراسيتامول', 'إيبوبروفين', 'ديكلوفيناك'],
            'advice' => 'تناول قرص باراسيتامول أو بنادول بعد الأكل مع شرب الماء. في حال الصداع الشديد يمكن استخدام بروفين أو رابيدوس.'
        ],
        [
            'keywords' => ['حرارة', 'حراره', 'سخونة', 'سخونه', 'حمى', 'حمي', 'مكسر'],
            'title' => 'الحمى وارتفاع درجة الحرارة وتكسير الجسم',
            'generics' => ['باراسيتامول', 'إيبوبروفين'],
            'advice' => 'تناول الباراسيتامول كخافض حرارة آمن كل 6-8 ساعات مع كمادات الماء الفاتر وتناول السوائل بكثرة.'
        ],
        [
            'keywords' => ['حموضة', 'حموضه', 'حرقان', 'ارتجاع', 'قرحة', 'قرحه', 'معدة', 'معده'],
            'title' => 'حموضة وحرقة المعدة والارتجاع المريئي',
            'generics' => ['أوميبرازول', 'بانتوبرازول'],
            'advice' => 'تناول كبسولة أوميبرازول 20 مجم صباحاً قبل الإفطار بنصف ساعة مع تجنب الأطعمة الدسمة والحارة.'
        ],
        [
            'keywords' => ['كحة', 'كحه', 'سعال', 'بلغم', 'رشح', 'زكام', 'انفلونزا', 'حلق'],
            'title' => 'السعال والرشح والزكام والتهاب الحلق',
            'generics' => ['باراسيتامول', 'أزيثرومايسين', 'أموكسيسيلين', 'سيتريزين'],
            'advice' => 'استخدام الباراسيتامول لتسكين الحلق والحرارة ومضادات الهيستامين للرشح مع المشروبات الدافئة.'
        ],
        [
            'keywords' => ['مفاصل', 'ركبة', 'ركبه', 'روماتيزم', 'عظام', 'ظهر', 'فقرات', 'خشونة'],
            'title' => 'آلام المفاصل والفقرات والروماتيزم',
            'generics' => ['سيليكوكسيب', 'ديكلوفيناك', 'ميلوكسيكام', 'إيبوبروفين'],
            'advice' => 'استخدام مضادات الالتهاب مثل سيلبركس أو فولتارين بعد الوجبات مباشرة لتخفيف التيبس والالتهاب.'
        ],
        [
            'keywords' => ['اسنان', 'أسنان', 'ضرس', 'لثة', 'لثه', 'خراج'],
            'title' => 'ألم الأسنان والتهاب اللثة',
            'generics' => ['ديكلوفيناك', 'إيبوبروفين', 'أموكسيسيلين + كلافولانات'],
            'advice' => 'تناول مسكن سريع مثل كتافلام أو بروفين بعد الأكل مع زيارة طبيب الأسنان.'
        ]
    ];

    foreach ($symptomRules as $rule) {
        $matched = false;
        foreach ($rule['keywords'] as $kw) {
            if (mb_stripos($q, $kw, 0, 'UTF-8') !== false) {
                $matched = true;
                break;
            }
        }

        if ($matched) {
            $suggestedMeds = [];
            foreach ($medicines as $m) {
                foreach ($rule['generics'] as $gen) {
                    if (mb_stripos($m['generic_name'] ?? '', $gen, 0, 'UTF-8') !== false || mb_stripos($m['name'] ?? '', $gen, 0, 'UTF-8') !== false) {
                        $suggestedMeds[] = $m;
                        break;
                    }
                }
            }
            usort($suggestedMeds, function($a, $b) {
                return ($b['stock_quantity'] > 0 <=> $a['stock_quantity'] > 0) ?: ($a['price'] <=> $b['price']);
            });

            $reply = "### 📋 التقييم السريري ودواعي الاستخدام:\n";
            $reply .= "**الحالة:** {$rule['title']}\n\n";
            $reply .= "#### 💊 الأدوية المقترحة المتوفرة حالياً في الصيدلية (مرتبة من الأوفر):\n";
            foreach (array_slice($suggestedMeds, 0, 5) as $idx => $med) {
                $num = $idx + 1;
                $star = ($idx === 0) ? " [الخيار الموصى به أولاً]" : "";
                $reply .= "{$num}. **{$med['name']}**{$star}\n";
                $reply .= "   - المادة الفعالة: `{$med['generic_name']}` | السعر: **{$med['price']} ريال** | الرصيد: `{$med['stock_quantity']}` علبة\n";
            }
            $reply .= "\n#### 🩺 إرشادات الاستخدام:\n{$rule['advice']}\n";

            return [
                'reply' => stripEmojis($reply),
                'intent' => 'SYMPTOM_DIAGNOSIS_RECOMMENDATION',
                'data' => $suggestedMeds
            ];
        }
    }

    // 2. البحث عن دواء مطابق بالاسم
    $found = null;
    foreach ($medicines as $m) {
        if (mb_stripos($m['name'], $q, 0, 'UTF-8') !== false || ($m['generic_name'] && mb_stripos($m['generic_name'], $q, 0, 'UTF-8') !== false)) {
            $found = $m;
            break;
        }
        foreach (explode(' ', $q) as $word) {
            if (mb_strlen($word, 'UTF-8') >= 3 && mb_stripos($m['name'], $word, 0, 'UTF-8') !== false) {
                $found = $m;
                break 2;
            }
        }
    }

    if ($found) {
        // البحث عن البدائل
        $alts = [];
        foreach ($medicines as $m) {
            if ($m['id'] !== $found['id'] && $m['generic_name'] && $m['generic_name'] === $found['generic_name']) {
                $alts[] = $m;
            }
        }
        usort($alts, function($a, $b) { return $a['price'] <=> $b['price']; });

        $reply = "### تفاصيل دواء: **{$found['name']}**\n";
        $reply .= "- **المادة الفعالة**: `{$found['generic_name']}`\n";
        $reply .= "- **السعر**: **{$found['price']} ريال** | **الرصيد بالمخزون**: {$found['stock_quantity']} علبة\n";
        $reply .= "- **تاريخ الصلاحية**: `{$found['expiry_date']}`\n\n";

        if (!empty($alts)) {
            $reply .= "#### البدائل المتكافئة المتاحة (مرتبة من الأرخص للأعلى):\n";
            foreach ($alts as $i => $alt) {
                $num = $i + 1;
                $diff = $found['price'] - $alt['price'];
                $saveText = ($diff > 0) ? "يوفر {$diff} ريال" : "أغلى بـ " . abs($diff) . " ريال";
                $reply .= "{$num}. **{$alt['name']}** — **{$alt['price']} ريال** (رصيد: {$alt['stock_quantity']}) — [{$saveText}]\n";
            }
        }

        return [
            'reply' => stripEmojis($reply),
            'intent' => 'PRICE_COMPARISON',
            'matched_medicine' => $found,
            'data' => array_merge([$found], $alts)
        ];
    }

    return [
        'reply' => "لم أتمكن من العثور على الدواء المطلوب بدقة. يمكنك سؤالي عن أعراض مريض (مثل: 'دواء للصداع'، 'علاج لحموضة المعدة'، 'مسكن لآلام الأسنان') أو كتابة اسم الدواء بوضوح (مثل: بنادول، أوجمنتين، بروفين).",
        'intent' => 'UNKNOWN',
        'data' => []
    ];
}
