<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != '1') {
    if (!isset($_SESSION['deluser']) || $_SESSION['deluser'] != '1') {
        header("HTTP/1.1 403 Forbidden");
        echo "Maaf, akses ke halaman ini ditolak.";
        exit();
    }
}
?>
<div class="admin-post">
    <h3>Admin Panel - Hapus Anggota</h3>
    <br>
    <div id="message-container-loading" style="display: none;">Loading...</div>
    <div id="message-container"></div>
    <br>
    <form method="POST" class="admin-form-post" id="admin-del-user">
        <label for="Anggota">Pilih Anggota:</label>
        <select name="anggota_id" id="anggota_id" class="form-control" required>
        </select>
        <br>
        <input type="text" name="del-user" id="del-user" value="del-user" readonly required hidden>
        <input type="submit" value="Hapus Anggota" class="btn-submit">
    </form>
</div>


<script>
    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "/admin/updateOptions.php?users", // Gantilah dengan skrip PHP yang mengembalikan opsi terbaru
            dataType: "json",
            success: function(response) {
                $("#anggota_id").html(response.options);
            },
            error: function() {
                $("#message-container").html("Error fetching updated user options.").show();
                console.error("Error fetching updated user options.");
            }
        });
        $("#admin-del-user").submit(function(event) {
            event.preventDefault();
            var form = $(this);
            var formData = form.serialize();
            var messagecontainer = $("#message-container");
            var timeout = 2000;
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
                    $('#admin-del-user')[0].reset();

                    $.ajax({
                        type: "GET",
                        url: "/admin/updateOptions.php?users",
                        dataType: "json",
                        success: function(response) {
                            $("#anggota_id").html(response.options);
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
                    $('#admin-del-user').find(':submit').prop('disabled', false);
                }
            });
        });
    });
</script>