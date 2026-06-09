<?php
// controllers/mahasiswa/dashboard.php
date_default_timezone_set('Asia/Jakarta');
include_once '../../../config/koneksi.php'; 
require_once __DIR__ . '/../auth/session_check.php';
checkRoleMahasiswa();

$mahasiswa_id = $_SESSION['mahasiswa_id'];
$nama_user = $_SESSION['nama'] ?? 'Mahasiswa';

// A. Tarik data Matkul (JOIN dosen & hitung total tugas)
$query_matkul = mysqli_query($conn, "
    SELECT mk.*, d.nama_dosen, 
           (SELECT COUNT(*) FROM tugas WHERE tugas.matkul_id = mk.id) as total_tugas 
    FROM mata_kuliah mk 
    JOIN dosen d ON mk.dosen_id = d.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id
");

$data_matkul = [];
if ($query_matkul) {
    while ($row = mysqli_fetch_assoc($query_matkul)) {
        $data_matkul[] = $row;
    }
}

// B. Tarik data Deadline (Kalender)
$query_tugas = mysqli_query($conn, "
    SELECT DATE(t.deadline) as tgl_deadline 
    FROM tugas t
    JOIN krs k ON t.matkul_id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id
");
$array_deadline = [];
if ($query_tugas) {
    while ($row = mysqli_fetch_assoc($query_tugas)) {
        $array_deadline[] = date("Y-n-j", strtotime($row['tgl_deadline']));
    }
}

// C. Tarik Tugas Mendatang
$query_reminders = mysqli_query($conn, "
    SELECT t.id, mk.nama_matkul, t.judul_tugas, t.deadline
    FROM tugas t
    JOIN mata_kuliah mk ON t.matkul_id = mk.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    LEFT JOIN pengumpulan_tugas pt ON pt.tugas_id = t.id AND pt.mahasiswa_id = k.mahasiswa_id
    WHERE k.mahasiswa_id = $mahasiswa_id 
    AND pt.id IS NULL
    ORDER BY t.deadline ASC
");

$data_dl_terdekat = [];
if ($query_reminders) {
    while ($row = mysqli_fetch_assoc($query_reminders)) {
        $data_dl_terdekat[] = $row;
    }
}

$tanggal_sekarang = date('d'); 
$bulan_sekarang = date('M');   
$tahun_sekarang = date('Y');   
?>