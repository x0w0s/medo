<?php
// تكوين بوت تليغرام
define('BOT_TOKEN', '8532129559:AAGmynX2GrrxowH34J3DuIm3Vh2u_v593uU');
define('CHAT_ID', '7763665935');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    
    if (empty($message)) {
        echo json_encode(['success' => false, 'error' => 'الرسالة فارغة']);
        exit;
    }
    
    // إرسال الرسالة إلى تليغرام
    $telegramUrl = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage";
    $data = [
        'chat_id' => CHAT_ID,
        'text' => "📩 رسالة جديدة من أحد الشباب (مجهول):\n\n" . $message,
        'parse_mode' => 'HTML'
    ];
    
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($telegramUrl, false, $context);
    
    if ($result === FALSE) {
        echo json_encode(['success' => false, 'error' => 'فشل الاتصال بتليغرام']);
    } else {
        $response = json_decode($result, true);
        if ($response['ok'] === true) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $response['description'] ?? 'خطأ غير معروف']);
        }
    }
    exit;
}

// إذا تم الوصول للملف بدون طلب POST
header('HTTP/1.0 403 Forbidden');
echo 'غير مسموح بالوصول المباشر.';
?>
