<?php
require_once __DIR__ . '/../auth/session_check.php';
checkRoleMahasiswa(); 
require_once '../../../config/koneksi.php';

$mahasiswa_id = $_SESSION['mahasiswa_id'];

// Query mengambil matkul berdasarkan KRS mahasiswa yang login
$query = "SELECT mk.id, mk.nama_matkul 
          FROM mata_kuliah mk
          JOIN krs k ON mk.id = k.mata_kuliah_id
          WHERE k.mahasiswa_id = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $mahasiswa_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$daftar_tugas = []; // Kita gunakan variabel ini untuk view
while($row = mysqli_fetch_assoc($result)) {
    $daftar_tugas[] = $row;
}
?>