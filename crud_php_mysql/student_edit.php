<?php
  session_start();
  if (!isset($_SESSION["username"])) { header("Location: login.php"); die(); }
  include("connection.php");

  $error_message = "";

  if (isset($_POST["submit"])) {
    // 1. Ambil & bersihkan input
    $nim        = mysqli_real_escape_string($connection, trim($_POST["nim"]));
    $name       = mysqli_real_escape_string($connection, htmlentities(strip_tags(trim($_POST["name"]))));
    $birth_city = mysqli_real_escape_string($connection, htmlentities(strip_tags(trim($_POST["birth_city"]))));
    $birth_date = mysqli_real_escape_string($connection, $_POST["birth_date"]);
    $faculty    = mysqli_real_escape_string($connection, $_POST["faculty"]);
    $department = mysqli_real_escape_string($connection, htmlentities(strip_tags(trim($_POST["department"]))));
    $gpa        = $_POST["gpa"];

    // 2. Validasi
    if (empty($name))       $error_message .= "- Nama belum diisi <br>";
    if (empty($birth_city)) $error_message .= "- Tempat lahir belum diisi <br>";
    if (empty($department)) $error_message .= "- Jurusan belum diisi <br>";
    if (!is_numeric($gpa) || $gpa <= 0) $error_message .= "- IPK harus angka <br>";

    // 3. Jika valid, UPDATE
    if ($error_message === "") {
      $gpa = (float) $gpa;
      $query = "UPDATE student SET
                  name='$name', birth_city='$birth_city', birth_date='$birth_date',
                  faculty='$faculty', department='$department', gpa=$gpa
                WHERE nim='$nim'";
      if (mysqli_query($connection, $query)) {
        $message = urlencode("Data mahasiswa <b>$name</b> berhasil diubah");
        header("Location: student_view.php?message=$message");
        die();
      } else {
        die("Query gagal: ".mysqli_errno($connection)." - ".mysqli_error($connection));
      }
    }
  } elseif (isset($_GET["nim"])) {
    // Pertama kali dibuka: ambil data lama dari database
    $nim = mysqli_real_escape_string($connection, $_GET["nim"]);
    $result = mysqli_query($connection, "SELECT * FROM student WHERE nim='$nim'");
    $data = mysqli_fetch_assoc($result);
    if (!$data) { header("Location: student_view.php"); die(); }

    $name = $data["name"]; $birth_city = $data["birth_city"];
    $birth_date = $data["birth_date"]; $faculty = $data["faculty"];
    $department = $data["department"]; $gpa = $data["gpa"];
  } else {
    header("Location: student_view.php"); die();
  }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Data Mahasiswa</title>
  <link href="assets/style.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <h2>Edit Data Mahasiswa</h2>
    <?php if ($error_message !== "") echo "<div class='error'>$error_message</div>"; ?>
    <form action="student_edit.php" method="post">
      <fieldset>
        <legend>Edit Mahasiswa</legend>
        <p>NIM : <b><?= $nim ?></b>
           <input type="hidden" name="nim" value="<?= $nim ?>"></p>
        <p>Nama : <input type="text" name="name" value="<?= $name ?>"></p>
        <p>Tempat Lahir : <input type="text" name="birth_city" value="<?= $birth_city ?>"></p>
        <p>Tanggal Lahir : <input type="date" name="birth_date" value="<?= $birth_date ?>"></p>
        <p>Fakultas :
          <select name="faculty">
            <option value="FTIB"  <?= $faculty=="FTIB"  ? "selected" : "" ?>>FTIB</option>
            <option value="FTEIC" <?= $faculty=="FTEIC" ? "selected" : "" ?>>FTEIC</option>
          </select></p>
        <p>Jurusan : <input type="text" name="department" value="<?= $department ?>"></p>
        <p>IPK : <input type="text" name="gpa" value="<?= $gpa ?>"></p>
      </fieldset>
      <p><input type="submit" name="submit" value="Simpan Perubahan">
         <a href="student_view.php">Batal</a></p>
    </form>
  </div>
</body>
</html>
<?php mysqli_close($connection); ?>