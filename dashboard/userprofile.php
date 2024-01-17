<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/head_info.php';
$id = $r['id'];
$username = $r['username'];
$name = $r['nama'];
$email = $r['email'];
$bornplace = $r['tempat_lahir'];
$borndate = $r['tanggal_lahir'];
$isadmin = $r['is_admin'];
?>

<form id="profile-form" onsubmit="event.preventDefault();" method=" POST">
    <div class="profile-form">
        <h2>Profil</h2>
        <label for="id">ID:</label>
        <input type="number" name="id" id="id" value="<?php echo $id; ?>" readonly required />
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?php echo $username; ?>" required />
        <br />
        <label for="name">Nama:</label>
        <input type="text" name="name" id="name" value="<?php echo $name; ?>" required />
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" value="<?php echo $email; ?>" required />
        <label for="bornplace">Tempat Lahir:</label>
        <input type="text" name="bornplace" id="bornplace" value="<?php echo $bornplace; ?>" required />
        <label for="borndate">Tanggal Lahir:</label>
        <input type="date" name="borndate" id="borndate" value="<?php echo $borndate; ?>" required />
        <?php
        function displayRole($roleName, $sessionKey)
        {
            if (isset($_SESSION[$sessionKey]) && $_SESSION[$sessionKey] == '1') {
                return $roleName;
            }
            return "";
        }

        $roles = array(
            displayRole('Tambah Transaksi', 'addtr'),
            displayRole('Hapus Transaksi', 'deltr'),
            displayRole('Tambah Anggota', 'adduser'),
            displayRole('Hapus Anggota', 'deluser')
        );

        $roles = array_filter($roles);

        $rolesMessage = implode(', ', $roles);

        if (!empty($rolesMessage)) {
            echo "<div>Role: $rolesMessage</div>";
        }
        ?>


        <br />
        <input type="submit" value="Simpan" />
        <a class="changepassword chg-pwd-button" data-url="/dashboard/changepassword.php" href="javascript:void(0)">Ganti kata sandi</a>
        <a href="config/logout.php" class="logout" rel="noopener noreferrer">Logout</a>
    </div>
</form>
<div id="profile-form-feedback" style="display: none">
    <div id="profile-form-loading" class="page-load">
        <div class="loading-popup">
            <div>
                <div class="loading-spinner"></div>
            </div>
            <span>Menyimpan...</span>
        </div>
    </div>
    <div id="profile-form-success" class="profile-success page-load" style="display: none">
        <div class="loading-popup">
            <div class="popup-window success">
                <span>Data berhasil disimpan.</span>
                <button class="CloseBtn">OK</button>
            </div>
        </div>
    </div>
    <div id="profile-form-error" class="profile-error page-load" style="display: none">
        <div class="loading-popup">
            <div class="popup-window error">
                <span>Terjadi kesalahan saat menyimpan data.</span>
                <button class="CloseBtn">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(".CloseBtn").click(function() {
        $("#profile-form-feedback").hide();
    });

    $("#profile-form").submit(function(event) {
        console.log("Button clicked!");
        event.preventDefault();

        var form = $(this);
        var formData = new FormData(form[0]);

        var feedbackSection = $("#profile-form-feedback");
        var loadingSection = $("#profile-form-loading");
        var successMessage = $("#profile-form-success");
        var errorMessage = $("#profile-form-error");

        feedbackSection.show();
        loadingSection.show();
        successMessage.hide();
        errorMessage.hide();

        var timeout = 10000;

        var ajaxTimeout = setTimeout(function() {
            errorMessage.show();
        }, timeout);

        $.ajax({
            type: "POST",
            url: "/config/profile.php",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function(response) {
                clearTimeout(ajaxTimeout);
                loadingSection.hide();
                if (response.success) {
                    successMessage.show();
                } else {
                    errorMessage.show();
                }
            },
            error: function() {
                clearTimeout(ajaxTimeout);
                loadingSection.hide();
                errorMessage.show();
            },
        });
    });
</script>