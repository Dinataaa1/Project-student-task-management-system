<?php
require_once '../../../../controllers/admin/tugas_controler.php';

$active_page = 'tugas_detail';
$jalur_css   = "../../../assets/css/index.css"; // Mundur 3 tingkat ke aset
include '../../../components/header.php';
?>

<link rel="stylesheet" href="../../../assets/css/pages/admin/detail.css">

<body>
    <div class="dashboard-wrapper">
        <?php include '../../../components/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include '../../../components/topbar.php'; ?>

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
                <form id="formTugas" action="../../../controllers/admin/tugas_controler.php" method="POST" enctype="multipart/form-data">

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
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
    </div>    
        <script src="../../../assets/js/admin/detail.js?v=<?= time(); ?>"></script>
</body>
</html>