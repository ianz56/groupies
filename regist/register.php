<?php
$host = "localhost";
$user = "wiperhel_groupies";
$pass = "Ian13254.";
$database = "wiperhel_groupies";
$conn = new mysqli($host, $user, $pass, $database);

if (isset($_POST['regist'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $query = "INSERT INTO approve (nama, username, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $nama, $username, $hashedPassword);

    if ($stmt->execute()) {
        echo "Anggota berhasil didaftarkan!. Tunggu beberapa saat sampai Admin mengkonfirmasi, kemudian coba login.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
