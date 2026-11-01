
<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "my_campus";

$connection = mysqli_connect(
    $dbhost,
    $dbuser,
    $dbpass,
    $dbname
);

if (!$connection) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
