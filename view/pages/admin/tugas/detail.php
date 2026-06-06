<?php
require_once '../../../controllers/admin/tugas_controler.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas Mata Kuliah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/pages/admin/detail.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="profile-area">
            <img src="https://ui-avatars.com/api/?name=Lulu&background=random&color=fff" alt="Profile">
            <p>Nama</p>
        </div>
        <div class="nav-menu">
            <a href="../dashboard.php" class="nav-item"><i class="fa-solid fa-house"></i></a>
            <a href="detail.php" class="nav-item active"><i class="fa-solid fa-address-card"></i></a>
            <a href="../setting.php" class="nav-item"><i class="fa-solid fa-gear"></i></a>
            <a href="../../../../controllers/logout.php" class="nav-item logout-btn" onclick="return confirm('Apakah Anda yakin ingin keluar dari aplikasi?');">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>LOL</h1>
        </div>

        <div class="content-area">
            <div class="content-header">
                <h2 class="greeting">Hai, Lulu! Ini adalah tugas mata kuliah <span id="namaMatkulText">Semua Mata Kuliah</span></h2>
                
                <div class="filter-container">
                    <select id="matkulFilter" class="matkul-dropdown" onchange="window.location.href='?matkul=' + this.value">
                        <option value="0">Semua Mata Kuliah</option>
                        <?php foreach ($data_matkul as $mk): ?>
                            <option value="<?= $mk['id'] ?>" <?= (isset($_GET['matkul']) && $_GET['matkul'] == $mk['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($mk['nama_matkul']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="grid-container">
                <?php foreach ($data_tugas as $tugas): ?>
                    <div class="card" onclick="window.location.href='detail_tugas.php?id=<?= $tugas['id'] ?>'" style="cursor: pointer;">
                        <div class="blob"></div>
                        <div class="menu-container">
                            <i class="fa-solid fa-ellipsis-vertical menu-icon" onclick="toggleMenu(event, this)"></i>
                        </div>
                        <div class="card-title tugas-text"><?= htmlspecialchars($tugas['judul_tugas']) ?></div>
                        <div style="margin-top: 10px; font-size: 12px; font-family: var(--font-body); color: var(--color-pink);">
                            <?= $tugas['deadline_format'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <a href="../dashboard.php" class="btn-back">
                <i class="fa-solid fa-chevron-left"></i>
            </a>

            <button class="fab" onclick="bukaModalTugas()">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>

    <div id="modalTugas" class="modal-overlay">
        <div class="modal-content">
            <form id="formTugas" method="POST" action="" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                <input type="hidden" name="action" value="create_tugas">
                
                <select name="matkul_id" class="input-desc" required>
                    <option value="">Pilih Mata Kuliah...</option>
                    <?php foreach ($data_matkul as $mk): ?>
                        <option value="<?= $mk['id'] ?>"><?= htmlspecialchars($mk['nama_matkul']) ?></option>
                    <?php endforeach; ?>
                </select>

                <div class="input-row">
                    <input type="text" name="judul_tugas" id="inputJudulTugas" placeholder="Judul Tugas" required autocomplete="off">
                    <div class="date-wrapper" title="Pilih Deadline">
                        <i class="fa-regular fa-calendar"></i>
                        <input type="datetime-local" name="deadline" id="inputDeadline" required>
                    </div>
                </div>

                <textarea name="deskripsi" id="inputDeskripsi" class="input-desc" rows="3" placeholder="Deskripsi" required></textarea>

                <button type="submit" class="btn-submit">SUBMIT</button>
            </form>
        </div>
    </div>

    <script src="../../../assets/js/admin/detail.js?v=1"></script>
</body>
</html>