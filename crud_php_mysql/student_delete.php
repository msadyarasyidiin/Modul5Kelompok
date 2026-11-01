<?php
  session_start();
  if (!isset($_SESSION["username"])) { header("Location: login.php"); die(); }
  include("connection.php");

  if (isset($_GET["nim"])) {
    $nim = mysqli_real_escape_string($connection, $_GET["nim"]);
    $query = "DELETE FROM student WHERE nim='$nim'";
    if (mysqli_query($connection, $query)) {
      $message = urlencode("Data dengan NIM <b>$nim</b> berhasil dihapus");
    } else {
      $message = urlencode("Gagal menghapus data");
    }
    header("Location: student_view.php?message=$message");
    die();
  }
  header("Location: student_view.php");
?>