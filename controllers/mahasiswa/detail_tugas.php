<?php
// ==========================================================================
// 1. OTENTIKASI & KONEKSI BASIS DATA (CONTROLLER)
// ==========================================================================
// session_start();
// if (!isset($_SESSION['mahasiswa_id'])) {
//     header("Location: ../../view/auth/login.php"); 
//     exit();
// }

// Mengatur zona waktu agar perhitungan deadline akurat (WIB)

date_default_timezone_set('Asia/Jakarta');

// Path disesuaikan dari controllers ke config
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

$id_tugas = isset($_GET['id']) ? $_GET['id'] : 0;

// Karena file ini akan di-require di dalam view, relative path header tetap mengarah ke daftar_tugas.php
if ($id_tugas == 0) { 
    header("Location: daftar_tugas.php"); 
    exit(); 
}

$id_user = 1; 

// ==========================================================================
// 2. LOGIKA PENGAMBILAN DATA (QUERY)
// ==========================================================================
$stmt_tugas = $conn->prepare("SELECT judul_tugas, deskripsi, deadline FROM tugas WHERE id = ?");
$stmt_tugas->bind_param("i", $id_tugas);
$stmt_tugas->execute();
$tugas = $stmt_tugas->get_result()->fetch_assoc();

if (!$tugas) { 
    header("Location: daftar_tugas.php"); 
    exit(); 
} 

$stmt_kumpul = $conn->prepare("SELECT nilai, file_tugas, waktu_kumpul FROM pengumpulan_tugas WHERE tugas_id = ? AND mahasiswa_id = ?");
$stmt_kumpul->bind_param("ii", $id_tugas, $id_user);
$stmt_kumpul->execute();
$result_kumpul = $stmt_kumpul->get_result();

$pengumpulan = null;
$status_color = "text-danger"; 
$teks_status = "Belum Mengumpulkan";

if ($result_kumpul->num_rows > 0) {
    $data_db = $result_kumpul->fetch_assoc();
    $tenggat_waktu = strtotime($tugas['deadline']);
    $waktu_kumpul = strtotime($data_db['waktu_kumpul']);
    
    if ($waktu_kumpul > $tenggat_waktu) {
        $teks_status = "Diserahkan Terlambat";
        $status_color = "text-warning"; 
    } else {
        $teks_status = "Dikumpulkan";
        $status_color = "text-status-figma"; 
    }
    
    $pengumpulan = [
        'nilai' => $data_db['nilai'],
        'status' => $teks_status,
        'berkas' => basename($data_db['file_tugas']) 
    ];
}

$deadline_format = date('d-m-Y', strtotime($tugas['deadline']));
?>