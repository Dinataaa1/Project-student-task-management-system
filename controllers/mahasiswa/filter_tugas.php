<?php
// Controller kecil untuk menerima filter dari form frontend dan
// mengarahkan kembali ke view `daftar_tugas.php` dengan query string.

// Tidak banyak logika: sanitasi input dan redirect.
session_start();

$matkul = isset($_GET['matkul']) ? $_GET['matkul'] : '';
$matkul = is_numeric($matkul) ? (int)$matkul : '';

// Redirect kembali ke view daftar_tugas
header("Location: ../../view/pages/mahasiswa/daftar_tugas.php" . ($matkul !== '' ? "?matkul={$matkul}" : ""));
exit();

?>
