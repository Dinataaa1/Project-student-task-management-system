<?php
require_once '../../../controllers/admin/setting_controler.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil Dosen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/pages/admin/setting.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="sidebar">
        <div class="profile-area">
            <?php
                $nama_sidebar = $nama_dosen ?? $profil['nama_dosen'] ?? 'Dosen';
                $nama_parts = explode(' ', $nama_sidebar);
                $nama_depan = $nama_parts[0];
                $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($nama_sidebar) . "&background=4F46E5&color=fff&bold=true";
            ?>
            <img src="<?= $avatar_url ?>" alt="Profile">
            <p><?= htmlspecialchars($nama_depan) ?></p>
        </div>
        
        <div class="nav-menu">
            <a href="dashboard.php" class="nav-item"><i class="fa-solid fa-house"></i></a>
            <a href="tugas/detail.php" class="nav-item"><i class="fa-solid fa-address-card"></i></a>
            <a href="setting.php" class="nav-item active"><i class="fa-solid fa-gear"></i></a>
            <a href="../../../controllers/logout.php" class="nav-item logout-btn" onclick="return confirm('Apakah Anda yakin ingin keluar dari aplikasi?');">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>LOL</h1>
        </div>

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