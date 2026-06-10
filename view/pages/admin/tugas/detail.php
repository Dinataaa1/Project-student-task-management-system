<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../../../controllers/admin/tugas_controler.php';

$current_matkul_name = "Semua Mata Kuliah"; 
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
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/pages/admin/detail.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="profile-area">
            <?php
                $nama_parts = explode(' ', $nama_dosen);
                $nama_depan = $nama_parts[0];
         
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
                    <span class="text-highlight-blue"><?= htmlspecialchars($current_matkul_name) ?></span>
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
                <?php 
                $data_tugas = $data_tugas ?? [];
                foreach ($data_tugas as $index => $tugas):
                    $warna_blob = ($index % 2 == 0) ? 'orange' : 'blue'; 
                ?>
                    <div class="card" 
                    onclick="window.location.href='detail_tugas.php?id=<?= $tugas['id'] ?>'"
                    data-id="<?= $tugas['id'] ?>"
                    data-matkul-id="<?= $tugas['matkul_id'] ?>"
                    data-judul="<?= htmlspecialchars($tugas['judul_tugas'], ENT_QUOTES) ?>"
                    data-deskripsi="<?= htmlspecialchars($tugas['deskripsi'], ENT_QUOTES) ?>"
                    data-deadline="<?= $tugas['deadline'] ?>">
                        
                        <div class="blob <?= $warna_blob ?> small"></div>
                        
                       <div class="menu-container">
                            <i class="fa-solid fa-ellipsis-vertical menu-icon menu-icon-custom"
                            onclick="event.stopPropagation(); let menu = this.nextElementSibling; menu.style.display = menu.style.display === 'block' ? 'none' : 'block';">
                            </i>
                            
                            <div class="dropdown-menu dropdown-menu-custom">
                                <a href="#" onclick="editTugas(event, this)" class="dropdown-item-bordered">
                                    <i class="fa-solid fa-pen dropdown-icon icon-blue"></i> Edit
                                </a>
                                <a href="?action=delete_tugas&id=<?= $tugas['id'] ?>" onclick="event.stopPropagation(); return confirm('Hapus tugas ini?');" class="dropdown-item-danger">
                                    <i class="fa-solid fa-trash dropdown-icon"></i> Hapus
                                </a>
                            </div>
                        </div>

                        <div class="task-card-title">
                            <?= htmlspecialchars($tugas['judul_tugas']) ?>
                        </div>

                        <div class="task-card-deadline">
                            <?= htmlspecialchars($tugas['deadline_format'] ?? date('d M Y, H:i', strtotime($tugas['deadline']))) ?>
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
            <h2 id="modalTitleTugas" class="modal-title-center">TAMBAH TUGAS</h2>
            
            <form id="formTugas" method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="action" id="formActionTugas" value="create_tugas">
                <input type="hidden" name="tugas_id" id="tugasId" value="">
                
                <div class="form-group-spaced">
                    <label for="inputMatkulId" class="form-label-small">MATA KULIAH</label>
                    <select name="matkul_id" id="inputMatkulId" required class="form-select-inline">
                        <option value="">Pilih Mata Kuliah...</option>
                        <?php foreach ($data_matkul as $mk): ?>
                            <option value="<?= $mk['id'] ?>"><?= htmlspecialchars($mk['nama_matkul']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group-spaced">
                    <label for="inputJudulTugas" class="form-label-small">JUDUL TUGAS</label>
                    <input type="text" id="inputJudulTugas" name="judul_tugas" required autocomplete="off" class="form-input-inline">
                </div>

                <div class="form-group-spaced">
                    <label for="inputDeadline" class="form-label-small">DEADLINE</label>
                    <input type="datetime-local" id="inputDeadline" name="deadline" required class="form-input-inline">
                </div>

                <div class="form-group-large">
                    <label for="inputDeskripsi" class="form-label-small">DESKRIPSI</label>
                    <textarea name="deskripsi" id="inputDeskripsi" rows="3" required class="form-textarea-custom"></textarea>
                </div>

                <div class="form-group-large">
                    <label for="inputFileLampiran" class="form-label-small">LAMPIRAN FILE (Opsional)</label>
                    <input type="file" id="inputFileLampiran" name="file_lampiran" class="form-input-inline">
                </div>
                
                <div class="submit-container">
                    <button type="submit" class="btn-submit">SUBMIT</button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="../../../assets/js/admin/detail.js?v=<?= time(); ?>"></script>
</body>
</html>