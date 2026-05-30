<?php
// Memanggil controller
require_once '../../../controllers/mahasiswa/pengaturan.php';

$active_page = 'pengaturan'; // Sesuaikan dengan logika sidebar-mu agar menu gerigi menyala
include '../../components/header.php';
?>

<div class="dashboard-wrapper">
    
    <?php include '../../components/sidebar.php'; ?>

    <div class="main-content">

    <?php include '../../components/topbar.php'; ?>

        <div class="content-area">
            
            <div class="pengaturan-wrapper">
                <div class="profile-card">

                    <div class="profile-img-container">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($data_user['nama_mahasiswa']) ?>&background=random" alt="Profile">
                    </div>

                    <div class="profile-name"><?= htmlspecialchars($data_user['nama_mahasiswa']) ?></div>
                    <div class="profile-email"><?= htmlspecialchars($data_user['email']) ?></div>

                    <div class="profile-details">
                        <div class="detail-row">
                            <div class="detail-label">Nama Lengkap:</div>
                            <div class="detail-value"><?= htmlspecialchars($data_user['nama_mahasiswa']) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Kelas:</div>
                            <div class="detail-value"><?= $kelas_placeholder ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">NRP:</div>
                            <div class="detail-value"><?= htmlspecialchars($data_user['nrp']) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email:</div>
                            <div class="detail-value"><?= htmlspecialchars($data_user['email']) ?></div>
                        </div>
                    </div>

                    <form action="../../../controllers/auth/logout.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin log out dari sistem?');">
                        <button type="submit" class="btn-logout">Log out</button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../../components/footer.php'; ?>