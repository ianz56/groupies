<?php
include 'config/conn.php';

$records_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

$query = "SELECT * FROM transaksi WHERE anggota_id != 1 ORDER BY tanggal DESC LIMIT $offset, $records_per_page";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['anggota_id'] . "</td>";
    echo "<td>" . number_format($row['jumlah'], 0, ',', '.') . "</td>";
    echo "<td>" . $row['tanggal'] . "</td>";
    echo "<td>" . $row['jenis_transaksi'] . "</td>";
    echo "</tr>";
}

$conn->close();
