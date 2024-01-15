<?php
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
$query = "SELECT t.*, a.nama_panggilan as anggota_nama, DATE_FORMAT(t.tanggal, '%y-%m-%d %H:%i') as formatted_tanggal
          FROM transaksi t
          INNER JOIN anggota a ON t.anggota_id = a.id
          WHERE t.anggota_id != 1 ORDER BY t.tanggal DESC";

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

<div id="loading" style="display: none;">Loading...</div>
<div>
    <canvas id="transactionChart" width="600" height="600"></canvas>
</div>

<script>
    // Tampilkan loading
    document.getElementById('loading').style.display = 'block';

    $.ajax({
        url: "/admin/updateOptions.php?all-chart",
        method: "GET",
        dataType: "json",
        success: function(chartData) {
            // Sembunyikan loading setelah selesai memuat data
            document.getElementById('loading').style.display = 'none';

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
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        },
        error: function(xhr, status, error) {
            // Sembunyikan loading dan tampilkan pesan error
            document.getElementById('loading').style.display = 'none';
            document.getElementById("transactionChart").innerHTML = "Error pada Chart: " + error;
            console.error("Error fetching chart data:", error);
        }
    });
</script>