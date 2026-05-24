<?php
// ==========================================================================
// 1. OTENTIKASI & KONEKSI BASIS DATA (CONTROLLER)
// ==========================================================================
// session_start();
// if (!isset($_SESSION['mahasiswa_id'])) {
//     header("Location: ../../view/auth/login.php"); 
//     exit();
// }

// Wajib ditambahkan agar PHP menggunakan jam Indonesia (WIB)
date_default_timezone_set('Asia/Jakarta');

include_once '../../../config/koneksi.php'; 

$mahasiswa_id = 1; 
$nama_user = "Luthfi Bahrur R."; 

// ==========================================================================
// 2. LOGIKA PENGAMBILAN DATA (QUERY)
// ==========================================================================

// A. Menarik data nama mata kuliah (Maksimal 4 untuk highlight dashboard)
$query_matkul = mysqli_query($conn, "
    SELECT mk.id, mk.nama_matkul 
    FROM mata_kuliah mk
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id
    LIMIT 4
");

$data_matkul = [];
$pilihan_warna = ["blob-orange", "blob-blue"];
$index_warna = 0;

if ($query_matkul) {
    while ($row = mysqli_fetch_assoc($query_matkul)) {
        $data_matkul[] = [
            "id" => $row['id'], 
            "nama" => $row['nama_matkul'],
            "warna" => $pilihan_warna[$index_warna % 2] 
        ];
        $index_warna++;
    }
}

// B. Menarik seluruh data batas waktu tugas (Untuk Calendar Widget di JS)
$query_tugas = mysqli_query($conn, "
    SELECT DATE(t.deadline) as tgl_deadline 
    FROM tugas t
    JOIN mata_kuliah mk ON t.matkul_id = mk.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id
");

$array_deadline = [];
if ($query_tugas) {
    while ($row = mysqli_fetch_assoc($query_tugas)) {
        $tanggal_mentah = date("Y-n-j", strtotime($row['tgl_deadline'])); 
        $array_deadline[] = $tanggal_mentah;
    }
}

// C. Menarik dua data tugas dengan tenggat waktu terdekat
$query_dl_terdekat = mysqli_query($conn, "
    SELECT t.judul_tugas, t.deadline 
    FROM tugas t
    JOIN mata_kuliah mk ON t.matkul_id = mk.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id AND t.deadline >= NOW()
    ORDER BY t.deadline ASC
    LIMIT 2
");

$data_dl_terdekat = [];
if ($query_dl_terdekat) {
    while ($row = mysqli_fetch_assoc($query_dl_terdekat)) {
        $data_dl_terdekat[] = $row;
    }
}

// Menyiapkan variabel tanggal untuk UI
$tanggal_sekarang = date('d'); 
$bulan_sekarang = date('M');   
$tahun_sekarang = date('Y');   
?>