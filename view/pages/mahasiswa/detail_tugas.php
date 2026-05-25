<?php
// Memanggil logika dari file controller
require_once '../../../controllers/mahasiswa/detail_tugas.php';

$active_page = 'tugas'; 

$jalur_css = "../../assets/css/index.css"; 
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

        <div class="content-area content-area-detail">
            
            <div class="card-container">
                
                <div class="custom-card card-left">
                    <div class="blob-hiasan-detail-large"></div>
                    
                    <div class="card-content-wrapper">
                        <h3 class="fw-bold mb-3" style="color: #333;"><?= htmlspecialchars($tugas['judul_tugas']) ?></h3>
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
                                <?= $pengumpulan ? htmlspecialchars($pengumpulan['berkas']) : 'Belum ada lampiran' ?>
                            </span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-end mt-auto pt-5">
                            <p class="<?= $status_color ?> m-0"><?= $teks_status ?></p>
                            
                            <button class="btn-edit-figma" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                <?= $pengumpulan ? '^ Edit Tugas' : 'Upload Tugas' ?>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <a href="daftar_tugas.php" class="position-absolute" style="bottom: 10px; left: 30px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#304c4d" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
                  <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                </svg>
            </a>
            
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
                    
                    <input type="hidden" name="tugas_id" value="<?= $id_tugas ?>">
                    
                    <button type="submit" name="submit_tugas" class="btn-submit-figma">
                        SUBMIT
                    </button>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Menangkap nama file dan menampilkannya di atas garis saat user selesai memilih file di pop-up
    document.getElementById('fileInput').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : "";
        document.getElementById('fileNameDisplay').textContent = fileName;
    });
</script>

<?php include '../../components/footer.php'; ?>