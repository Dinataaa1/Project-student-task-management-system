<?php
// Memanggil logika dari file controller
require_once '../../../controllers/mahasiswa/detail_tugas.php';

$active_page = 'tugas';
$jalur_css = "../../assets/css/detail-tugas.css";
include '../../components/header.php';

// Logika Acak Warna Blob untuk Card Kiri
$colors1 = ['#4F46E5', '#7E52E8', '#EC4899'];
shuffle($colors1);
$gradient1 = "linear-gradient(135deg, " . implode(", ", $colors1) . ")";
$delay1 = -rand(0, 10);

// Logika Acak Warna Blob untuk Card Kanan
$colors2 = ['#4F46E5', '#7E52E8', '#EC4899'];
shuffle($colors2);
$gradient2 = "linear-gradient(135deg, " . implode(", ", $colors2) . ")";
$delay2 = -rand(0, 10);
?>

<div class="dashboard-wrapper">
    <?php include '../../components/sidebar.php'; ?>
    <div class="main-content">
        <?php include '../../components/topbar.php'; ?>

        <div class="content-area content-area-detail">

            <div class="card-container d-flex gap-4">

                <div class="custom-card card-left flex-fill p-4" style="position: relative; overflow: hidden; background: white; border-radius: 15px;">
                    <div class="blob-hiasan-detail-large" style="--bg-gradasi: <?= $gradient1 ?>; animation-delay: <?= $delay1 ?>s;"></div>

                    <div class="card-content-wrapper d-flex flex-column h-100" style="position: relative; z-index: 2;">
                        <h3 class="fw-bold mb-3" style="color: #1E293B;">
                            <?= htmlspecialchars($detail_tugas['judul_tugas']) ?>
                        </h3>

                        <p class="text-dark mb-4" style="line-height: 1.6; font-size: 0.95rem; width: 85%;">
                            <?= nl2br(htmlspecialchars($detail_tugas['deskripsi'])) ?>
                        </p>

                        <div class="mt-auto">
                            <?php if (!empty($detail_tugas['file_lampiran'])) : ?>
                                <div class="mb-3">
                                    <a href="../../../controllers/uploads/tugas/<?= htmlspecialchars($detail_tugas['file_lampiran']) ?>" target="_blank" class="text-decoration-none">
                                        <span class="badge bg-secondary text-white p-2 border shadow-sm" style="cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; gap: 6px;">
                                            <i class="fas fa-file-download"></i> Lihat Lampiran Soal
                                        </span>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <p class="text-danger fw-bold mb-0">Deadline: <?= $deadline_format ?></p>
                        </div>
                    </div>
                </div>

                <div class="custom-card card-right flex-fill p-4" style="position: relative; overflow: hidden; background: white; border-radius: 15px;">
                    <div class="blob-hiasan-detail-large" style="--bg-gradasi: <?= $gradient2 ?>; animation-delay: <?= $delay2 ?>s;"></div>

                    <div class="card-content-wrapper d-flex flex-column h-100" style="position: relative; z-index: 2;">
                        <h2 class="fw-bold text-success mb-4">
                            <?= ($pengumpulan && isset($pengumpulan['nilai']) && $pengumpulan['nilai'] !== null) ? htmlspecialchars($pengumpulan['nilai']) : 'Nilai' ?>
                        </h2>

                        <div class="mb-4">
                            <?php if ($pengumpulan && !empty($pengumpulan['file_tugas'])): ?>
                                <?php
                                // Ambil nama file dari database
                                $file_tersimpan = basename($pengumpulan['file_tugas']);

                                // Potong angka waktu di depan (pisahkan berdasarkan tanda strip '-')
                                $potongan = explode('-', $file_tersimpan, 2);

                                // Jika ada tanda strip, ambil nama aslinya. Jika tidak, tampilkan apa adanya.
                                $nama_visual = isset($potongan[1]) ? $potongan[1] : $file_tersimpan;
                                ?>
                                <a href="../../../<?= htmlspecialchars($pengumpulan['file_tugas']) ?>" target="_blank" class="text-decoration-none">
                                    <span class="badge bg-light text-primary p-2 border" style="cursor: pointer; transition: 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                        <i class="fas fa-file-word"></i> <?= htmlspecialchars($nama_visual) ?>
                                    </span>
                                </a>
                            <?php else: ?>
                                <span class="badge bg-light text-secondary p-2 border">
                                    Belum ada lampiran
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between align-items-end mt-auto">
                            <p class="<?= htmlspecialchars($status_color ?? 'text-danger') ?> m-0 fw-bold">
                                <?= htmlspecialchars($teks_status ?? 'Belum Mengumpulkan') ?>
                            </p>

                            <button class="btn btn-dark fw-bold rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                <?= $pengumpulan ? 'Edit Tugas' : 'Upload Tugas' ?>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal-figma p-4">
            <div class="modal-body text-center">
                <form method="POST" action="../../../controllers/mahasiswa/proses_upload.php" enctype="multipart/form-data">

                    <div class="d-flex flex-column align-items-center gap-2 mb-4 mt-2">

                        <label class="btn-file-figma mb-0" style="cursor: pointer;">
                            <i class="fas fa-upload"></i> File Tugas
                            <input type="file" name="file_tugas" class="d-none" required id="fileInputTugas">
                        </label>

                        <div class="btn-file-figma mb-0" id="lampiranIndicator" style="cursor: default; opacity: 0.7; transition: all 0.3s;">
                            <i class="fas fa-file-alt"></i> <span id="fileNameDisplay">Lampiran</span>
                        </div>

                    </div>

                    <p class="modal-desc-figma mb-4">
                        Pilih file tugas yang akan diunggah. Pastikan format sesuai dengan ketentuan dosen.
                    </p>

                    <input type="hidden" name="tugas_id" value="<?= htmlspecialchars($tugas['id']) ?>">

                    <button type="submit" name="submit_tugas" class="btn-submit-figma">
                        SUBMIT
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Script untuk memindahkan nama file ke kotak "Lampiran"
    document.getElementById('fileInputTugas').addEventListener('change', function(e) {
        var file = e.target.files[0];
        var displaySpan = document.getElementById('fileNameDisplay');
        var indicator = document.getElementById('lampiranIndicator');

        if (file) {
            // Jika file dipilih, ganti teks 'Lampiran' jadi nama file
            displaySpan.textContent = file.name;
            // Bikin kotaknya jadi terang (tidak transparan lagi) untuk menandakan file siap
            indicator.style.opacity = "1";
            indicator.style.boxShadow = "0 2px 4px rgba(0,0,0,0.1)";
        } else {
            // Jika dibatalkan, kembalikan seperti semula
            displaySpan.textContent = "Lampiran";
            indicator.style.opacity = "0.7";
            indicator.style.boxShadow = "none";
        }
    });
</script>

<?php if ($active_page !== 'dashboard'): ?>
    <a href="daftar_tugas.php" class="position-fixed" style="bottom: 30px; left: 30px; z-index: 1000;">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#1E293B" class="bi bi-arrow-left-circle-fill shadow rounded-circle" viewBox="0 0 16 16">
            <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
        </svg>
    </a>
<?php endif; ?>

<?php include '../../components/footer.php'; ?>