<?php

require_once __DIR__ . '/../../config/koneksi.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Fungsi untuk melempar user yang BELUM LOGIN (dipakai di halaman dashboard dsb)
function harusLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . BASE_URL . "view/auth/login.php"); 
        exit(); 
    }
}

// Fungsi untuk melempar user yang SUDAH LOGIN (dipakai di halaman login)
function larangJikaSudahLogin() {
    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['role'] == 'dosen') {
            header("Location: view/pages/admin/dashboard.php");
        } else {
            header("Location: " . BASE_URL . "view/pages/mahasiswa/dashboard.php");
        }
        exit;
    }
}
?>