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

    $(".ajax-button, .chg-pwd-button").removeClass("active");
    button.addClass("active");
    loadingElement.show();

    $.ajax({
      type: "GET",
      url: url,
      success: function (response) {
        $("#transaction-history").html(response);
        loadingElement.hide();
      },
      error: function () {
        console.error("Error loading content.");
        loadingElement.hide();
      },
    });
  }

  $(".chg-pwd-button").click(function () {
    loadContent($(this), $("#loading-transaction"));
  });

  $(".ajax-button").click(function () {
    loadContent($(this), $("#loading-transaction"));
  });

  $(".close-pwd").click(function () {
    $("#pwd-box").hide();
  });

  $("#profile").click(function () {
    $("#navMenu").toggle();
  });

  $("#welcome").click(function () {
    $("#navMenu").fadeIn();
  });

  $(".changepassword").click(function () {
    $("#navMenu").fadeOut();
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
