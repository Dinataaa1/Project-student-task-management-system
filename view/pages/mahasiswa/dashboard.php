<?php
// Memanggil logika dari file controller
require_once '../../../controllers/mahasiswa/dashboard.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
// ==========================================================================
// MENGHUBUNGKAN FRONTEND DENGAN BACKEND (CONTROLLER)
// ==========================================================================

require_once __DIR__ . '/../../../controllers/mahasiswa/dashboard.php';

$active_page = 'dashboard';
include '../../components/header.php';
?>

<script>
    const dataTugasDB = <?= json_encode($array_deadline); ?>;
</script>

<div class="dashboard-wrapper">
    
    <?php include '../../components/sidebar.php'; ?>

    <?php include __DIR__ . '/../../components/sidebar.php'; ?>

    <div class="main-content">

    <?php include '../../components/topbar.php'; ?>
        
        <div class="content-area">

            <?php 
                $tampil_nama = !empty($nama_user) ? $nama_user : 'Mahasiswa'; 
            ?>
            <h4 class="fw-bold mb-4">Hai, <?= htmlspecialchars($tampil_nama) ?></h4>

            <div class="d-flex gap-3 align-items-center flex-wrap mb-4">
                <?php if (!empty($data_matkul)) : ?>
                    <?php foreach($data_matkul as $matkul) : ?>
                        <a href="daftar_tugas.php?matkul=<?= $matkul['id'] ?>" class="matkul-card text-decoration-none">
                            <div class="blob-hiasan <?= $matkul['warna'] ?>"></div>
                            <span><?= htmlspecialchars($matkul['nama']) ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted fst-italic">Belum ada mata kuliah yang diambil.</p>
                <?php endif; ?>
                <a href="daftar_matkul.php" class="ms-3 fw-bold text-decoration-none" style="color: #00a0e3;">See all ></a>
            </div>

            <div class="calendar-widget">
                
                <div class="cal-left">
                    <svg class="cal-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 16 16">
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                    </svg>

                    <div class="cal-left-date">
                        <h1><?= $tanggal_sekarang ?></h1>
                        <span><?= strtoupper($bulan_sekarang) ?> <?= $tahun_sekarang ?></span>
                    </div>
                    
                    <div class="notes-box">
                        <h6>NOTES TO BE MADE</h6>
                        
                        <?php if (!empty($data_dl_terdekat)) : ?>
                            <?php foreach($data_dl_terdekat as $dl) : ?>
                                <a href="detail_tugas.php?id=<?= $dl['id'] ?>" class="dl-item" style="display: flex; justify-content: space-between; align-items: center;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div class="dl-dot"></div>
                                        <span class="text-truncate" style="max-width: 140px;"><?= htmlspecialchars($dl['nama_matkul']) ?></span>
                                    </div>
                                    <span style="font-size: 0.8rem; font-weight: 700; opacity: 0.8;">
                                        <?= date('d M', strtotime($dl['deadline'])) ?>
                                    </span>
                                </a>
                                <?php $tgl_format = date('d M', strtotime($dl['deadline'])); ?>

                                <div class="dl-box d-flex justify-content-between align-items-center px-2" style="font-size: 0.75rem; color: #b02a37;">
                                    <span class="text-truncate fw-bold" style="max-width: 65%;"><?= htmlspecialchars($dl['judul_tugas']) ?></span>
                                    <span class="fw-bold"><?= $tgl_format ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="text-white opacity-75 small">Yeay! Tidak ada deadline terdekat.</div>
                        <?php endif; ?>
                        
                    </div>
                </div>

                <div class="cal-right">
                    <div class="cal-header">
                        <span id="btnPrev">&lt;</span>
                        <span id="displayBulanTahun"></span>
                        <span id="btnNext">&gt;</span>
                    </div>

                    <div class="cal-grid" style="border-bottom: none; border-left: none;">
                        <div class="cal-cell-header text-sun">SUN</div>
                        <div class="cal-cell-header text-dark">MON</div>
                        <div class="cal-cell-header text-dark">TUE</div>
                        <div class="cal-cell-header text-dark">WED</div>
                        <div class="cal-cell-header text-dark">THU</div>
                        <div class="cal-cell-header text-dark">FRI</div>
                        <div class="cal-cell-header text-sat">SAT</div>
                    </div>

                    <div class="cal-grid" id="wadahTanggal"></div>
                </div>

            </div>
            
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end custom-offcanvas" tabindex="-1" id="remindersPanel">
    <div class="offcanvas-header pb-0 mt-3">
        <h2 class="fw-bold m-0" style="color: #4a4a4a; font-size: 2.2rem;">Reminders</h2>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body pt-2">
        <hr class="reminders-divider">

        <?php 
        $warna_blob = ['blob-orange', 'blob-blue'];
        $index = 0;
        ?>

        <?php if (!empty($data_reminders)) : ?>
            <?php foreach($data_reminders as $rem) : ?>
                <a href="detail_tugas.php?id=<?= $rem['id'] ?>" class="rem-card">
                    <div class="rem-blob <?= $warna_blob[$index % 2] ?>"></div>
                    
                    <div class="rem-title"><?= htmlspecialchars($rem['nama_matkul']) ?></div>
                    <div class="rem-subtitle"><?= htmlspecialchars($rem['judul_tugas']) ?></div>
                    
                    <div class="rem-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/></svg>
                        Deadline: <?= date('d M Y, H:i', strtotime($rem['deadline'])) ?>
                    </div>
                    
                    <div class="rem-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16"><path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/></svg>
                        Dosen: <?= htmlspecialchars($rem['nama_dosen']) ?>
                    </div>
                </a>
                <?php $index++; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center text-muted mt-5">
                <p>Belum ada pengingat tugas baru.</p>
            </div>
        <?php endif; ?>

        <div class="text-end mt-4">
            <a href="daftar_tugas.php" class="text-decoration-none fw-bold" style="color: #7dd3fc;">See all</a>
        </div>
        
    </div>
</div>

<script src="../../assets/js/dashboard.js"></script>

<?php include '../../components/footer.php'; ?>