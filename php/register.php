<?php
include "database.php";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'];
    $class = $_POST['class'];
    $jurusan = $_POST['jurusan'];
    $nik_nip = $_POST['nik_nip'];
    $no_absen = $_POST['no_absen'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (name, class, jurusan, nik_nip, no_absen, password) 
            VALUES ('$name', '$class', '$jurusan', '$nik_nip', '$no_absen', '$password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Nama Lengkap" required>
            <input type="text" name="class" placeholder="Kelas" required>
            <input type="text" name="jurusan" placeholder="Jurusan" required>
            <input type="text" name="nik_nip" placeholder="NIK/NIP" required>
            <input type="number" name="no_absen" placeholder="No Absen" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login</a></p>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
