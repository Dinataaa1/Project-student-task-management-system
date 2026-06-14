<?php
// Ambil nama lengkap dari session (atau default 'User')
$nama_lengkap = $_SESSION['nama'] ?? 'User';

// Pecah nama berdasarkan spasi, lalu ambil array urutan pertama [0]
$pecah_nama = explode(' ', trim($nama_lengkap));
$nama_depan = $pecah_nama[0];
?>

<div class="sidebar sidebar-mini">

    <div class="sidebar-menu-top">
        <img src="https://ui-avatars.com/api/?name=<?= urlencode($nama_lengkap) ?>&background=random&rounded=true" alt="Profile">
        <div class="sidebar-name"><?= htmlspecialchars($nama_depan) ?></div>
    </div>

    <div class="sidebar-nav" style="position: relative;">
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
    </div>

    <div class="sidebar-menu-bottom">
        <a href="../../../controllers/logout.php" class="logout-icon text-decoration-none" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </a>
    </div>

</div>