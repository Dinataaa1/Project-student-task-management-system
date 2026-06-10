<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (!isset($_SESSION['mahasiswa_id'])) {
    header("Location: ../../view/pages/auth/login.php");
    exit();
}

$mahasiswa_id = $_SESSION['mahasiswa_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../view/pages/mahasiswa/daftar_tugas.php');
    exit();
}

$tugas_id = isset($_POST['tugas_id']) ? (int) $_POST['tugas_id'] : 0;

if ($tugas_id <= 0) {
    die("ID Tugas tidak valid.");
}

if (!isset($_FILES['file_tugas']) || $_FILES['file_tugas']['error'] !== UPLOAD_ERR_OK) {
    die("Gagal upload file. Kode error: " . $_FILES['file_tugas']['error']);
}

$allowed = ['pdf','doc','docx','zip','rar','txt'];
$ext = strtolower(pathinfo($_FILES['file_tugas']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    die("Format file tidak diizinkan.");
}

$uploadDir = '../uploads/mahasiswa_' . $mahasiswa_id . '/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Mengambil nama asli file tanpa ekstensi
$nama_asli = pathinfo($_FILES['file_tugas']['name'], PATHINFO_FILENAME);
// Membersihkan spasi atau karakter aneh agar aman di database
$nama_bersih = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nama_asli);

// Format baru: Waktu + Tanda Strip + Nama Asli + Ekstensi
$filename = time() . '-' . $nama_bersih . '.' . $ext;
$targetPath = $uploadDir . $filename;

if (move_uploaded_file($_FILES['file_tugas']['tmp_name'], $targetPath)) {
    
    $publicPath = 'controllers/uploads/mahasiswa_' . $mahasiswa_id . '/' . $filename;

    $stmt = $conn->prepare("SELECT id FROM pengumpulan_tugas WHERE tugas_id = ? AND mahasiswa_id = ?");
    $stmt->bind_param('ii', $tugas_id, $mahasiswa_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $u = $conn->prepare("UPDATE pengumpulan_tugas SET file_tugas = ?, waktu_kumpul = NOW() WHERE tugas_id = ? AND mahasiswa_id = ?");
        $u->bind_param('sii', $publicPath, $tugas_id, $mahasiswa_id);
        $u->execute();
    } else {
        $i = $conn->prepare("INSERT INTO pengumpulan_tugas (tugas_id, mahasiswa_id, file_tugas, waktu_kumpul) VALUES (?, ?, ?, NOW())");
        $i->bind_param('iis', $tugas_id, $mahasiswa_id, $publicPath);
        $i->execute();
    }

    header("Location: ../../view/pages/mahasiswa/detail_tugas.php?id={$tugas_id}&upload=success");
    exit();
} else {
    die("Gagal memindahkan file ke server.");
}
?>