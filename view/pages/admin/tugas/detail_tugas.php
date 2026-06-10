<?php
require_once '../../../../controllers/admin/detail_tugas_controler.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tugas & Penilaian</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/pages/admin/detail_tugas.css?v=3">
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
            <a href="detail.php" class="nav-item"><i class="fa-solid fa-address-card"></i></a>
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
            <?php if (!empty($pesan_error)): ?>
                <div class="alert-error">
                    <?= $pesan_error ?>
                </div>
            <?php else: ?>
            
            <h2 class="page-title"><?= htmlspecialchars($data_tugas['judul_tugas']) ?></h2>
            
            <div class="task-detail-card">
                <p class="task-desc"><?= nl2br(htmlspecialchars($data_tugas['deskripsi'])) ?></p>
                <div class="blob-large"></div>
                
                <div class="task-footer">
                    <?php if (!empty($data_tugas['file_lampiran'])): ?>
                        <a href="<?= BASE_URL ?>/uploads/tugas/<?= htmlspecialchars($data_tugas['file_lampiran']) ?>" 
                           class="btn-lampiran-view" 
                           target="_blank">
                           <i class="fa-solid fa-paperclip"></i> Lihat Lampiran
                        </a>
                    <?php else: ?>
                        <span class="btn-lampiran-view btn-lampiran-disabled">
                            Tidak ada lampiran
                        </span>
                    <?php endif; ?>
                    
                    <span class="deadline-text">Deadline: <?= date('d M Y, H:i', strtotime($data_tugas['deadline'])) ?></span>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>NRP</th>
                            <th>Nama</th>
                            <th>Lampiran</th>
                            <th>Nilai</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data_pengumpulan)): ?>
                            <tr>
                                <td colspan="5" class="text-center">Belum ada mahasiswa yang mengumpulkan</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data_pengumpulan as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nrp']) ?></td>
                                <td><?= htmlspecialchars($row['nama_mahasiswa']) ?></td>
                                <td>
                                    <?php if ($row['status_kumpul'] !== 'kosong' && !empty($row['file_path'])): ?>
                                        <a href="../../../../uploads/<?= htmlspecialchars($row['file_path']) ?>" class="btn-lihat" target="_blank">
                                            <i class="fa-solid fa-eye"></i> Lihat
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted-small">Belum ada file</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="nilai-wrapper">
                                        <input type="number" 
                                               class="input-nilai" 
                                               value="<?= htmlspecialchars($row['nilai'] ?? '') ?>" 
                                               data-id="<?= $row['pengumpulan_id'] ?>" 
                                               onchange="simpanNilai(this)" 
                                               min="0" max="100" 
                                               placeholder="..."> / 100
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= $row['status_kumpul'] ?>">
                                        <?= $row['status_teks'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="../../../assets/js/admin/detail_tugas.js?v=2"></script>
</body>
</html>