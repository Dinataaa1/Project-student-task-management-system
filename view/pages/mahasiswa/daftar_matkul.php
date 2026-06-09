<?php
require_once '../../../controllers/mahasiswa/daftar_matkul.php';
$active_page = 'matkul';
$nama_pengguna = $_SESSION['nama'] ?? 'Pengguna';
include '../../components/header.php';
?>

<div class="dashboard-wrapper">
    <?php include '../../components/sidebar.php'; ?>
    <div class="main-content">
        <?php include '../../components/topbar.php'; ?>

        <div class="content-area" style="padding: 40px;">
            <h5 class="fw-bold mb-4" style="color: #444;">Hai, <?= htmlspecialchars($nama_pengguna) ?>!</h5>

            <div class="d-flex flex-wrap gap-4 mt-4">
                <?php if (!empty($daftar_tugas)) : 
                    foreach ($daftar_tugas as $matkul) : 
                        // Gradasi 3 warna
                        $colors = ['#4F46E5', '#7E52E8', '#EC4899'];
                        shuffle($colors);
                        $gradient = "linear-gradient(135deg, " . implode(", ", $colors) . ")";
                        $delay = -rand(0, 10);
                ?>
                    <a href="daftar_tugas.php?matkul_id=<?= htmlspecialchars($matkul['id']) ?>" class="matkul-card-lg text-decoration-none">
                        <div class="blob-hiasan-lg" style="--bg-gradasi: <?= $gradient ?>; animation-delay: <?= $delay ?>s;"></div>
                        
                        <div class="matkul-content">
                            <h3 class="judul-matkul"><?= htmlspecialchars($matkul['nama_matkul']) ?></h3>
                            <p class="see-detail">Klik untuk melihat tugas &rarr;</p>
                        </div>
                    </a>
                <?php endforeach; 
                else : ?>
                    <p class="text-muted fw-bold p-3">Belum ada mata kuliah yang diambil.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php include '../../components/footer.php'; ?>