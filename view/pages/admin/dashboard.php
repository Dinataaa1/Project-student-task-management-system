<?php
require_once '../../../controllers/admin/matkul_controler.php';

// Inisialisasi parameter komponen
$active_page = 'dashboard';
$jalur_css   = "../../assets/css/index.css"; 
include '../../components/header.php'; 
?>

<link rel="stylesheet" href="../../assets/css/pages/admin/dashboard.css">

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

                <h2 class="greeting">Hai, <?= htmlspecialchars($nama_dosen) ?></h2>
                
                <div class="grid-container">
                    <?php 
                    $data_matkul = $data_matkul ?? [];
                    foreach ($data_matkul as $index => $mk):
                        $warna_blob = ($index % 2 == 0) ? 'orange' : 'blue'; 
                    ?>
                        <div class="card" 
                        onclick="window.location.href='tugas/detail.php?matkul=<?= $mk['id'] ?>'" 
                        data-id="<?= $mk['id'] ?>"
                        data-matkul="<?= htmlspecialchars($mk['nama_matkul'], ENT_QUOTES) ?>"
                        data-kelas-id="<?= $mk['kelas_id'] ?? '' ?>" 
                        data-ruangan="<?= htmlspecialchars($mk['ruangan'], ENT_QUOTES) ?>"
                        data-jadwal="<?= htmlspecialchars($mk['jadwal'], ENT_QUOTES) ?>">
                            
                            <div class="blob <?= $warna_blob ?>"></div>
                            
                            <div class="menu-container menu-container-custom">
                                <i class="fa-solid fa-ellipsis-vertical menu-icon menu-icon-custom" 
                                onclick="event.stopPropagation(); let menu = this.nextElementSibling; menu.style.display = menu.style.display === 'block' ? 'none' : 'block';">
                                </i>
                                
                                <div class="dropdown-menu dropdown-menu-custom">
                                    
                                    <a href="#" 
                                    onclick="editCard(event, this)" 
                                    class="dropdown-item dropdown-item-bordered">
                                    <i class="fa-solid fa-pen dropdown-icon icon-blue"></i> Edit
                                    </a>
                                    
                                    <a href="?action=delete_matkul&id=<?= $mk['id'] ?>" 
                                    onclick="event.stopPropagation(); return confirm('Hapus mata kuliah ini? Semua tugas di dalamnya juga akan ikut terhapus!');" 
                                    class="dropdown-item dropdown-item-danger">
                                    <i class="fa-solid fa-trash dropdown-icon"></i> Hapus
                                    </a>
                                    
                                </div>
                            </div>
                            
                            <div class="matkul-jadwal-custom">
                                <span class="matkul-kelas-highlight"><?= htmlspecialchars($mk['nama_kelas'] ?? 'Tanpa Kelas') ?></span> <br>
                                <?= htmlspecialchars($mk['ruangan'] ?? '') ?><?= !empty($mk['ruangan']) ? ' - ' : '' ?><?= htmlspecialchars($mk['jadwal']) ?>
                            </div>

                            <div class="card-title">
                                <?= htmlspecialchars($mk['nama_matkul']) ?>
                            </div>
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
                <h2 id="modalTitle" class="modal-title-custom">TAMBAH MATKUL</h2>
                
                <form id="formMatkul" method="POST" action="dashboard.php">
                    <input type="hidden" name="action" id="formAction" value="create_matkul">
                    <input type="hidden" name="matkul_id" id="matkulId" value="">
                    
                    <div class="form-group">
                        <label for="inputMatkul">MATKUL</label>
                        <input type="text" id="inputMatkul" name="nama_matkul" required autocomplete="off">
                    </div>
                    
                    <div class="form-group-spaced">
                        <label for="selectKelas" class="form-label-small">PILIH KELAS</label>
                        <select id="selectKelas" name="kelas_id" required class="form-select-inline">
                            <option value="" disabled selected>-- Pilih Kelas --</option>
                            <?php 
                            $data_kelas = $data_kelas ?? [];
                            foreach ($data_kelas as $k): 
                            ?>
                                <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group-spaced">
                        <label for="inputRuangan" class="form-label-small">RUANGAN</label>
                        <input type="text" id="inputRuangan" name="ruangan" required placeholder="Contoh: Lab 1" autocomplete="off" class="form-input-inline">
                    </div>
                    
                    <div class="form-group-spaced">
                        <label class="form-label-small">JADWAL</label>
                        
                        <div class="jadwal-container">
                            <select name="hari" required class="jadwal-input">
                                <option value="" disabled selected>Pilih Hari</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                                <option value="Minggu">Minggu</option>
                            </select>
                            
                            <input type="time" name="jam" required class="jadwal-input">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">SUBMIT</button>
                </form>
                </div>
            </div>
    <div>
    <script src="../../assets/js/admin/dashboard.js?v=<?= time(); ?>"></script>
</body>
</html>