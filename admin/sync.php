<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != '1') {
    if (!isset($_SESSION['addtr']) || $_SESSION['addtr'] != '1') {
        header("HTTP/1.1 403 Forbidden");
        echo "Maaf, akses ke halaman ini ditolak.";
        exit();
    }
}
?>

<div class="admin-post">
    <h3>Admin Panel - Sync Total Transaksi</h3>
    <br>

    <div id="message-container-loading" style="display: none;">Loading...</div>
    <div id="message-container"></div>
    <br>

    <form method="POST" class="admin-form-post" id="admin-sync-totals">
        <input type="text" name="sync-total" id="sync-total" value="sync-total" readonly required hidden>
        <input type="submit" value="Sinkronkan" class="btn-submit">
    </form>
</div>

<script>
    $(".admin-form-post").submit(function(event) {
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
                $('.admin-form-post').find(':submit').prop('disabled', true); // Menonaktifkan tombol submit selama pengiriman data
            },
            success: function(data) {
                messageload.hide();
                messagecontainer.html(data);
                $('.admin-form-post')[0].reset();
            },
            error: function() {
                messageload.hide();
                messagecontainer.html("Terjadi kesalahan saat sync data.").show();
            },
            timeout: timeout,
            complete: function(xhr, status) {
                $('.admin-form-post').find(':submit').prop('disabled', false);
            }
        });
    });
</script>