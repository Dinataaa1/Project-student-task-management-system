<?php
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
    // Data untuk memberi titik di kalender
    const dataTugasDB = <?= json_encode($array_deadline ?? []); ?>;

    // Data lengkap untuk daftar "Notes to be made"
    const dataNotesDB = <?= json_encode($data_dl_terdekat ?? []); ?>;
</script>

<div class="dashboard-wrapper">

    <?php include '../../components/sidebar.php'; ?>

    <div class="main-content">

        <?php include '../../components/topbar.php'; ?>

        <div class="content-area">

            <?php
            $tampil_nama = !empty($nama_user) ? $nama_user : 'Mahasiswa';
            ?>
            <h4 class="fw-bold mb-4">Hai, <?= htmlspecialchars($tampil_nama) ?></h4>

            <div class="d-flex flex-wrap gap-4 mt-4">
                <?php
                if (!empty($data_matkul)) :
                    foreach ($data_matkul as $matkul) :
                        // DEFINISI WARNA DI SINI (Di dalam loop agar tiap kartu dapat komposisi beda)
                        $colors = ['#4F46E5', '#7E52E8', '#EC4899'];
                        shuffle($colors); // Mengacak urutan
                        $gradient = "linear-gradient(135deg, " . implode(", ", $colors) . ")";

                        // Random delay agar gerakan lava tidak seragam
                        $delay = -rand(0, 10);
                ?>
                        <a href="daftar_tugas.php?matkul=<?= $matkul['id'] ?>" class="matkul-card-lg text-decoration-none">

                            <div class="blob-hiasan-lg" style="--bg-gradasi: <?= $gradient ?>; animation-delay: <?= $delay ?>s;"></div>

                            <div class="matkul-content">
                                <h3 class="judul-matkul"><?= htmlspecialchars($matkul['nama']) ?></h3>
                                <p class="see-detail">Lihat tugas &rarr;</p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted fst-italic">Belum ada mata kuliah yang diambil.</p>
                <?php endif; ?>
            </div>

            <div class="calendar-widget">

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

<script src="../../assets/js/dashboard.js"></script>

<?php include '../../components/footer.php'; ?>