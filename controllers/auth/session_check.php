<?php
// controllers/auth/session_check.php
require_once __DIR__ . '/../../config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

function checkRoleMahasiswa() {
    global $conn; 

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
            session_destroy();
            header("Location: " . BASE_URL . "view/auth/login.php?error=data_corrupt");
            exit();
        }
        
        $_SESSION['mahasiswa_id'] = (int) $row['id'];
    }
}

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