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
    if (!$row) {
        header("Location: ../../view/auth/login.php");
        exit();
    }
    $mahasiswa_id = (int) $row['id'];
}

$nama_user = $_SESSION['nama'] ?? '';

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

// D. Menarik ringkasan tugas dan nilai untuk dashboard
$query_tugas_nilai = mysqli_query($conn, "
    SELECT
        t.id,
        t.judul_tugas,
        t.deadline,
        mk.nama_matkul,
        p.nilai,
        p.file_tugas,
        p.waktu_kumpul
    FROM tugas t
    JOIN mata_kuliah mk ON t.matkul_id = mk.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    LEFT JOIN pengumpulan_tugas p ON p.tugas_id = t.id AND p.mahasiswa_id = $mahasiswa_id
    WHERE k.mahasiswa_id = $mahasiswa_id
    ORDER BY t.deadline ASC
    LIMIT 6
");

$data_tugas_nilai = [];
if ($query_tugas_nilai) {
    while ($row = mysqli_fetch_assoc($query_tugas_nilai)) {
        $status = 'Belum Mengumpulkan';
        if (!empty($row['waktu_kumpul'])) {
            $status = (strtotime($row['waktu_kumpul']) > strtotime($row['deadline'])) ? 'Diserahkan Terlambat' : 'Dikumpulkan';
        }
        $data_tugas_nilai[] = [
            'id' => $row['id'],
            'judul_tugas' => $row['judul_tugas'],
            'deadline' => $row['deadline'],
            'nama_matkul' => $row['nama_matkul'],
            'nilai' => $row['nilai'],
            'file_tugas' => $row['file_tugas'],
            'waktu_kumpul' => $row['waktu_kumpul'],
            'status' => $status,
        ];
    }
}

// Menyiapkan variabel tanggal untuk UI
$tanggal_sekarang = date('d'); 
$bulan_sekarang = date('M');   
$tahun_sekarang = date('Y');   
?>