<?php

// Periksa apakah formulir disubmit
if (isset($_POST['add-tranc'])) {

    // Ambil nilai dari formulir
    $anggota_id = $_POST['anggota_id'];
    $jumlah = $_POST['jumlah'];
    $tanggal = $_POST['tanggal'];
    $jenis_transaksi = $_POST['jenis_transaksi'];
    $ket = $_POST['ket'];

    // Ganti dengan kunci API Xendit Anda
    $api_key = 'xnd_development_mqnzmzRtctOuJk2k0fW3VcTiLhr3o7VzH0nOrDuCv4D8Ih8aYbjA2hflygiFgz5D';

    // Panggil fungsi untuk membuat invoice di Xendit
    $invoice_url = createXenditInvoice($api_key, $jumlah, $anggota_id, $tanggal, $jenis_transaksi, $ket);

    // Tambahkan log atau pesan sukses ke database atau tampilkan pesan ke pengguna
    if ($invoice_url) {
        echo 'Transaksi berhasil! Invoice URL: ' . $invoice_url;
    } else {
        echo 'Gagal membuat transaksi. Silakan coba lagi.';
    }
}

function createXenditInvoice($api_key, $amount, $anggota_id, $tanggal, $jenis_transaksi, $keterangan)
{
    $url = 'https://api.xendit.co/v2/invoices';

    $headers = array(
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($api_key . ':')
    );

    $data = array(
        'external_id' => 'transaksi_' . uniqid(), // Ganti dengan logika yang sesuai untuk external_id
        'amount' => $amount,
        'payer_email' => 'ianperdiansah05@gmail.com', // Ganti dengan email pelanggan yang sesuai
        'description' => $keterangan,
        'invoice_duration' => 24, // Durasi invoice dalam jam
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($httpCode == 200) {
        $invoice_data = json_decode($response, true);
        return $invoice_data['invoice_url'];
    } else {
        return false;
    }
}
