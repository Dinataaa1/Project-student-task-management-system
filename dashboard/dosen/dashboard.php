<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'dosen') {
    header("Location: ../../index.php");
    exit;
}
?>

<h1>Dashboard Dosen</h1>

<p>Selamat datang, <?= $_SESSION['nama']; ?></p>

<a href="../../auth/logout.php">Logout</a>