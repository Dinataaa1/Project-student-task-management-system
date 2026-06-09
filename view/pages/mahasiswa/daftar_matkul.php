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
            <h5 class="fw-bold mb-4" style="color: #444;">Hai, <?= htmlspecialchars($nama_pengguna) ?>! Ini adalah daftar mata kuliah Anda:</h5>

            <div class="d-flex flex-wrap gap-4 mt-4">
                <?php if (!empty($daftar_matkul)) : // Pastikan variabelnya konsisten
                    foreach ($daftar_matkul as $matkul) :
                        $colors = ['#4F46E5', '#7E52E8', '#EC4899'];
                        shuffle($colors);
                        $gradient = "linear-gradient(135deg, " . implode(", ", $colors) . ")";
                        $delay = -rand(0, 10);
                ?>
                    <a href="daftar_tugas.php?matkul_id=<?= htmlspecialchars($matkul['id']) ?>" class="matkul-card-lg text-decoration-none">
                        <div class="blob-hiasan-lg" style="--bg-gradasi: <?= $gradient ?>; animation-delay: <?= $delay ?>s;"></div>
                        
                        <div class="matkul-content" style="padding: 15px;">
                            <h3 class="judul-matkul mb-3" style="font-size: 1.1rem; font-weight: 700; color: #1e293b;">
                                <?= htmlspecialchars($matkul['nama_matkul']) ?>
                            </h3>
                            
                            <div class="info-details" style="font-size: 0.8rem; color: #64748b; line-height: 1.6;">
                                <p class="mb-1"><i class="fas fa-chalkboard-teacher"></i> <?= htmlspecialchars($matkul['nama_dosen']) ?></p>
                                <p class="mb-1"><i class="fas fa-door-open"></i> Ruang: <?= htmlspecialchars($matkul['ruangan']) ?></p>
                                <p class="mb-1"><i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($matkul['jadwal']) ?></p>
                            </div>

                            <div class="mt-3">
                                <span class="badge bg-primary" style="font-size: 0.7rem;">
                                    <?= $matkul['total_tugas'] ?> Tugas Aktif
                                </span>
                            </div>
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

<?php if ($active_page !== 'dashboard'): ?>
    <a href="dashboard.php" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
<?php endif; ?>
<?php include '../../components/footer.php'; ?>