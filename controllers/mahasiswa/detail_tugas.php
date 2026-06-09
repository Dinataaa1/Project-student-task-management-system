<?php
// controllers/mahasiswa/detail_tugas.php
require_once '../../../config/koneksi.php';
require_once __DIR__ . '/../auth/session_check.php';

checkRoleMahasiswa();

$mahasiswa_id = $_SESSION['mahasiswa_id'];
$id_tugas = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_tugas == 0) { 
    header("Location: daftar_tugas.php"); 
    exit(); 
}

// 1. Ambil Detail Tugas
// Memastikan semua kolom (termasuk file_lampiran) terambil
$stmt = mysqli_prepare($conn, "SELECT * FROM tugas WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id_tugas);
mysqli_stmt_execute($stmt);
$tugas = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$tugas) { 
    header("Location: daftar_tugas.php"); 
    exit(); 
} 

// 2. Ambil Data Pengumpulan Mahasiswa
$stmt2 = mysqli_prepare($conn, "SELECT * FROM pengumpulan_tugas WHERE tugas_id = ? AND mahasiswa_id = ?");
mysqli_stmt_bind_param($stmt2, "ii", $id_tugas, $mahasiswa_id);
mysqli_stmt_execute($stmt2);
$pengumpulan = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2));

// 3. Logika Status (Teks & Warna)
$deadline_format = date('d M Y, H:i', strtotime($tugas['deadline']));
$sekarang = date('Y-m-d H:i:s');
$tenggat_waktu = strtotime($tugas['deadline']);

$status_color = "text-danger"; // Default
$teks_status = "Belum Mengumpulkan";

if ($pengumpulan) {
    $waktu_kumpul = strtotime($pengumpulan['waktu_kumpul']);
    
    if ($waktu_kumpul > $tenggat_waktu) {
        $status_color = "text-warning"; 
        $teks_status = "Diserahkan Terlambat";
    } else {
        // Mengikuti class 'text-status-figma' yang ada di View kamu
        $status_color = "text-success"; 
        $teks_status = "Dikumpulkan";
    }
}
?>