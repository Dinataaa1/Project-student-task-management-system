<?php
// Memanggil logika dari file controller
require_once '../../../controllers/mahasiswa/detail_tugas.php';

$active_page = 'tugas';
$jalur_css = "../../assets/css/detail-tugas.css";
include '../../components/header.php';
?>

<div class="dashboard-wrapper">
    <?php include '../../components/sidebar.php'; ?>
    <div class="main-content">
        <?php include '../../components/topbar.php'; ?>

        <div class="content-area content-area-detail">
            <div class="card-container">

                <div class="custom-card card-left">
                    <div class="blob-hiasan-detail-large"></div>

                    <div class="card-content-wrapper">
                        <h3 class="fw-bold mb-3" style="color: #1E293B;"><?= htmlspecialchars($tugas['judul_tugas']) ?></h3>

                        <p class="text-dark mb-4" style="line-height: 1.6; font-size: 0.95rem; width: 85%;">
                            <?= nl2br(htmlspecialchars($tugas['deskripsi'])) ?>
                        </p>

                        <div class="mt-auto">
                            <?php if (!empty($tugas['file_lampiran'])) : ?>
                                <div class="mb-3">
                                    <span class="badge-pink-figma">Lampiran Soal</span>
                                </div>
                            <?php endif; ?>

                            <p class="text-deadline-figma mb-0">Deadline: <?= $deadline_format ?></p>
                        </div>
                    </div>
                </div>

                <div class="custom-card card-right">
                    <div class="blob-hiasan-detail-large"></div>

                    <div class="card-content-wrapper">
                        <h2 class="text-nilai-figma mb-4">
                            <?= ($pengumpulan && $pengumpulan['nilai'] !== null) ? $pengumpulan['nilai'] : 'Nilai' ?>
                        </h2>

                        <div>
                            <span class="badge-orange-figma">
                                <?= $pengumpulan ? htmlspecialchars($pengumpulan['file_tugas']) : 'Belum ada lampiran' ?>
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-end mt-auto pt-5">
                            <p class="<?= $status_color ?> m-0 fw-bold"><?= $teks_status ?></p>

                            <button class="btn-edit-figma" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                <?= $pengumpulan ? '^ Edit Tugas' : 'Upload Tugas' ?>
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

                    <label class="btn-file-figma mb-4">
                        File Tugas
                        <input type="file" name="file_tugas" class="d-none" required id="fileInput">
                    </label>

                    <p class="modal-desc-figma mb-3">
                        Pilih file tugas yang akan diunggah. Pastikan format sesuai dengan ketentuan dosen.
                    </p>

                    <div id="fileNameDisplay" class="file-name-line mb-5"></div>

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
    document.getElementById('fileInput').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : "";
        document.getElementById('fileNameDisplay').textContent = fileName;
    });
</script>

<?php if ($active_page !== 'dashboard'): ?>
    <a href="dashboard.php" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
<?php endif; ?>
<?php include '../../components/footer.php'; ?>