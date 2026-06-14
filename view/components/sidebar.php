<?php
// Deteksi apakah pengguna berada di area admin atau mahasiswa
$is_admin = (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false);

// Atur nama dan jalur dinamis berdasarkan kedalaman folder
if ($is_admin) {
    $nama_lengkap = $nama_dosen ?? $_SESSION['nama'] ?? 'Dosen';
    // Jika berada di dalam subfolder (seperti admin/tugas/), mundurkan satu tingkat
    $is_subfolder = (basename(dirname($_SERVER['PHP_SELF'])) === 'tugas');
    $path_prefix  = $is_subfolder ? '../' : '';
    $logout_path  = $is_subfolder ? '../../../../controllers/logout.php' : '../../../controllers/logout.php';
} else {
    $nama_lengkap = $_SESSION['nama'] ?? 'Mahasiswa';
    $path_prefix  = '';
    $logout_path  = '../../../controllers/logout.php';
}

$pecah_nama = explode(' ', trim($nama_lengkap));
$nama_depan = $pecah_nama[0];
?>

<div class="sidebar sidebar-mini">

    <div class="sidebar-menu-top">
        <img src="https://ui-avatars.com/api/?name=<?= urlencode($nama_lengkap) ?>&background=4F46E5&color=fff&bold=true" alt="Profile">
        <div class="sidebar-name"><?= htmlspecialchars($nama_depan) ?></div>
    </div>

    <div class="sidebar-nav" style="position: relative;">
        <?php if ($is_admin): ?>
            <a href="<?= $path_prefix ?>dashboard.php" class="sidebar-item text-decoration-none <?= ($active_page == 'dashboard') ? 'active' : '' ?>">
                <i class="fa-solid fa-house"></i>
            </a>

            <a href="<?= $path_prefix ?>tugas/detail.php" class="sidebar-item text-decoration-none <?= ($active_page == 'tugas_detail') ? 'active' : '' ?>">
                <i class="fa-solid fa-address-card"></i>
            </a>

            <a href="<?= $path_prefix ?>setting.php" class="sidebar-item text-decoration-none <?= ($active_page == 'setting') ? 'active' : '' ?>">
                <i class="fa-solid fa-gear"></i>
            </a>
        <?php else: ?>
            <div class="active-indicator" id="activeIndicator"></div>

            <a href="dashboard.php" class="sidebar-item text-decoration-none <?= ($active_page == 'dashboard') ? 'active' : '' ?>">
                <i class="fa-solid fa-house"></i>
            </a>

            <a href="daftar_matkul.php" class="sidebar-item text-decoration-none <?= ($active_page == 'matkul') ? 'active' : '' ?>">
                <i class="fa-solid fa-book"></i>
            </a>

            <a href="daftar_tugas.php" class="sidebar-item text-decoration-none <?= ($active_page == 'tugas') ? 'active' : '' ?>">
                <i class="fa-solid fa-list-check"></i>
            </a>

            <a href="pengaturan.php" class="sidebar-item text-decoration-none <?= ($active_page == 'pengaturan') ? 'active' : '' ?>">
                <i class="fa-solid fa-gear"></i>
            </a>
        <?php endif; ?>
    </div>

    <div class="sidebar-menu-bottom">
        <a href="<?= $logout_path ?>" class="logout-icon text-decoration-none" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </a>
    </div>

</div>