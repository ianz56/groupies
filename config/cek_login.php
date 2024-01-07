<?php
include 'conn.php';

// Memeriksa koneksi
if ($conn->connect_error) {
	die("Koneksi gagal: " . $conn->connect_error);
}
include 'lib/function.php';

// fungsi untuk menghindari injeksi dari user yang jahil
function anti_injection($data)
{
	$filter = stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES)));
	return $filter;
}

$username = anti_injection($_POST['username']);
$password = $_POST['password']; // Hapus md5(), hash password akan dilakukan lebih lanjut

// menghindari sql injection
$injeksi_username = mysqli_real_escape_string($conn, $username);

// pastikan username adalah berupa huruf atau angka.
if (!ctype_alnum($injeksi_username)) {
	echo "Sekarang loginnya tidak bisa di injeksi lho.";
} else {
	$login = mysqli_query($conn, "SELECT * FROM anggota WHERE username='$username'");

	if ($login) {
		$ketemu = mysqli_num_rows($login);
		$r      = mysqli_fetch_assoc($login);

		// Apabila username ditemukan
		if ($ketemu > 0 && password_verify($password, $r['password'])) {
			session_start();
			$_SESSION['id']            = $r['id'];
			$_SESSION['username']      = $r['username'];
			$_SESSION['nama']          = $r['nama'];
			$_SESSION['email']         = $r['email'];
			$_SESSION['tempat_lahir']  = $r['tempat_lahir'];
			$_SESSION['tanggal_lahir'] = $r['tanggal_lahir'];
			$_SESSION['admin']         = $r['is_admin'];
			$_SESSION['pwd_chg']       = $r['pwd_chg'];
			$_SESSION['deletable']     = $r['deletable'];
			$_SESSION['adduser']       = $r['adduser'];
			$_SESSION['deluser']       = $r['deluser'];
			$_SESSION['addtr']       	= $r['addtr'];
			$_SESSION['deltr']       	= $r['deltr'];

			login_validate();
			$sid_lama = session_id();
			session_regenerate_id();
			$sid_baru = session_id();

			if ($r['is_admin'] == '1') {
				mysqli_query($conn, "UPDATE anggota SET id_session='$sid_baru', last_login=NOW() WHERE username='$username'");
			}
			if ($r['is_admin'] == '0') {
				mysqli_query($conn, "UPDATE anggota SET id_session='$sid_baru', last_login=NOW() WHERE username='$username'");
			}

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
				$result = file_get_contents($apiUrl, false, $context);

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
				// Jika terjadi kesalahan dengan bot pertama, coba dengan bot kedua
				$botTokenBot2 = '5130207434:AAGXsHr7sxmy88mo5clF53i7yKDM_GR9PRk';
				sendTelegramNotification($botTokenBot2, $chatId, $message);
			}

			header("Location: /index.php");
		} else {
			echo "<script>alert('Login Gagal.'); window.location = '/index.php'</script>";
		}
	} else {
		die("Query Error: " . mysqli_error($conn)); // Tambahkan penanganan error
	}
}
