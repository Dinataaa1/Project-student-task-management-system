<?php
// Memanggil logika dari file controller yang sudah kita perbaiki
require_once '../../../controllers/mahasiswa/daftar_tugas.php';

$active_page = 'tugas';
$jalur_css = "../../assets/css/index.css";
include '../../components/header.php';
?>

<div class="dashboard-wrapper">
    <?php include '../../components/sidebar.php'; ?>

    <div class="main-content">
        <?php include '../../components/topbar.php'; ?>

        <div class="content-area">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold" style="color: #1E293B;">
                    Hai, <?= htmlspecialchars($_SESSION['nama_user'] ?? 'Luthfi Bahrur R.') ?>! Ini adalah tugas mata kuliah Semua
                </h4>

                <select class="form-select w-auto fw-bold" style="border-radius: 10px; color: #1E293B; border-color: #cbd5e1;">
                    <option selected>Semua Mata Kuliah</option>
                </select>
            </div>

            <div class="d-flex flex-wrap gap-4 mt-4">
                <?php
                if (!empty($daftar_tugas)) :
                    foreach ($daftar_tugas as $tugas) :
                        // 1. Generate gradien acak 3 warna
                        $colors = ['#4F46E5', '#7E52E8', '#EC4899'];
                        shuffle($colors);
                        $gradient = "linear-gradient(135deg, " . implode(", ", $colors) . ")";

                        // 2. Random delay agar gerakan tidak seragam
                        $delay = -rand(0, 10);
                ?>

                        <a href="detail_tugas.php?id=<?= htmlspecialchars($tugas['id']) ?>" class="tugas-card text-decoration-none">
                            <div class="blob-hiasan-tugas" style="--bg-gradasi: <?= $gradient ?>; animation-delay: <?= $delay ?>s;"></div>

                            <div class="tugas-content">
                                <h3 class="judul-tugas"><?= htmlspecialchars($tugas['judul_tugas']) ?></h3>
                            </div>
                        </a>

                    <?php
                    endforeach;
                else :
                    ?>
                    <p class="text-muted fw-bold p-3">Belum ada tugas yang tersedia.</p>
                <?php endif; ?>
            </div>

            <a href="dashboard.php" class="position-absolute" style="bottom: 40px; left: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="#1E293B" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
                    <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
                </svg>
            </a>

        </div>
    </div>
</div>

<?php include '../../components/footer.php'; ?>