<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != '1') {
    if (!isset($_SESSION['deltr']) || $_SESSION['deltr'] != '1') {
        header("HTTP/1.1 403 Forbidden");
        echo "Maaf, akses ke halaman ini ditolak.";
        exit();
    }
}
include '../config/conn.php';
?>
<div class="admin-post">
    <h3>Admin Panel - Hapus Transaksi</h3>
    <br>
    <div id="message-container-loading" style="display: none;">Loading...</div>
    <div id="message-container"></div>
    <br>
    <form method="POST" class="admin-form-post" id="admin-del-trans">
        <label for="transaksi_id">Pilih Transaksi:</label>
        <select name="transaksi_id" id="transaksi_id" class="form-control" required>
        </select>
        <br>
        <input type="text" name="del-tranc" id="del-tranc" value="del-tranc" readonly required hidden>
        <input type="submit" value="Hapus Transaksi" class="btn-submit">
    </form>
</div>


<script>
    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "/admin/updateOptions.php?usertrans", // Gantilah dengan skrip PHP yang mengembalikan opsi terbaru
            dataType: "json",
            success: function(response) {
                $("#transaksi_id").html(response.options);
            },
            error: function() {
                console.error("Error fetching updated transaction options.");
            }
        });
        $("#admin-del-trans").submit(function(event) {
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
                success: function(data) {
                    messageload.hide();
                    messagecontainer.html(data);
                    $('#admin-del-trans')[0].reset();

                    // Perbarui opsi dalam elemen select setelah penghapusan berhasil
                    $.ajax({
                        type: "GET",
                        url: "/admin/updateOptions.php?usertrans", // Gantilah dengan skrip PHP yang mengembalikan opsi terbaru
                        dataType: "json",
                        success: function(response) {
                            $("#transaksi_id").html(response.options);
                        },
                        error: function() {
                            console.error("Error fetching updated transaction options.");
                        }
                    });
                },
                error: function() {
                    messageload.hide();
                    messagecontainer.html("Terjadi kesalahan saat mengirim data.").show();
                },
                timeout: timeout,
                complete: function(xhr, status) {
                    $('#admin-del-trans').find(':submit').prop('disabled', false);
                }
            });
        });
    });
</script>