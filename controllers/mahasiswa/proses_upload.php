<?php
// controllers/mahasiswa/proses_upload.php

require_once __DIR__ . '/../auth/session_check.php';

checkRoleMahasiswa();

validasiCSRFToken();

// 4. Pastikan metode pengiriman adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../view/pages/mahasiswa/daftar_tugas.php');
    exit();
}

$mahasiswa_id = (int) $_SESSION['mahasiswa_id'];
$tugas_id = isset($_POST['tugas_id']) ? (int) $_POST['tugas_id'] : 0;

if ($tugas_id <= 0) {
    die("Error: ID Tugas tidak valid.");
}

// 5. Cek apakah file diunggah tanpa error
if (!isset($_FILES['file_tugas']) || $_FILES['file_tugas']['error'] !== UPLOAD_ERR_OK) {
    die("Gagal upload file. Kode error: " . ($_FILES['file_tugas']['error'] ?? 'Unknown'));
}

// 6. Validasi Ekstensi File (Whitelist yang Ketat)
$allowed = ['pdf', 'doc', 'docx', 'zip', 'rar', 'txt'];
$ext = strtolower(pathinfo($_FILES['file_tugas']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    die("Format file tidak diizinkan. Ekstensi yang diperbolehkan: " . implode(', ', $allowed));
}

// 7. Pengamanan Path Upload dengan __DIR__ (Mencegah Relative Path Traversal)
$uploadDir = __DIR__ . '/../uploads/mahasiswa_' . $mahasiswa_id . '/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// 8. Sanitasi Nama File dari Karakter Berbahaya
$nama_asli = pathinfo($_FILES['file_tugas']['name'], PATHINFO_FILENAME);
$nama_bersih = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nama_asli);

// Format nama file akhir: Waktu (Unix) - Nama Bersih . Ekstensi
$filename = time() . '-' . $nama_bersih . '.' . $ext;
$targetPath = $uploadDir . $filename;

// 9. Proses Pemindahan File dan Rekam ke Database
if (move_uploaded_file($_FILES['file_tugas']['tmp_name'], $targetPath)) {
    
    // Path relatif untuk disimpan ke database agar mudah dipanggil di Frontend
    $publicPath = 'controllers/uploads/mahasiswa_' . $mahasiswa_id . '/' . $filename;

    // Gunakan Prepared Statement untuk mengecek apakah tugas ini sudah pernah dikumpulkan
    $stmt = $conn->prepare("SELECT id FROM pengumpulan_tugas WHERE tugas_id = ? AND mahasiswa_id = ?");
    $stmt->bind_param('ii', $tugas_id, $mahasiswa_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // UPDATE jika mahasiswa mengunggah ulang tugas (Re-Submit)
        $u = $conn->prepare("UPDATE pengumpulan_tugas SET file_tugas = ?, waktu_kumpul = NOW() WHERE tugas_id = ? AND mahasiswa_id = ?");
        $u->bind_param('sii', $publicPath, $tugas_id, $mahasiswa_id);
        $u->execute();
    } else {
        // INSERT jika ini adalah pengumpulan pertama kali
        $i = $conn->prepare("INSERT INTO pengumpulan_tugas (tugas_id, mahasiswa_id, file_tugas, waktu_kumpul) VALUES (?, ?, ?, NOW())");
        $i->bind_param('iis', $tugas_id, $mahasiswa_id, $publicPath);
        $i->execute();
    }

    // Arahkan kembali ke halaman detail tugas dengan parameter sukses
    header("Location: ../../view/pages/mahasiswa/detail_tugas.php?id={$tugas_id}&upload=success");
    exit();
} else {
    die("Gagal memindahkan file tugas ke server Anda.");
}
?>