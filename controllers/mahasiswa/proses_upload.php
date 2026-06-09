<?php
// 1. WAJIB: Aktifkan sesi di setiap file proses!
session_start();

// 2. Koneksi ke DB
require_once __DIR__ . '/../../config/koneksi.php';

// 3. Cek Login
if (!isset($_SESSION['mahasiswa_id'])) {
    header("Location: ../../view/pages/auth/login.php");
    exit();
}

$mahasiswa_id = $_SESSION['mahasiswa_id'];

// 4. Validasi POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../view/pages/mahasiswa/daftar_tugas.php');
    exit();
}

$tugas_id = isset($_POST['tugas_id']) ? (int) $_POST['tugas_id'] : 0;

if ($tugas_id <= 0) {
    die("ID Tugas tidak valid.");
}

// 5. Validasi File
if (!isset($_FILES['file_tugas']) || $_FILES['file_tugas']['error'] !== UPLOAD_ERR_OK) {
    die("Gagal upload file. Kode error: " . $_FILES['file_tugas']['error']);
}

$allowed = ['pdf','doc','docx','zip','rar','txt'];
$ext = strtolower(pathinfo($_FILES['file_tugas']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    die("Format file tidak diizinkan.");
}

// 6. Persiapan Folder (Simpan di folder yang benar agar bisa diakses oleh view)
// Pastikan folder 'uploads' ada di root project
$uploadDir = __DIR__ . '/uploads/mahasiswa_' . $mahasiswa_id . '/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$filename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
$targetPath = $uploadDir . $filename;

// 7. Simpan file
if (move_uploaded_file($_FILES['file_tugas']['tmp_name'], $targetPath)) {
    
    // Simpan path relatif ke database
    $publicPath = 'controllers/uploads/mahasiswa_' . $mahasiswa_id . '/' . $filename;

    // 8. Cek apakah sudah pernah upload sebelumnya
    $stmt = $conn->prepare("SELECT id FROM pengumpulan_tugas WHERE tugas_id = ? AND mahasiswa_id = ?");
    $stmt->bind_param('ii', $tugas_id, $mahasiswa_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // UPDATE
        $u = $conn->prepare("UPDATE pengumpulan_tugas SET file_tugas = ?, waktu_kumpul = NOW() WHERE tugas_id = ? AND mahasiswa_id = ?");
        $u->bind_param('sii', $publicPath, $tugas_id, $mahasiswa_id);
        $u->execute();
    } else {
        // INSERT
        $i = $conn->prepare("INSERT INTO pengumpulan_tugas (tugas_id, mahasiswa_id, file_tugas, waktu_kumpul) VALUES (?, ?, ?, NOW())");
        $i->bind_param('iis', $tugas_id, $mahasiswa_id, $publicPath);
        $i->execute();
    }

    header("Location: ../../view/pages/mahasiswa/detail_tugas.php?id={$tugas_id}&upload=success");
    exit();
}

$publicPath = 'mahasiswa_' . $mahasiswa_id . '/' . $filename;

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
    die("Gagal memindahkan file ke server.");
}
?>