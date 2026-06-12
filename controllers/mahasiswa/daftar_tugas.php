<?php
// controllers/mahasiswa/daftar_tugas.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once __DIR__ . '/../auth/session_check.php';
checkRoleMahasiswa(); 
require_once '../../../config/koneksi.php';

$mahasiswa_id = $_SESSION['mahasiswa_id'];
$matkul_filter = isset($_GET['matkul_id']) ? (int)$_GET['matkul_id'] : 0;

// Logika Query: 
// 1. Join Tugas ke KRS untuk memastikan mahasiswa hanya melihat tugas dari matkul yang dia ambil.
// 2. Jika ada matkul_id, tambahkan filter WHERE matkul_id.
$query = "SELECT t.* FROM tugas t
          JOIN krs k ON t.matkul_id = k.mata_kuliah_id
          WHERE k.mahasiswa_id = ?";

if ($matkul_filter > 0) {
    $query .= " AND t.matkul_id = ?";
}

$stmt = mysqli_prepare($conn, $query);

if ($matkul_filter > 0) {
    mysqli_stmt_bind_param($stmt, "ii", $mahasiswa_id, $matkul_filter);
} else {
    mysqli_stmt_bind_param($stmt, "i", $mahasiswa_id);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$daftar_tugas = [];
while($row = mysqli_fetch_assoc($result)) {
    $daftar_tugas[] = $row;
}
?>