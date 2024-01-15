<?php
session_start();
if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];
    include 'config/conn.php';
    if ($conn->connect_error) {
        die(include 'error.php' . $conn->connect_error);
    }

    // Mengambil data
    $sqlUser = "SELECT nama, email, tanggal_lahir, tempat_lahir, is_admin FROM anggota WHERE id = '$userid'";
    $resultUser = $conn->query($sqlUser);

    // Memeriksa hasil query kredit
    if ($resultUser->num_rows > 0) :
        $rowUser = $resultUser->fetch_assoc();
        $nama = $rowUser["nama"];
        $email = $rowUser["email"];
        $tempatLahir = $rowUser["tempat_lahir"];
        $isAdmin = ($rowUser["is_admin"] == 1) ? "Admin" : "Bukan Admin";
    else : echo 'Tidak ada data';
    endif;
    // echo "$userid $nama $email $tempatLahir $isAdmin";

    $query = "SELECT SUM(jumlah) AS total_saldo FROM transaksi WHERE anggota_id = $userid";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        $totalSaldo = $row['total_saldo'];
        $formattedSaldo = number_format($totalSaldo, 0, ',', '.');
    } else {
        $formattedSaldo = 'Error';
    }

    $saldo = $formattedSaldo;

    $conn->close();
} else {
    echo "HmMMM";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GroupiesSaver</title>
    <link rel="stylesheet" href="style.css" />
    <script src="https://kit.fontawesome.com/d1c5590ed7.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div id="loading" style="display: flex;">
        <div class="loading-spinner"></div>
    </div>
    <?php include 'header.php' ?>
    <main class="container">
        <section>
            <div class="userProfile wallet">
                <i class="fa-solid fa-wallet"></i>
                <div class="subWallet">
                    <div class="subWalletText">
                        <div class="userHead name"><span>Nama</span><span>: </span> <span><?php echo $nama ?></span></div>
                        <div class="userHead saving"><span>Jumlah Tabungan</span><span>: </span> <span>Rp<?php echo $saldo ?></span></div>
                    </div>
                </div>
            </div>

            </div>
            <div class="button">
                <a class="ajax-button" href="javascript:void(0)" data-url="/transaction.php?userid=<?php echo $userid ?>">Semua Riwayat Transaksi</a>
                <?php
                $logout = (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) || (isset($_SESSION['admin']) && $_SESSION['admin'] == 0) ? '<a class="ajax-button" href="/logout.php" data-url="/logout.php">Logout</a>' : "";
                echo $logout;
                ?>
            </div>

            <div id="loading-transaction" style="display: none;">
                <div class="loading-spinner"></div>
            </div>
            <div id="transaction-history"></div>

        </section>
    </main>
    <script src="script.js"></script>
</body>

</html>