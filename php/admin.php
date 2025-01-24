<?php
session_start();

include "database.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_user') {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'];
        $class = $_POST['class'];
        $jurusan = $_POST['jurusan'];
        $nik_nip = $_POST['nik_nip'];
        $no_absen = $_POST['no_absen'];
        $role = $_POST['role'];
        $password = $_POST['password'] ?? '';

        $stmt = $conn->prepare("SELECT id FROM users WHERE nik_nip = ? AND id != ?");
        $stmt->bind_param("si", $nik_nip, $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "NIK/NIP sudah terdaftar.";
            $message_type = 'error';
        } else {
            if ($id) {
                if (!empty($password)) {
                    $stmt = $conn->prepare("UPDATE users SET name = ?, class = ?, jurusan = ?, nik_nip = ?, no_absen = ?, role = ?, password = ? WHERE id = ?");
                    $stmt->bind_param("sssssssi", $name, $class, $jurusan, $nik_nip, $no_absen, $role, $password, $id);
                } else {
                    $stmt = $conn->prepare("UPDATE users SET name = ?, class = ?, jurusan = ?, nik_nip = ?, no_absen = ?, role = ? WHERE id = ?");
                    $stmt->bind_param("ssssssi", $name, $class, $jurusan, $nik_nip, $no_absen, $role, $id);
                }
            } else {
                $stmt = $conn->prepare("INSERT INTO users (name, class, jurusan, nik_nip, no_absen, role, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssss", $name, $class, $jurusan, $nik_nip, $no_absen, $role, $password);
            }

            if ($stmt->execute()) {
                $message = $id ? "Data pengguna berhasil diperbarui." : "Data pengguna berhasil ditambahkan.";
                $message_type = 'success';
            } else {
                $message = "Terjadi kesalahan saat menyimpan data.";
                $message_type = 'error';
            }
        }
    } elseif ($action === 'delete_user') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Data pengguna berhasil dihapus.";
            $message_type = 'success';
        } else {
            $message = "Terjadi kesalahan saat menghapus data pengguna.";
            $message_type = 'error';
        }
    } elseif ($action === 'delete_attendance') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM absens WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Data absensi berhasil dihapus.";
            $message_type = 'success';
        } else {
            $message = "Terjadi kesalahan saat menghapus data absensi.";
            $message_type = 'error';
        }
    }
}

$users = $conn->query("SELECT * FROM users ORDER BY id ASC");

$attendance = $conn->query("SELECT a.id, a.time, a.status, u.name, u.no_absen, u.class FROM absens a JOIN users u ON a.user_id = u.id ORDER BY a.time DESC");

if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $user_data = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admincss.css">
</head>
<body>
<nav>
    <div>Admin Dashboard</div>
    <ul>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>
<div class="container">
    <?php if ($message): ?>
        <div class="alert <?= $message_type ?>"> <?= htmlspecialchars($message) ?> </div>
    <?php endif; ?>
    <section>
        <h2>Kelola Pengguna</h2>
        <form method="POST">
            <input type="hidden" name="action" value="save_user">
            <input type="hidden" name="id" value="<?= $user_data['id'] ?? '' ?>">
            <input type="text" name="name" placeholder="Nama" value="<?= htmlspecialchars($user_data['name'] ?? '') ?>" required>
            <input type="text" name="class" placeholder="Kelas" value="<?= htmlspecialchars($user_data['class'] ?? '') ?>" required>
            <input type="text" name="jurusan" placeholder="Jurusan" value="<?= htmlspecialchars($user_data['jurusan'] ?? '') ?>" required>
            <input type="text" name="nik_nip" placeholder="NIK/NIP" value="<?= htmlspecialchars($user_data['nik_nip'] ?? '') ?>" required>
            <input type="text" name="no_absen" placeholder="No Absen" value="<?= htmlspecialchars($user_data['no_absen'] ?? '') ?>" required>
            <input type="password" name="password" placeholder="Password">
            <select name="role" required>
                <option value="siswa" <?= (isset($user_data) && $user_data['role'] === 'siswa') ? 'selected' : '' ?>>Siswa</option>
                <option value="guru" <?= (isset($user_data) && $user_data['role'] === 'guru') ? 'selected' : '' ?>>Guru</option>
                <option value="admin" <?= (isset($user_data) && $user_data['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
            </select>
            <button type="submit">Simpan</button>
        </form>
    </section>
    <section>
        <h2>Daftar Pengguna</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>NIK/NIP</th>
                    <th>No Absen</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['class']) ?></td>
                        <td><?= htmlspecialchars($user['jurusan']) ?></td>
                        <td><?= htmlspecialchars($user['nik_nip']) ?></td>
                        <td><?= htmlspecialchars($user['no_absen']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <a href="?edit_id=<?= $user['id'] ?>">Edit</a>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
    <section>
        <h2>Daftar Absensi</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>No Absen</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $attendance->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['no_absen']) ?></td>
                        <td><?= htmlspecialchars($row['class']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['time']) ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete_attendance">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</div>
</body>
</html>
