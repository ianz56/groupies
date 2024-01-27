<?php
include '../config/conn.php';

if (isset($_POST['add-tranc'])) {
    session_start();
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] != '1') {
        if (!isset($_SESSION['addtr']) || $_SESSION['addtr'] != '1') {
            header("HTTP/1.1 403 Forbidden");
            echo "Maaf, akses ke halaman ini ditolak.";
            exit();
        }
    }
    $anggota_id = $_POST['anggota_id'];
    $jumlah = $_POST['jumlah'];
    $tanggal_input = $_POST['tanggal'];
    $tanggal = date('Y-m-d H:i:s', strtotime($tanggal_input));
    $jenis_transaksi = $_POST['jenis_transaksi'];
    $keterangan = $_POST['ket'];

    // Jika jenis transaksi adalah "Penarikan", tambahkan tanda minus pada jumlah
    if ($jenis_transaksi == 'Penarikan') {
        $jumlah = -$jumlah;
    }

    // Insert data ke tabel 'transaksi'
    $query_transaksi = "INSERT INTO transaksi (anggota_id, jumlah, tanggal, jenis_transaksi, keterangan) VALUES (?, ?, ?, ?, ?)";
    $stmt_transaksi = $conn->prepare($query_transaksi);
    $stmt_transaksi->bind_param("idsss", $anggota_id, $jumlah, $tanggal, $jenis_transaksi, $keterangan);

    // Cek apakah transaksi berhasil dijalankan
    if ($stmt_transaksi->execute()) {
        // Insert data ke tabel 'member_balance_total' (asumsi tabel ini berisi total saldo anggota)
        $query_total = "UPDATE member_balance_total SET total = ?, last_transaction = ? WHERE member_id = ?";
        $stmt_total = $conn->prepare($query_total);

        // Ambil total sebelum transaksi untuk di-update
        $query_get_total = "SELECT total FROM member_balance_total WHERE member_id = ?";
        $stmt_get_total = $conn->prepare($query_get_total);
        $stmt_get_total->bind_param("i", $anggota_id);
        $stmt_get_total->execute();
        $stmt_get_total->bind_result($total_sebelumnya);
        $stmt_get_total->fetch();


        // Hitung total setelah transaksi
        $total_setelah_transaksi = $total_sebelumnya + $jumlah;

        // Update total dan last_transaction
        $stmt_total->bind_param("dss", $total_setelah_transaksi, $tanggal, $anggota_id);
        $stmt_get_total->close();
        $stmt_total->execute();
        echo "Transaksi berhasil ditambahkan!";
    } else {
        echo "Error: " . $stmt_transaksi->error;
    }

    // Tutup statement
    $stmt_transaksi->close();
    $stmt_total->close();
}


if (isset($_POST['del-tranc'])) {
    session_start();
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] != '1') {
        if (!isset($_SESSION['deltr']) || $_SESSION['deltr'] != '1') {
            header("HTTP/1.1 403 Forbidden");
            echo "Maaf, akses ke halaman ini ditolak.";
            exit();
        }
    }
    $transaksi_id = $_POST['transaksi_id'];
    $query = "DELETE FROM transaksi WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $transaksi_id);

    if ($stmt->execute()) {
        echo "Transaksi berhasil dihapus!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
// $query_anggota = "SELECT id, nama FROM anggota";
// $result_anggota = $conn->query($query_anggota);

if (isset($_POST['add-user'])) {
    session_start();
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] != '1') {
        if (!isset($_SESSION['adduser']) || $_SESSION['adduser'] != '1') {
            header("HTTP/1.1 403 Forbidden");
            echo "Maaf, akses ke halaman ini ditolak.";
            exit();
        }
    }
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $query = "INSERT INTO anggota (nama, username, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $nama, $username, $hashedPassword);

    if ($stmt->execute()) {
        echo "Anggota berhasil ditambahkan!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_POST['del-user'])) {
    session_start();
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] != '1') {
        if (!isset($_SESSION['deluser']) || $_SESSION['deluser'] != '1') {
            header("HTTP/1.1 403 Forbidden");
            echo "Maaf, akses ke halaman ini ditolak.";
            exit();
        }
    }

    $anggota_id = $_POST['anggota_id'];

    // Periksa apakah anggota dapat dihapus
    $check_query = "SELECT deletable FROM anggota WHERE id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $anggota_id);
    $check_stmt->execute();
    $check_stmt->bind_result($deletable);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($deletable == 0) {
        echo "Tidak dapat menghapus kuncen grup!.";
    } else {
        // Anggota dapat dihapus, lanjutkan dengan menghapusnya
        $delete_query = "DELETE FROM anggota WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $anggota_id);

        if ($delete_stmt->execute()) {
            echo "Anggota berhasil dihapus!";
        } else {
            $error_message = "Error: " . $delete_stmt->error;
        }

        $delete_stmt->close();
    }
}

if (isset($_POST['app-user'])) {
    session_start();
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] != '1') {
        if (!isset($_SESSION['adduser']) || $_SESSION['adduser'] != '1') {
            header("HTTP/1.1 403 Forbidden");
            echo "Maaf, akses ke halaman ini ditolak.";
            exit();
        }
    }
    // Cek apakah ada anggota yang dipilih untuk diapprove
    // Lakukan loop untuk setiap anggota yang dipilih
    foreach ($_POST['approved_users'] as $user_id) {
        // Ambil data anggota dari tabel approve berdasarkan ID
        $query_select_user = "SELECT * FROM approve WHERE id = ?";
        $stmt_select_user = $conn->prepare($query_select_user);
        $stmt_select_user->bind_param("i", $user_id);
        $stmt_select_user->execute();
        $result_select_user = $stmt_select_user->get_result();
        $user_data = $result_select_user->fetch_assoc();

        // Pindahkan anggota ke tabel anggota
        $query_approve_user = "INSERT INTO anggota (nama, username, password) VALUES (?, ?, ?)";
        $stmt_approve_user = $conn->prepare($query_approve_user);
        $stmt_approve_user->bind_param("sss", $user_data['nama'], $user_data['username'], $user_data['password']);
        $stmt_approve_user->execute();

        // Hapus anggota dari tabel approve
        $query_delete_user = "DELETE FROM approve WHERE id = ?";
        $stmt_delete_user = $conn->prepare($query_delete_user);
        $stmt_delete_user->bind_param("i", $user_id);
        $stmt_delete_user->execute();
    }

    echo "Anggota berhasil diapprove dan dipindahkan ke tabel anggota.";
}
if (isset($_POST['user-role'])) {
    // Perform your session validation
    // if (!isset($_SESSION['admin']) || $_SESSION['admin'] != '1') {
    //     header("HTTP/1.1 403 Forbidden");
    //     echo "Maaf, akses ke halaman ini ditolak.";
    //     exit();
    // }

    $user_id = $_POST['anggota_id'];
    $add_transaksi = isset($_POST['add_transaksi']) ? 1 : 0;
    $del_transaksi = isset($_POST['del_transaksi']) ? 1 : 0;
    $add_user = isset($_POST['add_user']) ? 1 : 0;
    $del_user = isset($_POST['del_user']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE anggota SET addtr=?, deltr=?, adduser=?, deluser=? WHERE id=?");
    $stmt->bind_param("iiiii", $add_transaksi, $del_transaksi, $add_user, $del_user, $user_id);

    if ($stmt->execute()) {
        echo "Role berhasil disubmit!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_POST['sync-total'])) {
    function syncTotalBalances($conn)
    {
        // Ambil nilai transaksi untuk setiap anggota dari tabel 'transaksi'
        $query_transaksi = "SELECT anggota_id, SUM(jumlah) AS total_transaksi FROM transaksi GROUP BY anggota_id";
        $result_transaksi = $conn->query($query_transaksi);

        // Periksa jika query berhasil dijalankan
        if ($result_transaksi) {
            // Loop melalui hasil query
            while ($row_transaksi = $result_transaksi->fetch_assoc()) {
                $anggota_id = $row_transaksi['anggota_id'];
                $total_transaksi = $row_transaksi['total_transaksi'];

                // Update total pada tabel 'member_balance_total' langsung
                $query_update_total = "UPDATE member_balance_total SET total = ? WHERE member_id = ?";
                $stmt_update_total = $conn->prepare($query_update_total);
                $stmt_update_total->bind_param("di", $total_transaksi, $anggota_id);
                $stmt_update_total->execute();
                $stmt_update_total->close();
            }

            echo "Sync berhasil dilakukan!";
        } else {
            echo "Sync gagal!";
        }
    }

    syncTotalBalances($conn);
}

$conn->close();
