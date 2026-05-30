<?php
// config/seeder.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Hubungkan ke koneksi database menggunakan __DIR__
require_once __DIR__ . '/koneksi.php';

echo "<pre><h3>=== SYSTEM AUTOMATION SEEDER ===</h3>";

// 1. Bersihkan tabel lama agar tidak terjadi bentrok data (Urutan foreign key harus pas)
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0;");
mysqli_query($conn, "TRUNCATE TABLE pengumpulan_tugas;");
mysqli_query($conn, "TRUNCATE TABLE krs;");
mysqli_query($conn, "TRUNCATE TABLE tugas;");
mysqli_query($conn, "TRUNCATE TABLE mata_kuliah;");
mysqli_query($conn, "TRUNCATE TABLE mahasiswa;");
mysqli_query($conn, "TRUNCATE TABLE dosen;");
mysqli_query($conn, "TRUNCATE TABLE users;");
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1;");
echo "✓ Database berhasil dikosongkan secara aman.<br>";

// 2. Definisi Data Dummy Akun Penguji (Tambahkan sebanyak-banyaknya di sini)
$data_dummy = [
    // Kelompok Peran: Mahasiswa
    [
        'nama' => 'Ganis Ahmad',
        'email' => 'ganis@mhs.pens.ac.id',
        'role' => 'mahasiswa',
        'identitas' => '3125600099' // NRP
    ],
    [
        'nama' => 'Luthfi Bahrur R.',
        'email' => 'luthfi@mhs.pens.ac.id',
        'role' => 'mahasiswa',
        'identitas' => '3125600001' // NRP
    ],
    [
        'nama' => 'Luluatul Mahfudoh',
        'email' => 'lulu@mhs.pens.ac.id',
        'role' => 'mahasiswa',
        'identitas' => '3125600075' // NRP
    ],
    // Kelompok Peran: Dosen
    [
        'nama' => 'Dio Achmad',
        'email' => 'dio@pens.ac.id',
        'role' => 'dosen',
        'identitas' => '198504102015' // NIP
    ]
];

// Kata sandi seragam untuk mempermudah proses testing semua akun
$password_default = 'password123'; 

// 3. Proses Looping, Auto-Hash Bcrypt, dan Insert Relasi
foreach ($data_dummy as $data) {
    // PHP akan membuat hash 60 karakter yang sempurna secara dinamis di setiap komputer/laptop
    $hash_password = password_hash($password_default, PASSWORD_BCRYPT);
    
    // Insert ke tabel users
    $stmt_user = mysqli_prepare($conn, "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_user, "ssss", $data['nama'], $data['email'], $hash_password, $data['role']);
    mysqli_stmt_execute($stmt_user);
    
    // Ambil ID User yang baru saja tercipta
    $user_id = mysqli_insert_id($conn);
    
    // Masukkan ke detail profil masing-masing peran secara otomatis
    if ($data['role'] === 'mahasiswa') {
        $stmt_mhs = mysqli_prepare($conn, "INSERT INTO mahasiswa (user_id, nrp, nama_mahasiswa) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt_mhs, "iss", $user_id, $data['identitas'], $data['nama']);
        mysqli_stmt_execute($stmt_mhs);
        echo "✓ Akun Mahasiswa berhasil dibuat: {$data['nama']} ({$data['email']})<br>";
    } else if ($data['role'] === 'dosen') {
        $stmt_dsn = mysqli_prepare($conn, "INSERT INTO dosen (user_id, nip, nama_dosen) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt_dsn, "iss", $user_id, $data['identitas'], $data['nama']);
        mysqli_stmt_execute($stmt_dsn);
        echo "✓ Akun Dosen berhasil dibuat: {$data['nama']} ({$data['email']})<br>";
    }
}

echo "<br><b>SUKSES! Semua akun uji siap digunakan dengan password: <span style='color:green;'>$password_default</span></b>";
echo "</pre>";
?>