cat << 'EOF' > /tmp/nilai_process.php
<?php
// ==========================================================================
// controllers/admin/nilai_process.php
// Handle pemberian nilai tugas mahasiswa oleh dosen.
// Di-connect lewat fetch() JavaScript di: view/pages/admin/form_nilai.php
// Mengembalikan JSON (bukan HTML).
// ==========================================================================

// --- 1. HEADER JSON ---
header('Content-Type: application/json');

// --- 2. AUTENTIKASI ---
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dosen') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Silakan login terlebih dahulu.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan.']);
    exit();
}

include_once __DIR__ . '/../../config/koneksi.php';

// --- 3. AMBIL INPUT ---
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

// --- 4. VALIDASI INPUT ---
if ($pengumpulan_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID pengumpulan tidak valid.']);
    exit();
}

if (is_null($nilai_input) || !is_numeric($nilai_input)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nilai harus berupa angka.']);
    exit();
}

$nilai = (int)$nilai_input;

if ($nilai < 0 || $nilai > 100) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nilai harus antara 0 sampai 100.']);
    exit();
}

// --- 5. AMBIL DOSEN_ID ---
$user_id = $_SESSION['user_id'];

$stmt_dosen = $conn->prepare("SELECT id FROM dosen WHERE user_id = ?");
$stmt_dosen->bind_param("i", $user_id);
$stmt_dosen->execute();
$data_dosen = $stmt_dosen->get_result()->fetch_assoc();

if (!$data_dosen) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Data dosen tidak ditemukan.']);
    exit();
}

$dosen_id = $data_dosen['id'];

// --- 6. TRANSAKSI DATABASE ---
$conn->begin_transaction();

try {
    // STEP 1: Verifikasi + kunci baris (FOR UPDATE)
    $stmt_cek = $conn->prepare("
        SELECT pt.id, pt.nilai AS nilai_lama,
               t.judul_tugas, m.nama_mahasiswa
        FROM pengumpulan_tugas pt
        JOIN tugas t        ON pt.tugas_id     = t.id
        JOIN mata_kuliah mk ON t.matkul_id     = mk.id
        JOIN mahasiswa m    ON pt.mahasiswa_id = m.id
        WHERE pt.id = ? AND mk.dosen_id = ?
        FOR UPDATE
    ");
    $stmt_cek->bind_param("ii", $pengumpulan_id, $dosen_id);
    $stmt_cek->execute();
    $data_pengumpulan = $stmt_cek->get_result()->fetch_assoc();
    $stmt_cek->close(); // Langsung tutup setelah selesai digunakan

    if (!$data_pengumpulan) {
        $conn->rollback();
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Pengumpulan tidak ditemukan atau bukan wewenang Anda.']);
        exit();
    }

    // STEP 2: Update nilai
    $stmt_update = $conn->prepare("UPDATE pengumpulan_tugas SET nilai = ? WHERE id = ?");
    $stmt_update->bind_param("ii", $nilai, $pengumpulan_id);
    
    // Jika execute() gagal total (misal: masalah koneksi/gagal query), lempar Exception
    if (!$stmt_update->execute()) {
        throw new Exception("Gagal memperbarui data di database.");
    }
    $stmt_update->close();

    // STEP 3: Commit
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
            'nilai_baru'     => $nilai,
        ]
    ]);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}
?>
EOF
echo "nilai_process.php done"