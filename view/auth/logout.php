<?php
// controllers/auth/logout.php
session_start();
session_unset();    // Menghapus semua variabel sesi
session_destroy();  // Menghancurkan sesi sepenuhnya

// Mengarahkan kembali ke halaman login
header("Location: ../../view/auth/login.php");
exit();
?>