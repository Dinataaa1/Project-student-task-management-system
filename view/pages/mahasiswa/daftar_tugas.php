<?php
require_once '../../../controllers/mahasiswa/daftar_tugas.php';

// Pastikan sesi sudah berjalan untuk mengambil nama
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Mengambil nama dari sesi login (fallback ke 'Mahasiswa' jika kosong)
$tampil_nama = $_SESSION['nama'] ?? (isset($nama_user) && !empty($nama_user) ? $nama_user : 'Mahasiswa');

// Setup variabel untuk komponen
$active_page = 'tugas';
include '../../components/header.php';
// (Pemanggilan header yang ganda sudah dihapus)
?>

<div class="dashboard-wrapper">
    
    <?php include '../../components/sidebar.php'; ?>

    <div class="main-content">

        <?php include '../../components/topbar.php'; ?>
    
        <div class="content-area position-relative" style="min-height: calc(100vh - 70px); padding: 40px;">
            
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h5 class="fw-bold m-0" style="color: #444;">
                    Hai, <?= htmlspecialchars($tampil_nama) ?>! Ini adalah tugas mata kuliah <?= htmlspecialchars($nama_matkul_terpilih ?? 'Semua') ?>
                </h5>
                
                <form method="GET" action="daftar_tugas.php">
                    <select name="matkul" class="form-select border-2 shadow-sm fw-semibold" style="border-color: #00a0e3;" onchange="this.form.submit()">
                        <option value="">Semua Mata Kuliah</option>
                        
                        <?php if(!empty($data_matkul)): ?>
                            <?php foreach ($data_matkul as $mk) : ?>
                                <option value="<?= $mk['id'] ?>" <?= (isset($matkul_aktif) && $matkul_aktif == $mk['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($mk['nama_matkul']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
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

            <a href="dashboard.php" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                </svg>
            </a>
            
        </div>
    </div>
</div>

<?php include '../../components/footer.php'; ?>