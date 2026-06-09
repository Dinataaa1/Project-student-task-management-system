<?php
// controllers/mahasiswa/daftar_matkul.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../auth/session_check.php';
checkRoleMahasiswa();
require_once '../../../config/koneksi.php';

$mahasiswa_id = $_SESSION['mahasiswa_id'];

// Query mengambil semua matkul yang diambil mahasiswa tersebut
$query = "
    SELECT mk.*, d.nama_dosen, 
           (SELECT COUNT(*) FROM tugas WHERE tugas.matkul_id = mk.id) as total_tugas 
    FROM mata_kuliah mk 
    JOIN dosen d ON mk.dosen_id = d.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = ?
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $mahasiswa_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$daftar_matkul = [];
while($row = mysqli_fetch_assoc($result)) {
    $daftar_matkul[] = $row;
}
?>