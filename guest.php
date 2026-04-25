<?php
session_start();

$_SESSION['login'] = true;
$_SESSION['role'] = 'user';

header("Location: pages/dashboard.php");
exit;
