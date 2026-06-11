<?php
// controllers/mahasiswa/pengaturan.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../auth/session_check.php';

// Pastikan user adalah mahasiswa
checkRoleMahasiswa();

// Panggil koneksi
require_once __DIR__ . '/../../config/koneksi.php';

$mahasiswa_id = $_SESSION['mahasiswa_id'];

// Query tanpa JOIN ke tabel 'kelas' yang tidak ada
$sql = "
    SELECT m.nama_mahasiswa, m.nrp, u.email 
    FROM mahasiswa m
    JOIN users u ON m.user_id = u.id
    WHERE m.id = ?
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $mahasiswa_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data_user = mysqli_fetch_assoc($result);

// Berikan nilai default untuk nama_kelas agar view tidak error
if ($data_user) {
    // Jika di masa depan kamu menambahkan kolom 'kelas' di tabel mahasiswa,
    // kamu bisa mengubahnya menjadi: $data_user['kelas'] ?? 'Belum diatur';
    $data_user['nama_kelas'] = 'Belum ada data kelas';
}
?>