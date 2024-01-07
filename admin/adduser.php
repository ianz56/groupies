<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != '1') {
    if (!isset($_SESSION['adduser']) || $_SESSION['adduser'] != '1') {
        header("HTTP/1.1 403 Forbidden");
        echo "Maaf, akses ke halaman ini ditolak.";
        exit();
    }
}
?>

<div class="admin-post">

    <h3>Admin Panel - Tambah User</h3>
    <br>

    <div id="message-container-loading" style="display: none;">Loading...</div>
    <div id="message-container"></div>
    <br>

    <form method="POST" class="admin-form-post" id="admin-add-user">
        <div class="form-group">
            <label for="anggota_id">Nama Anggota:</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="username">Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <input type="text" name="add-user" id="add-user" value="add-user" readonly required hidden>
        <input type="submit" value="Input User" class="btn-submit">
    </form>
</div>



<script>
    $("#admin-add-user").submit(function(event) {
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
            beforeSend: function() {
                $('#admin-add-user').find(':submit').prop('disabled', true);
            },
            success: function(data) {
                messageload.hide();
                messagecontainer.html(data);
                $('#admin-add-user')[0].reset();
            },
            error: function() {
                messageload.hide();
                messagecontainer.html("Terjadi kesalahan saat mengirim data.").show();
            },
            timeout: timeout,
            complete: function(xhr, status) {
                $('#admin-add-user').find(':submit').prop('disabled', false);
            }
        });
    });
</script>