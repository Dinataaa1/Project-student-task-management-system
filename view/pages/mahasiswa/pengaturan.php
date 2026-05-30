<?php
// Memanggil controller
require_once '../../../controllers/mahasiswa/pengaturan.php';

$active_page = 'pengaturan'; // Sesuaikan dengan logika sidebar-mu agar menu gerigi menyala
include '../../components/header.php';
?>

<div class="pengaturan-wrapper">
    <div class="profile-card">

        <div class="profile-img-container">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($data_user['nama_mahasiswa'] ?? 'Nama') ?>&background=random" alt="Profile">
        </div>

        <div class="profile-name"><?= htmlspecialchars($data_user['nama_mahasiswa'] ?? 'NAMA LENGKAP') ?></div>
        <div class="profile-email"><?= htmlspecialchars($data_user['email'] ?? 'email@kampus.ac.id') ?></div>

        <div class="profile-details">
            <div class="detail-item">
                <div class="detail-label">Nama Lengkap:</div>
                <div class="detail-value"><?= htmlspecialchars($data_user['nama_mahasiswa'] ?? 'Data Kosong') ?></div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Kelas / Status:</div>
                <div class="detail-value"><?= isset($kelas_placeholder) ? $kelas_placeholder : 'Mahasiswa Aktif' ?></div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Nomor Induk:</div>
                <div class="detail-value"><?= htmlspecialchars($data_user['nrp'] ?? '00000000') ?></div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Email:</div>
                <div class="detail-value"><?= htmlspecialchars($data_user['email'] ?? 'email@kampus.ac.id') ?></div>
            </div>
        </div>

        <button type="button" class="btn-edit-profil">Edit Profil</button>

    </div>
</div>

<?php include '../../components/footer.php'; ?>