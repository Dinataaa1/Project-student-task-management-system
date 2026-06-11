<?php
// config/seeder.php

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    die("Akses ditolak: File ini hanya dapat dijalankan melalui terminal/SSH.");
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Hubungkan ke koneksi database menggunakan __DIR__
require_once __DIR__ . '/koneksi.php';

echo "<pre><h3>=== SYSTEM AUTOMATION SEEDER v3 ===</h3>";

// 1. Bersihkan seluruh tabel dengan memperhatikan integritas Foreign Key
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0;");
mysqli_query($conn, "TRUNCATE TABLE kelas;");
mysqli_query($conn, "TRUNCATE TABLE pengumpulan_tugas;");
mysqli_query($conn, "TRUNCATE TABLE krs;");
mysqli_query($conn, "TRUNCATE TABLE tugas;");
mysqli_query($conn, "TRUNCATE TABLE mata_kuliah;");
mysqli_query($conn, "TRUNCATE TABLE mahasiswa;");
mysqli_query($conn, "TRUNCATE TABLE dosen;");
mysqli_query($conn, "TRUNCATE TABLE users;");
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1;");
echo "✓ Database berhasil dikosongkan secara aman.<br>";

// 2. Injeksi Data Master Kelas
echo "<br><b>--- INJEKSI DATA KELAS ---</b><br>";
$data_kelas = [
    'D4 IT A',
    'D4 IT B',
    'D4 IT C',
    'D4 IT D'
];

$map_kelas_id = [];
foreach ($data_kelas as $nama_kelas) {
    $stmt_kelas = mysqli_prepare($conn, "INSERT INTO kelas (nama_kelas) VALUES (?)");
    mysqli_stmt_bind_param($stmt_kelas, "s", $nama_kelas);
    mysqli_stmt_execute($stmt_kelas);
    $map_kelas_id[] = mysqli_insert_id($conn);
    echo "✓ Kelas berhasil dibuat: {$nama_kelas}<br>";
}

// 3. Definisi Data Master Akun Penguji beserta Index Kelasnya
$data_dummy = [
    [
        'nama' => 'Ganis Ahmad',
        'email' => 'ganis@mhs.pens.ac.id',
        'role' => 'mahasiswa',
        'identitas' => '3125600099',
        'kelas_index' => 2 
    ],
    [
        'nama' => 'Luthfi Bahrur R.',
        'email' => 'luthfi@mhs.pens.ac.id',
        'role' => 'mahasiswa',
        'identitas' => '3125600001',
        'kelas_index' => 2 
    ],
    [
        'nama' => 'Luluatul Mahfudoh',
        'email' => 'lulu@mhs.pens.ac.id',
        'role' => 'mahasiswa',
        'identitas' => '3125600075',
        'kelas_index' => 1 
    ],
    [
        'nama' => 'Dio Achmad',
        'email' => 'dio@pens.ac.id',
        'role' => 'dosen',
        'identitas' => '198504102015'
    ]
];

$password_default = 'password123'; // Kata sandi seragam untuk testing
$map_mahasiswa_id = []; // Menyimpan ID Auto-Increment entitas mahasiswa
$dosen_db_id = null;    // Menyimpan ID Auto-Increment entitas dosen

// 4. Proses Injeksi Akun Penguji
echo "<br><b>--- INJEKSI DATA AKUN PENGUJI ---</b><br>";
foreach ($data_dummy as $data) {
    $hash_password = password_hash($password_default, PASSWORD_BCRYPT);
    
    $stmt_user = mysqli_prepare($conn, "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_user, "ssss", $data['nama'], $data['email'], $hash_password, $data['role']);
    mysqli_stmt_execute($stmt_user);
    $user_id = mysqli_insert_id($conn);
    
    if ($data['role'] === 'mahasiswa') {
        $target_kelas_id = $map_kelas_id[$data['kelas_index']];
        $stmt_mhs = mysqli_prepare($conn, "INSERT INTO mahasiswa (user_id, nrp, nama_mahasiswa, kelas_id) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_mhs, "issi", $user_id, $data['identitas'], $data['nama'], $target_kelas_id);
        mysqli_stmt_execute($stmt_mhs);
        $map_mahasiswa_id[] = mysqli_insert_id($conn); // Simpan ID mahasiswa untuk pembagian KRS
        echo "✓ Akun Mahasiswa berhasil dibuat: {$data['nama']} ({$data_kelas[$data['kelas_index']]})<br>";
    } else if ($data['role'] === 'dosen') {
        $stmt_dsn = mysqli_prepare($conn, "INSERT INTO dosen (user_id, nip, nama_dosen) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt_dsn, "iss", $user_id, $data['identitas'], $data['nama']);
        mysqli_stmt_execute($stmt_dsn);
        $dosen_db_id = mysqli_insert_id($conn); // Simpan ID dosen sebagai pemilik matkul
        echo "✓ Akun Dosen berhasil dibuat: {$data['nama']}<br>";
    }
}

// 5. Injeksi Data Mata Kuliah beserta Mapping Kelasnya (Dimiliki oleh Dosen Pak Dio)
echo "<br><b>--- INJEKSI DATA MATA KULIAH ---</b><br>";
$data_matkul = [
    ['nama' => 'Workshop Frontend Web', 'ruang' => 'AJ-201', 'jadwal' => 'Senin, 08:00', 'kelas_index' => 2],
    ['nama' => 'Basis Data Lanjut', 'ruang' => 'AJ-302', 'jadwal' => 'Selasa, 10:00', 'kelas_index' => 2],
    ['nama' => 'Pemrograman Berorientasi Objek', 'ruang' => 'D-203', 'jadwal' => 'Kamis, 13:00', 'kelas_index' => 1]
];

$map_matkul_id = [];
foreach ($data_matkul as $mk) {
    $target_kelas_id = $map_kelas_id[$mk['kelas_index']];
    $stmt_mk = mysqli_prepare($conn, "INSERT INTO mata_kuliah (nama_matkul, dosen_id, kelas_id, ruangan, jadwal) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_mk, "siiss", $mk['nama'], $dosen_db_id, $target_kelas_id, $mk['ruang'], $mk['jadwal']);
    mysqli_stmt_execute($stmt_mk);
    $map_matkul_id[] = mysqli_insert_id($conn);
    echo "✓ Mata Kuliah Berhasil Diinjeksi: {$mk['nama']} ({$data_kelas[$mk['kelas_index']]})<br>";
}

// 6. Injeksi Data Transaksi KRS (Mendaftarkan Semua Mahasiswa ke Semua Matkul di atas)
echo "<br><b>--- INJEKSI DATA KRS MAHASISWA ---</b><br>";
foreach ($map_mahasiswa_id as $mhs_id) {
    foreach ($map_matkul_id as $mk_id) {
        $semester = "4";
        $stmt_krs = mysqli_prepare($conn, "INSERT INTO krs (mahasiswa_id, mata_kuliah_id, semester) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt_krs, "iis", $mhs_id, $mk_id, $semester);
        mysqli_stmt_execute($stmt_krs);
    }
    echo "✓ Anggota Mahasiswa ID {$mhs_id} otomatis mengambil seluruh Mata Kuliah (KRS aktif).<br>";
}

// 7. Injeksi Data Tugas Kuliah (Menghubungkan ke ID Mata Kuliah yang eksis)
echo "<br><b>--- INJEKSI DATA TUGAS ---</b><br>";
$data_tugas = [
    [
        'matkul_index' => 0, // Workshop Frontend Web
        'judul' => 'Tugas 1: Slicing UI Figma Manajemen Tugas',
        'deskripsi' => 'Silakan kumpulkan berkas HTML dan CSS sesuai dengan rancangan mockup Figma yang telah disepakati kelompok masing-masing.',
        'deadline' => '2026-06-05 23:59:00'
    ],
    [
        'matkul_index' => 0, // Workshop Frontend Web
        'judul' => 'Tugas 2: Integrasi PHP Native & Session Check',
        'deskripsi' => 'Implementasikan fungsi BASE_URL absolut dan pembatasan hak akses halaman user menggunakan session PHP native.',
        'deadline' => '2026-06-12 23:59:00'
    ],
    [
        'matkul_index' => 1, // Basis Data Lanjut
        'judul' => 'Tugas Kelompok: Perancangan Skema Database E-Commerce',
        'deskripsi' => 'Buatlah file database.sql lengkap beserta foreign key constraint dan kumpulkan dalam format berkas SQL.',
        'deadline' => '2026-06-08 18:00:00'
    ]
];

foreach ($data_tugas as $tg) {
    $target_matkul_id = $map_matkul_id[$tg['matkul_index']];
    $stmt_tg = mysqli_prepare($conn, "INSERT INTO tugas (matkul_id, judul_tugas, deskripsi, deadline) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_tg, "isss", $target_matkul_id, $tg['judul'], $tg['deskripsi'], $tg['deadline']);
    mysqli_stmt_execute($stmt_tg);
    echo "✓ Tugas Berhasil Disuntikkan: {$tg['judul']} (Matkul ID: {$target_matkul_id})<br>";
}

echo "<br><b>🚀 OTOMATISASI BERHASIL! Seluruh data akun, kelas, KRS, mata kuliah, dan tugas siap diuji.</b>";
echo "</pre>";
?>