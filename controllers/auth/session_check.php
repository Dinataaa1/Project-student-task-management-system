<?php
// controllers/auth/session_check.php
require_once __DIR__ . '/../../config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    // Menyatukan parameter konfigurasi kuki dalam satu array terstruktur
    session_start([
        'cookie_httponly' => true,
        'cookie_secure'   => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'),
        'cookie_samesite' => 'Strict', // Memastikan kuki tidak bocor pada request lintas situs
        'use_only_cookies' => true      # Tambahan: Memaksa sesi hanya pakai kuki, bukan lewat URL (SID)
    ]);
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function validasiCSRFToken() {
    $headers = getallheaders();
    $client_token = '';

    if (isset($headers['X-CSRF-TOKEN'])) {
        $client_token = $headers['X-CSRF-TOKEN'];
    }
    elseif (isset($_POST['csrf_token'])) {
        $client_token = $_POST['csrf_token'];
    }

    if (empty($client_token)|| !isset($_SESSION['csrf_token']) || $client_token !== $_SESSION['csrf_token']) {

        http_response_code(403);
        if (isset($headers['X-CSRF-TOKEN'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Akses ditolak: Validasi token CSRF gagal.'
            ]);
        } else {
            die("<h3>Akses Ditolak: Validasi Keamanan CSRF Gagal!</h3><p>Silakan kembali ke halaman login, segarkan (refresh) browser Anda, dan coba masuk kembali.</p>");
        }
        exit();
    }
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