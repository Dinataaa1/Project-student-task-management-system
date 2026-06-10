<?php

// jalur absolut file .env 
$env_path = __DIR__ . '/../.env';

if (!file_exists($env_path)) {
    die("<b>Error Keamanan:</b> Berkas konfigurasi .env tidak ditemukan. Silakan salin .env.example menjadi .env di folder root proyek Anda.");
}

// konfigurasi dari file .env
$env = parse_ini_file($env_path);

define('BASE_URL', $env['BASE_URL']);

$conn = mysqli_connect(
    $env['DB_HOST'],
    $env['DB_USER'],
    $env['DB_PASS'],
    $env['DB_NAME']
);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");