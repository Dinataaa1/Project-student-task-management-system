<?php
// Memanggil controller
require_once '../../../controllers/mahasiswa/daftar_matkul.php';

// Pastikan sesi sudah berjalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// MENGAMBIL NAMA ASLI DARI SESI LOGIN
// Sistem akan mencari $_SESSION['nama']. Jika kosong, baru dia mencari variabel $nama_user dari controller.
$nama_pengguna = $_SESSION['nama'] ?? (isset($nama_user) && !empty($nama_user) ? $nama_user : 'Pengguna');

$active_page = 'matkul'; 
include '../../components/header.php';
?>

<div class="dashboard-wrapper">
    
    <?php include '../../components/sidebar.php'; ?>

    <div class="main-content">

        <?php include '../../components/topbar.php'; ?>
        
        <div class="content-area position-relative" style="min-height: calc(100vh - 70px); padding: 40px;">
            
            <h5 class="fw-bold mb-4" style="color: #444;">
                Hai, <?= htmlspecialchars($nama_pengguna) ?>!
            </h5>
            
            <div class="d-flex gap-4 flex-wrap pb-5">
                
                <?php if (!empty($data_matkul)) : ?>
                    <?php foreach ($data_matkul as $mk) : ?>
                        <div class="card p-3 shadow-sm border-0 rounded-4" style="min-width: 250px;">
                            <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($mk['nama_matkul']) ?></h6>
                            <small class="text-muted">SKS: <?= htmlspecialchars($mk['sks'] ?? '-') ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="alert w-100 fw-bold text-muted border-0 shadow-sm rounded-3 py-3" style="background-color: #ffffff;" role="alert">
                        Belum ada mata kuliah yang diambil.
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