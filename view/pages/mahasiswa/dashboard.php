<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../../controllers/mahasiswa/dashboard.php';

$active_page = 'dashboard';
include '../../components/header.php';
?>

<div class="dashboard-wrapper">
    <div id="overlay" class="overlay" onclick="closeSidebar()"></div>
    <?php include '../../components/sidebar.php'; ?>
    <div class="main-content">
        <?php include '../../components/topbar.php'; ?>

        <div class="content-area">
            <h4 class="fw-bold mb-4">Hai, <?= htmlspecialchars($nama_user ?? 'Mahasiswa') ?></h4>

            <!-- DAFTAR MATA KULIAH (HORIZONTAL SCROLL) -->
            <div class="scroll-container" id="matkulSlider">
                <?php if (!empty($data_matkul)) :
                    foreach ($data_matkul as $matkul) :
                        // Randomisasi Gradasi
                        $colors = ['#4F46E5', '#7E52E8', '#EC4899'];
                        shuffle($colors);
                        $gradient = "linear-gradient(135deg, " . implode(", ", $colors) . ")";
                        // Randomisasi Animasi Blob
                        $delay = -rand(0, 10);
                ?>
                        <a href="daftar_tugas.php?matkul_id=<?= htmlspecialchars($matkul['id']) ?>" class="matkul-card-lg" style="margin-bottom: 10px;">
                            <div class="blob-hiasan-lg" style="--bg-gradasi: <?= $gradient ?>; animation-delay: <?= $delay ?>s;"></div>

                            <div class="matkul-content" style="padding: 15px; display: flex; flex-direction: column; height: 100%;">

                                <!-- Bagian atas kartu (Judul & Info) -->
                                <div style="flex: 1;">
                                    <h3 class="judul-matkul mb-3" style="font-size: 1.1rem; font-weight: 700;">
                                        <?= htmlspecialchars($matkul['nama_matkul']) ?>
                                    </h3>

                                    <div class="info-details" style="font-size: 0.75rem; color: #475569; line-height: 1.6;">
                                        <p class="mb-1"><i class="fas fa-chalkboard-teacher"></i> <?= htmlspecialchars($matkul['nama_dosen']) ?></p>
                                        <p class="mb-1"><i class="fas fa-door-open"></i> Ruang: <?= htmlspecialchars($matkul['ruangan']) ?></p>
                                        <p class="mb-1"><i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($matkul['jadwal']) ?></p>
                                    </div>
                                </div>

                                <!-- Badge (Ini akan selalu menempel di bawah karena margin-top: auto) -->
                                <div class="mt-3" style="margin-top: auto;">
                                    <span class="badge bg-primary" style="font-size: 0.7rem;">
                                        <?= $matkul['total_tugas'] ?> Tugas Aktif
                                    </span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach;
                else : ?>
                    <p class="text-muted fst-italic">Belum ada mata kuliah yang diambil.</p>
                <?php endif; ?>
            </div>

            <!-- KALENDER -->
            <div class="calendar-widget" style="margin-top: 10px;">
                <div class="cal-left">
                    <svg class="cal-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 16 16">
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                    </svg>
                    <div class="cal-left-date">
                        <h1><?= htmlspecialchars($tanggal_sekarang ?? date('d')) ?></h1>
                        <span><?= strtoupper(htmlspecialchars($bulan_sekarang ?? date('M'))) ?> <?= htmlspecialchars($tahun_sekarang ?? date('Y')) ?></span>
                    </div>
                    <div class="notes-box">
                        <h6>Tugas Bulan Ini</h6>
                        <div id="notesContainer"></div>
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

<script>
    const dataTugasDB = <?= json_encode($array_deadline ?? []); ?>;
    const dataNotesDB = <?= json_encode($data_dl_terdekat ?? []); ?>;
</script>
<script src="../../assets/js/dashboard.js"></script>

<script>
    // FUNGSI DRAG-TO-SCROLL MATA KULIAH
    const slider = document.querySelector('#matkulSlider');
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });
    slider.addEventListener('mouseleave', () => isDown = false);
    slider.addEventListener('mouseup', () => isDown = false);
    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 2;
        slider.scrollLeft = scrollLeft - walk;
    });
</script>

<?php include '../../components/footer.php'; ?>