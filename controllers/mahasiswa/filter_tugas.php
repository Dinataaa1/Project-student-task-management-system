<?php
// Controller kecil untuk menerima filter dari form frontend dan
// mengarahkan kembali ke view `daftar_tugas.php` dengan query string.

// Tidak banyak logika: sanitasi input dan redirect.
require_once __DIR__ . '/../auth/session_check.php';
checkRoleMahasiswa(); 

// Sekarang kamu bisa langsung pakai ini tanpa perlu query ulang!
$mahasiswa_id = $_SESSION['mahasiswa_id'];

$matkul = isset($_GET['matkul']) ? $_GET['matkul'] : '';
$matkul = is_numeric($matkul) ? (int)$matkul : '';

// Redirect kembali ke view daftar_tugas
header("Location: ../../view/pages/mahasiswa/daftar_tugas.php" . ($matkul !== '' ? "?matkul={$matkul}" : ""));
exit();

?>
