<?php
$conn = mysqli_connect("localhost", "root", "", "portofolio");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// mencatat aktivitas/log admin
function catat_log($conn, $user_id, $aksi)
{
    $aksi_aman = mysqli_real_escape_string($conn, $aksi);
    $query = "INSERT INTO activity_logs (user_id, activity) VALUES ('$user_id', '$aksi_aman')";
    mysqli_query($conn, $query);
}
