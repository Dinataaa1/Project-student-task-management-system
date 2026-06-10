<?php
// controllers/mahasiswa/pengaturan.php

include_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../auth/session_check.php';

// Pastikan hanya mahasiswa sah yang bisa mengakses halaman ini
checkRoleMahasiswa();

$id_user = $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT u.email, m.nama_mahasiswa, m.nrp, k.nama_kelas
    FROM users u
    JOIN mahasiswa m ON u.id = m.user_id
    LEFT JOIN kelas k ON m.kelas_id = k.id
    WHERE u.id = $id_user
");

$data_user = mysqli_fetch_assoc($query);

// 3. LOGIKA FALLBACK JIKA KELAS BELUM DISET DI DATABASE
$nama_kelas = !empty($data_user['nama_kelas']) ? $data_user['nama_kelas'] : "Informatika / Belum diatur"; 
?>