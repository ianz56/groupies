<?php
session_abort();
session_start();
include_once 'conn.php';
$anggota_id = $_SESSION['id'];
$query = "SELECT SUM(jumlah) AS total_saldo FROM transaksi WHERE anggota_id = $anggota_id";
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $totalSaldo = $row['total_saldo'];
    $formattedSaldo = number_format((float) $totalSaldo, 0, ',', '.');
} else {
    $formattedSaldo = 'Error';
}

$userQuery = mysqli_query($conn, "SELECT * FROM anggota WHERE id='$anggota_id'");
$r = mysqli_fetch_assoc($userQuery);
