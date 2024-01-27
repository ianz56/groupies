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
        $query_chart = "SELECT a.nama_panggilan as anggota_nama, tt.total AS total_amount
                FROM anggota a
                INNER JOIN member_balance_total tt ON a.id = tt.member_id
                WHERE a.id != 1 AND tt.total > 0";
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

if (isset($_GET['balance-total'])) {
    $member_id = intval($_GET['balance-total']);
    function getTransactionOptions($conn, $member_id)
    {
        if ($member_id === 0) {
            $query = "SELECT SUM(total) AS total FROM `member_balance_total`";
        } else {
            $query = "SELECT SUM(total) AS total FROM `member_balance_total` WHERE member_id = ?";
        }

        $stmt = $conn->prepare($query);
        if ($member_id !== 0) {
            $stmt->bind_param("i", $member_id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $result_total = $result->fetch_assoc();
        $data = $result_total['total'];
        $formattedSaldo = number_format((float) $data, 0, ',', '.');
        return $formattedSaldo;
    }
    $response = 'Rp' . getTransactionOptions($conn, $member_id);
    echo json_encode($response);
    $conn->close();
}
