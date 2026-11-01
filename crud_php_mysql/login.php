
<?php
session_start();

$error_message = "";
$username = "";

if (isset($_POST["submit"])) {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "") {
        $error_message .= "Username belum diisi. ";
    }

    if ($password === "") {
        $error_message .= "Password belum diisi. ";
    }

    if ($error_message === "") {

        include("connection.php");

        $password_sha1 = sha1($password);

        $query = "SELECT username FROM admin
                  WHERE username = ? AND password = ?";

        $stmt = mysqli_prepare($connection, $query);

        mysqli_stmt_bind_param(
            $stmt,
            "ss",
            $username,
            $password_sha1
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {

            session_regenerate_id(true);
            $_SESSION["username"] = $username;

            header("Location: student_view.php");
            exit;

        } else {
            $error_message = "Username atau password salah.";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Login Data Mahasiswa</title>
    <link rel="stylesheet" href="assets/login.css">
</head>
<body>

<div class="container">

    <h1>Data Mahasiswa</h1>
    <p>Silakan login untuk melanjutkan</p>

    <?php if ($error_message !== ""): ?>
        <div class="error">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="post">

        <label for="username">Username</label>
        <input
            type="text"
            id="username"
            name="username"
            value="<?= htmlspecialchars($username) ?>"
            placeholder="Masukkan username"
        >

        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Masukkan password"
        >

        <button type="submit" name="submit">
            Login
        </button>

    </form>

</div>

</body>
</html>
