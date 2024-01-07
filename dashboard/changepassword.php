<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

include '../config/conn.php';

$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    if ($newPassword === $confirmPassword) {
        $query = "UPDATE anggota SET password = ?, pwd_chg = 1 WHERE id = ?";
        $stmt = $conn->prepare($query);
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->bind_param("si", $hashedPassword, $user_id);
        $stmt->execute();

        // Tambahkan baris berikut untuk mengatur pwd_chg menjadi 1
        $updatePwdChgQuery = "UPDATE anggota SET pwd_chg = 1 WHERE id = ?";
        $updatePwdChgStmt = $conn->prepare($updatePwdChgQuery);
        $updatePwdChgStmt->bind_param("i", $user_id);
        $updatePwdChgStmt->execute();
        $updatePwdChgStmt->close();
        $_SESSION['pwd_chg'] = '1';
        $stmt->close();
        echo json_encode(["success" => true]);
        exit();
    } else {
        echo json_encode(["success" => false, "error" => "Password baru tidak cocok dengan konfirmasi password."]);
        exit();
    }
}

?>

<div class="changepasswordform">
    <h2>Ganti Password</h2>
    <?php if (isset($error)) { ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php } ?>
    <?php if (isset($_GET['success']) && $_GET['success'] === 'true') { ?>
        <div class="success-message">Password berhasil diubah!</div>
    <?php } ?>
    <div id="error-message" class="error-message" style="color: red;"></div>
    <div id="success-message" class="success-message" style="color: lightseagreen;"></div>
    <form id="changepassword-form" method="POST">
        <label for="new_password">Password Baru:</label>
        <input type="password" name="new_password" placeholder="Password Baru" required>
        <label for="confirm_password">Konfirmasi Password Baru:</label>
        <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
        <button type="submit">Ganti Password</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $("#changepassword-form").submit(function(event) {
            event.preventDefault();

            var form = $(this);
            var formData = form.serialize();

            var errorContainer = $("#error-message");
            var successContainer = $("#success-message");

            $.ajax({
                type: "POST",
                url: "dashboard/changepassword.php", // Ganti dengan URL yang sesuai
                data: formData,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        errorContainer.hide();
                        successContainer.text("Password berhasil diubah! Dan web ini akan memulai ulang dalam 3 detik.").show();
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else {
                        successContainer.hide();
                        errorContainer.text(response.error).show();
                    }
                },
                error: function() {
                    successContainer.hide();
                    errorContainer.text("Terjadi kesalahan saat mengirim data.").show();
                }
            });
        });
    });
</script>