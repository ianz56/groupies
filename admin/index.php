<?php
session_start();
if (!isset($_SESSION['admin']) ||  $_SESSION['admin'] != '1') {
    echo "<script>window.location = '/login.'</script>";
    exit();
}

$timeout = 600;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    echo "<script>window.location = '/config/logout.php'</script>";
    exit();
}

$_SESSION['last_activity'] = time();

?>



<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no">
    <title>GroupiesSaver ADMIN</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    <link rel="manifest" href="/img/site.webmanifest">
    <link rel="stylesheet" href="/admin/css/style.css" />
    <script src="https://kit.fontawesome.com/d1c5590ed7.js" crossorigin="anonymous"></script>
    <script src="/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <header>
        <div class="logo">
            <span>GroupiesSaver</span><span style="font-size: small"> | ADMIN</span>
        </div>
    </header>
    <main class="container">
        <section>
            <div class="button">
                <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == '1') {
                    echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/dashboard.php">Dashboard</a>';
                    echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/addtranc.php">Tambah Transaksi</a>';
                    echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/adduser.php">Tambah Anggota</a>';
                    echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/appruser.php">Terima Anggota</a>';
                    echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/deleteuser.php">Hapus Anggota</a>';
                    echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/deletetranc.php">Hapus Transaksi</a>';
                    echo '<a class="ajax-button" href="javascript:void(0)" data-url="/admin/role.php">Role</a>';
                    echo '<a class="ajax-button" href="javascript:void(0)" data-url="/dashboard/changepassword.php">Ganti Password</a>';
                } ?>
                <a class="ajax-button" href="/config/logout.php" data-url="/config/logout.php">Logout</a>
            </div>
            <div id="ajax-result">
                <div id="ajax-display">
                    <p>Memuat...</p>
                </div>
                <div id="ajax-loading" style="display: none;" class="page-load">
                    <div class="loading-popup">
                        <div>
                            <div class="loading-spinner"></div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                var defaultButton = $(".ajax-button[data-url='/admin/dashboard.php']");
                if (defaultButton.length > 0) {
                    defaultButton.addClass("active");

                    $.ajax({
                        url: defaultButton.attr("data-url"),
                        type: "GET",
                        success: function(response) {
                            $("#ajax-display").html(response);
                        },
                        error: function() {
                            console.error("Error loading default content.");
                        },
                    });
                } else {
                    console.error("Default button not found.");
                }
            }, 2000);

            function loadContent(button, loadingElement) {
                var url = button.data("url");

                $(".ajax-button, .chg-pwd-button").removeClass("active");
                button.addClass("active");
                loadingElement.show();

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        $("#ajax-display").html(response);
                        loadingElement.hide();
                    },
                    error: function() {
                        console.error("Error loading content.");
                        loadingElement.hide();
                    },
                });
            }

            $(".chg-pwd-button").click(function() {
                loadContent($(this), $("#ajax-loading"));
            });

            $(".ajax-button").click(function() {
                loadContent($(this), $("#ajax-loading"));
            });
        });
    </script>

</body>

</html>