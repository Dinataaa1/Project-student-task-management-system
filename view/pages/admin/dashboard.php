<?php
// Memanggil backend controller (Mundur 3 tingkat)
require_once '../../../controllers/admin/matkul_controler.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/pages/admin/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="profile-area">
            <img src="https://ui-avatars.com/api/?name=Lulu&background=random&color=fff" alt="Profile">
            <p>Nama</p>
        </div>
        <div class="nav-menu">
            <a href="dashboard.php" class="nav-item active"><i class="fa-solid fa-house"></i></a>
            <a href="tugas/detail.php" class="nav-item"><i class="fa-solid fa-address-card"></i></a>
            <a href="setting.php" class="nav-item"><i class="fa-solid fa-gear"></i></a>
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
                <div style="color: red; margin-bottom: 15px; font-weight: bold;"><?= $pesan_error ?></div>
            <?php endif; ?>
            <?php if (!empty($pesan_sukses)): ?>
                <div style="color: green; margin-bottom: 15px; font-weight: bold;"><?= $pesan_sukses ?></div>
            <?php endif; ?>

            <h2 class="greeting">Hai, <?= htmlspecialchars($nama_dosen) ?></h2>
            
            <div class="grid-container">
                <?php foreach ($data_matkul_list as $index => $matkul): ?>
                    <?php 
                        // Membuat warna blob selang-seling (orange dan blue)
                        $blobClass = ($index % 2 === 0) ? 'orange' : 'blue'; 
                    ?>
                    <div class="card" onclick="window.location.href='tugas/detail.php?matkul=<?= $matkul['id'] ?>'" style="cursor: pointer;">
                        <div class="blob <?= $blobClass ?>"></div>
                        <div class="menu-container">
                            <i class="fa-solid fa-ellipsis-vertical menu-icon" onclick="toggleMenu(event, this)"></i>
                            </div>
                        <div class="card-info">
                            <p class="kelas-text"><?= htmlspecialchars($matkul['ruangan']) ?></p>
                            <p class="jadwal-text"><?= htmlspecialchars($matkul['jadwal']) ?></p>
                        </div>
                        <div class="card-title matkul-text"><?= htmlspecialchars($matkul['nama_matkul']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="fab" onclick="bukaModalAdd()">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>

    <div id="modalMatkul" class="modal-overlay"> 
        <div class="modal-content">
            <!-- TODO : FORM BELUM NYAMBUNG BACK END -->
            <form id="formMatkul" method="POST" action=""> 
                <input type="hidden" name="action" value="create_matkul">
                
                <div class="form-group">
                    <label for="inputMatkul">MATKUL</label>
                    <input type="text" id="inputMatkul" name="nama_matkul" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="inputKelas">KELAS/RUANGAN</label>
                    <input type="text" id="inputKelas" name="ruangan" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="inputJadwal">JADWAL</label>
                    <input type="text" id="inputJadwal" name="jadwal" required autocomplete="off">
                </div>
                <button type="submit" class="btn-submit">SUBMIT</button>
            </form>
        </div>
    </div>

        <script src="../../assets/js/admin/dashboard.js"></script>
</body>
</html>