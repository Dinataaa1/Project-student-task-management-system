<?php
// controllers/mahasiswa/daftar_tugas.php
require_once __DIR__ . '/../auth/session_check.php';
checkRoleMahasiswa(); 
require_once '../../../config/koneksi.php';

$mahasiswa_id = $_SESSION['mahasiswa_id'];

// Mengambil Daftar Tugas berdasarkan matkul yang diikuti mahasiswa tersebut (via KRS)
// Query ini join ke tugas, mata_kuliah, dan krs untuk memastikan hanya tugas dari matkul yang diambil yang muncul
$query = "SELECT t.id, t.judul_tugas 
          FROM tugas t
          JOIN mata_kuliah mk ON t.matkul_id = mk.id
          JOIN krs k ON mk.id = k.mata_kuliah_id
          WHERE k.mahasiswa_id = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $mahasiswa_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$daftar_tugas = []; // Variabel inilah yang akan dibaca oleh View
if ($result) {
    while($row = mysqli_fetch_assoc($result)) {
        $daftar_tugas[] = $row;
    }
}
?>