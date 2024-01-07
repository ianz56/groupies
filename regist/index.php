<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Regist</title>
  <link rel="stylesheet" href="/css/style.css?v=2" />
  <script src="https://kit.fontawesome.com/d1c5590ed7.js" crossorigin="anonymous"></script>
  <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png" />
  <link rel="manifest" href="/img/site.webmanifest" />
  <script src="/js/jquery.min.js"></script>
</head>

<body>
  <header>
    <div class="logo">
      <span>GroupiesSaver</span><span style="font-size: small"> | Register</span>
    </div>
  </header>
  <main id="login">
    <div>
      <h2>Register</h2>
      <span>Masukkan nama, username, dan password</span>
    </div>
    <br />
    <section>
      <div id="message-container-loading" style="display: none">
        Loading...
      </div>
      <div id="message-container"></div>
      <br />
      <form method="post" id="login-form">
        <label for="name">
          <i class="fa-solid fa-user"></i>
          <input type="text" class="form-control" placeholder="Nama" name="nama" required="Masukkan Nama" autocomplete="off" />
        </label>
        <label for="username">
          <i class="fa-solid fa-user"></i>
          <input type="text" class="form-control" placeholder="Username" name="username" required="Masukkan Username" autocomplete="off" />
        </label>
        <label for="password">
          <i class="fa-solid fa-key"></i>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off" />
        </label>
        <div class="showPass">
          <input type="checkbox" id="showPassword" name="showPassword" onclick="showPass()" />
          <p id="show">Lihat Password</p>
        </div>
        <input type="text" name="regist" id="regist" value="regist" readonly required hidden />

        <input type="submit" class="btn btn-success btn-sm btn-block login" value="Daftar" />
      </form>

      <a class="btn btn-success btn-sm btn-block login" style="display: block; width: 100%; text-align:center; color:var(--bg2); margin: 5px;" href="/">Login / Beranda</a>
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

    $("#login-form").submit(function(event) {
      event.preventDefault();
      var form = $(this);
      var formData = form.serialize();
      var timeout = 2000;
      var messagecontainer = $("#message-container");
      var messageload = $("#message-container-loading");

      messageload.show();
      $.ajax({
        type: "POST",
        url: "/regist/register.php",
        data: formData,
        dataType: "text",
        beforeSend: function() {
          $("#login-form").find(":submit").prop("disabled", true);
        },
        success: function(data) {
          messageload.hide();
          messagecontainer.html(data);
          $("#login-form")[0].reset();
        },
        error: function() {
          messageload.hide();
          messagecontainer
            .html("Terjadi kesalahan saat mengirim data.")
            .show();
        },
        timeout: timeout,
        complete: function(xhr, status) {
          $("#login-form").find(":submit").prop("disabled", false);
        },
      });
    });
  </script>
</body>

</html>