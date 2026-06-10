<?php
// controllers/mahasiswa/daftar_matkul.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once __DIR__ . '/../auth/session_check.php';
checkRoleMahasiswa();
require_once '../../../config/koneksi.php';

$mahasiswa_id = $_SESSION['mahasiswa_id'];

// A. Tarik data Matkul & Hitung tugas yang BELUM DIKUMPULKAN
// Gunakan tanda '?' untuk variabel $mahasiswa_id
$sql = "
    SELECT mk.*, d.nama_dosen, 
           (SELECT COUNT(*) 
            FROM tugas t 
            WHERE t.matkul_id = mk.id 
            AND t.id NOT IN (
                SELECT tugas_id 
                FROM pengumpulan_tugas pt 
                WHERE pt.mahasiswa_id = k.mahasiswa_id
            )
           ) as total_tugas 
    FROM mata_kuliah mk 
    JOIN dosen d ON mk.dosen_id = d.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = ?
";

// B. Eksekusi menggunakan Prepared Statement
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $mahasiswa_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// C. Masukkan ke array untuk dipakai di View
$daftar_matkul = [];
if ($result) {
    while($row = mysqli_fetch_assoc($result)) {
        $daftar_matkul[] = $row;
    }
}
?>