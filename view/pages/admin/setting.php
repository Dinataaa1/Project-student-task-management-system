<?php
require_once '../../../controllers/admin/setting_controler.php';

$active_page = 'setting';
$jalur_css   = "../../assets/css/index.css";
include '../../components/header.php';
?>

<link rel="stylesheet" href="../../assets/css/pages/admin/setting.css">

<body>
    <div class="dashboard-wrapper">
    <?php include '../../components/sidebar.php'; ?>
    
    <div class="main-content">
        <?php include '../../components/topbar.php'; ?>

        <div class="content-area">
            <?php if (!empty($pesan_error)): ?>
                <div class="alert-error"><?= $pesan_error ?></div>
            <?php endif; ?>
            <?php if (!empty($pesan_sukses)): ?>
                <div class="alert-success"><?= $pesan_sukses ?></div>
            <?php endif; ?>

            <h2 class="page-title">Profil Dosen</h2>
            
            <div class="profile-card">
                <div class="profile-header">
                    <div class="avatar-container">
                        <img src="<?= $avatar_url ?>" alt="Foto Profil">
                    </div>
                    <div class="profile-name-tag">
                        <h3><?= htmlspecialchars(strtoupper($nama_depan)) ?></h3>
                    </div>
                </div>

                <div class="profile-info-grid">
                    <div class="info-item">
                        <label>Nama Lengkap:</label>
                        <span><?= htmlspecialchars($profil['nama_lengkap']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>NIP:</label>
                        <span><?= htmlspecialchars($profil['nip']) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Email:</label>
                        <span><?= htmlspecialchars($profil['email']) ?></span>
                    </div>
                </div>

                <button class="btn-edit-profile" onclick="bukaModalProfil()">Edit Profil</button>
            </div>
        </div>
    </div>

    <div id="modalEditProfil" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <h2 class="modal-title">EDIT PROFIL</h2>
            
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_profil">

                <div class="form-group">
                    <label class="form-label">NAMA LENGKAP</label>
                    <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($profil['nama_lengkap']) ?>" required class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" value="<?= htmlspecialchars($profil['nip']) ?>" required class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">EMAIL</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($profil['email']) ?>" required class="form-input">
                </div>

                <div class="modal-actions">
                    <button type="button" onclick="tutupModalProfil()" class="btn-cancel">Batal</button>
                    <button type="submit" class="btn-save">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    </div> <script src="../../assets/js/admin/setting.js?v=1"></script>
</body>
</html>