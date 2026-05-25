cat << 'EOF' > /tmp/tugas_controler.php
<?php
// ==========================================================================
// controllers/admin/tugas_controler.php
// Handle CRUD Tugas (Create & Read) milik dosen.
// Di-require di: view/pages/admin/tugas/create.php
//                view/pages/admin/tugas/detail.php
// ==========================================================================

// --- 1. AUTENTIKASI & KONEKSI ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dosen') {
    // Jalur absolut langsung mengarah ke halaman login tanpa peduli lokasi file yang me-require
    header("Location: /Project-student-task-management-system/view/auth/login.php");
    exit();
}

date_default_timezone_set('Asia/Jakarta');
include_once __DIR__ . '/../../config/koneksi.php';

// Ambil dosen_id dari tabel dosen berdasarkan user_id session
$user_id = $_SESSION['user_id'];

$stmt_dosen = $conn->prepare("SELECT id FROM dosen WHERE user_id = ?");
$stmt_dosen->bind_param("i", $user_id);
$stmt_dosen->execute();
$data_dosen = $stmt_dosen->get_result()->fetch_assoc();

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

    // Validasi field wajib
    if (empty($matkul_id) || empty($judul_tugas) || empty($deadline)) {
        $pesan_error = "Mata kuliah, judul tugas, dan deadline wajib diisi.";

    // Validasi format deadline
    } elseif (strtotime($deadline) === false) {
        $pesan_error = "Format deadline tidak valid.";

    // Validasi deadline harus di masa depan
    } elseif (strtotime($deadline) <= time()) {
        $pesan_error = "Deadline harus lebih dari waktu sekarang.";

    } else {
        // Keamanan: pastikan matkul ini benar milik dosen yang login
        $stmt_cek = $conn->prepare("SELECT id FROM mata_kuliah WHERE id = ? AND dosen_id = ?");
        $stmt_cek->bind_param("ii", $matkul_id, $dosen_id);
        $stmt_cek->execute();

        if (!$stmt_cek->get_result()->fetch_assoc()) {
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
$result_tugas = $stmt_tugas->get_result();

$data_tugas    = [];
$waktu_sekarang = time();

while ($row = $result_tugas->fetch_assoc()) {
    $deadline_ts = strtotime($row['deadline']);

    // Status deadline untuk warna badge di view
    if ($deadline_ts < $waktu_sekarang) {
        $row['status_deadline'] = 'lewat';       // merah
    } elseif ($deadline_ts - $waktu_sekarang <= 86400) {
        $row['status_deadline'] = 'mendesak';    // kuning (< 24 jam)
    } else {
        $row['status_deadline'] = 'aktif';       // hijau
    }

    $row['deadline_format'] = date('d M Y, H:i', $deadline_ts);
    $data_tugas[] = $row;
}
?>
EOF
echo "tugas_controler.php done"