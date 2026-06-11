<?php
require_once __DIR__ . '/session_check.php';
require_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validasiCSRFToken();

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama']    = $user['nama'];
        $_SESSION['role']    = $user['role'];

        if ($user['role'] == 'dosen') {
            header("Location: ../../view/pages/admin/dashboard.php");
        } else {
            header("Location: ../../view/pages/mahasiswa/dashboard.php");
        }
        exit;
    } else {
        header("Location: ../../view/auth/login.php?error=1");
        exit;
    }
}
?>