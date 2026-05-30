<?php
// ==========================================================================
// 1. OTENTIKASI & KONEKSI BASIS DATA (CONTROLLER)
// ==========================================================================
date_default_timezone_set('Asia/Jakarta');
include_once '../../../config/koneksi.php'; 

$mahasiswa_id = 1; 
$nama_user = "Luthfi Bahrur R."; 

// ==========================================================================
// 2. LOGIKA PENGAMBILAN DATA (QUERY)
// ==========================================================================

// A. Menarik data nama mata kuliah (Maksimal 4 untuk highlight dashboard)
$query_matkul = mysqli_query($conn, "
    SELECT mk.id, mk.nama_matkul 
    FROM mata_kuliah mk
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id
    LIMIT 4
");

$data_matkul = [];
$pilihan_warna = ["blob-orange", "blob-blue"];
$index_warna = 0;

if ($query_matkul) {
    while ($row = mysqli_fetch_assoc($query_matkul)) {
        $data_matkul[] = [
            "id" => $row['id'], 
            "nama" => $row['nama_matkul'],
            "warna" => $pilihan_warna[$index_warna % 2] 
        ];
        $index_warna++;
    }
}

// B. Menarik data tanggal batas waktu tugas (Untuk Titik Merah di Kalender)
$query_tugas = mysqli_query($conn, "
    SELECT DATE(t.deadline) as tgl_deadline 
    FROM tugas t
    JOIN mata_kuliah mk ON t.matkul_id = mk.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id
");

$array_deadline = [];
if ($query_tugas) {
    while ($row = mysqli_fetch_assoc($query_tugas)) {
        // Disimpan dalam format Y-n-j (Contoh: 2026-5-13)
        $array_deadline[] = date("Y-n-j", strtotime($row['tgl_deadline'])); 
    }
}

// C. Menarik data deadline terdekat (Hanya tugas yang BELUM dikumpulkan)
$query_dl_terdekat = mysqli_query($conn, "
    SELECT t.id, mk.nama_matkul, t.deadline 
    FROM tugas t
    JOIN mata_kuliah mk ON t.matkul_id = mk.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    -- Melakukan relasi ke tabel pengumpulan untuk mengecek status
    LEFT JOIN pengumpulan_tugas pt ON pt.tugas_id = t.id AND pt.mahasiswa_id = k.mahasiswa_id
    WHERE k.mahasiswa_id = $mahasiswa_id 
    AND pt.id IS NULL -- LOGIKA INTI: Hanya ambil yang data pengumpulannya KOSONG (Belum kumpul)
    ORDER BY t.deadline ASC
    LIMIT 4
");

$data_dl_terdekat = [];
if ($query_dl_terdekat) {
    while ($row = mysqli_fetch_assoc($query_dl_terdekat)) {
        $data_dl_terdekat[] = $row;
    }
}

$tanggal_sekarang = date('d'); 
$bulan_sekarang = date('M');   
$tahun_sekarang = date('Y');   

// D. Menarik data untuk Panel Reminders (Tugas belum kumpul, urut terdekat, beserta nama Dosen)
$query_reminders = mysqli_query($conn, "
    SELECT t.id, mk.nama_matkul, t.judul_tugas, t.deadline, d.nama_dosen
    FROM tugas t
    JOIN mata_kuliah mk ON t.matkul_id = mk.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    JOIN dosen d ON mk.dosen_id = d.id
    LEFT JOIN pengumpulan_tugas pt ON pt.tugas_id = t.id AND pt.mahasiswa_id = k.mahasiswa_id
    WHERE k.mahasiswa_id = $mahasiswa_id 
    AND pt.id IS NULL
    ORDER BY t.deadline ASC
    LIMIT 3
");

$data_reminders = [];
if ($query_reminders) {
    while ($row = mysqli_fetch_assoc($query_reminders)) {
        $data_reminders[] = $row;
    }
}
?>