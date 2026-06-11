<?php
// ==========================================================================
// controllers/admin/nilai_process.php
// Handle pemberian nilai tugas mahasiswa oleh dosen.
// Di-connect lewat fetch() JavaScript di: view/pages/admin/form_nilai.php
// ==========================================================================

require_once __DIR__ . '/../auth/session_check.php';

checkRoleDosen();

validasiCSRFToken();

// --- 4. FORMAT RESPON JSON & VALIDASI METODE ---
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan.']);
    exit();
}

// --- 5. AMBIL INPUT ---
// Support JSON body (fetch JS) maupun form POST biasa
$content_type = $_SERVER['CONTENT_TYPE'] ?? '';
if (str_contains($content_type, 'application/json')) {
    $body           = json_decode(file_get_contents('php://input'), true);
    $pengumpulan_id = (int)($body['pengumpulan_id'] ?? 0);
    $nilai_input    = $body['nilai'] ?? null;
} else {
    $pengumpulan_id = (int)($_POST['pengumpulan_id'] ?? 0);
    $nilai_input    = $_POST['nilai'] ?? null;
}

// --- 6. VALIDASI INPUT FORMAT ---
if ($pengumpulan_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID Pengumpulan tidak valid.']);
    exit();
}
if ($nilai_input === null || $nilai_input === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nilai tidak boleh kosong.']);
    exit();
}
$nilai = (int)$nilai_input;
if ($nilai < 0 || $nilai > 100) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nilai harus berada antara 0 - 100.']);
    exit();
}

// Ambil ID Dosen dari session cache yang sudah di-set oleh checkRoleDosen()
$dosen_id = (int)$_SESSION['dosen_id'];

// --- 7. PROSES DATABASE (TRANSAKSI) ---
try {
    // STEP 1: Mulai Transaksi Database (Pessimistic Locking)
    $conn->begin_transaction();

    // Query untuk memastikan tugas ini benar-benar milik mata kuliah yang diajar dosen tersebut
    $query_cek = "
        SELECT p.id, p.nilai AS nilai_lama, t.judul_tugas, m.nama_mahasiswa 
        FROM pengumpulan_tugas p
        JOIN tugas t ON p.tugas_id = t.id
        JOIN mata_kuliah mk ON t.matkul_id = mk.id
        JOIN mahasiswa m ON p.mahasiswa_id = m.id
        WHERE p.id = ? AND mk.dosen_id = ?
        FOR UPDATE
    ";
    
    $stmt_cek = $conn->prepare($query_cek);
    $stmt_cek->bind_param("ii", $pengumpulan_id, $dosen_id);
    $stmt_cek->execute();
    $data_pengumpulan = $stmt_cek->get_result()->fetch_assoc();
    $stmt_cek->close();

    // Proteksi IDOR (Mencegah dosen lain menilai tugas yang bukan kelasnya)
    if (!$data_pengumpulan) {
        $conn->rollback();
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Pengumpulan tidak ditemukan atau bukan wewenang Anda.']);
        exit();
    }

    // STEP 2: Update nilai
    $stmt_update = $conn->prepare("UPDATE pengumpulan_tugas SET nilai = ? WHERE id = ?");
    $stmt_update->bind_param("ii", $nilai, $pengumpulan_id);

    if (!$stmt_update->execute()) {
        throw new Exception("Gagal memperbarui data di database.");
    }
    $stmt_update->close();

    // STEP 3: Commit — semua berhasil, simpan permanen
    $conn->commit();

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Nilai berhasil disimpan.',
        'data'    => [
            'pengumpulan_id' => $pengumpulan_id,
            'nama_mahasiswa' => $data_pengumpulan['nama_mahasiswa'],
            'judul_tugas'    => $data_pengumpulan['judul_tugas'],
            'nilai_lama'     => $data_pengumpulan['nilai_lama'] !== null ? (int)$data_pengumpulan['nilai_lama'] : null,
            'nilai_baru'     => $nilai
        ]
    ]);

} catch (Exception $e) {
    // Batalkan seluruh perubahan jika terjadi error di tengah jalan
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
}
?>