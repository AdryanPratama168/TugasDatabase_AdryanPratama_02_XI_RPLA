<?php
include "database.php";

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $nik_nip = $_POST['nik_nip'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE nik_nip = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nik_nip);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user'] = $user;

            if ($user['role'] == 'admin') {
                $_SESSION['admin'] = $user; 
                header("Location: admin.php"); 
            } else {
                header("Location: siswa.php");
            }
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "NIK/NIP tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Absensi App</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="text" name="nik_nip" placeholder="NIK/NIP" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
