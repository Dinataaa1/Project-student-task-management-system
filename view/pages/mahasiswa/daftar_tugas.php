<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../../controllers/auth/session_check.php';

checkRoleMahasiswa();

$mahasiswa_id = (int) $_SESSION['mahasiswa_id'];
$nama_user    = $_SESSION['nama'] ?? 'Mahasiswa';

$active_page = 'tugas';
$jalur_css = "../../assets/css/index.css";
include '../../components/header.php';

// 3. Logika Filter Matkul
$selected_matkul = isset($_GET['matkul_id']) ? (int)$_GET['matkul_id'] : 0;
$nama_matkul_aktif = "Semua Mata Kuliah";

// Ambil nama matkul jika ada filter yang dipilih
if ($selected_matkul > 0) {
    $query_nama = "SELECT nama_matkul FROM mata_kuliah WHERE id = '$selected_matkul'";
    $result_nama = mysqli_query($conn, $query_nama);
    if ($result_nama && mysqli_num_rows($result_nama) > 0) {
        $row_nama = mysqli_fetch_assoc($result_nama);
        $nama_matkul_aktif = $row_nama['nama_matkul'];
    }
}

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

                <div style="width: 280px; position: relative; z-index: 50;">
                    <div class="custom-dropdown" id="matkulDropdown">

                        <div class="dropdown-header" onclick="toggleDropdown()">
                            <span id="dropdownSelectedText" style="opacity: 0.7;"><?= htmlspecialchars($nama_matkul_aktif) ?></span>
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </div>

                        <ul class="dropdown-list">
                            <?php
                            // Acak warna untuk opsi "Semua Mata Kuliah"
                            $c_semua = ['#4F46E5', '#7E52E8', '#EC4899'];
                            shuffle($c_semua);
                            $grad_semua = "linear-gradient(135deg, " . implode(", ", $c_semua) . ")";
                            ?>
                            <li style="--i: 1; --bg-gradasi: <?= $grad_semua ?>;" onclick="selectOption(0)" class="<?= $selected_matkul == 0 ? 'active' : '' ?>">
                                Semua Mata Kuliah
                            </li>

                            <?php
                            $index = 2;
                            $query_matkul_user = "SELECT mk.id, mk.nama_matkul FROM mata_kuliah mk 
                                  JOIN krs k ON mk.id = k.mata_kuliah_id 
                                  WHERE k.mahasiswa_id = '$mahasiswa_id'";
                            $result_matkul = mysqli_query($conn, $query_matkul_user);

                            while ($row = mysqli_fetch_assoc($result_matkul)) {
                                $isActive = ($selected_matkul == $row['id']) ? 'active' : '';

                                // Acak warna untuk setiap mata kuliah
                                $c_loop = ['#4F46E5', '#7E52E8', '#EC4899'];
                                shuffle($c_loop);
                                $grad_loop = "linear-gradient(135deg, " . implode(", ", $c_loop) . ")";

                                // Print <li> dengan gradasi acak
                                echo "<li style='--i: {$index}; --bg-gradasi: {$grad_loop};' onclick='selectOption({$row['id']})' class='{$isActive}'>{$row['nama_matkul']}</li>";
                                $index++;
                            }
                            ?>
                        </ul>
                    </div>
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