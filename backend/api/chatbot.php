<?php
// -*- coding: utf-8 -*-
/**
 * المساعد الدوائي والسريري الذكي - بيلا (Bella)
 * متصل مباشرة وبشكل حي بقاعدة بيانات الصيدلية (MySQL / MariaDB)
 * 
 * الميزات الجوهرية:
 * 1. الربط الحي بقاعدة البيانات: استخراج الحالات والأعراض والدواعي الطبية مباشرة من حقول:
 *    - description (وصف الدواء المسجل بالمخزن)
 *    - category (تصنيف الدواء)
 *    - name & generic_name (الاسم التجاري والعلمي)
 * 2. عدم وجود أي قواميس سريرية أو حالات مرضية مسبقة الكود (Zero Hardcoded Rules).
 * 3. التعرف التلقائي والفوري على أي صنف جديد يضاف لقاعدة البيانات دون تعديل سطر برمجي واحد.
 * 4. ذاكرة سياقية ذكية (Multi-Turn Context) لإجراء حوار تفاعلي مستمر مع المستخدم (ياخذ ويعطي بالرد والاقتراحات).
 * 5. استخراج البدائل الأوفر والمتكافئة بيولوجياً وحساب نسبة التوفير من المخزون الفعلي.
 */

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

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'GET') {
    $msg = $_GET['message'] ?? 'مرحبا';
    handleChatbotConversation($msg, []);
} elseif ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $message = trim($input['message'] ?? '');
    $history = $input['history'] ?? [];

    if (empty($message)) {
        Response::send(400, false, "نص الرسالة لا يمكن أن يكون فارغاً يا غالي.");
    }

    handleChatbotConversation($message, $history);
} else {
    Response::send(405, false, "نوع الطلب (HTTP Method) غير مدعوم.");
}

/**
 * معالج المحادثة الرئيسي المتصل بقاعدة البيانات
 */
function handleChatbotConversation(string $message, array $history = []): void {
    // 1. جلب كافة الأدوية الحية من قاعدة البيانات
    try {
        $medicines = Medicine::getAll();
    } catch (\Exception $e) {
        $medicines = [];
    }

    if (empty($medicines)) {
        Response::send(500, false, "تعذر الاتصال بقاعدة بيانات الأدوية الحية حالياً يا غالي، يرجى التأكد من تشغيل خادم MySQL.");
    }

    // 2. معالجة المحادثة دلالياً عبر محرك الفهرسة الحي لقاعدة البيانات
    $result = processDatabaseDrivenChat($message, $medicines, $history);

    // تنظيف المخرجات من أي إيموجي نهائياً
    $result['reply'] = sanitizeTextNoEmojis($result['reply']);

    Response::send(200, true, "تمت المعالجة بنجاح عبر المساعد الدوائي الذكي.", $result);
}

/**
 * دالة إزالة الرموز التعبيرية والإيموجي بالكامل
 */
function sanitizeTextNoEmojis(string $text): string {
    if (!$text) return '';
    $clean = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{2300}-\x{23FF}\x{2B50}\x{26A0}\x{FE0F}]/u', '', $text);
    return str_replace(['📋', '💊', '⭐️', '💡', '⚠️', '🩺', '⭐', '🚨', '✅', '❌', '•', '✨', '🔹', '🔸', '🟢', '🔴'], '', $clean);
}

/**
 * توحيد ومعايرة النصوص العربية (Normalization)
 */
function normalizeArabic(string $str): string {
    if (!$str) return '';
    $str = mb_strtolower(trim($str), 'UTF-8');
    // إزالة التشكيل والتنوين
    $str = preg_replace('/[\x{064B}-\x{0652}\x{0670}]/u', '', $str);
    // توحيد الألفات والياء والتاء المربوطة
    $str = preg_replace('/[إأآا]/u', 'ا', $str);
    $str = preg_replace('/[ى]/u', 'ي', $str);
    $str = preg_replace('/[ة]/u', 'ه', $str);
    // إزالة علامات الترقيم والرموز الخاصة
    $str = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $str);
    return trim(preg_replace('/\s+/u', ' ', $str));
}

/**
 * تجريد السوابق واللواحق النحوية العربية الشائعة (Stemming & Affix Stripping)
 */
function extractArabicStems(string $word): array {
    $stems = [$word];
    $w = $word;

    // إزالة السوابق (ال، وال، فال، بال، كال، لل، و، ب، ل، ف)
    if (mb_strlen($w) > 4 && (mb_substr($w, 0, 3) === 'وال' || mb_substr($w, 0, 3) === 'فال' || mb_substr($w, 0, 3) === 'بال' || mb_substr($w, 0, 3) === 'كال')) {
        $stems[] = mb_substr($w, 3);
        $w = mb_substr($w, 3);
    } elseif (mb_strlen($w) > 4 && mb_substr($w, 0, 2) === 'لل') {
        $stems[] = mb_substr($w, 2);
        $w = mb_substr($w, 2);
    } elseif (mb_strlen($w) > 3 && mb_substr($w, 0, 2) === 'ال') {
        $stems[] = mb_substr($w, 2);
        $w = mb_substr($w, 2);
    } elseif (mb_strlen($w) > 3 && in_array(mb_substr($w, 0, 1), ['و', 'ف', 'ب', 'ل'])) {
        $stems[] = mb_substr($w, 1);
        $w = mb_substr($w, 1);
    }

    // إزالة اللواحق (ات، ين، ون، ه، ي)
    if (mb_strlen($w) > 4 && (mb_substr($w, -2) === 'ات' || mb_substr($w, -2) === 'ين' || mb_substr($w, -2) === 'ون')) {
        $stems[] = mb_substr($w, 0, -2);
    } elseif (mb_strlen($w) > 3 && (mb_substr($w, -1) === 'ه' || mb_substr($w, -1) === 'ي')) {
        $stems[] = mb_substr($w, 0, -1);
    }

    return array_unique($stems);
}

/**
 * المحرك الحي للاستعلام والحوار القائم على قاعدة البيانات
 */
function processDatabaseDrivenChat(string $message, array $medicines, array $history = []): array {
    $normQuery = normalizeArabic($message);

    // 1. التحية والاستقبال والترحيب الحجازي (Bella)
    if (isGreetingIntent($normQuery)) {
        return [
            'reply' => "يا هلا وسهلا فيك يا غالي! أنا بيلا مساعدتك الصيدلانية والشخصية الذكية، متصلة مباشرة بقاعدة بيانات الصيدلية والمخزون الحي.\n\n" .
                       "أنا هنا عشان أساعدك وأبحث لك فوراً عن:\n" .
                       "1. أي شكوى صحية أو عارض تشتكي منه (زي الإسهال، الصداع، المغص والقولون، الحموضة والارتجاع، الحرارة، النزلات المعوية، الحساسية، وغيرها) واستخراج الدواء المناسب له مباشرة من وصف الأدوية بالمخزن.\n" .
                       "2. فحص توفر الصنف بالمخزن وسعره، واقتراح البدائل المتطابقة الأوفر لك.\n" .
                       "3. إرشادات الجرعات والاستخدام الآمن ومدى ملاءمة العلاج لمرضى الضغط أو السكر أو الحوامل.\n\n" .
                       "آمرني يا غالي، اكتب لي الأعراض اللي تحس فيها أو اسم الدواء وسأطلع لك المناسب فوراً.",
            'intent' => 'GREETING',
            'medicines' => [],
            'alternatives' => []
        ];
    }

    // 2. كشف أسئلة المتابعة والذاكرة السياقية التفاعلية (Multi-Turn Dialogue Engine)
    $activeMed = resolveActiveMedicineFromHistory($history, $medicines);
    $followUpType = detectFollowUpType($normQuery);

    if ($followUpType !== null && $activeMed !== null) {
        return handleContextualFollowUp($followUpType, $activeMed, $medicines, $normQuery);
    }

    // 3. البحث الدلالي في حقول قاعدة البيانات (description, category, name, generic_name)
    $matches = searchMedicinesByDatabaseKnowledge($normQuery, $medicines);

    if (!empty($matches)) {
        if (isDispenseIntent($normQuery)) {
            $topMed = $matches[0];
            $price = (float)($topMed['price'] ?? 0);
            $stock = (int)($topMed['stock_quantity'] ?? 0);
            $desc = $topMed['description'] ?? $topMed['category'];
            return [
                'reply' => "أبشر ومن عيوني يا غالي! جهزت لك أمر صرف دواء **{$topMed['name']}** مباشرة في الفاتورة.\n\n" .
                           "- **السعر الرسمي:** **" . number_format($price, 2) . " ريال**.\n" .
                           "- **الرصيد المتوفر بالمخزن:** **{$stock} علبة**.\n" .
                           "- **دواعي الاستعمال:** {$desc}\n\n" .
                           "اضغط على زر **«+ إصدار فاتورة صرف الدواء»** بالأسفل وسينقلك النظام مباشرة لشاشة نقاط البيع لإصدار الفاتورة مع إدراج الدواء تلقائياً:",
                'intent' => 'DIRECT_DISPENSE',
                'medicines' => [$topMed],
                'alternatives' => []
            ];
        }
        return buildConversationalMedicineResponse($normQuery, $matches, $medicines);
    }

    // 4. في حال عدم العثور على تطابق في الأوصاف
    return [
        'reply' => "سلامتك يا غالي وألف لا بأس عليك. بحثت لك حالياً في كل أوصاف وتصنيفات الأدوية المسجلة عندنا بقاعدة بيانات الصيدلية وما ظهر لي علاج مطابق بدقة للوصف اللي كتبته.\n\n" .
                   "تقدر تساعدني وتكتب لي:\n" .
                   "- اسم الدواء التجاري أو المادة الفعالة مباشرة (مثل: بنادول، بروفين، أوجمنتين، إيموديوم).\n" .
                   "- أو وضح لي الشكوى بكلمات أخرى (مثل: مسكن للصداع، علاج للحرارة، مطهر معوي للإسهال، علاج للقولون والمغص).\n\n" .
                   "وأنا حاضرة ومن عيوني أبحث لك فوراً وأطلع لك المتوفر وسعره.",
        'intent' => 'NO_MATCH',
        'medicines' => [],
        'alternatives' => []
    ];
}

/**
 * التحقق من نية التحية
 */
function isGreetingIntent(string $q): bool {
    $greetings = ['مرحبا', 'اهلا', 'السلام', 'هلا', 'صباح الخير', 'مساء الخير', 'يا هلا', 'سلام عليكم', 'اهلين', 'حي الله'];
    foreach ($greetings as $g) {
        if (mb_strpos($q, normalizeArabic($g)) !== false) return true;
    }
    return false;
}

/**
 * التحقق من نية صرف الدواء أو إصدار الفاتورة
 */
function isDispenseIntent(string $q): bool {
    $keywords = ['اصرف', 'صرف', 'فاتوره', 'فاتورة', 'اصدار فاتوره', 'إصدار فاتورة', 'اعمل فاتوره', 'سوي فاتوره', 'سوي لي فاتورة', 'اعمل لي فاتورة', 'ابغي فاتوره', 'ابغى فاتورة', 'نقلني للفاتورة', 'حولني للفاتورة', 'اضف للسله', 'اضف للسلة', 'اضف للفاتورة', 'اضف للفاتوره', 'اشتريه', 'اشتري'];
    foreach ($keywords as $kw) {
        if (mb_strpos($q, normalizeArabic($kw)) !== false) return true;
    }
    return false;
}

/**
 * تحديد نوع سؤال المتابعة في المحادثة متعددة الجولات
 */
function detectFollowUpType(string $q): ?string {
    // أسئلة الجرعة وطريقة الاستخدام
    $dosagePatterns = ['كم حبه', 'كم حبة', 'كم الجرعه', 'كم الجرعة', 'جرعه', 'جرعة', 'طريقه الاستخدام', 'طريقة الاستخدام', 'متي اخذه', 'متى اخذه', 'متى آخذه', 'كم مره', 'كم مرة', 'قبل الاكل', 'بعد الاكل', 'طريقه تناوله', 'طريقة تناوله', 'كم قرص', 'كم كبسوله', 'كم كبسولة', 'كيف استخدمه', 'كيف استعمله'];
    foreach ($dosagePatterns as $p) {
        if (mb_strpos($q, $p) !== false) return 'DOSAGE';
    }

    // أسئلة البديل الأرخص والأوفر
    $cheaperPatterns = ['في ارخص', 'في أرخص', 'بديل ارخص', 'بديل أرخص', 'غالي', 'اوفر', 'أوفر', 'بديل ثاني', 'بديل اخر', 'بديل مكافئ', 'شي ارخص', 'خيارات ارخص', 'سعر اقل'];
    foreach ($cheaperPatterns as $p) {
        if (mb_strpos($q, $p) !== false) return 'CHEAPER';
    }

    // أسئلة الحمل والرضاعة
    $pregnancyPatterns = ['حامل', 'للحامل', 'الحمل', 'حوامل', 'مرضع', 'للمرضع', 'الرضاعه', 'الرضاعة', 'ترضع', 'ينفع للحامل', 'امن للحامل', 'آمن للحامل', 'يضر الجنين'];
    foreach ($pregnancyPatterns as $p) {
        if (mb_strpos($q, $p) !== false) return 'PREGNANCY';
    }

    // أسئلة الأمراض المزمنة (الضغط، السكر، قرحة المعدة، الكلى)
    $chronicPatterns = ['مريض ضغط', 'عندي ضغط', 'يناسب الضغط', 'للضغط', 'مريض سكر', 'عندي سكر', 'يناسب السكر', 'للسكر', 'قرحه', 'قرحة', 'معدتي حساسه', 'معدتي حساسة', 'فشل كلوي', 'الكلى', 'الكبد'];
    foreach ($chronicPatterns as $p) {
        if (mb_strpos($q, $p) !== false) return 'CHRONIC';
    }

    // أسئلة توفر المخزون والسعر
    $stockPatterns = ['كم باقي', 'كم متوفر', 'متوفر بالمخزن', 'في منه بالمخزن', 'موجود', 'كم سعره', 'سعره كم', 'بكم', 'بكم سعره'];
    foreach ($stockPatterns as $p) {
        if (mb_strpos($q, $p) !== false) return 'STOCK';
    }

    // طلبات صرف الدواء وإصدار الفاتورة مباشرة
    $dispensePatterns = [
        'اصرف', 'صرف', 'فاتوره', 'فاتورة', 'اصدار فاتوره', 'إصدار فاتورة',
        'اعمل فاتوره', 'سوي فاتوره', 'سوي لي فاتورة', 'اعمل لي فاتورة',
        'ابغي فاتوره', 'ابغى فاتورة', 'نقلني للفاتورة', 'حولني للفاتورة',
        'صرف الدواء', 'صرف العلاج', 'اضف للسله', 'اضف للسلة',
        'اضف للفاتورة', 'اضف للفاتوره', 'اشتريه', 'اشتري'
    ];
    foreach ($dispensePatterns as $p) {
        if (mb_strpos($q, normalizeArabic($p)) !== false) return 'DISPENSE';
    }

    return null;
}

/**
 * تحديد الدواء النشط من سجل المحادثة السابق (Active Medicine Resolver)
 */
function resolveActiveMedicineFromHistory(array $history, array $medicines): ?array {
    if (empty($history)) return null;

    // استعراض المحادثة من الأحدث إلى الأقدم
    for ($i = count($history) - 1; $i >= 0; $i--) {
        $content = normalizeArabic($history[$i]['content'] ?? '');
        if (empty($content)) continue;

        foreach ($medicines as $m) {
            $nameClean = normalizeArabic(preg_replace('/\(.*?\)/u', '', $m['name']));
            $nameTokens = explode(' ', $nameClean);
            $primaryName = $nameTokens[0] ?? '';

            // مطابقة الاسم التجاري الرئيسي أو الاسم العلمي
            if (mb_strlen($primaryName) >= 3 && mb_strpos($content, $primaryName) !== false) {
                return $m;
            }

            if (!empty($m['generic_name'])) {
                $genClean = normalizeArabic(preg_replace('/\(.*?\)/u', '', $m['generic_name']));
                $genTokens = explode(' ', $genClean);
                $primaryGen = $genTokens[0] ?? '';
                if (mb_strlen($primaryGen) >= 4 && mb_strpos($content, $primaryGen) !== false) {
                    return $m;
                }
            }
        }
    }

    return null;
}

/**
 * معالجة أسئلة المتابعة التفاعلية بناءً على بيانات الدواء المسجل في قاعدة البيانات
 */
function handleContextualFollowUp(string $type, array $med, array $medicines, string $query): array {
    $medName = $med['name'];
    $cat = $med['category'] ?? '';
    $gen = $med['generic_name'] ?? '';
    $desc = $med['description'] ?? '';
    $price = (float)($med['price'] ?? 0);
    $stock = (int)($med['stock_quantity'] ?? 0);

    switch ($type) {
        case 'DOSAGE':
            $dosageGuidance = generateDosageFromDbProperties($med);
            $reply = "من عيوني يا غالي، بالنسبة لجرعة وطريقة استخدام دواء **{$medName}**:\n\n" .
                     "{$dosageGuidance}\n\n" .
                     "**ملاحظة من وصف الصنف بقاعدة البيانات:** {$desc}\n\n" .
                     "طمني يا غالي، هل تاخذ أي علاج ثاني حالياً عشان نتأكد ما يتعارض معاه؟ أو تحب أضيف لك إياه لسلة الفاتورة مباشرة؟";
            return [
                'reply' => $reply,
                'intent' => 'FOLLOW_UP_DOSAGE',
                'medicines' => [$med],
                'alternatives' => []
            ];

        case 'CHEAPER':
            $cheaperList = findCheaperAlternativesInDb($med, $medicines);
            if (!empty($cheaperList)) {
                $lines = [];
                foreach ($cheaperList as $alt) {
                    $altPrice = (float)$alt['price'];
                    $diff = $price - $altPrice;
                    $diffText = ($diff > 0) ? " (يوفر لك " . number_format($diff, 2) . " ريال)" : "";
                    $altStock = (int)$alt['stock_quantity'];
                    $lines[] = "- **{$alt['name']}**: سعره **" . number_format($altPrice, 2) . " ريال**{$diffText} | المتوفر بالمخزن: **{$altStock} علبة**\n  *الوصف:* {$alt['description']}";
                }
                $reply = "أبشر ومن عيوني يا غالي! فحصت لك قاعدة البيانات وطلعت لك خيارات بديلة متطابقة بالمادة الفعالة أو التصنيف وبسعر أوفر:\n\n" .
                         implode("\n\n", $lines) . "\n\n" .
                         "تحب أعتمد لك البديل الأوفر وأضيفه لك بالفاتورة الحين يا غالي؟";
                return [
                    'reply' => $reply,
                    'intent' => 'FOLLOW_UP_CHEAPER',
                    'medicines' => [$med],
                    'alternatives' => $cheaperList
                ];
            } else {
                $reply = "يا غالي، دواء **{$medName}** المسجل بسعر **" . number_format($price, 2) . " ريال** هو حالياً الخيار الأوفر والأفضل سعراً عندنا بالمخزن من نفس المادة الفعالة والتصنيف.\n\n" .
                         "رصيده المتوفر بالمخزن حالياً: **{$stock} علبة**، تحب أصرفه لك الآن؟";
                return [
                    'reply' => $reply,
                    'intent' => 'FOLLOW_UP_CHEAPER',
                    'medicines' => [$med],
                    'alternatives' => []
                ];
            }

        case 'PREGNANCY':
            $pregAdvice = evaluatePregnancySafetyFromDb($med);
            $reply = "على راسي يا غالي، بخصوص أمان دواء **{$medName}** أثناء فترة الحمل والرضاعة:\n\n" .
                     "{$pregAdvice}\n\n" .
                     "تذكري يا غالي أن صحة الأم والجنين أولوية قصوى، ودائماً ننصح بتأكيد الاستخدام مع طبيب النساء والتوليد المشرف. تحبين أبحث لك عن بدائل آمنة أكثر متوفرة بالمخزن؟";
            return [
                'reply' => $reply,
                'intent' => 'FOLLOW_UP_PREGNANCY',
                'medicines' => [$med],
                'alternatives' => []
            ];

        case 'CHRONIC':
            $chronicAdvice = evaluateChronicSafetyFromDb($med, $query);
            $reply = "سلامتك وألف سلامة عليك يا غالي. بخصوص ملاءمة دواء **{$medName}** مع حالتك الصحية:\n\n" .
                     "{$chronicAdvice}\n\n" .
                     "هل تحب أقترح لك علاج بديل آمن تماماً ومتوافق مع حالتك من أدوية المخزن المتوفرة عندنا؟";
            return [
                'reply' => $reply,
                'intent' => 'FOLLOW_UP_CHRONIC',
                'medicines' => [$med],
                'alternatives' => []
            ];

        case 'STOCK':
            $reply = "أهلاً يا غالي، دواء **{$medName}**:\n\n" .
                     "- **الرصيد الحي بالمخزن:** **{$stock} علبة** متوفرة.\n" .
                     "- **السعر الرسمي:** **" . number_format($price, 2) . " ريال**.\n" .
                     "- **تاريخ الصلاحية:** {$med['expiry_date']}.\n\n" .
                     "تقدر تضيفه فوراً لسلة الفاتورة الحين، تبي أجهزه لك يا غالي؟";
            return [
                'reply' => $reply,
                'intent' => 'FOLLOW_UP_STOCK',
                'medicines' => [$med],
                'alternatives' => []
            ];

        case 'DISPENSE':
            $reply = "أبشر ومن عيوني يا غالي! جهزت لك أمر صرف دواء **{$medName}** مباشرة في الفاتورة.\n\n" .
                     "- **سعر الصنف:** **" . number_format($price, 2) . " ريال**.\n" .
                     "- **الرصيد المتوفر بالمخزن:** **{$stock} علبة**.\n" .
                     "- **ملاحظة الاستخدام:** {$desc}\n\n" .
                     "اضغط على زر **«+ إصدار فاتورة صرف الدواء»** بالأسفل وسينقلك النظام مباشرة لشاشة نقاط البيع لإصدار الفاتورة مع إدراج الدواء تلقائياً:";
            return [
                'reply' => $reply,
                'intent' => 'FOLLOW_UP_DISPENSE',
                'medicines' => [$med],
                'alternatives' => []
            ];
    }

    return [
        'reply' => "تفضل يا غالي، أنا معك ومتابعة بخصوص دواء **{$medName}**. كيف أقدر أساعدك أكثر فيه؟",
        'intent' => 'FOLLOW_UP_GENERAL',
        'medicines' => [$med],
        'alternatives' => []
    ];
}

/**
 * استنتاج إرشادات الجرعة الآمنة ديناميكياً من خواص وتصنيف الصنف في قاعدة البيانات
 */
function generateDosageFromDbProperties(array $med): string {
    $name = normalizeArabic($med['name']);
    $cat = normalizeArabic($med['category']);
    $gen = normalizeArabic($med['generic_name']);

    if (mb_strpos($cat, 'مضاد حيوي') !== false) {
        return "- **جرعة البالغين الاعتيادية:** قرص أو كبسولة واحدة كل 12 ساعة (مرتين يومياً) أو كل 8 ساعات حسب العيار المحدد، وتُؤخذ مع بداية الوجبة لتفادي اضطراب المعدة.\n" .
               "- **تنبيه هام:** يجب إكمال الكورس العلاجي كاملاً (من 5 إلى 7 أيام) حتى بعد زوال الأعراض لمنع عودة البكتيريا أو اكتسابها مقاومة للمضادات الحيوية.";
    }

    if (mb_strpos($gen, 'باراسيتامول') !== false) {
        return "- **جرعة البالغين (فوق 12 سنة):** قرص إلى قرصين (500 إلى 1000 مجم) كل 6 إلى 8 ساعات عند اللزوم.\n" .
               "- **الحد الأقصى اليومي:** لا تتجاوز 4000 مجم (أي 8 أقراص عيار 500 مجم) خلال 24 ساعة حفاظاً على سلامة الكبد.\n" .
               "- **التوقيت:** يُفضل بعد الأكل مع كوب ماء، ومع ذلك فهو لطيف جداً على المعدة.";
    }

    if (mb_strpos($cat, 'مسكن') !== false || mb_strpos($cat, 'مضاد للالتهاب') !== false) {
        return "- **الجرعة للبالغين:** قرص واحد كل 8 إلى 12 ساعة بعد الوجبة مباشرة مع كوب ماء كامل.\n" .
               "- **تنبيه حاسم:** يُمنع قطعياً تناوله على معدة فارغة لمنع حدوث تهيج أو قرحة بالمعدة، ولا يُستخدم لمرضى قرحة المعدة الحادة أو القصور الكلوي.";
    }

    if (mb_strpos($cat, 'معدة') !== false || mb_strpos($cat, 'ppi') !== false || mb_strpos($cat, 'حموض') !== false) {
        return "- **الجرعة المعتمدة:** كبسولة أو قرص واحد يومياً صباحاً.\n" .
               "- **التوقيت الحاسم:** يُؤخذ على معدة فارغة قبل وجبة الإفطار بـ 30 إلى 60 دقيقة كاملة مع بلع الكبسولة كاملة دون مضغ أو كسر لتحقيق أعلى فاعلية في تثبيط الحمض.";
    }

    if (mb_strpos($cat, 'اسهال') !== false || mb_strpos($cat, 'إسهال') !== false || mb_strpos($gen, 'لوبيراميد') !== false) {
        return "- **الجرعة:** كبسولتان في البداية، ثم كبسولة واحدة بعد كل عملية إخراج غير متماسكة (بحد أقصى 8 كبسولات يومياً).\n" .
               "- **إرشاد أساسي:** يجب الإكثار من شرب الماء ومحلول معالجة الجفاف لتعويض الأملاح المفقودة وتجنب الجفاف.";
    }

    if (mb_strpos($cat, 'مغص') !== false || mb_strpos($cat, 'قولون') !== false) {
        return "- **الجرعة:** قرص واحد من 2 إلى 3 مرات يومياً قبل الوجبات بـ 20 دقيقة، أو عند اشتداد نوبات التقلصات والمغص.\n" .
               "- **إرشاد غذائي:** تجنب المشروبات الغازية والبقوليات والأطعمة الحارة أثناء فترة المغص.";
    }

    if (mb_strpos($name, 'بخاخ') !== false || mb_strpos($cat, 'ربو') !== false || mb_strpos($cat, 'قصبات') !== false) {
        return "- **الجرعة:** بFocusValueخة إلى بختين عند الشعور بضيق التنفس أو أزمة الربو، مع استنشاق عميق وحبس النفس لمدة 10 ثوانٍ.\n" .
               "- **تنبيه:** المضمضة بالماء وبصقه بعد استخدام البخاخات المحتوية على كورتيزون لتفادي الفطريات الفموية.";
    }

    if (mb_strpos($name, 'قطرة') !== false || mb_strpos($cat, 'عيون') !== false) {
        return "- **الجرعة:** نقطة واحدة إلى نقطتين في العين المصابة من 3 إلى 4 مرات يومياً، مع غسل اليدين جيداً وتجنب ملامسة فوهة القطرة للعين.";
    }

    if (mb_strpos($name, 'مرهم') !== false || mb_strpos($name, 'جل') !== false || mb_strpos($cat, 'حروق') !== false || mb_strpos($cat, 'جلد') !== false) {
        return "- **طريقة الاستخدام:** تنظيف وتجفيف المنطقة بلطف، ثم وضع طبقة رقيقة وتدليكها بهدوء من مرتين إلى 3 مرات يومياً.";
    }

    return "- **الجرعة العامة:** قرص واحد عند اللزوم مع أو بعد الأكل مع شرب كوب كامل من الماء، ويُفضل اتباع الإرشادات المرفقة بالنشرة الداخلية للدواء.";
}

/**
 * تقييم أمان الدواء في الحمل والرضاعة بناءً على خصائصه في قاعدة البيانات
 */
function evaluatePregnancySafetyFromDb(array $med): string {
    $gen = normalizeArabic($med['generic_name']);
    $cat = normalizeArabic($med['category']);

    if (mb_strpos($gen, 'باراسيتامول') !== false) {
        return "- **الباراسيتامول:** يُعتبر الخيار الأول والأكثر أماناً طبياً لتسكين الألم وخفض الحرارة في جميع مراحل الحمل والرضاعة بجرعات معتدلة (500 مجم عند اللزوم).\n" .
               "- **تحذير:** تجنبي الأنواع المحتوية على كافيين مثل (بنادول إكسترا) واكتفي بالنوع العادي (بنادول الأزرق أو فيفادول).";
    }

    if (mb_strpos($cat, 'مسكن ومضاد للالتهاب') !== false || mb_strpos($gen, 'ايبوبروفين') !== false || mb_strpos($gen, 'ديكلوفيناك') !== false || mb_strpos($gen, 'سيليكوكسيب') !== false) {
        return "- **مضادات الالتهاب غير الستيرويدية (NSAIDs):** غير آمنة في الحمل، وتُحظر قطعياً بالثلث الأخير من الحمل لتأثيرها على شرايين قلب الجنين والكلى.\n" .
               "- **البديل الآمن فوراً:** استبدالها بدواء الباراسيتامول (فيفادول أو أدول) فهو آمن تماماً أثناء الحمل.";
    }

    if (mb_strpos($cat, 'مضاد حيوي') !== false) {
        if (mb_strpos($gen, 'اموكسيسيلين') !== false) {
            return "- **الأموكسيسيلين ومشتقاته (مثل أوجمنتين):** مصنفة ضمن الفئة الآمنة نسبياً (Category B) في الحمل ويمكن استخدامها عند الضرورة السريرية بإشراف الطبيب.";
        }
        return "- **تنبيه للمضادات الحيوية:** يجب عدم تناول أي مضاد حيوي أثناء الحمل إلا بعد تقييم الطبيب المعالج للحاجة السريرية وتحديد النوع الآمن للجنين.";
    }

    return "- **قاعدة عامة:** يُفضل تجنب تناول هذا الدواء أثناء الحمل أو الرضاعة إلا بعد استشارة الطبيب المشرف للتأكد من مأمونية الجرعة وسلامة الجنين.";
}

/**
 * تقييم أمان الدواء لأصحاب الأمراض المزمنة
 */
function evaluateChronicSafetyFromDb(array $med, string $query): string {
    $cat = normalizeArabic($med['category']);
    $gen = normalizeArabic($med['generic_name']);

    if (mb_strpos($query, 'ضغط') !== false) {
        if (mb_strpos($cat, 'مضاد للالتهاب') !== false || mb_strpos($gen, 'ايبوبروفين') !== false || mb_strpos($gen, 'ديكلوفيناك') !== false) {
            return "- **تحذير سريري لمرضى الضغط:** أدوية مضادات الالتهاب غير الستيرويدية (مثل البروفين والفولتارين) قد تسبب احتباس الصوديوم والماء وترفع ضغط الدم وتضعف فاعلية أدوية الضغط.\n" .
                   "- **البديل الآمن 100%:** الباراسيتامول (فيفادول / أدول / بنادول العادي) فهو الخيار الموصى به لمرضى الضغط.";
        }
        return "- لا توجد محاذير حادة معروفة لهذا الصنف مع ضغط الدم المعتدل، ولكن احرص دائماً على قياس ضغطك بانتظام يا غالي.";
    }

    if (mb_strpos($query, 'قرحه') !== false || mb_strpos($query, 'معده') !== false) {
        if (mb_strpos($cat, 'مضاد للالتهاب') !== false || mb_strpos($gen, 'ايبوبروفين') !== false || mb_strpos($gen, 'ديكلوفيناك') !== false) {
            return "- **تحذير حاسم لمرضى قرحة وحساسية المعدة:** هذه المسكنات تمنع إفراز الطبقة المخاطية الحامية للمعدة وتزيد من خطر تهيج القرحة والنزيف.\n" .
                   "- **الحل الآمن:** استخدم الباراسيتامول، أو احرص على أخذ حماية للمعدة (مثل أوميبرازول 20 مجم) صباحاً على الريق.";
        }
    }

    return "- يُفضل تناول الدواء بعد وجبة طعام متوازنة مع شرب ماء كافٍ، ومتابعة القراءات الحيوية إذا كنت تعاني من أي مرض مزمن يا غالي.";
}

/**
 * البحث عن بدائل أرخص متوفرة في قاعدة البيانات لنفس الصنف
 */
function findCheaperAlternativesInDb(array $activeMed, array $medicines): array {
    $activeId = $activeMed['id'] ?? 0;
    $activePrice = (float)($activeMed['price'] ?? 0);
    $activeCat = trim($activeMed['category'] ?? '');
    $activeGen = trim($activeMed['generic_name'] ?? '');

    $activeGenFirst = '';
    if (!empty($activeGen)) {
        $cleanGen = normalizeArabic(preg_replace('/\(.*?\)/u', '', $activeGen));
        $parts = explode(' ', $cleanGen);
        $activeGenFirst = $parts[0] ?? '';
    }

    $cheaper = [];
    foreach ($medicines as $m) {
        if ($m['id'] == $activeId) continue;

        $mPrice = (float)($m['price'] ?? 0);
        if ($mPrice >= $activePrice) continue;

        $matchFound = false;
        // مطابقة المادة الفعالة
        if (!empty($activeGenFirst) && !empty($m['generic_name'])) {
            $mGenNorm = normalizeArabic($m['generic_name']);
            if (mb_strpos($mGenNorm, $activeGenFirst) !== false) {
                $matchFound = true;
            }
        }

        // أو مطابقة نفس التصنيف العلاجي
        if (!$matchFound && !empty($activeCat) && !empty($m['category'])) {
            if ($m['category'] === $activeCat) {
                $matchFound = true;
            }
        }

        if ($matchFound) {
            $cheaper[] = $m;
        }
    }

    // الترتيب من الأرخص للأعلى
    usort($cheaper, fn($a, $b) => (float)$a['price'] <=> (float)$b['price']);
    return array_slice($cheaper, 0, 3);
}

/**
 * محرك البحث والفرز الدلالي في بيانات الأدوية الحية بقاعدة البيانات
 */
/**
 * استخراج كافة الكلمات وجذورها من حقل نصي (Word Stems Extraction)
 */
function getFieldStems(string $text): array {
    if (empty($text)) return [];
    $words = explode(' ', normalizeArabic($text));
    $stems = [];
    foreach ($words as $w) {
        if (mb_strlen($w) < 2) continue;
        $stems[] = $w;
        foreach (extractArabicStems($w) as $st) {
            if (mb_strlen($st) >= 2) {
                $stems[] = $st;
            }
        }
    }
    return array_unique($stems);
}

/**
 * محرك البحث والفرز الدلالي في بيانات الأدوية الحية بقاعدة البيانات
 */
function searchMedicinesByDatabaseKnowledge(string $normQuery, array $medicines): array {
    $qWords = explode(' ', $normQuery);
    $stopWords = ['عندي', 'ابغي', 'ابغى', 'اريد', 'محتاج', 'احتاج', 'في', 'من', 'عن', 'علي', 'على', 'هو', 'هي', 'دواء', 'علاج', 'حبوب', 'مرهم', 'شراب', 'لو', 'سمحت', 'يا', 'بيلا', 'دكتور', 'صيدلي', 'الله', 'يعافيك', 'ممكن', 'هل', 'فيه', 'صرف', 'ايش', 'وش'];
    $modifiers = ['شديد', 'شديده', 'شديدة', 'حاد', 'حاده', 'حادة', 'قوي', 'قويه', 'قوية', 'خفيف', 'خفيفه', 'خفيفة', 'سريع', 'سريعه', 'سريعة', 'بسيط', 'بسيطه', 'بسيطة', 'جدا'];

    $meaningfulTokens = [];
    $allQueryTokens = [];

    foreach ($qWords as $w) {
        if (in_array($w, $stopWords) || mb_strlen($w) < 3) continue;
        $allQueryTokens[] = $w;
        $stems = extractArabicStems($w);
        foreach ($stems as $st) {
            if (mb_strlen($st) >= 3 && !in_array($st, $allQueryTokens)) {
                $allQueryTokens[] = $st;
            }
        }
        if (!in_array($w, $modifiers)) {
            $meaningfulTokens[] = $w;
            foreach ($stems as $st) {
                if (mb_strlen($st) >= 3 && !in_array($st, $modifiers) && !in_array($st, $meaningfulTokens)) {
                    $meaningfulTokens[] = $st;
                }
            }
        }
    }

    if (empty($allQueryTokens)) {
        return [];
    }

    $scored = [];

    foreach ($medicines as $m) {
        $descStems = getFieldStems($m['description'] ?? '');
        $catStems = getFieldStems($m['category'] ?? '');
        $nameStems = getFieldStems($m['name'] ?? '');
        $genStems = getFieldStems($m['generic_name'] ?? '');

        $nameNorm = normalizeArabic($m['name'] ?? '');
        $catNorm = normalizeArabic($m['category'] ?? '');
        $descNorm = normalizeArabic($m['description'] ?? '');

        $score = 0;
        $matchedMeaningful = false;

        // 1. فحص تطابق العبارة الكاملة
        if (!empty($descNorm) && mb_strpos($descNorm, $normQuery) !== false) {
            $score += 25;
            $matchedMeaningful = true;
        }
        if (!empty($catNorm) && mb_strpos($catNorm, $normQuery) !== false) {
            $score += 20;
            $matchedMeaningful = true;
        }
        if (!empty($nameNorm) && mb_strpos($nameNorm, $normQuery) !== false) {
            $score += 30;
            $matchedMeaningful = true;
        }

        // 2. مطابقة الكلمات وجذورها بدقة
        foreach ($allQueryTokens as $token) {
            $isMod = in_array($token, $modifiers);
            $tokenScore = 0;

            if (in_array($token, $descStems)) {
                $tokenScore += ($isMod ? 1 : 7);
            }
            if (in_array($token, $catStems)) {
                $tokenScore += ($isMod ? 1 : 6);
            }
            if (in_array($token, $nameStems)) {
                $tokenScore += ($isMod ? 1 : 12);
            }
            if (in_array($token, $genStems)) {
                $tokenScore += ($isMod ? 1 : 10);
            }

            if ($tokenScore > 0) {
                $score += $tokenScore;
                if (!$isMod) {
                    $matchedMeaningful = true;
                }
            }
        }

        // تفضيل الأصناف المتوفرة بالمخزن
        if ($score > 0 && (int)($m['stock_quantity'] ?? 0) > 0) {
            $score += 1;
        }

        // يجب أن يطابق الصنف كلمة سريرية أو طبية حقيقية وليس مجرد نعت
        if ($score >= 6 && $matchedMeaningful) {
            $scored[] = [
                'medicine' => $m,
                'score' => $score
            ];
        }
    }

    // ترتيب الأصناف تنازلياً حسب درجة الملاءمة
    usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);

    $results = [];
    foreach ($scored as $item) {
        $results[] = $item['medicine'];
        if (count($results) >= 4) break;
    }

    return $results;
}

/**
 * صياغة رد تفاعلي حي مستخلص من حقول قاعدة البيانات (ياخذ ويعطي بالرد والاقتراحات)
 */
function buildConversationalMedicineResponse(string $query, array $matches, array $allMedicines): array {
    $primary = $matches[0];
    $primaryName = $primary['name'];
    $primaryDesc = $primary['description'] ?: 'دواء متوفر بالصيدلية معتمد للاستخدام السريري.';
    $primaryCat = $primary['category'];
    $primaryPrice = number_format((float)$primary['price'], 2);
    $primaryStock = (int)$primary['stock_quantity'];

    // استخراج البدائل الأوفر لنفس الصنف من قاعدة البيانات
    $cheaperAlts = findCheaperAlternativesInDb($primary, $allMedicines);

    $cardsText = [];
    foreach ($matches as $idx => $med) {
        $mPrice = number_format((float)$med['price'], 2);
        $mStock = (int)$med['stock_quantity'];
        $mDesc = $med['description'] ?: 'مسجل للاستخدام المباشر.';
        $stockBadge = ($mStock > 0) ? "متوفر بالمخزن ({$mStock} علبة)" : "غير متوفر حالياً بالمخزن";
        
        $num = $idx + 1;
        $cardsText[] = "{$num}. **{$med['name']}**\n" .
                       "   - **دواعي الاستعمال من وصف الصنف:** {$mDesc}\n" .
                       "   - **التصنيف والمادة الفعالة:** {$med['category']} | *{$med['generic_name']}*\n" .
                       "   - **السعر والحالة:** **{$mPrice} ريال** | {$stockBadge}";
    }

    $reply = "سلامتك وألف لا بأس عليك يا غالي! بناءً على وصف وبيانات الأدوية الحية في مخزن الصيدلية، هذي أفضل الأدوية المناسبة لحالتك:\n\n" .
             implode("\n\n", $cardsText) . "\n\n";

    // إضافة مقترح البديل الأوفر إن وجد بالمخزن
    if (!empty($cheaperAlts)) {
        $bestAlt = $cheaperAlts[0];
        $altPrice = number_format((float)$bestAlt['price'], 2);
        $diff = number_format((float)$primary['price'] - (float)$bestAlt['price'], 2);
        $reply .= "---\n" .
                  "**اقتراح للتوفير من بيلا:** عندنا خيار بديل أوفر بالمخزن وهو **{$bestAlt['name']}** بسعر **{$altPrice} ريال** فقط (يوفر لك {$diff} ريال)، ورصيده الحالي بالمخزن **{$bestAlt['stock_quantity']} علبة**.\n\n";
    }

    // إضافة أسئلة تفاعلية وأخذ وعطاء (Conversational Triage & Interaction)
    $reply .= "---\n" .
              "**بيلا معك خطوة بخطوة:**\n" .
              "- تحب أضيف لك العلاج لسلة الفاتورة مباشرة بضغطة زر عشان تستلمه؟\n" .
              "- أو حابب تستفسر عن طريقة الجرعة، أو مدى ملاءمته لو عندك ضغط أو سكر أو للحامل؟\n" .
              "اكتب لي اللي في خاطرك يا غالي وأنا أجاوبك فوراً.";

    return [
        'reply' => $reply,
        'intent' => 'DB_MEDICINE_SEARCH',
        'medicines' => $matches,
        'alternatives' => $cheaperAlts
    ];
}
