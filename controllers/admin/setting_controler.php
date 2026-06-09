<?php
// ==========================================================================
// controllers/admin/setting_controler.php
// Handle Read & Update Data Profil Dosen (Tanpa Jabatan)
// ==========================================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dosen') {
    header("Location: /Project-student-task-management-system/view/auth/login.php");
    exit();
}

include_once __DIR__ . '/../../config/koneksi.php';

$user_id = $_SESSION['user_id'];
$pesan_sukses = '';
$pesan_error  = '';

// 1. PROSES UPDATE PROFIL (Transaksi karena beda tabel)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_profil') {
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $nip          = trim($_POST['nip']          ?? '');
    $email        = trim($_POST['email']        ?? '');

    if (empty($nama_lengkap) || empty($nip)) {
        $pesan_error = "Nama Lengkap dan NIP wajib diisi.";
    } else {
        $conn->begin_transaction();
        try {
            // Update tabel users untuk nama dan email
            $stmt_user = $conn->prepare("UPDATE users SET nama = ?, email = ? WHERE id = ?");
            $stmt_user->bind_param("ssi", $nama_lengkap, $email, $user_id);
            $stmt_user->execute();
            $stmt_user->close();

            // Update tabel dosen untuk nip dan nama_dosen (TIDAK ADA JABATAN)
            $stmt_dosen = $conn->prepare("UPDATE dosen SET nama_dosen = ?, nip = ? WHERE user_id = ?");
            $stmt_dosen->bind_param("ssi", $nama_lengkap, $nip, $user_id);
            $stmt_dosen->execute();
            $stmt_dosen->close();

            $conn->commit();
            $pesan_sukses = "Profil berhasil diperbarui!";
            $_SESSION['nama'] = $nama_lengkap; 
        } catch (Exception $e) {
            $conn->rollback();
            $pesan_error = "Gagal memperbarui profil: " . $e->getMessage();
        }
    }
}

// 2. READ DATA PROFIL SAAT INI (TIDAK ADA JABATAN)
$stmt_profil = $conn->prepare("
    SELECT d.nama_dosen, d.nip, u.email 
    FROM dosen d
    JOIN users u ON d.user_id = u.id
    WHERE d.user_id = ?
");
$stmt_profil->bind_param("i", $user_id);
$stmt_profil->execute();
$profil = $stmt_profil->get_result()->fetch_assoc();
$stmt_profil->close();

if (!$profil) {
    $profil = [
        'nama_dosen' => 'Nama Belum Diatur',
        'nip'        => '-',
        'email'      => '-'
    ];
}
// Mapping agar nama_lengkap sesuai dengan variabel di view Anda
$profil['nama_lengkap'] = $profil['nama_dosen'];
?>