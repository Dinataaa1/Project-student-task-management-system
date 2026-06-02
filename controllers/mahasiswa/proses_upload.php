<?php

include_once '../../../config/koneksi.php';

require_once __DIR__ . '/../auth/session_check.php';
checkRoleMahasiswa(); 

// Sekarang kamu bisa langsung pakai ini tanpa perlu query ulang!


// Ensure POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../view/pages/mahasiswa/daftar_tugas.php');
    exit();
}

$mahasiswa_id = $_SESSION['mahasiswa_id'];

// if (isset($_SESSION['mahasiswa_id'])) {
//     $mahasiswa_id = (int) $_SESSION['mahasiswa_id'];
// } else {
//     $user_id = (int) $_SESSION['user_id'];
//     $r = mysqli_query($conn, "SELECT id FROM mahasiswa WHERE user_id = $user_id LIMIT 1");
//     $row = mysqli_fetch_assoc($r);
//     $mahasiswa_id = $row ? (int)$row['id'] : 0;
// }

$tugas_id = isset($_POST['tugas_id']) ? (int) $_POST['tugas_id'] : 0;
if ($tugas_id <= 0 || $mahasiswa_id <= 0) {
    $msg = urlencode('Data tugas/mahasiswa tidak valid.');
    header("Location: ../../view/pages/mahasiswa/daftar_tugas.php?upload=error&msg={$msg}");
    exit();
}

if (!isset($_FILES['file_tugas']) || $_FILES['file_tugas']['error'] !== UPLOAD_ERR_OK) {
    $msg = urlencode('File tidak ditemukan atau gagal diunggah.');
    header("Location: ../../view/pages/mahasiswa/detail_tugas.php?id={$tugas_id}&upload=error&msg={$msg}");
    exit();
}

$allowed = ['pdf','doc','docx','zip','rar','txt'];
$maxSize = 10 * 1024 * 1024; // 10 MB

$origName = $_FILES['file_tugas']['name'];
$ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    $msg = urlencode('Ekstensi file tidak diizinkan.');
    header("Location: ../../view/pages/mahasiswa/detail_tugas.php?id={$tugas_id}&upload=error&msg={$msg}");
    exit();
}

if ($_FILES['file_tugas']['size'] > $maxSize) {
    $msg = urlencode('Ukuran file melebihi batas 10MB.');
    header("Location: ../../view/pages/mahasiswa/detail_tugas.php?id={$tugas_id}&upload=error&msg={$msg}");
    exit();
}

$baseUploadDir = __DIR__ . '/../uploads/';
$studentDir = $baseUploadDir . 'mahasiswa_' . $mahasiswa_id . '/';
if (!is_dir($studentDir)) {
    mkdir($studentDir, 0750, true);
}

$filename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
$target = $studentDir . $filename;

if (!move_uploaded_file($_FILES['file_tugas']['tmp_name'], $target)) {
    $msg = urlencode('Gagal menyimpan file di server.');
    header("Location: ../../view/pages/mahasiswa/detail_tugas.php?id={$tugas_id}&upload=error&msg={$msg}");
    exit();
}

$publicPath = 'controllers/uploads/mahasiswa_' . $mahasiswa_id . '/' . $filename; // relative path for DB/frontend

// Insert or update pengumpulan_tugas
$stmt = $conn->prepare("SELECT id FROM pengumpulan_tugas WHERE tugas_id = ? AND mahasiswa_id = ? LIMIT 1");
$stmt->bind_param('ii', $tugas_id, $mahasiswa_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $id = (int)$row['id'];
    $u = $conn->prepare("UPDATE pengumpulan_tugas SET file_tugas = ?, waktu_kumpul = NOW() WHERE id = ?");
    $u->bind_param('si', $publicPath, $id);
    $u->execute();
} else {
    $i = $conn->prepare("INSERT INTO pengumpulan_tugas (tugas_id, mahasiswa_id, file_tugas, waktu_kumpul) VALUES (?, ?, ?, NOW())");
    $i->bind_param('iis', $tugas_id, $mahasiswa_id, $publicPath);
    $i->execute();
}

$msg = urlencode('File berhasil diunggah.');
header("Location: ../../view/pages/mahasiswa/detail_tugas.php?id={$tugas_id}&upload=success&msg={$msg}");
exit();

?>
