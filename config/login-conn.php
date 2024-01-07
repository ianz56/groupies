<?php

session_start();

header('Content-Type: application/json');

if (isset($_SESSION['username'])) {
    $userInfo = [
        'id'            => $_SESSION['id'],
        'username'      => $_SESSION['username'],
        'nama'          => $_SESSION['nama'],
        'email'         => $_SESSION['email'],
        'tempat_lahir'  => $_SESSION['tempat_lahir'],
        'tanggal_lahir' => $_SESSION['tanggal_lahir'],
        'admin'         => $_SESSION['admin'],
        'pwd_chg'       => $_SESSION['pwd_chg'],
        'deletable'     => $_SESSION['deletable'],
        'adduser'       => $_SESSION['adduser'],
        'deluser'       => $_SESSION['deluser'],
        'addtr'         => $_SESSION['addtr'],
        'deltr'         => $_SESSION['deltr']
    ];

    echo json_encode(['status' => 'success', 'userInfo' => $userInfo]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('conn.php');

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM anggota WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $user = mysqli_fetch_assoc($result);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id']            = $user['id'];
            $_SESSION['username']      = $user['username'];
            $_SESSION['nama']          = $user['nama'];
            $_SESSION['email']         = $user['email'];
            $_SESSION['tempat_lahir']  = $user['tempat_lahir'];
            $_SESSION['tanggal_lahir'] = $user['tanggal_lahir'];
            $_SESSION['admin']         = $user['is_admin'];
            $_SESSION['pwd_chg']       = $user['pwd_chg'];
            $_SESSION['deletable']     = $user['deletable'];
            $_SESSION['adduser']       = $user['adduser'];
            $_SESSION['deluser']       = $user['deluser'];
            $_SESSION['addtr']         = $user['addtr'];
            $_SESSION['deltr']         = $user['deltr'];

            $userInfo = [
                'id'            => $user['id'],
                'username'      => $user['username'],
                'nama'          => $user['nama'],
                'email'         => $user['email'],
                'tempat_lahir'  => $user['tempat_lahir'],
                'tanggal_lahir' => $user['tanggal_lahir'],
                'admin'         => $user['is_admin'],
                'pwd_chg'       => $user['pwd_chg'],
                'deletable'     => $user['deletable'],
                'adduser'       => $user['adduser'],
                'deluser'       => $user['deluser'],
                'addtr'         => $user['addtr'],
                'deltr'         => $user['deltr']
            ];

            function sendTelegramNotification($botToken, $chatId, $message)
            {
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
                $result = file_get_contents(
                    $apiUrl,
                    false,
                    $context
                );

                if ($result === FALSE) {
                    echo "Telegram API Error: Unable to send message\n";
                }

                return $result;
            }

            $botToken = '1711990101:AAF_0eJPm-uQAy1Z_G6SWe4OyAoouADu0X8';
            $chatId = '-1001246332958';
            $message = "'{$username}' berhasil login.";

            $result = sendTelegramNotification($botToken, $chatId, $message);

            if ($result === FALSE) {
                $botTokenBot2 = '5130207434:AAGXsHr7sxmy88mo5clF53i7yKDM_GR9PRk';
                sendTelegramNotification($botTokenBot2, $chatId, $message);
            }

            echo json_encode(['status' => 'success', 'userInfo' => $userInfo]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Username atau password salah.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database query error.']);
    }
    mysqli_close($conn);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
