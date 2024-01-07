<?php
session_start(); // Pastikan telah memulai sesi di file ini

// Cek apakah pengguna sudah login
if (isset($_SESSION['username'])) {
  // Jika sudah, redirect ke index.php
  header("Location: /index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LOGIN</title>
  <link rel="stylesheet" href="css/style.css?v=2" />
  <script src="https://kit.fontawesome.com/d1c5590ed7.js" crossorigin="anonymous"></script>
  <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png" />
  <link rel="manifest" href="/img/site.webmanifest" />
</head>

<body>
  <header>
    <div class="logo">
      <span>GroupiesSaver</span><span style="font-size: small"> | Beta</span>
    </div>
  </header>
  <main id="login">
    <div>
      <h2>Login</h2>
    </div>
    <span class="form-desc">Masukkan username dan password</span>
    <br /><br />
    <section>
      <form action="config/cek_login.php" method="post" id="login-form">
        <label for="username">
          <i class="fa-solid fa-user"></i>
          <input type="text" class="form-control" placeholder="Username" name="username" required="Masukkan Username" autocomplete="off" />
        </label>
        <label for="password">
          <i class="fa-solid fa-key"></i>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off" />
        </label>
        <label for="showPassword">
          <div class="showPass">
            <input type="checkbox" title="showPassword" id="showPassword" name="showPassword" onclick="showPass()" />
            <p id="show">Lihat Password</p>
          </div>
        </label>

        <input type="submit" class="btn btn-success btn-sm btn-block login" value="Login" />
      </form>
    </section>
  </main>
  <script>
    function showPass() {
      const password = document.getElementById("password");
      const showPassword = document.getElementById("showPassword");

      if (showPassword.checked) {
        password.setAttribute("type", "text");
      } else {
        password.setAttribute("type", "password");
      }
    }
  </script>
</body>

</html>