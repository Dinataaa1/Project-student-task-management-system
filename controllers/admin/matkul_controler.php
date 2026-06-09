<?php
// ==========================================================================
// controllers/admin/matkul_controler.php
// Handle CRUD Mata Kuliah milik dosen.
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
    // [BARU] Menerima data kelas_id dari form dropdown
    $kelas_id    = (int)($_POST['kelas_id']   ?? 0); 
    
    // --- PENGGABUNGAN JADWAL BARU ---
    $hari = trim($_POST['hari'] ?? '');
    $jam  = trim($_POST['jam']  ?? '');
    $jadwal = $hari . ', ' . $jam;

    // [BARU] Validasi juga memastikan kelas_id tidak boleh kosong/0
    
    if (empty($nama_matkul) || $kelas_id === 0 || empty($ruangan)) {
        $pesan_error = "Nama mata kuliah, Kelas, dan Ruangan wajib diisi.";

    } elseif (mb_strlen($nama_matkul) > 100) {
        $pesan_error = "Nama mata kuliah maksimal 100 karakter.";

    } else {
        $stmt_dup = $conn->prepare("SELECT id FROM mata_kuliah WHERE nama_matkul = ? AND dosen_id = ?");
        $stmt_dup->bind_param("si", $nama_matkul, $dosen_id);
        $stmt_dup->execute();
        $duplikat = $stmt_dup->get_result()->fetch_assoc();
        $stmt_dup->close();

        if ($duplikat) {
            $pesan_error = "Mata kuliah \"" . htmlspecialchars($nama_matkul) . "\" sudah ada.";
        } else {
            // [BARU] Menambahkan kelas_id ke dalam Query INSERT
            $stmt_insert = $conn->prepare("
                INSERT INTO mata_kuliah (nama_matkul, dosen_id, kelas_id, ruangan, jadwal)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt_insert->bind_param("siiss", $nama_matkul, $dosen_id, $kelas_id, $ruangan, $jadwal);

            if ($stmt_insert->execute()) {
                // [BARU] LOGIKA KRS OTOMATIS DIMULAI DI SINI
                $new_matkul_id = $conn->insert_id; 
                $jumlah_mhs = 0;

                // 1. Cari semua mahasiswa yang ID kelasnya sama dengan yang dipilih
                $stmt_mhs = $conn->prepare("SELECT id FROM mahasiswa WHERE kelas_id = ?");
                $stmt_mhs->bind_param("i", $kelas_id);
                $stmt_mhs->execute();
                $result_mhs = $stmt_mhs->get_result();

                // 2. Jika ada mahasiswa, daftarkan mereka ke KRS satu per satu
                if ($result_mhs->num_rows > 0) {
                    $stmt_krs = $conn->prepare("INSERT INTO krs (mahasiswa_id, mata_kuliah_id) VALUES (?, ?)");
                    while ($mhs = $result_mhs->fetch_assoc()) {
                        $mhs_id = $mhs['id'];
                        $stmt_krs->bind_param("ii", $mhs_id, $new_matkul_id);
                        $stmt_krs->execute();
                        $jumlah_mhs++;
                    }
                    $stmt_krs->close();
                }
                $stmt_mhs->close();

                // Pesan sukses disesuaikan untuk menampilkan jumlah mahasiswa yang masuk KRS
                $pesan_sukses = "Matkul \"" . htmlspecialchars($nama_matkul) . "\" ditambahkan! $jumlah_mhs mahasiswa otomatis masuk KRS.";
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
    // [BARU] Menerima data kelas_id dari form edit
    $kelas_id    = (int)($_POST['kelas_id']   ?? 0);
    
    // --- PENGGABUNGAN JADWAL BARU ---
    $hari = trim($_POST['hari'] ?? '');
    $jam  = trim($_POST['jam']  ?? '');
    $jadwal = $hari . ', ' . $jam;

    // [BARU] Validasi kelas tidak boleh kosong
    if ($matkul_id <= 0 || empty($nama_matkul) || $kelas_id === 0 || empty($ruangan)) {
        $pesan_error = "Data tidak lengkap. ID, nama mata kuliah, kelas, dan ruangan wajib diisi.";

    } else {
        $stmt_cek = $conn->prepare("SELECT id FROM mata_kuliah WHERE id = ? AND dosen_id = ?");
        $stmt_cek->bind_param("ii", $matkul_id, $dosen_id);
        $stmt_cek->execute();
        $cek = $stmt_cek->get_result()->fetch_assoc();
        $stmt_cek->close();

        if (!$cek) {
            $pesan_error = "Mata kuliah tidak ditemukan atau bukan milik Anda.";
        } else {
            // [BARU] Menambahkan pembaruan kolom kelas_id
            $stmt_update = $conn->prepare("
                UPDATE mata_kuliah
                SET nama_matkul = ?, kelas_id = ?, ruangan = ?, jadwal = ?
                WHERE id = ? AND dosen_id = ?
            ");
            $stmt_update->bind_param("sissii", $nama_matkul, $kelas_id, $ruangan, $jadwal, $matkul_id, $dosen_id);

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

    // [BARU] Menambahkan kelas_id ke data yang diambil
    $stmt_edit = $conn->prepare("
        SELECT id, nama_matkul, kelas_id, ruangan, jadwal
        FROM mata_kuliah
        WHERE id = ? AND dosen_id = ?
    ");
    $stmt_edit->bind_param("ii", $edit_id, $dosen_id);
    $stmt_edit->execute();
    $data_edit = $stmt_edit->get_result()->fetch_assoc();
    $stmt_edit->close();

    if (!$data_edit) {
        $pesan_error = "Mata kuliah tidak ditemukan.";
    }
}

// ==========================================================================
// --- 7. READ: Daftar semua matkul milik dosen ---
// ==========================================================================
// [BARU] Melakukan LEFT JOIN ke tabel kelas untuk mengambil nama_kelas
$stmt_list = $conn->prepare("
    SELECT mk.id, mk.nama_matkul, mk.ruangan, mk.jadwal, mk.kelas_id, k.nama_kelas,
           COUNT(t.id) AS jumlah_tugas
    FROM mata_kuliah mk
    LEFT JOIN kelas k ON mk.kelas_id = k.id
    LEFT JOIN tugas t ON t.matkul_id = mk.id
    WHERE mk.dosen_id = ?
    GROUP BY mk.id
    ORDER BY mk.nama_matkul ASC
");
$stmt_list->bind_param("i", $dosen_id);
$stmt_list->execute();
$result_list = $stmt_list->get_result();

$data_matkul = []; 
while ($row = $result_list->fetch_assoc()) {
    $data_matkul[] = $row; 
}
$stmt_list->close();

// ==========================================================================
// --- 8. READ: Ambil Data Kelas untuk Dropdown (BARU) ---
// ==========================================================================
// Data ini dibutuhkan agar file dashboard.php bisa memunculkan pilihan kelas di form
$stmt_kelas = $conn->prepare("SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas ASC");
$stmt_kelas->execute();
$result_kelas = $stmt_kelas->get_result();

$data_kelas = [];
while ($row = $result_kelas->fetch_assoc()) {
    $data_kelas[] = $row;
}
$stmt_kelas->close();

?>