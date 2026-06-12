<?php
// ==========================================================================
// controllers/admin/tugas_controler.php
// Handle Create & Read Tugas milik dosen.
// Di-require di: view/pages/admin/tugas/create.php
//                view/pages/admin/tugas/detail.php
// ==========================================================================

// --- 1. AUTENTIKASI & KONEKSI ---
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

$stmt_dosen = $conn->prepare("SELECT id FROM dosen WHERE user_id = ?");
$stmt_dosen->bind_param("i", $user_id);
$stmt_dosen->execute();
$data_dosen = $stmt_dosen->get_result()->fetch_assoc();
$stmt_dosen->close();

if (!$data_dosen) {
    session_destroy();
    header("Location: /Project-student-task-management-system/view/auth/login.php");
    exit();
}

$dosen_id   = $data_dosen['id'];
$nama_dosen = $_SESSION['nama'];

// --- 2. VARIABEL FEEDBACK ---
$pesan_sukses = '';
$pesan_error  = '';

// --- 3. CREATE TUGAS (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_tugas') {

    $matkul_id   = trim($_POST['matkul_id']   ?? '');
    $judul_tugas = trim($_POST['judul_tugas'] ?? '');
    $deskripsi   = trim($_POST['deskripsi']   ?? '');
    $deadline    = trim($_POST['deadline']    ?? '');

    if (empty($matkul_id) || empty($judul_tugas) || empty($deadline)) {
        $pesan_error = "Mata kuliah, judul tugas, dan deadline wajib diisi.";

    } elseif (strtotime($deadline) === false) {
        $pesan_error = "Format deadline tidak valid.";

    } elseif (strtotime($deadline) <= time()) {
        $pesan_error = "Deadline harus lebih dari waktu sekarang.";

    } else {
        // Keamanan: pastikan matkul ini milik dosen yang login
        $stmt_cek = $conn->prepare("SELECT id FROM mata_kuliah WHERE id = ? AND dosen_id = ?");
        $stmt_cek->bind_param("ii", $matkul_id, $dosen_id);
        $stmt_cek->execute();
        $cek = $stmt_cek->get_result()->fetch_assoc();
        $stmt_cek->close();

        if (!$cek) {
            $pesan_error = "Mata kuliah tidak valid atau bukan milik Anda.";
        } else {
            $stmt_insert = $conn->prepare("
                INSERT INTO tugas (matkul_id, judul_tugas, deskripsi, deadline)
                VALUES (?, ?, ?, ?)
            ");
            $stmt_insert->bind_param("isss", $matkul_id, $judul_tugas, $deskripsi, $deadline);

            if ($stmt_insert->execute()) {
                $pesan_sukses = "Tugas \"" . htmlspecialchars($judul_tugas) . "\" berhasil ditambahkan.";
            } else {
                $pesan_error = "Gagal menyimpan tugas. Silakan coba lagi.";
            }
            $stmt_insert->close();
        }
    }
}

// --- 4. READ: Dropdown matkul untuk form create ---
$stmt_matkul = $conn->prepare("
    SELECT id, nama_matkul FROM mata_kuliah
    WHERE dosen_id = ?
    ORDER BY nama_matkul ASC
");
$stmt_matkul->bind_param("i", $dosen_id);
$stmt_matkul->execute();
$result_matkul = $stmt_matkul->get_result();

$data_matkul = [];
while ($row = $result_matkul->fetch_assoc()) {
    $data_matkul[] = $row;
}
$stmt_matkul->close();

// --- 5. READ: Daftar tugas (bisa difilter per matkul via ?matkul=ID) ---
$filter_matkul_id = isset($_GET['matkul']) ? (int)$_GET['matkul'] : 0;

if ($filter_matkul_id > 0) {
    $stmt_tugas = $conn->prepare("
        SELECT t.id, t.judul_tugas, t.deskripsi, t.deadline, mk.nama_matkul
        FROM tugas t
        JOIN mata_kuliah mk ON t.matkul_id = mk.id
        WHERE mk.dosen_id = ? AND t.matkul_id = ?
        ORDER BY t.deadline ASC
    ");
    $stmt_tugas->bind_param("ii", $dosen_id, $filter_matkul_id);
} else {
    $stmt_tugas = $conn->prepare("
        SELECT t.id, t.judul_tugas, t.deskripsi, t.deadline, mk.nama_matkul
        FROM tugas t
        JOIN mata_kuliah mk ON t.matkul_id = mk.id
        WHERE mk.dosen_id = ?
        ORDER BY t.deadline ASC
    ");
    $stmt_tugas->bind_param("i", $dosen_id);
}

$stmt_tugas->execute();
$result_tugas   = $stmt_tugas->get_result();
$data_tugas     = [];
$waktu_sekarang = time();

while ($row = $result_tugas->fetch_assoc()) {
    $deadline_ts = strtotime($row['deadline']);

    if ($deadline_ts < $waktu_sekarang) {
        $row['status_deadline'] = 'lewat';
    } elseif ($deadline_ts - $waktu_sekarang <= 86400) {
        $row['status_deadline'] = 'mendesak';
    } else {
        $row['status_deadline'] = 'aktif';
    }

    $row['deadline_format'] = date('d M Y, H:i', $deadline_ts);
    $data_tugas[] = $row;
}
$stmt_tugas->close();
?>