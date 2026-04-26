<?php
session_start();
require "../koneksi.php";

// 1. Proteksi Halaman (Hanya Admin)
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$admin_id = 1; // Berdasarkan data user di database Anda
$message = "";

// 2. Logika Tambah Foto & Log Otomatis
if (isset($_POST['upload_foto'])) {
    $nama_file = $_FILES['foto']['name'];
    $tmp_name = $_FILES['foto']['tmp_name'];
    $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);

    // Rename file agar unik
    $nama_baru = time() . "_" . uniqid() . "." . $ekstensi;
    $target_dir = "../database/uploads/";

    if (move_uploaded_file($tmp_name, $target_dir . $nama_baru)) {
        // Simpan ke tabel photos
        mysqli_query($conn, "INSERT INTO photos (user_id, file_name) VALUES ('$admin_id', '$nama_baru')");

        // CATAT LOG OTOMATIS
        $log_msg = "Admin mengunggah foto baru: $nama_baru";
        mysqli_query($conn, "INSERT INTO activity_logs (user_id, activity) VALUES ('$admin_id', '$log_msg')");

        $message = "Foto berhasil diunggah!";
    }
}

// 3. Logika Hapus Foto & Log Otomatis
if (isset($_GET['hapus_foto'])) {
    $id_foto = $_GET['hapus_foto'];

    // Cari nama file dulu untuk dihapus dari folder
    $ambil = mysqli_query($conn, "SELECT file_name FROM photos WHERE id = '$id_foto'");
    $data = mysqli_fetch_assoc($ambil);
    $nama_file = $data['file_name'];

    if (unlink("../database/uploads/" . $nama_file)) {
        mysqli_query($conn, "DELETE FROM photos WHERE id = '$id_foto'");

        // CATAT LOG OTOMATIS
        $log_msg = "Admin menghapus foto: $nama_file";
        mysqli_query($conn, "INSERT INTO activity_logs (user_id, activity) VALUES ('$admin_id', '$log_msg')");

        $message = "Foto berhasil dihapus!";
    }
}

// 4. Ambil data foto untuk ditampilkan di tabel edit
$query_photos = mysqli_query($conn, "SELECT * FROM photos ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Portofolio - Admin</title>
    <link rel="stylesheet" href="../style/dashboard.css">
</head>

<body>
    <?php include "../layouts/header.php" ?>

    <div class="container">
        <h1>Kelola Portofolio</h1>
        <a href="dashboard.php" style="text-decoration: none;">← Kembali ke Dashboard</a>
        <hr>

        <?php if ($message): ?>
            <div class="alert"><?= $message ?></div>
        <?php endif; ?>

        <div class="upload-section">
            <h3>Tambah Foto Portofolio</h3>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="file" name="foto" required>
                <button type="submit" name="upload_foto" style="cursor:pointer;">Upload Sekarang</button>
            </form>
        </div>

        <h3>Daftar Foto Saat Ini</h3>
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama File</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($query_photos)): ?>
                    <tr>
                        <td><img src="../database/uploads/<?= $row['file_name'] ?>" width="80"></td>
                        <td><?= $row['file_name'] ?></td>
                        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        <td>
                            <a href="?hapus_foto=<?= $row['id'] ?>" class="btn-hapus" onclick="return confirm('Hapus foto ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($query_photos) == 0): ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">Belum ada foto portofolio.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php include "../layouts/footer.php" ?>
</body>

</html>