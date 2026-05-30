<?php
// ==========================================================================
// 1. OTENTIKASI & KONEKSI BASIS DATA (CONTROLLER)
// ==========================================================================
// session_start();
// if (!isset($_SESSION['mahasiswa_id'])) {
//     header("Location: ../../view/auth/login.php"); 
//     exit();
// }

include_once '../../../config/koneksi.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../../view/auth/login.php");
    exit();
}

if (isset($_SESSION['mahasiswa_id'])) {
    $mahasiswa_id = (int) $_SESSION['mahasiswa_id'];
} else {
    $user_id = (int) $_SESSION['user_id'];
    $res = mysqli_query($conn, "SELECT id FROM mahasiswa WHERE user_id = $user_id LIMIT 1");
    $row = mysqli_fetch_assoc($res);
    if (!$row) { header("Location: ../../view/auth/login.php"); exit(); }
    $mahasiswa_id = (int) $row['id'];
}

$nama_user = $_SESSION['nama'] ?? '';

// ==========================================================================
// 2. LOGIKA PENYARINGAN DATA (FILTER) & PENGAMBILAN DATA
// ==========================================================================
$matkul_aktif = isset($_GET['matkul']) ? $_GET['matkul'] : '';

// A. Mengambil data untuk Dropdown Mata Kuliah
$query_matkul = mysqli_query($conn, "
    SELECT mk.id, mk.nama_matkul 
    FROM mata_kuliah mk
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id
");

$data_matkul = [];
if ($query_matkul) {
    while($row = mysqli_fetch_assoc($query_matkul)) {
        $data_matkul[] = $row;
    }
}

// B. Menentukan Nama Mata Kuliah yang sedang difilter
$nama_matkul_terpilih = "Semua";
if ($matkul_aktif != '') {
    $cari_nama = mysqli_query($conn, "SELECT nama_matkul FROM mata_kuliah WHERE id = '$matkul_aktif'");
    if($baris = mysqli_fetch_assoc($cari_nama)) {
        $nama_matkul_terpilih = $baris['nama_matkul'];
    }
}

// C. Mengambil Daftar Tugas berdasarkan Filter
$kondisi_filter = $matkul_aktif != '' ? "AND t.matkul_id = '$matkul_aktif'" : "";
$query_tugas = mysqli_query($conn, "
    SELECT t.id, t.judul_tugas 
    FROM tugas t
    JOIN mata_kuliah mk ON t.matkul_id = mk.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id $kondisi_filter
");

$data_tugas = [];
if ($query_tugas) {
    while($row = mysqli_fetch_assoc($query_tugas)) {
        $data_tugas[] = $row;
    }
}
?>