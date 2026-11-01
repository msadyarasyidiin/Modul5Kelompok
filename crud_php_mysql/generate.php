
<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";

// Membuat koneksi database
$connection = mysqli_connect($dbhost, $dbuser, $dbpass);

if (!$connection) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Membuat database
$query = "CREATE DATABASE IF NOT EXISTS my_campus";

if (!mysqli_query($connection, $query)) {
    die("Query Error: " . mysqli_error($connection));
}

echo "Database my_campus berhasil dibuat.<br>";

// Memilih database
mysqli_select_db($connection, "my_campus");

// Menghapus tabel mahasiswa lama
$query = "DROP TABLE IF EXISTS student";
mysqli_query($connection, $query);

// Membuat tabel mahasiswa
$query = "CREATE TABLE student (
    nim CHAR(8) PRIMARY KEY,
    name VARCHAR(100),
    birth_city VARCHAR(50),
    birth_date DATE,
    faculty VARCHAR(50),
    department VARCHAR(50),
    gpa DECIMAL(3,2)
)";

mysqli_query($connection, $query)
    or die(mysqli_error($connection));

echo "Tabel student berhasil dibuat.<br>";

// Memasukkan data mahasiswa
$query = "INSERT INTO student VALUES
('12041952', 'Brian Yuko', 'Padang', '1996-11-23', 'FTIB', 'Sistem Informasi', 3.1),
('12042010', 'Safira Yanuar', 'Bandung', '1994-08-22', 'FTIB', 'Sistem Informasi', 3.9),
('12042012', 'Rezja Zalva', 'Jakarta', '1997-12-31', 'FTIB', 'Informatika', 3.5),
('12042034', 'Agha Rizky', 'Jakarta', '1997-06-28', 'FTEIC', 'Sains Data', 3.4),
('12042041', 'Adhiaksa', 'Medan', '1995-04-02', 'FTEIC', 'Bisnis Digital', 3.7)";

mysqli_query($connection, $query)
    or die(mysqli_error($connection));

echo "Data mahasiswa berhasil ditambahkan.<br>";

// Menghapus tabel admin lama
$query = "DROP TABLE IF EXISTS admin";
mysqli_query($connection, $query);

// Membuat tabel admin
$query = "CREATE TABLE admin (
    username VARCHAR(50),
    password CHAR(40)
)";

mysqli_query($connection, $query)
    or die(mysqli_error($connection));

// Menambahkan akun admin
$username = "admin";
$password = sha1("adminmahasiswa");

$query = "INSERT INTO admin VALUES ('$username', '$password')";

mysqli_query($connection, $query)
    or die(mysqli_error($connection));

echo "Tabel admin berhasil dibuat dan diisi.<br>";

// Menutup koneksi
mysqli_close($connection);
?>
