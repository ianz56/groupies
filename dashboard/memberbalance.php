<?php
session_abort();
session_start();
if (!isset($_SESSION['admin']) || ($_SESSION['admin'] != '0')) {
    if (isset($_SESSION['admin']) && ($_SESSION['admin'] = '1')) {
        header("Location: /admin");
    }
    echo "<script>window.location = '/login.php'</script>";
    exit();
}
include '../config/conn.php';

// Konfigurasi pagination
$records_per_page = 7;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Query untuk mendapatkan jumlah total data
$total_rows_query = "SELECT COUNT(*) AS total_rows FROM transaksi WHERE anggota_id != 1";
$total_rows_result = $conn->query($total_rows_query);
$total_rows = $total_rows_result->fetch_assoc()['total_rows'];

// Query untuk mendapatkan data transaksi dengan pagination
$query = "SELECT t.*, a.nama_panggilan as anggota_nama, DATE_FORMAT(t.tanggal, '%d-%m-%Y %H:%i') as formatted_tanggal
          FROM transaksi t
          INNER JOIN anggota a ON t.anggota_id = a.id
          WHERE t.anggota_id != 1 ORDER BY t.tanggal DESC LIMIT $offset, $records_per_page";

$result = $conn->query($query);

// Menghitung jumlah halaman
$total_pages = ceil($total_rows / $records_per_page);

// Mendapatkan saldo anggota
$query_saldo = "SELECT SUM(jumlah) AS total_saldo FROM transaksi WHERE anggota_id != 1";
$result_saldo = $conn->query($query_saldo);
$row_saldo = $result_saldo->fetch_assoc();
$totalSaldo = $row_saldo['total_saldo'];
$formattedSaldo = 'Rp' . number_format($totalSaldo, 0, ',', '.');
?>

<h1>Saldo Anggota: <?php echo $formattedSaldo ?></h1>
<br>
<div>
    <canvas id="transactionChart" width="300" height="300"></canvas>
</div>

<br>
<p>7 Riwayat Transaksi Terakhir:</p>
<div class="transaction-table">
    <table id="transaksiTable">
        <thead>
            <tr>
                <th class="th-id">ID Trans.</th>
                <th class="th-name">Nama</th>
                <th class="th-amount">Jumlah</th>
                <th class="th-date">Tanggal</th>
                <th class="th-type">Jenis Transaksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['anggota_nama'] . "</td>";
                echo "<td>" . 'Rp' . number_format($row['jumlah'], 0, ',', '.') . "</td>";
                echo "<td>" . $row['formatted_tanggal'] . "</td>";
                echo "<td>" . $row['jenis_transaksi'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<br><br><br>
<!-- Pagination Links -->
<!-- <div class="pagination"> -->
<?php
// for ($i = 1; $i <= $total_pages; $i++) {
// echo "<a href='javascript:void(0)' class='page-link' data-page='$i'>$i</a>";
// }
// 
?>
<!-- </div> -->

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
<script>
    // Use AJAX to fetch data from the server
    $.ajax({
        url: "/admin/updateOptions.php?all-chart",
        method: "GET",
        dataType: "json",
        success: function(chartData) {
            var anggotaNames = chartData.map(item => item.anggota_nama);
            var totalAmounts = chartData.map(item => item.total_amount);
            var ctx = document.getElementById('transactionChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: anggotaNames,
                    datasets: [{
                        label: 'Total Transaction Amount',
                        data: totalAmounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 170, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    // responsive: false,
                    maintainAspectRatio: false, // Add this line to disable aspect ratio
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        },
        error: function(xhr, status, error) {
            console.error("Error fetching chart data:", error);
        }
    });
</script>
<script>
    $(document).ready(function() {
        $(".page-link").click(function() {
            console.log("Button clicked!");
            var page = $(this).data("page");
            $.ajax({
                url: "/config/ajax_pagination.php",
                type: "GET",
                data: {
                    page: page,
                },
                success: function(response) {
                    $("#transaksiTable tbody").html(response);
                },
            });
        });
    });
</script>