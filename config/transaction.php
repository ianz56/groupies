<?php
if (isset($_GET['userid'])) {
    // Mengambil id dari parameter URL
    $id = $_GET['userid'];
    include 'conn.php';
    $anggota_id = $id;

    // Query untuk mengambil data transaksi berdasarkan ID anggota
    $transaksiQuery = "SELECT * FROM transaksi WHERE anggota_id = $anggota_id ORDER BY id DESC";
    $transaksiResult = $conn->query($transaksiQuery);

    if ($transaksiResult && $transaksiResult->num_rows > 0) {
        while ($transaksi = $transaksiResult->fetch_assoc()) {
            $tanggalTransaksi = $transaksi['tanggal']; // Ganti dengan kolom yang sesuai di tabel transaksi
            $jenisTransaksi = $transaksi['jenis_transaksi'];
            $jumlahTransaksi = number_format($transaksi['jumlah'], 0, ',', '.'); // Formatkan ke format uang
            $keterangan = $transaksi['keterangan']; // Tambahkan baris ini untuk mengambil keterangan

            echo "
    <div class='transaction'>
        <div>
            <span>Tanggal :</span>
            <span>$tanggalTransaksi</span>
        </div>
        <div>
            <span>Jenis Transaksi :</span>
            <span>$jenisTransaksi</span>
        </div>
        <div>
            <span>Jumlah :</span>
            <span>Rp$jumlahTransaksi</span>
        </div>";
            // Tambahkan kondisi untuk menampilkan div keterangan hanya jika keterangan ada
            if (!empty($keterangan)) {
                echo "
            <div>
                <span>Keterangan :</span>
                <span>$keterangan</span>
            </div>";
            }

            echo "</div>";
        }
        exit();
    } else {
        echo "<p>Tidak ada transaksi.</p>";
    }
} else {
    session_start();
    include 'conn.php';

    $batas = 5;
    $halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
    $halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

    $previous = $halaman - 1;
    $next = $halaman + 1;

    $anggota_id = $_SESSION['id'];

    // Hitung total data
    $countQuery = "SELECT COUNT(*) AS total FROM transaksi WHERE anggota_id = $anggota_id";
    $countResult = $conn->query($countQuery);
    $totalData = $countResult->fetch_assoc()['total'];

    // Hitung total halaman
    $totalHalaman = ceil($totalData / $batas);

    $transaksiQuery = "SELECT * FROM transaksi WHERE anggota_id = $anggota_id ORDER BY id DESC LIMIT $halaman_awal, $batas";
    $transaksiResult = $conn->query($transaksiQuery);

    if ($transaksiResult && $transaksiResult->num_rows > 0) {
        while ($transaksi = $transaksiResult->fetch_assoc()) {
            $tanggalTransaksi = $transaksi['tanggal'];
            $jenisTransaksi = $transaksi['jenis_transaksi'];
            $jumlahTransaksi = number_format($transaksi['jumlah'], 0, ',', '.'); // Formatkan ke format uang
            $keterangan = $transaksi['keterangan']; // Tambahkan baris ini untuk mengambil keterangan

            echo "
    <div class='transaction'>
        <div>
            <span>Tanggal :</span>
            <span>$tanggalTransaksi</span>
        </div>
        <div>
            <span>Jenis Transaksi :</span>
            <span>$jenisTransaksi</span>
        </div>
        <div>
            <span>Jumlah :</span>
            <span>Rp$jumlahTransaksi</span>
        </div>";
            // Tambahkan kondisi untuk menampilkan div keterangan hanya jika keterangan ada
            if (!empty($keterangan)) {
                echo "
            <div>
                <span>Keterangan :</span>
                <span>$keterangan</span>
            </div>";
            }

            echo "</div>";
        }

        // Tampilkan tombol navigasi
        echo "<div class='pagination'>";
        if ($halaman > 1) {
            echo "<a href='javascript:void(0)' class='pagination-link' data-page='$previous'>Previous</a>";
        }

        for ($i = 1; $i <= $totalHalaman; $i++) {
            // Tambahkan kelas 'disabled' untuk menonaktifkan tautan pada halaman aktif
            $class = ($i == $halaman) ? 'disabled' : '';
            echo "<a href='javascript:void(0)' class='pagination-link $class' data-page='$i'>$i</a>";
        }

        if ($halaman < $totalHalaman) {
            echo "<a href='javascript:void(0)' class='pagination-link' data-page='$next'>Next</a>";
        }
        echo "</div>";
    } else {
        echo "<p>Tidak ada transaksi.</p>";
    }
}
?>

<script>
    $(document).ready(function() {
        $(".pagination-link").on("click", function(e) {
            e.preventDefault();
            $("#loading-transaction").show();
            var page = $(this).data("page");

            $.ajax({
                url: "/config/transaction.php?halaman=" + page,
                type: "GET",
                success: function(data) {
                    $("#transaction-history").html(data);
                    $("#loading-transaction").hide();
                },
            });
        });
    });
</script>