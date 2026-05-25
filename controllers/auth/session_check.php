<?php
session_start();

// Fungsi untuk melempar user yang BELUM LOGIN (dipakai di halaman dashboard dsb)
function harusLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /LOLUAS/view/auth/login.php"); 
        exit(); 
    }
}

// Fungsi untuk melempar user yang SUDAH LOGIN (dipakai di halaman login)
function larangJikaSudahLogin() {
    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['role'] == 'dosen') {
            header("Location: /LOLUAS/view/pages/admin/dashboard.php");
        } else {
            header("Location: /LOLUAS/view/pages/mahasiswa/dashboard.php");
        }
        exit;
    }
}
?>