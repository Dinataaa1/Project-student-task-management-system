<?php
// Memanggil logika dan query dari file controller
require_once '../../../controllers/mahasiswa/daftar_matkul.php';
// Menyiapkan variabel untuk komponen Header & Sidebar
$active_page = 'matkul'; // (atau 'tugas', 'matkul')
include_once '../../components/header.php';
?>

<div class="dashboard-wrapper">
    
    <?php include '../../components/sidebar.php'; ?>


    <div class="main-content">

    <?php include '../../components/topbar.php'; ?>

        <div class="content-area">
            <h5 class="fw-bold mb-4" style="color: #444;">
                Hai, <?= htmlspecialchars($nama_user) ?>
            </h5>

            <div class="d-flex gap-4 flex-wrap pb-5">
                <?php if (!empty($data_matkul)) : ?>
                    <?php foreach ($data_matkul as $index => $matkul) : ?>
                        
                        <a href="daftar_tugas.php?matkul=<?= $matkul['id'] ?>" class="matkul-card-lg">
                            <div class="blob-hiasan-lg <?= $pilihan_warna[$index % 2] ?>"></div>
                            <div class="d-flex justify-content-between align-items-end w-100" style="z-index: 2;">
                                <span class="judul-matkul"><?= htmlspecialchars($matkul['nama_matkul']) ?></span>
                                <span class="see-detail"></span>
                            </div>
                        </a>
                        
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="alert alert-light w-100 fw-bold text-muted border-0 shadow-sm">
                        Belum ada mata kuliah yang diambil.
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</div>

<?php include '../../components/footer.php'; ?>