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
        <div class="topbar">
            <h2 class="m-0 fw-bold" style="color: #555;">LOL</h2>
            <div class="position-relative" style="cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#888" class="bi bi-bell-fill" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/></svg>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-info border border-light rounded-circle"></span>
            </div>
        </div>

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