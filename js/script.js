$(document).ready(function () {
  var defaultButton = $(".ajax-button[data-url='/config/transaction.php']");

  if (defaultButton.length > 0) {
    defaultButton.addClass("active");

    $.ajax({
      url: defaultButton.attr("data-url"),
      type: "GET",
      success: function (response) {
        $("#transaction-history").html(response);
      },
      error: function () {
        console.error("Error loading default content.");
      },
    });
  } else {
    console.error("Default button not found.");
  }

  function loadContent(button, loadingElement) {
    var url = button.data("url");

    // Menghapus kelas 'active' dari semua tombol yang terlibat
    $(".ajax-button, .chg-pwd-button, .ajax-button-nav").removeClass("active");

    // Menambahkan kelas 'active' pada tombol yang sedang diklik
    if (!button.hasClass("ajax-button-nav")) {
      button.addClass("active");
    }

    // Menampilkan elemen loading
    loadingElement.show();

    $.ajax({
      type: "GET",
      url: url,
      success: function (response) {
        // Memuat konten ke dalam elemen dengan ID 'transaction-history'
        $("#transaction-history").html(response);
      },
      error: function () {
        console.error("Error loading content.");
      },
      complete: function () {
        // Menyembunyikan elemen loading setelah permintaan selesai (baik berhasil atau gagal)
        loadingElement.hide();
      },
    });
  }

  // Menggunakan event handler untuk semua jenis tombol yang terlibat
  $(".chg-pwd-button, .ajax-button, .ajax-button-nav").click(function () {
    loadContent($(this), $("#loading-transaction"));
  });

  $(".close-pwd, .changepassword").click(function () {
    $("#pwd-box").fadeOut();
  });

  $(".burger, .nav-bg, .ajax-button-nav").click(function () {
    $(".nav-menu, nav, .nav-bg").toggleClass("nav-active");
    $(".burger").toggleClass("toggle");
  });

  // $("#profile").click(function () {
  //   $("#navMenu").toggle();
  // });

  $("#welcome").click(function () {
    $("#navMenu").fadeIn();
  });

  $(".CloseBtn").click(function () {
    $("#profile-form-feedback").hide();
  });

  $("#profile-form").submit(function (event) {
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

    var ajaxTimeout = setTimeout(function () {
      errorMessage.show();
    }, timeout);

    $.ajax({
      type: "POST",
      url: "/config/profile.php",
      data: formData,
      dataType: "json",
      processData: false,
      contentType: false,
      success: function (response) {
        clearTimeout(ajaxTimeout);
        loadingSection.hide();
        if (response.success) {
          successMessage.show();
        } else {
          errorMessage.show();
        }
      },
      error: function () {
        clearTimeout(ajaxTimeout);
        loadingSection.hide();
        errorMessage.show();
      },
    });
  });

  $("#openPopupBtn").click(function () {
    console.log("button clicked");
    $("#popup").fadeIn();
  });

  $("#closePopupBtn").click(function () {
    console.log("button clicked");
    $("#popup").fadeOut();
  });
  $(".bg-popup").click(function () {
    $("#popup").fadeOut();
    $("#navMenu").fadeOut();
  });
  $(".close-btn").click(function () {
    $("#popup").fadeOut();
    $("#navMenu").fadeOut();
    $("#popup").fadeOut();
  });
});
