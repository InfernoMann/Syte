<?php
// Скрипт для отправки IP адреса на email

// Получаем данные из POST запроса
$visitorIP = isset($_POST['ip']) ? $_POST['ip'] : $_SERVER['REMOTE_ADDR'];
$userAgent = isset($_POST['userAgent']) ? $_POST['userAgent'] : $_SERVER['HTTP_USER_AGENT'];
$timestamp = isset($_POST['timestamp']) ? $_POST['timestamp'] : date('Y-m-d H:i:s');
$referrer = isset($_POST['referrer']) ? $_POST['referrer'] : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Прямой заход');

// Если IP не получен из POST, пытаемся получить из заголовков
if (empty($visitorIP) || $visitorIP === '::1') {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $visitorIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
        $visitorIP = $_SERVER['HTTP_X_REAL_IP'];
    } else {
        $visitorIP = $_SERVER['REMOTE_ADDR'];
    }
}

// Email получателя
$to = 'robertitbaev15@gmail.com';

// Тема письма
$subject = 'Новый посетитель сайта - IP: ' . $visitorIP;

// Тело письма
$message = "Новый посетитель на сайте!\n\n";
$message .= "IP адрес: " . $visitorIP . "\n";
$message .= "Время посещения: " . $timestamp . "\n";
$message .= "User Agent: " . $userAgent . "\n";
$message .= "Реферер: " . $referrer . "\n";
$message .= "Страница: " . (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'Неизвестно') . "\n";

// Заголовки письма
$headers = "From: noreply@yoursite.com\r\n";
$headers .= "Reply-To: noreply@yoursite.com\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Отправка письма
$mailSent = mail($to, $subject, $message, $headers);

// Возвращаем ответ (для отладки можно раскомментировать)
// if ($mailSent) {
//     echo json_encode(['status' => 'success', 'message' => 'Email отправлен']);
// } else {
//     echo json_encode(['status' => 'error', 'message' => 'Ошибка отправки email']);
// }

// Для безопасности не выводим ничего
http_response_code(200);
exit;
?>
