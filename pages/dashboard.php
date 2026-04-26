<?php
session_start();
require "../koneksi.php";

if (!isset($_SESSION['login'])) {
    header("Location: main.php");
    exit;
}

$activities = mysqli_query($conn, "SELECT * FROM activity_logs ORDER BY created_at DESC");
$photos = mysqli_query($conn, "SELECT * FROM photos ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Portofolio</title>
    <link rel="stylesheet" href="../style/dashboard.css">
</head>

<body>
    <?php include "../layouts/header.php" ?>

    <h1>Dashboard</h1>
    <p>Status Login: <strong><?= strtoupper($_SESSION['role']); ?></strong></p>

    <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="edit.php" style="display:inline-block; margin-bottom: 20px;">[+] Kelola Data Portofolio</a>
    <?php endif; ?>

    <div class="portfolio-section">
        <h3>Log Aktivitas</h3>
        <ul>
            <?php while ($row = mysqli_fetch_assoc($activities)): ?>
                <li><strong><?= $row['created_at'] ?>:</strong> <?= htmlspecialchars($row['activity']) ?></li>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($activities) == 0) echo "<li>Belum ada aktivitas.</li>"; ?>
        </ul>
    </div>

    <div class="portfolio-section">
        <h3>Galeri Foto</h3>
        <div class="gallery">
            <?php while ($photo = mysqli_fetch_assoc($photos)): ?>
                <img src="../database/uploads/<?= htmlspecialchars($photo['file_name']) ?>" alt="Portfolio Image">
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($photos) == 0) echo "<p>Belum ada foto yang diunggah.</p>"; ?>
        </div>
    </div>

    <br>
    <a href="../logout.php">Logout (Exit)</a>

    <?php include "../layouts/footer.php" ?>
</body>

</html>