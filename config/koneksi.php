<?php
// config/koneksi.php

function getEnvVar($key, $default = null) {
    $val = getenv($key);
    if ($val !== false) {
        return $val;
    }
    
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    if (isset($_SERVER[$key])) {
        return $_SERVER[$key];
    }

    static $env_file_data = null;
    if ($env_file_data === null) {
        $env_path = __DIR__ . '/../.env';
        if (file_exists($env_path)) {
            $env_file_data = parse_ini_file($env_path, false, INI_SCANNER_RAW);
        } else {
            $env_file_data = []; 
        }
    }

    return isset($env_file_data[$key]) ? $env_file_data[$key] : $default;
}

$db_host = getEnvVar('DB_HOST', 'localhost');
$db_user = getEnvVar('DB_USER', 'root');
$db_pass = getEnvVar('DB_PASS', '');
$db_name = getEnvVar('DB_NAME', 'lol_db');

$base_url = getEnvVar('BASE_URL', '/Project-student-task-management-system/');

if (!defined('BASE_URL')) {
    define('BASE_URL', $base_url);
}

mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {

    $app_env = getEnvVar('APP_ENV', 'local');
    if ($app_env === 'production') {
        error_log("Database connection failed: " . $conn->connect_error);
        die("Terjadi kesalahan pada sistem. Sistem sedang dalam perbaikan.");
    } else {
        die("Koneksi database gagal (Mode Lokal): " . $conn->connect_error);
    }
}

$conn->set_charset("utf8mb4");
?>