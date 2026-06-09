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
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/pages/admin/dashboard.css">
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
                
                $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($nama_dosen) . "&background=4F46E5&color=fff&bold=true";
            ?>
            <img src="<?= $avatar_url ?>" alt="Profile">
            <p><?= htmlspecialchars($nama_depan) ?></p>
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
                <?php 
                $data_matkul = $data_matkul ?? [];
                foreach ($data_matkul as $index => $mk):
                    $warna_blob = ($index % 2 == 0) ? 'orange' : 'blue'; 
                ?>
                    <div class="card" 
                    onclick="window.location.href='tugas/detail.php?matkul=<?= $mk['id'] ?>'" 
                    style="position: relative; overflow: hidden; cursor: pointer;"
                    data-id="<?= $mk['id'] ?>"
                    data-matkul="<?= htmlspecialchars($mk['nama_matkul'], ENT_QUOTES) ?>"
                    data-kelas-id="<?= $mk['kelas_id'] ?? '' ?>" 
                    data-ruangan="<?= htmlspecialchars($mk['ruangan'], ENT_QUOTES) ?>"
                    data-jadwal="<?= htmlspecialchars($mk['jadwal'], ENT_QUOTES) ?>">
                        <div class="blob <?= $warna_blob ?>"></div>
                        
                        <div class="menu-container" style="position: absolute; top: 16px; right: 16px; z-index: 10;">
                            <i class="fa-solid fa-ellipsis-vertical menu-icon" 
                            style="padding: 5px 15px; cursor: pointer; font-size: 18px;" 
                            onclick="event.stopPropagation(); let menu = this.nextElementSibling; menu.style.display = menu.style.display === 'block' ? 'none' : 'block';">
                            </i>
                            
                            <div class="dropdown-menu" style="display: none; position: absolute; top: 100%; right: 0; background: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.15); border-radius: 8px; min-width: 130px; overflow: hidden; text-align: left;">
                                
                                <a href="#" 
                                onclick="editCard(event, this)" 
                                style="display: block; padding: 10px 15px; text-decoration: none; color: #333; font-size: 14px; border-bottom: 1px solid #f1f1f1;">
                                <i class="fa-solid fa-pen" style="margin-right: 8px; color: var(--color-blue);"></i> Edit
                                </a>
                                
                                <a href="?action=delete_matkul&id=<?= $mk['id'] ?>" 
                                onclick="event.stopPropagation(); return confirm('Hapus mata kuliah ini? Semua tugas di dalamnya juga akan ikut terhapus!');" 
                                style="display: block; padding: 10px 15px; text-decoration: none; color: #e11d48; font-size: 14px;">
                                <i class="fa-solid fa-trash" style="margin-right: 8px;"></i> Hapus
                                </a>
                                
                            </div>
                        </div>
                        
                        <div class="matkul-jadwal" style="position: relative; z-index: 1; font-size: 12px; color: #64748b; margin-bottom: 10px;">
                            <span style="font-weight: 600; color: #4F46E5;"><?= htmlspecialchars($mk['nama_kelas'] ?? 'Tanpa Kelas') ?></span> <br>
                            <?= htmlspecialchars($mk['ruangan'] ?? '') ?><?= !empty($mk['ruangan']) ? ' - ' : '' ?><?= htmlspecialchars($mk['jadwal']) ?>
                        </div>

                        <div class="card-title" style="position: relative; z-index: 1;">
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

    <div id="modalMatkul" class="modal-overlay" style="display: none;">
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

    <script src="../../assets/js/admin/dashboard.js?v=<?= time(); ?>"></script>
</body>
</html>