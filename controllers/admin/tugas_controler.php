<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dosen') {
    header("Location: /Project-student-task-management-system/view/auth/login.php"); exit();
}
date_default_timezone_set('Asia/Jakarta');
include_once __DIR__ . '/../../config/koneksi.php';

$user_id = $_SESSION['user_id'];
$pesan_sukses = ''; $pesan_error  = '';

$stmt_dosen = $conn->prepare("SELECT id FROM dosen WHERE user_id = ?");
$stmt_dosen->bind_param("i", $user_id);
$stmt_dosen->execute();
$data_dosen = $stmt_dosen->get_result()->fetch_assoc();
$stmt_dosen->close();
$dosen_id = $data_dosen['id'];
$nama_dosen = $_SESSION['nama'] ?? 'Dosen';

// FUNGSI BANTUAN UNTUK UPLOAD FILE
function prosesUploadFile() {
    if (isset($_FILES['file_lampiran']) && $_FILES['file_lampiran']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/tugas/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true); // Buat folder jika belum ada
        $file_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", basename($_FILES['file_lampiran']['name']));
        if (move_uploaded_file($_FILES['file_lampiran']['tmp_name'], $upload_dir . $file_name)) {
            return $file_name;
        }
    }
    return null;
}

// 1. CREATE TUGAS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_tugas') {
    $matkul_id = trim($_POST['matkul_id'] ?? ''); $judul_tugas = trim($_POST['judul_tugas'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? ''); $deadline = trim($_POST['deadline'] ?? '');
    $file_lampiran = prosesUploadFile();

    if (empty($matkul_id) || empty($judul_tugas) || empty($deadline)) {
        $pesan_error = "Mata kuliah, judul, dan deadline wajib diisi.";
    } else {
        $stmt_insert = $conn->prepare("INSERT INTO tugas (matkul_id, judul_tugas, deskripsi, deadline, file_lampiran) VALUES (?, ?, ?, ?, ?)");
        $stmt_insert->bind_param("issss", $matkul_id, $judul_tugas, $deskripsi, $deadline, $file_lampiran);
        if ($stmt_insert->execute()) $pesan_sukses = "Tugas berhasil ditambahkan.";
        else $pesan_error = "Gagal menyimpan tugas.";
        $stmt_insert->close();
    }
}

// 2. UPDATE TUGAS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_tugas') {
    $tugas_id = (int)($_POST['tugas_id'] ?? 0); $matkul_id = trim($_POST['matkul_id'] ?? '');
    $judul_tugas = trim($_POST['judul_tugas'] ?? ''); $deskripsi = trim($_POST['deskripsi'] ?? '');
    $deadline = trim($_POST['deadline'] ?? '');
    $file_lampiran = prosesUploadFile();

    if ($tugas_id > 0 && !empty($matkul_id) && !empty($judul_tugas) && !empty($deadline)) {
        if ($file_lampiran) { // Jika ada file baru diunggah
            $stmt_update = $conn->prepare("UPDATE tugas SET matkul_id=?, judul_tugas=?, deskripsi=?, deadline=?, file_lampiran=? WHERE id=?");
            $stmt_update->bind_param("issssi", $matkul_id, $judul_tugas, $deskripsi, $deadline, $file_lampiran, $tugas_id);
        } else { // Jika tidak ada file baru, biarkan file lama
            $stmt_update = $conn->prepare("UPDATE tugas SET matkul_id=?, judul_tugas=?, deskripsi=?, deadline=? WHERE id=?");
            $stmt_update->bind_param("isssi", $matkul_id, $judul_tugas, $deskripsi, $deadline, $tugas_id);
        }
        if ($stmt_update->execute()) $pesan_sukses = "Tugas berhasil diperbarui.";
        else $pesan_error = "Gagal memperbarui tugas.";
        $stmt_update->close();
    }
}

// 3. DELETE TUGAS
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'delete_tugas') {
    $tugas_id = (int)($_GET['id'] ?? 0);
    if ($tugas_id > 0) {
        $stmt_delete = $conn->prepare("DELETE FROM tugas WHERE id = ?");
        $stmt_delete->bind_param("i", $tugas_id);
        if ($stmt_delete->execute()) $pesan_sukses = "Tugas berhasil dihapus.";
        $stmt_delete->close();
    }
}

// 4. READ (Matkul & Daftar Tugas)
$stmt_matkul = $conn->prepare("SELECT id, nama_matkul FROM mata_kuliah WHERE dosen_id = ? ORDER BY nama_matkul ASC");
$stmt_matkul->bind_param("i", $dosen_id);
$stmt_matkul->execute();
$data_matkul = [];
$res_matkul = $stmt_matkul->get_result();
while ($row = $res_matkul->fetch_assoc()) $data_matkul[] = $row;
$stmt_matkul->close();

$filter_matkul_id = isset($_GET['matkul']) ? (int)$_GET['matkul'] : 0;
$query_tugas = "SELECT t.id, t.matkul_id, t.judul_tugas, t.deskripsi, t.deadline, t.file_lampiran, mk.nama_matkul FROM tugas t JOIN mata_kuliah mk ON t.matkul_id = mk.id WHERE mk.dosen_id = ?";
if ($filter_matkul_id > 0) {
    $stmt_tugas = $conn->prepare($query_tugas . " AND t.matkul_id = ? ORDER BY t.deadline ASC");
    $stmt_tugas->bind_param("ii", $dosen_id, $filter_matkul_id);
} else {
    $stmt_tugas = $conn->prepare($query_tugas . " ORDER BY t.deadline ASC");
    $stmt_tugas->bind_param("i", $dosen_id);
}
$stmt_tugas->execute();
$result_tugas = $stmt_tugas->get_result();
$data_tugas = [];
while ($row = $result_tugas->fetch_assoc()) {
    $row['deadline_format'] = date('d M Y, H:i', strtotime($row['deadline']));
    $data_tugas[] = $row;
}
$stmt_tugas->close();
?>