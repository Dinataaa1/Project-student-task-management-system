<?php
// Memanggil controller
require_once '../../../controllers/mahasiswa/pengaturan.php';

$active_page = 'pengaturan'; 
include '../../components/header.php';
?>

<link rel="stylesheet" href="../../assets/css/pages/mahasiswa/pengaturan.css">

<div class="dashboard-wrapper">
    
    <!-- Panggil Sidebar -->
    <?php include '../../components/sidebar.php'; ?>

    <div class="main-content">

        <!-- Panggil Topbar -->
        <?php include '../../components/topbar.php'; ?>

        <!-- Area Konten Utama -->
        <!-- WAJIB ada class position-relative agar tombol back bisa fix di kiri bawah area ini -->
        <div class="content-area position-relative" style="min-height: calc(100vh - 70px); padding: 40px;">
            
            <!-- Judul Halaman -->
            <h4 class="fw-bold mb-4" style="color: #222;">Profil Mahasiswa</h4>
            
            <!-- Pembungkus agar kartu ada di tengah -->
            <div class="d-flex justify-content-center align-items-start">
                <div class="profile-card">

                    <!-- Foto Profil -->
                    <div class="profile-img-container">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($data_user['nama_mahasiswa'] ?? 'NA') ?>&background=random" alt="Profile">
                    </div>

                    <!-- Header Nama & Email -->
                    <div class="profile-name"><?= htmlspecialchars($data_user['nama_mahasiswa'] ?? 'NAMA LENGKAP') ?></div>
                    <div class="profile-email"><?= htmlspecialchars($data_user['email'] ?? 'email@kampus.ac.id') ?></div>

                    <!-- Informasi Rinci -->
                    <div class="profile-details">
                        
                        <div class="detail-item">
                            <div class="detail-label">Nama Lengkap:</div>
                            <div class="detail-value"><?= htmlspecialchars($data_user['nama_mahasiswa'] ?? '-') ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Status / Kelas:</div>
                            <div class="detail-value"><?= htmlspecialchars($data_user['nama_kelas'] ?? '-') ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Nomor Induk:</div>
                            <div class="detail-value"><?= htmlspecialchars($data_user['nrp'] ?? '-') ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Email:</div>
                            <div class="detail-value"><?= htmlspecialchars($data_user['email'] ?? '-') ?></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($active_page !== 'dashboard'): ?>
    <a href="dashboard.php" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
<?php endif; ?>
<?php include '../../components/footer.php'; ?>