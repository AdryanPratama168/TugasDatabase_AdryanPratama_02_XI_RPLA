<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include "database.php";
$user = $_SESSION['user'];
$message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['attendance_status'])) {
    $user_id = $user['id'];
    $time = date('Y-m-d H:i:s');
    $status = $_POST['attendance_status'];
    $sql = "INSERT INTO absens (user_id, name, class, jurusan, nik_nip, no_absen, role, time, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "issssssss", 
        $user_id, 
        $user['name'], 
        $user['class'], 
        $user['jurusan'], 
        $user['nik_nip'], 
        $user['no_absen'], 
        $user['role'], 
        $time,
        $status
    );

    if ($stmt->execute()) {
        $message = "Absensi berhasil dicatat!";
        $message_type = "success";
    } else {
        $message = "Terjadi kesalahan saat mencatat absensi.";
        $message_type = "error";
    }
}

$today = date('Y-m-d');
$attendance_query = "SELECT * FROM absens WHERE user_id = ? AND DATE(time) = ? ORDER BY time DESC";
$stmt = $conn->prepare($attendance_query);
$stmt->bind_param("is", $user['id'], $today);
$stmt->execute();
$attendance_result = $stmt->get_result();
$today_attendances = $attendance_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absen - Absensi App</title>
    <link rel="stylesheet" href="../css/absen.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo"><i class="fas fa-clock"></i> Absensi App</div>
        <ul class="nav-links">
            <li><a href="siswa.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="absen-card">
            <div class="card-header">
                <i class="fas fa-user-check"></i>
                <h2>Form Absensi</h2>
                <p class="current-time" id="current-time"></p>
            </div>
            
            <div class="card-body">
                <div class="user-info">
                    <p><i class="fas fa-user"></i> Nama: <?= htmlspecialchars($user['name']) ?></p>
                    <p><i class="fas fa-building"></i> Jurusan: <?= htmlspecialchars($user['jurusan']) ?></p>
                    <p><i class="fas fa-id-card"></i> No. Absen: <?= htmlspecialchars($user['no_absen']) ?></p>
                    <p><i class="fas fa-graduation-cap"></i> Kelas: <?= htmlspecialchars($user['class']) ?></p>
                </div>

                <form method="POST" action="" class="absen-form">
                    <div class="attendance-options">
                        <button type="submit" name="attendance_status" value="hadir" class="submit-btn btn-hadir">
                            <i class="fas fa-check-circle"></i> Hadir
                        </button>
                        <button type="submit" name="attendance_status" value="izin" class="submit-btn btn-izin">
                            <i class="fas fa-calendar-times"></i> Izin
                        </button>
                        <button type="submit" name="attendance_status" value="sakit" class="submit-btn btn-sakit">
                            <i class="fas fa-first-aid"></i> Sakit
                        </button>
                    </div>
                </form>

                <?php if (!empty($today_attendances)): ?>
                <div class="attendance-history">
                    <h3>Riwayat Absensi Hari Ini:</h3>
                    <ul>
                        <?php foreach ($today_attendances as $attendance): ?>
                        <li>
                            Status: <?= htmlspecialchars($attendance['status']) ?> 
                            | Waktu: <?= htmlspecialchars($attendance['time']) ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if ($message): ?>
                    <div class="message <?= $message_type ?>">
                        <i class="fas fa-<?= $message_type === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; <?= date('Y') ?> Absensi App. All rights reserved.</p>
    </footer>

    <script>
        function updateTime() {
            const timeElement = document.getElementById('current-time');
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            timeElement.textContent = now.toLocaleDateString('id-ID', options);
        }
        
        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>
</html>