<?php
// ==========================================================================
// controllers/admin/matkul_controler.php
// Handle CRUD Mata Kuliah milik dosen.
// Di-require di: view/pages/admin/mata_kuliah/matkul.php   (Read)
//                view/pages/admin/mata_kuliah/create.php   (Create)
//                view/pages/admin/mata_kuliah/edit.php     (Update)
// Delete dipanggil via: ?action=delete&id=ID
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

$stmt_dosen = $conn->prepare("SELECT id, nama_dosen FROM dosen WHERE user_id = ?");
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
$nama_dosen = $data_dosen['nama_dosen'];

// --- 2. VARIABEL FEEDBACK ---
$pesan_sukses = '';
$pesan_error  = '';

// ==========================================================================
// --- 3. CREATE MATKUL (POST action=create_matkul) ---
// ==========================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_matkul') {

    $nama_matkul = trim($_POST['nama_matkul'] ?? '');
    $ruangan     = trim($_POST['ruangan']     ?? '');
    $jadwal      = trim($_POST['jadwal']      ?? '');

    // Validasi
    if (empty($nama_matkul)) {
        $pesan_error = "Nama mata kuliah wajib diisi.";

    } elseif (mb_strlen($nama_matkul) > 100) {
        $pesan_error = "Nama mata kuliah maksimal 100 karakter.";

    } else {
        // Cek duplikat: dosen tidak boleh punya 2 matkul dengan nama sama
        $stmt_dup = $conn->prepare("SELECT id FROM mata_kuliah WHERE nama_matkul = ? AND dosen_id = ?");
        $stmt_dup->bind_param("si", $nama_matkul, $dosen_id);
        $stmt_dup->execute();
        $duplikat = $stmt_dup->get_result()->fetch_assoc();
        $stmt_dup->close();

        if ($duplikat) {
            $pesan_error = "Mata kuliah \"" . htmlspecialchars($nama_matkul) . "\" sudah ada.";
        } else {
            $stmt_insert = $conn->prepare("
                INSERT INTO mata_kuliah (nama_matkul, dosen_id, ruangan, jadwal)
                VALUES (?, ?, ?, ?)
            ");
            $stmt_insert->bind_param("siss", $nama_matkul, $dosen_id, $ruangan, $jadwal);

            if ($stmt_insert->execute()) {
                $pesan_sukses = "Mata kuliah \"" . htmlspecialchars($nama_matkul) . "\" berhasil ditambahkan.";
            } else {
                $pesan_error = "Gagal menyimpan mata kuliah. Silakan coba lagi.";
            }
            $stmt_insert->close();
        }
    }
}

// ==========================================================================
// --- 4. UPDATE MATKUL (POST action=edit_matkul) ---
// ==========================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_matkul') {

    $matkul_id   = (int)($_POST['matkul_id']   ?? 0);
    $nama_matkul = trim($_POST['nama_matkul'] ?? '');
    $ruangan     = trim($_POST['ruangan']     ?? '');
    $jadwal      = trim($_POST['jadwal']      ?? '');

    if ($matkul_id <= 0 || empty($nama_matkul)) {
        $pesan_error = "Data tidak lengkap. ID dan nama mata kuliah wajib diisi.";

    } else {
        // Keamanan: pastikan matkul ini benar milik dosen yang login
        $stmt_cek = $conn->prepare("SELECT id FROM mata_kuliah WHERE id = ? AND dosen_id = ?");
        $stmt_cek->bind_param("ii", $matkul_id, $dosen_id);
        $stmt_cek->execute();
        $cek = $stmt_cek->get_result()->fetch_assoc();
        $stmt_cek->close();

        if (!$cek) {
            $pesan_error = "Mata kuliah tidak ditemukan atau bukan milik Anda.";
        } else {
            $stmt_update = $conn->prepare("
                UPDATE mata_kuliah
                SET nama_matkul = ?, ruangan = ?, jadwal = ?
                WHERE id = ? AND dosen_id = ?
            ");
            $stmt_update->bind_param("sssii", $nama_matkul, $ruangan, $jadwal, $matkul_id, $dosen_id);

            if ($stmt_update->execute()) {
                $pesan_sukses = "Mata kuliah berhasil diperbarui.";
            } else {
                $pesan_error = "Gagal memperbarui mata kuliah. Silakan coba lagi.";
            }
            $stmt_update->close();
        }
    }
}

// ==========================================================================
// --- 5. DELETE MATKUL (GET action=delete&id=ID) ---
// ==========================================================================
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'delete_matkul') {

    $matkul_id = (int)($_GET['id'] ?? 0);

    if ($matkul_id <= 0) {
        $pesan_error = "ID mata kuliah tidak valid.";
    } else {
        // Keamanan: pastikan matkul ini milik dosen yang login
        $stmt_cek = $conn->prepare("SELECT id FROM mata_kuliah WHERE id = ? AND dosen_id = ?");
        $stmt_cek->bind_param("ii", $matkul_id, $dosen_id);
        $stmt_cek->execute();
        $cek = $stmt_cek->get_result()->fetch_assoc();
        $stmt_cek->close();

        if (!$cek) {
            $pesan_error = "Mata kuliah tidak ditemukan atau bukan milik Anda.";
        } else {
            $stmt_delete = $conn->prepare("DELETE FROM mata_kuliah WHERE id = ? AND dosen_id = ?");
            $stmt_delete->bind_param("ii", $matkul_id, $dosen_id);

            if ($stmt_delete->execute()) {
                // Tugas yang terkait otomatis terhapus karena ON DELETE CASCADE di database
                $pesan_sukses = "Mata kuliah berhasil dihapus.";
            } else {
                $pesan_error = "Gagal menghapus mata kuliah. Silakan coba lagi.";
            }
            $stmt_delete->close();
        }
    }
}

// ==========================================================================
// --- 6. READ: Ambil data satu matkul untuk form edit (via ?edit_id=ID) ---
// ==========================================================================
$data_edit = null;

if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];

    $stmt_edit = $conn->prepare("
        SELECT id, nama_matkul, ruangan, jadwal
        FROM mata_kuliah
        WHERE id = ? AND dosen_id = ?
    ");
    $stmt_edit->bind_param("ii", $edit_id, $dosen_id);
    $stmt_edit->execute();
    $data_edit = $stmt_edit->get_result()->fetch_assoc();
    $stmt_edit->close();

    // Kalau ID tidak ditemukan / bukan milik dosen ini
    if (!$data_edit) {
        $pesan_error = "Mata kuliah tidak ditemukan.";
    }
}

// ==========================================================================
// --- 7. READ: Daftar semua matkul milik dosen ---
// ==========================================================================
$stmt_list = $conn->prepare("
    SELECT mk.id, mk.nama_matkul, mk.ruangan, mk.jadwal,
           COUNT(t.id) AS jumlah_tugas
    FROM mata_kuliah mk
    LEFT JOIN tugas t ON t.matkul_id = mk.id
    WHERE mk.dosen_id = ?
    GROUP BY mk.id
    ORDER BY mk.nama_matkul ASC
");
$stmt_list->bind_param("i", $dosen_id);
$stmt_list->execute();
$result_list = $stmt_list->get_result();

$data_matkul_list = [];
while ($row = $result_list->fetch_assoc()) {
    $data_matkul_list[] = $row;
}
$stmt_list->close();
?>