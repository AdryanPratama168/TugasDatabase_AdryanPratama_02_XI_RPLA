<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Absensi App</title>
    <link rel="stylesheet" href="../css/cssdashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Absensi App</div>
        <ul class="nav-links">
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">
        <div class="welcome-card">
            <h2>Selamat Datang, <?= htmlspecialchars($user['name']) ?></h2>
            <p>Jurusan <strong><?= htmlspecialchars($user['jurusan']) ?></strong></p>
            <p>Kelas <strong><?= htmlspecialchars($user['class']) ?></strong></p>
            <p>No. Absen <strong><?= htmlspecialchars($user['no_absen']) ?></strong></p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-clock"></i>
                <h3>Absensi Hari Ini</h3>
                <p>Status: Belum Absen</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-calendar-check"></i>
                <h3>Total Kehadiran</h3>
                <p>20 dari 22 hari</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-clock"></i>
                <h3>Keterlambatan</h3>
                <p>2 kali bulan ini</p>
            </div>
        </div>
        <div class="action-buttons">
            <button onclick="location.href='absen.php'">
                <i class="fas fa-check-circle"></i>
                Absen Sekarang
            </button>
        </div>
    </div>
    <footer class="footer">
        <p>&copy; <?= date('Y') ?> Absensi App. All rights reserved.</p>
    </footer>
</body>
</html>
