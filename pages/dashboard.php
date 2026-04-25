<?php
session_start();
require "../koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Dashboard</h1>
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="edit.php">Edit Data</a>
    <?php endif; ?>
    <a href="./main.php">Exit</a>
</body>

</html>