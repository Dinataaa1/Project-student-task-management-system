<?php
// 1. Inisialisasi Sesi & Error Handling
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. Koneksi & Data Mahasiswa
require_once '../../../config/koneksi.php';
// Pastikan ID mahasiswa diambil dari sesi saat login
$mahasiswa_id = $_SESSION['mahasiswa_id'] ?? null;
$nama_user = $_SESSION['nama_user'] ?? ($_SESSION['nama'] ?? 'Mahasiswa');

if (!$mahasiswa_id) {
    header("Location: ../auth/login.php"); // Redirect jika belum login
    exit;
}

$active_page = 'tugas';
$jalur_css = "../../assets/css/index.css";
include '../../components/header.php';

// 3. Logika Filter Matkul
$selected_matkul = isset($_GET['matkul_id']) ? (int)$_GET['matkul_id'] : 0;
$nama_matkul_aktif = "Semua Mata Kuliah";

// Ganti query $query_tugas menjadi ini:
$query_tugas = "
    SELECT t.*, 
           pt.file_tugas as status_kumpul, 
           IF(t.file_lampiran IS NOT NULL AND t.file_lampiran != '', 1, 0) as ada_lampiran
    FROM tugas t 
    LEFT JOIN pengumpulan_tugas pt ON t.id = pt.tugas_id AND pt.mahasiswa_id = '$mahasiswa_id'
    WHERE 1=1
";

// Tambahkan filter matkul jika ada
if ($selected_matkul > 0) {
    $query_tugas .= " AND t.matkul_id = '$selected_matkul'";
}
// Tambahkan filter KRS mahasiswa (supaya aman)
$query_tugas .= " AND t.matkul_id IN (SELECT mata_kuliah_id FROM krs WHERE mahasiswa_id = '$mahasiswa_id')";

$result_tugas = mysqli_query($conn, $query_tugas);
$daftar_tugas = mysqli_fetch_all($result_tugas, MYSQLI_ASSOC);
?>

<div class="dashboard-wrapper">
    <?php include '../../components/sidebar.php'; ?>

    <div class="main-content">
        <?php include '../../components/topbar.php'; ?>

        <div class="content-area">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold" style="color: #1E293B;">
                    Hai, <?= htmlspecialchars($nama_user) ?>!
                    Tugas: <b><?= htmlspecialchars($nama_matkul_aktif) ?></b>
                </h5>

                <div style="width: 250px;">
                    <select name="filter_matkul" class="form-select" onchange="window.location.href='daftar_tugas.php?matkul_id='+this.value">
                        <option value="0">Semua Mata Kuliah</option>
                        <?php
                        // Ambil daftar matkul yang HANYA diambil mahasiswa tersebut
                        $query_matkul_user = "SELECT mk.id, mk.nama_matkul FROM mata_kuliah mk 
                                              JOIN krs k ON mk.id = k.mata_kuliah_id 
                                              WHERE k.mahasiswa_id = '$mahasiswa_id'";
                        $result_matkul = mysqli_query($conn, $query_matkul_user);
                        while ($row = mysqli_fetch_assoc($result_matkul)) {
                            $selected = ($selected_matkul == $row['id']) ? 'selected' : '';
                            echo "<option value='{$row['id']}' $selected>{$row['nama_matkul']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-4 mt-4">
                <?php if (!empty($daftar_tugas)) :
                    foreach ($daftar_tugas as $tugas) :
                        // Logika Randomisasi Blob
                        $colors = ['#4F46E5', '#7E52E8', '#EC4899'];
                        shuffle($colors);
                        $gradient = "linear-gradient(135deg, " . implode(", ", $colors) . ")";
                        $delay = -rand(0, 10);

                        // Logika Badge Status
                        $is_submitted = !empty($tugas['status_kumpul']);
                        $badge_status = $is_submitted
                            ? '<span class="badge bg-success" style="font-size: 0.7rem;">Sudah Kumpul</span>'
                            : '<span class="badge bg-warning text-dark" style="font-size: 0.7rem;">Belum Kumpul</span>';
                ?>
                        <a href="detail_tugas.php?id=<?= htmlspecialchars($tugas['id']) ?>" class="tugas-card text-decoration-none">
                            <!-- Blob Dekorasi dengan Gradasi Acak -->
                            <div class="blob-hiasan-tugas" style="--bg-gradasi: <?= $gradient ?>; animation-delay: <?= $delay ?>s;"></div>

                            <div class="tugas-content">
                                <h3 class="judul-tugas"><?= htmlspecialchars($tugas['judul_tugas']) ?></h3>

                                <!-- Indikator Status saja (Tanpa deskripsi) -->
                                <div class="mt-3">
                                    <?= $badge_status ?>
                                    <?php if ($tugas['ada_lampiran']): ?>
                                        <span class="badge bg-info text-dark" style="font-size: 0.7rem;">
                                            <i class="fas fa-paperclip"></i> File
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                <?php endforeach;
                else :
                    echo '<p class="text-muted fw-bold p-3">Belum ada tugas yang tersedia.</p>';
                endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($active_page !== 'dashboard'): ?>
    <a href="dashboard.php" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
<?php endif; ?>
<?php include '../../components/footer.php'; ?>