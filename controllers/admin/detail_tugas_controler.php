<?php
// ==========================================================================
// controllers/admin/detail_tugas_controler.php
// Handle Read Detail Tugas & Daftar Pengumpulan Mahasiswa
// ==========================================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dosen') {
    header("Location: /Project-student-task-management-system/view/auth/login.php");
    exit();
}

date_default_timezone_set('Asia/Jakarta');
include_once __DIR__ . '/../../config/koneksi.php';

$user_id = $_SESSION['user_id'];

// 1. Ambil ID Dosen & Nama (Untuk Sidebar)
$stmt_dosen = $conn->prepare("SELECT id, nama_dosen FROM dosen WHERE user_id = ?");
$stmt_dosen->bind_param("i", $user_id);
$stmt_dosen->execute();
$data_dosen = $stmt_dosen->get_result()->fetch_assoc();
$stmt_dosen->close();

if (!$data_dosen) {
    header("Location: /Project-student-task-management-system/view/auth/login.php");
    exit();
}
$dosen_id = $data_dosen['id'];
$nama_dosen = $data_dosen['nama_dosen']; 

// 2. Variabel Feedback & ID Tugas
$tugas_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$pesan_error = '';

// 3. Ambil Detail Tugas
$stmt_tugas = $conn->prepare("
    SELECT t.id, t.judul_tugas, t.deskripsi, t.deadline, t.file_lampiran, mk.nama_matkul
    FROM tugas t
    JOIN mata_kuliah mk ON t.matkul_id = mk.id
    WHERE t.id = ? AND mk.dosen_id = ?
");
$stmt_tugas->bind_param("ii", $tugas_id, $dosen_id);
$stmt_tugas->execute();
$data_tugas = $stmt_tugas->get_result()->fetch_assoc();
$stmt_tugas->close();

if (!$data_tugas) {
    $pesan_error = "Tugas tidak ditemukan atau bukan wewenang Anda.";
}

// 4. Ambil Daftar Pengumpulan Mahasiswa
$data_pengumpulan = [];
if ($data_tugas) {
    // Menggunakan Alias (AS) agar sesuai dengan variabel di frontend Anda
    $stmt_pengumpulan = $conn->prepare("
        SELECT pt.id AS pengumpulan_id, pt.file_tugas AS file_path, pt.nilai, pt.waktu_kumpul AS tanggal_kumpul,
               m.nama_mahasiswa, m.nrp
        FROM pengumpulan_tugas pt
        JOIN mahasiswa m ON pt.mahasiswa_id = m.id
        WHERE pt.tugas_id = ?
        ORDER BY m.nrp ASC
    ");
    $stmt_pengumpulan->bind_param("i", $tugas_id);
    $stmt_pengumpulan->execute();
    $result = $stmt_pengumpulan->get_result();

    $deadline_ts = strtotime($data_tugas['deadline']);

    while ($row = $result->fetch_assoc()) {
        if (empty($row['tanggal_kumpul'])) {
            $row['status_kumpul'] = 'kosong';
            $row['status_teks']   = 'Belum Kumpul';
        } else {
            $kumpul_ts = strtotime($row['tanggal_kumpul']);
            if ($kumpul_ts > $deadline_ts) {
                $row['status_kumpul'] = 'terlambat';
                $row['status_teks']   = 'Terlambat';
            } else {
                $row['status_kumpul'] = 'tepat';
                $row['status_teks']   = 'Tepat Waktu';
            }
        }
        $data_pengumpulan[] = $row;
    }
    $stmt_pengumpulan->close();
}
?>