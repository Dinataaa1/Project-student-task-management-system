<?php
// Panggil otak (controller) topbar agar terhubung ke database
require_once __DIR__ . '/../../controllers/components/topbar.php';
?>

<div class="topbar">
    <div class="topbar-left">
        <h3 class="fw-bold m-0" style="color: #444;">LOL</h3>
    </div>

    <div class="topbar-right position-relative">
        <div class="bell-container" id="bellIcon" data-userid="<?= $_SESSION['user_id'] ?? 'guest' ?>" style="cursor: pointer; position: relative;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#888" class="bi bi-bell-fill" viewBox="0 0 16 16">
              <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
            </svg>
            <span id="blueDot" class="position-absolute top-0 start-100 translate-middle p-1 bg-info border border-light rounded-circle d-none"></span>
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