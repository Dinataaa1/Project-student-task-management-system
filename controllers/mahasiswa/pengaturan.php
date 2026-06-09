<?php
// controllers/mahasiswa/pengaturan.php
session_start();
include_once '../../../config/koneksi.php';

// Mengambil ID user dari sesi, fallback ke 1 jika belum ada sesi (untuk testing)
$id_user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 3;

// Query mengambil data gabungan dari tabel users dan mahasiswa
$query = mysqli_query($conn, "
    SELECT u.email, m.nama_mahasiswa, m.nrp
    FROM users u
    JOIN mahasiswa m ON u.id = m.user_id
    WHERE u.id = $id_user
");

$data_user = mysqli_fetch_assoc($query);

// Karena kolom 'kelas' belum ada di database, kita beri nilai sementara
$kelas_placeholder = "Informatika / Belum diatur"; 
?>