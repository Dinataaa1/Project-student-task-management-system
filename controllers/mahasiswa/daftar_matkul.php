<?php
// ==========================================================================
// 1. OTENTIKASI & KONEKSI BASIS DATA (CONTROLLER)
// ==========================================================================
// session_start();
// if (!isset($_SESSION['mahasiswa_id'])) {
//     header("Location: ../../view/auth/login.php"); 
//     exit();
// }

// Menyesuaikan jalur path dari folder controllers ke folder config
include_once '../../../config/koneksi.php';

// Data simulasi pengguna login. Akan diubah menjadi data dinamis melalui $_SESSION.
$mahasiswa_id = 1; 
$nama_user = "Luthfi Bahrur R."; 

// ==========================================================================
// 2. LOGIKA PENGAMBILAN DATA (QUERY)
// ==========================================================================
// Menarik data seluruh mata kuliah yang ditempuh oleh pengguna
$query_matkul = mysqli_query($conn, "
    SELECT mk.id, mk.nama_matkul 
    FROM mata_kuliah mk
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id
");

// Memasukkan hasil query ke dalam Array agar lebih mudah dibaca oleh Frontend
$data_matkul = [];
if ($query_matkul && mysqli_num_rows($query_matkul) > 0) {
    while($row = mysqli_fetch_assoc($query_matkul)) {
        $data_matkul[] = $row;
    }
}

// Menyiapkan urutan gaya presentasi blob
$pilihan_warna = ["blob-orange", "blob-blue"];
?>