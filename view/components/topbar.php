<?php
// Panggil otak (controller) topbar agar terhubung ke database
require_once __DIR__ . '/../../controllers/components/topbar.php';
?>

<div class="topbar d-flex justify-content-between align-items-center w-100" style="height: 70px; padding: 0 40px;">
    
    <div class="topbar">
        <h1 class="logo-text">LOL</h1>
    </div>

    <div class="topbar-right">
        <div class="bell-container" id="bellIcon" data-userid="<?= $_SESSION['user_id'] ?? 'guest' ?>" style="cursor: pointer;">
            <div class="bell-wrapper" style="transform: scale(0.45); transform-origin: center; display: flex; align-items: center; justify-content: center;">
                <div class="bell">
                    <div class="bell-top"></div>
                    <div class="bell-bot"></div>
                    <div id="blueDot" class="bell-notification d-none">!</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="notifOverlay" class="notif-overlay"></div>

<div id="notifPanel" class="notif-panel">
    <div class="notif-header">
        <h5 class="fw-bold m-0 text-dark">Reminder Tugas</h5>
        <button id="closeNotifBtn" type="button" class="btn-close" aria-label="Close"></button>
    </div>
    
    <div class="notif-body">
        <?php $data_tugas_belum_dikumpul = $data_tugas_belum_dikumpul ?? []; ?>

        <?php if (!empty($data_tugas_belum_dikumpul)) : ?>
            <?php foreach ($data_tugas_belum_dikumpul as $tugas) : ?>
                <a href="detail_tugas.php?id=<?= $tugas['id'] ?>" class="text-decoration-none">
                    <div class="notif-card shadow-sm" data-id="<?= $tugas['id'] ?>">
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;"><?= htmlspecialchars($tugas['judul_tugas']) ?></h6>
                        <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size: 0.75rem;">Belum dikumpulkan!</span>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center p-4 text-muted mt-4">
                <div style="font-size: 2rem; opacity: 0.2; margin-bottom: 10px;">☕</div>
                <div style="font-size: 0.85rem;">Semua tugas sudah beres.<br>Santai dulu!</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="../../assets/js/components/topbar.js"></script>