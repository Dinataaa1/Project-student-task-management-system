<?php
require_once '../../../controllers/mahasiswa/daftar_tugas.php';

// Setup variabel untuk komponen
$active_page = 'tugas';
include '../../components/header.php';

include '../../components/header.php';
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

        <div class="content-area position-relative" style="min-height: calc(100vh - 70px);">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0" style="color: #444;">
                    Hai, <?= htmlspecialchars($nama_user) ?>! Ini adalah tugas mata kuliah <?= htmlspecialchars($nama_matkul_terpilih) ?>
                </h5>
                
                <form method="GET" action="daftar_tugas.php">
                    <select name="matkul" class="form-select border-2 shadow-sm fw-semibold" style="border-color: #00a0e3;" onchange="this.form.submit()">
                        <option value="">Semua Mata Kuliah</option>
                        
                        <?php foreach ($data_matkul as $mk) : ?>
                            <option value="<?= $mk['id'] ?>" <?= $matkul_aktif == $mk['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($mk['nama_matkul']) ?>
                            </option>
                        <?php endforeach; ?>
                        
                    </select>
                </form>
            </div>

            <div class="d-flex gap-4 flex-wrap pb-5">
                <?php if (!empty($data_tugas)) : ?>
                    <?php foreach ($data_tugas as $tugas) : ?>
                        
                        <a href="detail_tugas.php?id=<?= $tugas['id'] ?>" class="tugas-card blob-orange">
                            <div class="blob-hiasan blob-orange"></div>
                            <span><?= htmlspecialchars($tugas['judul_tugas']) ?></span>
                        </a>
                        
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="alert alert-light w-100 text-center fw-bold text-muted border-0 shadow-sm" role="alert">
                        Yeay! Belum ada tugas untuk mata kuliah ini.
                    </div>
                <?php endif; ?>
            </div>

            <a href="dashboard.php" class="position-absolute text-decoration-none" style="bottom: 30px; left: 30px; color: #205c54;">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
                  <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                </svg>
            </a>
            
        </div>
    </div>
</div>

<?php include '../../components/footer.php'; ?>