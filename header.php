<?php include 'dashboard/basicinfo.php' ?>
<header>
  <div class="logo">
    <span><?php echo NAME ?></span>
    <!-- <span style="font-size: small"> | Beta</span> -->
  </div>
  <div class="profile" id="profile">
    <i class="fa-solid fa-user"></i>
  </div>
</header>
<nav id="navMenu">
  <div class="bg-popup"></div>
  <div class="profile-popup">
    <form id="profile-form" method="POST">
      <div class="profile-form">
        <span id="close-btn" class="close-btn"><i class="fa-solid fa-xmark"></i></span>
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
  </div>
</nav>