<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// ==========================================================================
// MENGHUBUNGKAN FRONTEND DENGAN BACKEND (CONTROLLER)
// ==========================================================================

require_once __DIR__ . '/../../../controllers/mahasiswa/dashboard.php';

// Menyiapkan variabel untuk komponen Header & Sidebar
$active_page = 'dashboard'; // Memberi tahu sidebar untuk menyalakan ikon Home
include_once __DIR__ . '/../../components/header.php';
?>

<script>
    const dataTugasDB = <?= json_encode($array_deadline); ?>;
</script>

<div class="dashboard-wrapper">
    

    <?php include __DIR__ . '/../../components/sidebar.php'; ?>

    <div class="main-content">
        
        <div class="topbar">
            <h2 class="m-0 fw-bold" style="color: #666; font-size: 1.8rem;">LOL</h2>
            <div class="position-relative" style="cursor: pointer;" onclick="alert('Belum ada notifikasi baru.')">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#999" class="bi bi-bell-fill" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/></svg>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-info border border-light rounded-circle" style="width: 12px; height: 12px; transform: translate(-30%, -30%) !important;"></span>
            </div>
        </div>

        <div class="content-area">

            <h4 class="fw-bold mb-4">Hai, <?= htmlspecialchars($nama_user) ?></h4>

            <div class="d-flex gap-3 align-items-center flex-wrap mb-4">
                <?php if (!empty($data_matkul)) : ?>
                    <?php foreach($data_matkul as $matkul) : ?>
                        <a href="daftar_tugas.php?matkul=<?= $matkul['id'] ?>" class="matkul-card text-decoration-none">
                            <div class="blob-hiasan <?= $matkul['warna'] ?>"></div>
                            <span><?= htmlspecialchars($matkul['nama']) ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
                
<
                <a href="daftar_matkul.php" class="ms-3 fw-bold text-decoration-none" style="color: #00a0e3;">See all ></a>
            </div>

            <div class="calendar-widget mt-4">
                <div class="cal-left">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="big-date-circle">
                            <?= $tanggal_sekarang ?>
                        </div>
                        <div class="lh-sm text-white">
                            <div class="fw-bold" style="font-size: 1.2rem;"><?= ucfirst($bulan_sekarang) ?></div>
                            <div style="font-size: 1rem; opacity: 0.9;"><?= $tahun_sekarang ?></div>
                        </div>
                    </div>
                    
                    <button class="btn-notes" onclick="alert('Fitur tambah catatan tugas sedang disiapkan!')">NOTES TO BE MADE</button>
                    
                    <div class="mt-4">
                        <div class="dl-box fw-bold text-center mb-2">DL Terdekat</div>
                        
                        <?php if (!empty($data_dl_terdekat)) : ?>
                            <?php foreach($data_dl_terdekat as $dl) : ?>
                                <?php $tgl_format = date('d M', strtotime($dl['deadline'])); ?>

                                <div class="dl-box d-flex justify-content-between align-items-center px-2" style="font-size: 0.75rem; color: #b02a37;">
                                    <span class="text-truncate fw-bold" style="max-width: 65%;"><?= htmlspecialchars($dl['judul_tugas']) ?></span>
                                    <span class="fw-bold"><?= $tgl_format ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="dl-empty-box"></div>
                            <div class="dl-empty-box"></div>
                            <div class="dl-empty-box"></div>
                            <div class="dl-empty-box"></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="cal-right position-relative">
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex position-relative me-2">
                            <div class="avatar-circle bg-success text-white">G</div>
                            <div class="avatar-circle bg-danger text-white" style="margin-left: -10px;">L</div>
                        </div>
                        <div class="fw-bold text-white" style="font-size: 0.9rem; letter-spacing: 1px;">ALENDAR</div>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-5 mb-4 text-center align-items-end text-white">
                        <div class="d-flex flex-column align-items-center" style="opacity: 0.7; cursor: pointer;" id="btnPrev">
                            <div style="font-size: 0.9rem;">Oct</div>
                            <div class="fw-bold" style="font-size: 0.8rem;">2021</div>
                        </div>
                        <div class="d-flex flex-column align-items-center fw-bold" id="displayBulanTahun" style="cursor: pointer; transform: scale(1.2);">
                            <div style="font-size: 1rem;">Nov</div>
                            <div style="font-size: 0.8rem;">2021</div>
                        </div>
                        <div class="d-flex flex-column align-items-center" style="opacity: 0.7; cursor: pointer;" id="btnNext">
                            <div style="font-size: 0.9rem;">Dec</div>
                            <div class="fw-bold" style="font-size: 0.8rem;">2021</div>
                        </div>
                    </div>

                    <div class="cal-grid text-white fw-bold" style="font-size: 0.8rem; padding-bottom: 10px;">
                        <div>SUN</div><div>MON</div><div>TUS</div><div>WED</div><div>THUR</div><div>FRI</div><div>SAT</div>
                    </div>

                    <div class="cal-grid" id="wadahTanggal"></div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script src="../../assets/js/dashboard.js"></script>

<?php include '../../components/footer.php'; ?>