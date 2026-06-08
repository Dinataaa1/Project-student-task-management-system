<?php
// (Pastikan require_once tugas_controler.php sudah ada di baris paling atas)
require_once '../../../../controllers/admin/tugas_controler.php';

// Menentukan nama mata kuliah yang sedang dipilih
$current_matkul_name = "Semua Mata Kuliah"; // Default
$selected_matkul_id = isset($_GET['matkul']) ? (int)$_GET['matkul'] : 0;

if ($selected_matkul_id > 0) {
    foreach ($data_matkul as $mk) {
        if ($mk['id'] == $selected_matkul_id) {
            $current_matkul_name = $mk['nama_matkul'];
            break;
        }
    }
}
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
            <?php
                // Memecah nama lengkap menjadi array berdasarkan spasi
                $nama_parts = explode(' ', $nama_dosen);
                // Mengambil elemen pertama (nama depan)
                $nama_depan = $nama_parts[0];
                
                // Membuat URL Avatar dinamis. 
                // Menggunakan urlencode agar spasi pada nama aman dikirim lewat URL.
                // Background diatur ke warna biru indigo palet Anda (4F46E5)
                $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($nama_dosen) . "&background=4F46E5&color=fff&bold=true";
            ?>
            <img src="<?= $avatar_url ?>" alt="Profile">
            <p><?= htmlspecialchars($nama_depan) ?></p>
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
                <h2 class="greeting">
                    Hai, <?= htmlspecialchars(explode(' ', $nama_dosen)[0]) ?>! 
                    Ini adalah tugas mata kuliah 
                    <span style="color: var(--color-blue);"><?= htmlspecialchars($current_matkul_name) ?></span>
                </h2>
                
                <div class="filter-container">
                    <select id="matkulFilter" class="matkul-dropdown" onchange="window.location.href='?matkul=' + this.value">
                    <option value="0" <?= ($selected_matkul_id == 0) ? 'selected' : '' ?>>Semua Mata Kuliah</option>
                    <?php foreach ($data_matkul as $mk): ?>
                        <option value="<?= $mk['id'] ?>" <?= ($selected_matkul_id == $mk['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($mk['nama_matkul']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                </div>
            </div>
            
            <div class="grid-container">
                <?php foreach ($data_tugas as $tugas): ?>
                    <div class="card" 
                        onclick="window.location.href='detail_tugas.php?id=<?= $tugas['id'] ?>'" 
                        data-id="<?= $tugas['id'] ?>"
                        data-matkul-id="<?= $tugas['matkul_id'] ?>"
                        data-judul="<?= htmlspecialchars($tugas['judul_tugas']) ?>"
                        data-deskripsi="<?= htmlspecialchars($tugas['deskripsi']) ?>"
                        data-deadline="<?= htmlspecialchars($tugas['deadline']) ?>"
                        /* 1. Tambahkan height: 180px agar ukurannya tetap sama semua */
                        style="cursor: pointer; display: flex; flex-direction: column; justify-content: flex-start; padding-top: 20px; height: 180px; box-sizing: border-box;">
                        
                        <div class="menu-container">
                            <i class="fa-solid fa-ellipsis-vertical menu-icon" onclick="toggleMenu(event, this)"></i>
                            <div class="dropdown-menu">
                                <a href="#" onclick="editTugas(event, this)">Edit</a>
                                <a href="?action=delete_tugas&id=<?= $tugas['id'] ?>&matkul=<?= $selected_matkul_id ?? 0 ?>" class="text-danger" onclick="return confirm('Hapus tugas ini?');">Hapus</a>
                            </div>
                        </div>
                        
                        <div class="card-title tugas-text" style="padding-right: 30px; margin-top: 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                            <?= htmlspecialchars($tugas['judul_tugas']) ?>
                        </div>
                        
                        <div style="margin-top: auto; padding-top: 15px; font-size: 12px; font-family: var(--font-body); color: var(--color-pink);">
                            <?= $tugas['deadline_format'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="fab" onclick="bukaModalTugas()">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>

    <div id="modalTugas" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <h2 id="modalTitleTugas" style="margin-bottom: 20px; font-family: 'Poppins'; text-align: center;">TAMBAH TUGAS</h2>
            
            <form id="formTugas" method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="action" id="formActionTugas" value="create_tugas">
                <input type="hidden" name="tugas_id" id="tugasId" value="">
                
                <div class="form-group" style="margin-bottom: 15px; width: 100%;">
                    <label for="inputMatkulId" style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 5px;">MATA KULIAH</label>
                    <select name="matkul_id" id="inputMatkulId" required style="width: 100%; padding: 8px 0; border: none; border-bottom: 1px solid #333; outline: none; background: transparent; font-family: 'Inter', sans-serif;">
                        <option value="">Pilih Mata Kuliah...</option>
                        <?php foreach ($data_matkul as $mk): ?>
                            <option value="<?= $mk['id'] ?>"><?= htmlspecialchars($mk['nama_matkul']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 15px; width: 100%;">
                    <label for="inputJudulTugas" style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 5px;">JUDUL TUGAS</label>
                    <input type="text" id="inputJudulTugas" name="judul_tugas" required autocomplete="off" style="width: 100%; padding: 8px 0; border: none; border-bottom: 1px solid #333; outline: none; background: transparent;">
                </div>

                <div class="form-group" style="margin-bottom: 15px; width: 100%;">
                    <label for="inputDeadline" style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 5px;">DEADLINE</label>
                    <input type="datetime-local" id="inputDeadline" name="deadline" required style="width: 100%; padding: 8px 0; border: none; border-bottom: 1px solid #333; outline: none; background: transparent; font-family: 'Inter', sans-serif;">
                </div>

                <div class="form-group" style="margin-bottom: 20px; width: 100%;">
                    <label for="inputDeskripsi" style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 5px;">DESKRIPSI</label>
                    <textarea name="deskripsi" id="inputDeskripsi" rows="3" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; outline: none; font-family: 'Inter', sans-serif; resize: vertical;"></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 20px; width: 100%;">
                    <label for="inputFileLampiran" style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 5px;">LAMPIRAN FILE (Opsional)</label>
                    <input type="file" id="inputFileLampiran" name="file_lampiran" style="width: 100%; padding: 8px 0; border: none; border-bottom: 1px solid #333; outline: none; background: transparent; font-family: 'Inter', sans-serif;">
                </div>
                
                <div style="width: 100%; text-align: center;">
                    <button type="submit" class="btn-submit">SUBMIT</button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="../../../assets/js/admin/detail.js?v=<?= time(); ?>"></script>
</body>
</html>