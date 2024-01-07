<?php
include '../config/conn.php';

if (isset($_GET['usertrans'])) {
    function getTransactionOptions($conn)
    {
        $query_transaksi = "SELECT id, anggota_id, jumlah, tanggal, jenis_transaksi FROM transaksi";
        $result_transaksi = $conn->query($query_transaksi);

        $options = '';
        while ($row = $result_transaksi->fetch_assoc()) {
            $options .= "<option value='{$row['id']}'>ID: {$row['id']} - Anggota ID: {$row['anggota_id']} - Jumlah: {$row['jumlah']} - Tanggal: {$row['tanggal']} - Jenis Transaksi: {$row['jenis_transaksi']}</option>";
        }

        return $options;
    }
    $options = getTransactionOptions($conn);
    $response = ["options" => $options];
    echo json_encode($response);
    $conn->close();
}

if (isset($_GET['users'])) {
    function getTransactionOptions($conn)
    {
        $query_anggota = "SELECT id, username, nama FROM anggota";
        $result_anggota = $conn->query($query_anggota);

        $options = '';
        while ($row = $result_anggota->fetch_assoc()) {
            $options .= "<option value='{$row['id']}'>ID: {$row['id']} - Username: {$row['username']} - Nama: {$row['nama']}</option>";
        }

        return $options;
    }
    $options = getTransactionOptions($conn);
    $response = ["options" => $options];
    echo json_encode($response);
    $conn->close();
}
if (isset($_GET['user-pending'])) {
    function getTransactionOptions($conn)
    {
        $query_anggota = "SELECT id, nama, username, password FROM approve";
        $result_anggota = $conn->query($query_anggota);

        $options = '';
        if ($result_anggota->num_rows > 0) {
            while ($row = $result_anggota->fetch_assoc()) {
                $options .= "<input type='checkbox' class='form-check-input' name='approved_users[]' value='{$row['id']}'>";
                $options .= "<label class='form-check-label'>ID: {$row['id']} - Nama: {$row['nama']} - Username: {$row['username']}</label>";
                $options .= "<br />";
            }
        } else {
            $options = "No pending users found.";
        }

        return $options;
    }

    $response = ["options" => getTransactionOptions($conn)];
    echo json_encode($response);
    $conn->close();
}

if (isset($_GET['all-chart'])) {
    function getTransactionOptions($conn)
    {
        $query_chart = "SELECT a.nama_panggilan as anggota_nama, SUM(t.jumlah) AS total_amount FROM transaksi t
                        INNER JOIN anggota a ON t.anggota_id = a.id
                        WHERE t.anggota_id != 1 GROUP BY t.anggota_id";
        $result_chart = $conn->query($query_chart);

        $chartData = [];
        if ($result_chart->num_rows > 0) {
            while ($row_chart = $result_chart->fetch_assoc()) {
                $chartData[] = [
                    'anggota_nama' => $row_chart['anggota_nama'],
                    'total_amount' => $row_chart['total_amount'],
                ];
            }
        } else {
            $chartData = "No statistic found.";
        }

        return $chartData;
    }

    $response = getTransactionOptions($conn);
    echo json_encode($response);
    $conn->close();
}
