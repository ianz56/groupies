<?php
include_once 'dashboard/basicinfo.php';

session_start();

if (isset($_SESSION['username'])) {
  header("Location: /index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GroupiesSaver - Login</title>
  <link rel="stylesheet" href="/css/style.css?v=<?php echo VERSION ?>" />
  <script src="https://kit.fontawesome.com/d1c5590ed7.js" crossorigin="anonymous"></script>
  <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png" />
  <link rel="manifest" href="/img/site.webmanifest" />
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
  <header>
    <div class="logo">
      <span><?php echo NAME ?></span>
    </div>
  </header>
  <main id="login">
    <div id="info">
      <div>
        <h2>Selamat Datang 👋</h2>
        <span class="form-desc">Silakan masukkan informasi login Anda</span>
      </div>
      <div id="pwd-box" class="pwd-box float" style="display: none;">
        <div id="error-message"></div>
        <span class="close-pwd"><i class="fa-solid fa-xmark"></i></span>
      </div>
    </div>

    <section>
      <form id="login-form" onsubmit="event.preventDefault(); loginAjax();">
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

        <div id="loading" style="display: none;">Loading...</div>
        <input type="submit" class="btn btn-success btn-sm btn-block login" value="Login" hidden />
        <div class="submit-button" onclick="loginAjax()">Login</div>
      </form>
    </section>
  </main>
  <div class="border"></div>
  <?php include 'footer.php' ?>
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

    $(".close-pwd").click(function() {
      $("#pwd-box").fadeOut();
    });
    $("#pwd-box").click(function() {
      $("#pwd-box").fadeOut();
    })


    function loginAjax() {
      const username = document.getElementsByName("username")[0].value;
      const password = document.getElementsByName("password")[0].value;

      // Validasi bahwa kedua kolom tidak boleh kosong
      if (username.trim() === "" || password.trim() === "") {
        $("#pwd-box").fadeIn();
        $('#error-message').text("Username dan password harus diisi.")
        return;
      }
      const formData = $('#login-form').serialize();
      const submitButton = $('.submit-button');
      const errorMessage = $('#error-message');

      submitButton.html('<div class="loader"></div>');
      errorMessage.empty();
      $("#pwd-box").fadeOut();


      $.ajax({
        type: 'POST',
        url: '/config/login-conn.php',
        data: formData,
        cache: false,
        dataType: 'json',
        success: function(response) {
          if (response.status === 'success') {
            window.location.href = '/';
          } else {
            // Handle login failure
            $("#pwd-box").fadeIn();
            errorMessage.text('Login gagal: ' + response.message);
            submitButton.text('Login');

          }
        },
        error: function(xhr, status, error) {
          $("#pwd-box").fadeIn();
          console.error('AJAX Error: ' + status, error);
          errorMessage.text('Terjadi kesalahan saat login. Coba lagi.');
          submitButton.text('Login');
        },
        complete: function() {
          // Menyembunyikan elemen loading setelah permintaan selesai (baik sukses atau gagal)
          // loading.hide();
        }
      });
    }
  </script>

</body>

</html>