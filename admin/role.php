<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != '1') {
    echo "<script>window.location = '/login.php'</script>";
    exit();
}
include '../config/conn.php';
$query_anggota = "SELECT id, nama FROM anggota";
$result_anggota = $conn->query($query_anggota);
?>

<div class="admin-post">

    <h3>Admin Panel - Edit Role</h3>
    <br>

    <div id="message-container-loading" style="display: none;">Loading...</div>
    <div id="message-container"></div>
    <br>

    <form method="POST" class="admin-form-post" id="admin-role">
        <div class="form-group">
            <label for="anggota_id">ID Anggota:</label>
            <select name="anggota_id" id="anggota_id" class="form-control" required>
                <?php while ($row = $result_anggota->fetch_assoc()) : ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <input type="checkbox" name="add_transaksi" id="add_transaksi">
            <label for="add_transaksi">Role: Tambah Transaksi</label><br />

            <input type="checkbox" name="del_transaksi" id="del_transaksi">
            <label for="del_transaksi">Role: Hapus Transaksi</label><br />

            <input type="checkbox" name="add_user" id="add_user">
            <label for="add_user">Role: Tambah Pengguna</label><br />

            <input type="checkbox" name="del_user" id="del_user">
            <label for="del_user">Role: Hapus Pengguna</label><br />
        </div>

        <input type="text" name="user-role" id="user-role" value="user-role" readonly required hidden>
        <input type="submit" value="Submit" class="btn-submit">
    </form>
</div>

<script>
    $("#admin-role").submit(function(event) {
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
                $('#admin-role')[0].reset();
            },
            error: function() {
                messageload.hide();
                messagecontainer.html("Terjadi kesalahan saat mengirim data.").show();
            },
            timeout: timeout,
            complete: function(xhr, status) {
                $('#admin-role').find(':submit').prop('disabled', false);
            }
        });
    });
</script>