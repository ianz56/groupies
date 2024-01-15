<?php
  session_start();
  session_destroy();
  //  header("location:index.php");
  echo"<script>window.location = '/index.php'</script>";
