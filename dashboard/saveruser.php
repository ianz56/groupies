<?php
include 'config/conn.php';
$userQuery = "SELECT * FROM anggota WHERE is_admin = 0 ORDER BY id  ASC";
$userResult = $conn->query($userQuery);

if ($userResult && $userResult->num_rows > 0) {
    while ($user = $userResult->fetch_assoc()) {
        echo '<a class="user" href="user.php?userid=' . $user['id'] . '">';
        echo '<i class="fa-solid fa-arrow-up-right-from-square"></i>';
        echo "<span>" . "ID : " . $user['id'] . "</span>";
        echo "<span>" . "Nama : " . $user['nama'] . "</span>";
        echo "</a>";
    }
} else {
    echo "Tidak ada user.";
}
