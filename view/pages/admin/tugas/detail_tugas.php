<?php
require_once '../../../../controllers/admin/detail_tugas_controler.php';

$active_page = 'tugas_detail';
$jalur_css   = "../../../assets/css/index.css";
include '../../../components/header.php';
?>

<link rel="stylesheet" href="../../../assets/css/pages/admin/detail_tugas.css">

<div class="dashboard-wrapper">
    <?php include '../../../components/sidebar.php'; ?>
    
    <div class="main-content">
        <?php include '../../../components/topbar.php'; ?>

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
                        <a href="../../../../controllers/uploads/tugas/<?= htmlspecialchars($data_tugas['file_lampiran']) ?>" 
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
                                        <a href="<?= BASE_URL ?><?= htmlspecialchars($row['file_path']) ?>" class="btn-lihat" target="_blank">
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