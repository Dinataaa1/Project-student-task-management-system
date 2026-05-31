<?php
// controllers/auth/session_check.php
require_once __DIR__ . '/../../config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =========================================================================
// 1. FUNGSI UNTUK HALAMAN LOGIN (Mencegah user yang sudah login masuk form)
// =========================================================================
function larangJikaSudahLogin() {
    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['role'] === 'dosen') {
            // PERBAIKAN: Menambahkan BASE_URL yang sebelumnya hilang di rute admin
            header("Location: " . BASE_URL . "view/pages/admin/dashboard.php");
        } else {
            header("Location: " . BASE_URL . "view/pages/mahasiswa/dashboard.php");
        }
        exit();
    }
}

// =========================================================================
// 2. FUNGSI PROTEKSI HALAMAN MAHASISWA (Integrasi dengan Kodemu)
// =========================================================================
function checkRoleMahasiswa() {
    global $conn; // Wajib agar mysqli_query bisa mengenali koneksi database

    // A. Cek apakah belum login atau rolenya bukan mahasiswa
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
        header("Location: " . BASE_URL . "view/auth/login.php");
        exit();
    }

    // B. Logika Caching ID Entitas (Mencegah query berulang di setiap halaman)
    if (!isset($_SESSION['mahasiswa_id'])) {
        $user_id = (int) $_SESSION['user_id'];
        $res = mysqli_query($conn, "SELECT id FROM mahasiswa WHERE user_id = $user_id LIMIT 1");
        $row = mysqli_fetch_assoc($res);
        
        if (!$row) {
            // Tindakan preventif: Jika relasi data di DB rusak/hilang, paksa logout
            session_destroy();
            header("Location: " . BASE_URL . "view/auth/login.php?error=data_corrupt");
            exit();
        }
        
        // Simpan ke sesi global agar controller lain tinggal pakai $_SESSION['mahasiswa_id']
        $_SESSION['mahasiswa_id'] = (int) $row['id'];
    }
}

// =========================================================================
// 3. FUNGSI PROTEKSI HALAMAN DOSEN (Admin)
// =========================================================================
function checkRoleDosen() {
    global $conn;

    // A. Cek apakah belum login atau rolenya bukan dosen
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dosen') {
        header("Location: " . BASE_URL . "view/auth/login.php");
        exit();
    }

    // B. Logika Caching ID Entitas Dosen
    if (!isset($_SESSION['dosen_id'])) {
        $user_id = (int) $_SESSION['user_id'];
        $res = mysqli_query($conn, "SELECT id FROM dosen WHERE user_id = $user_id LIMIT 1");
        $row = mysqli_fetch_assoc($res);
        
        if (!$row) {
            session_destroy();
            header("Location: " . BASE_URL . "view/auth/login.php?error=data_corrupt");
            exit();
        }
        
        $_SESSION['dosen_id'] = (int) $row['id'];
    }
}
?>