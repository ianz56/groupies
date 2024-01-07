<?php
date_default_timezone_set('Asia/Jakarta');
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != '1') {
    if (!isset($_SESSION['addtr']) || $_SESSION['addtr'] != '1') {
        header("HTTP/1.1 403 Forbidden");
        echo "Maaf, akses ke halaman ini ditolak.";
        exit();
    }
}
include '../config/conn.php';
$query_anggota = "SELECT id, nama FROM anggota";
$result_anggota = $conn->query($query_anggota);
?>

<div class="admin-post">

    <h3>Admin Panel - Input Transaksi</h3>
    <br>

    <div id="message-container-loading" style="display: none;">Loading...</div>
    <div id="message-container"></div>
    <br>

    <form method="POST" class="admin-form-post" id="admin-add-trans">
        <div class="form-group">
            <label for="anggota_id">ID Anggota:</label>
            <select name="anggota_id" id="anggota_id" class="form-control" required>
                <?php while ($row = $result_anggota->fetch_assoc()) : ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="jumlah">Jumlah:</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" inputmode="numeric" required>
        </div>
        <div class="form-group">
            <label for="tanggal">Tanggal:</label>
            <input type="datetime-local" name="tanggal" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="jenis_transaksi">Jenis Transaksi:</label>
            <select name="jenis_transaksi" id="jenis_transaksi" class="form-control" required>
                <option value="Setoran">Setoran</option>
                <option value="Penarikan">Penarikan</option>
            </select>
        </div>
        <div class="form-group">
            <label for="ket">Keterangan:</label>
            <input type="text" name="ket" id="ket" class="form-control">
        </div>
        <input type="text" name="add-tranc" id="add-tranc" value="add-tranc" readonly required hidden>
        <input type="submit" value="Input Transaksi" class="btn-submit">
    </form>
</div>

<script>
    $("#admin-add-trans").submit(function(event) {
        event.preventDefault();
        var form = $(this);
        var formData = form.serialize();
        var timeout = 2000;
        var messagecontainer = $("#message-container");
        var messageload = $("#message-container-loading");

        messageload.show();
        $.ajax({
            type: "POST",
            url: "/admin/inputconn.php",
            data: formData,
            dataType: "text",
            // beforeSend: function() {
            //     $('#admin-add-trans').find(':submit').prop('disabled', true); // Menonaktifkan tombol submit selama pengiriman data
            // },
            success: function(data) {
                messageload.hide();
                messagecontainer.html(data);
                $('#admin-add-trans')[0].reset();
            },
            error: function() {
                messageload.hide();
                messagecontainer.html("Terjadi kesalahan saat mengirim data.").show();
            },
            timeout: timeout,
            complete: function(xhr, status) {
                $('#admin-add-trans').find(':submit').prop('disabled', false);
            }
        });
    });
</script>