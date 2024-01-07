<?php
$username = "IAN";

// Fungsi untuk mengirim notifikasi ke Telegram
function sendTelegramNotification($message)
{
    $botToken = '5130207434:AAGXsHr7sxmy88mo5clF53i7yKDM_GR9PRk';
    $chatId = '-1001246332958';
    $apiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";

    $postData = [
        'chat_id' => $chatId,
        'text' => $message,
    ];

    $contextOptions = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($postData),
        ],
    ];

    $context = stream_context_create($contextOptions);
    $result = file_get_contents($apiUrl, false, $context);

    if ($result === FALSE) {
        echo 'Telegram API Error: Unable to send message';
    }

    return $result;
}

// Pemanggilan fungsi untuk mengirim notifikasi ke Telegram
$message = "Pengguna dengan username '{$username}' berhasil login.";
sendTelegramNotification($message);
