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

    <h3>Admin Panel - Terima Anggota</h3>
    <br>

    <div id="message-container-loading" style="display: none;">Loading...</div>
    <div id="message-container"></div>
    <br>

    <form method="POST" class="admin-form-post" id="admin-appr-user">
        <div class='form-group'>

        </div>
        <input type="text" name="app-user" id="app-user" value="app-user" readonly required hidden>
        <input type="submit" value="Terima Anggota Terpilih" class="btn-submit">
    </form>
</div>


<script>
    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "/admin/updateOptions.php?user-pending", // Gantilah dengan skrip PHP yang mengembalikan opsi terbaru
            dataType: "json",
            success: function(response) {
                $(".form-group").html(response.options);
            },
            error: function() {
                console.error("Error fetching updated transaction options.");
            }
        });
        $("#admin-appr-user").submit(function(event) {
            event.preventDefault();
            var form = $(this);
            var formData = form.serialize();
            var timeout = 3000;
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
                    $('#admin-appr-user')[0].reset();

                    // Perbarui opsi dalam elemen select setelah penghapusan berhasil
                    $.ajax({
                        type: "GET",
                        url: "/admin/updateOptions.php?user-pending", // Gantilah dengan skrip PHP yang mengembalikan opsi terbaru
                        dataType: "json",
                        success: function(response) {
                            $(".form-group").html(response.options);
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
                    $('#admin-appr-user').find(':submit').prop('disabled', false);
                }
            });
        });
    });
</script>