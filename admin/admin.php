<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != '1') {
    echo "<script>window.location = '/login.php'</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GroupiesSaver</title>
    <link rel="stylesheet" href="/css/style.css" />
    <script src="https://kit.fontawesome.com/d1c5590ed7.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <?php
    include 'header.php';
    ?>

    <a class="user" href="/admin/addtranc.php">
        <i class="fa-solid fa-arrow-up-right-from-square"></i>
        <p>Tambah Transaksi</p>
    </a>

    <a class="user" href="/admin/deletetranc.php">
        <i class="fa-solid fa-arrow-up-right-from-square"></i>
        <p>Hapus Transaksi</p>
    </a>

    <a class="user" href="/config/logout.php" style="background-color: black; color: white;">
        <i class="fa-solid fa-arrow-up-right-from-square"></i>
        <p>Logout</p>
    </a>