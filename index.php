<?php
session_start();
if (!isset($_SESSION['admin']) || ($_SESSION['admin'] != '0')) {
  if (isset($_SESSION['admin']) && ($_SESSION['admin'] = '1')) {
    header("Location: /admin");
  }
  echo "<script>window.location = '/login.php'</script>";
  exit();
}



$timeout = 600;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
  session_unset();
  session_destroy();
  echo "<script>window.location.href = '/config/logout.php';</script>";
  exit();
}

$_SESSION['last_activity'] = time();
include 'dashboard/basicinfo.php';
include 'config/conn.php';
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

$id = $r['id'];
$username = $r['username'];
$name = $r['nama'];
$email = $r['email'];
$bornplace = $r['tempat_lahir'];
$borndate = $r['tanggal_lahir'];
$isadmin = $r['is_admin'];

?>



<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="format-detection" content="telephone=no">
  <title>GroupiesSaver</title>
  <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
  <link rel="manifest" href="/img/site.webmanifest">
  <link rel="stylesheet" href="/css/style.css?v=1.7" />
  <script src="https://kit.fontawesome.com/d1c5590ed7.js" crossorigin="anonymous"></script>
  <script src="/js/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
  <?php include 'header.php' ?>
  <main class="container">
    <h3 id="welcome">Hai, <?php echo $name ?> 👋</h3>
    <?php
    if (isset($_SESSION['pwd_chg']) && $_SESSION['pwd_chg'] == '0') {

      echo '<div id="pwd-box" class="pwd-box">';
      echo '<p>Password belum diganti, silakan ganti <a class="chg-pwd-button" data-url="dashboard/changepassword.php" href="javascript:void(0)" >di sini</a></p>';
      echo '<span class="close-pwd"><i class="fa-solid fa-xmark"></i></span>';
      echo '</div>';
    }
    ?>

    <section>
      <div class="wallet">
        <i class="fa-solid fa-wallet"></i>
        <div class="subWallet">
          <a id="openPopupBtn" href="#">+</a>
          <div class="subWalletText">
            <span>Saldo Anda</span>
            <h3>Rp<?php echo $formattedSaldo; ?></h3>
          </div>
        </div>
      </div>

      <div id="popup" class="popup">
        <div class="bg-popup"></div>
        <div class="popup-content">
          <span id="close-btn" class="close-btn"><i class="fa-solid fa-xmark"></i></span>
          <div>
            <p class="dis-user-select">Untuk menambahkan saldo Anda, transfer pada nomor rekening berikut:</p>
            <label class="dis-user-select">BRI:</label>
            <span id="copyText">3452-01-006046-50-1</span>

            <br>
            <label class="dis-user-select">DANA:</label>
            <span id="copyText">082118265630</span>
            <br>
            <span class="dis-user-select">a.n Iax Perdxxxxxh</span>

          </div>
          <br>
          <!-- <button id="closePopupBtn" class="dis-user-select">Tutup</button> -->
        </div>
      </div>

      <div class="button">
        <a class="ajax-button" href="javascript:void(0)" data-url="/config/transaction.php">Riwayat Transaksi</a>
        <a class="ajax-button" href="javascript:void(0)" data-url="/dashboard/memberbalance.php">Saldo Anggota</a>
        <!-- <a class="ajax-button" href="javascript:void(0)" data-url="/xendit/addtranc.php">Tambah Transaksi</a> -->
        <!-- <a class="ajax-button" href="javascript:void(0)" data-url="/bersamamu/index.html">Bersamamu - JAZ</a> -->
        <?php if (isset($_SESSION['adduser']) && $_SESSION['adduser'] == '1') {
          echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/adduser.php">Tambah Anggota</a>';
          echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/appruser.php">Terima Anggota</a>';
        } ?>
        <?php if (isset($_SESSION['adduser']) && $_SESSION['deluser'] == '1') {
          echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/deleteuser.php">Hapus Anggota</a>';
        } ?>
        <?php if (isset($_SESSION['adduser']) && $_SESSION['addtr'] == '1') {
          echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/addtranc.php">Tambah Transaksi</a>';
        } ?>
        <?php if (isset($_SESSION['deluser']) && $_SESSION['deltr'] == '1') {
          echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/deletetranc.php">Hapus Transaksi</a>';
        } ?>

        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == '1') {
          echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/adduser.php">Tambah Anggota</a>';
          echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/appruser.php">Terima Anggota</a>';
          echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/deleteuser.php">Hapus Anggota</a>';
          echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/addtranc.php">Tambah Transaksi</a>';
          echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/deletetranc.php">Hapus Transaksi</a>';
        } ?>


        <a class="ajax-button" href="/config/logout.php" data-url="/config/logout.php">Logout</a>
      </div>
      <div id="ajax-result">
        <div id="transaction-history">
          <p>Memuat...</p>
        </div>
        <div id="loading-transaction" style="display: none;" class="page-load">
          <div class="loading-popup">
            <div>
              <div class="loading-spinner"></div>
            </div>
          </div>
        </div>
      </div>

    </section>
  </main>
  <?php include 'footer.php' ?>
  <script src="js/script.js?v=1.2"></script>

</body>

</html>