<?php
// controllers/components/topbar.php

// Pastikan koneksi database terpanggil (gunakan __DIR__ agar alamatnya absolut/pasti)
require_once __DIR__ . '/../../config/koneksi.php';

// Siapkan wadah kosong dulu
$data_tugas_belum_dikumpul = [];

// Pastikan user sudah login dan punya user_id
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Cari ID Mahasiswa asli berdasarkan user_id
    $stmt_mhs = mysqli_prepare($conn, "SELECT id FROM mahasiswa WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt_mhs, "i", $user_id);
    mysqli_stmt_execute($stmt_mhs);
    $result_mhs = mysqli_stmt_get_result($stmt_mhs);
    $mhs = mysqli_fetch_assoc($result_mhs);

    // Jika dia mahasiswa (punya id mahasiswa), jalankan query sakti
    if ($mhs) {
        $mahasiswa_id = $mhs['id'];

        $query_sql = "
            SELECT * FROM tugas 
            WHERE id NOT IN (
                SELECT tugas_id FROM pengumpulan_tugas WHERE mahasiswa_id = ?
            )
        ";
        
        $stmt_tugas = mysqli_prepare($conn, $query_sql);
        mysqli_stmt_bind_param($stmt_tugas, "i", $mahasiswa_id);
        mysqli_stmt_execute($stmt_tugas);
        $result_tugas = mysqli_stmt_get_result($stmt_tugas);

        while ($row = mysqli_fetch_assoc($result_tugas)) {
            $data_tugas_belum_dikumpul[] = $row;
        }
    }
}
?>