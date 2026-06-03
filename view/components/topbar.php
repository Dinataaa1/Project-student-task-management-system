<?php
// Panggil otak (controller) topbar sebelum merender HTML
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

        <div id="notifDropdown" class="notif-dropdown shadow-lg rounded-3 d-none" style="position: absolute; right: 0; top: 40px; width: 320px; background: white; z-index: 100; border: 1px solid #eee;">
            <div class="p-3 border-bottom fw-bold text-dark" style="background-color: #f8f9fa; border-radius: 10px 10px 0 0;">
                Reminder Tugas
            </div>
            
            <div class="notif-list p-2" style="max-height: 300px; overflow-y: auto;">
                <?php $data_tugas_belum_dikumpul = $data_tugas_belum_dikumpul ?? []; ?>

                <?php if (!empty($data_tugas_belum_dikumpul)) : ?>
                    <?php foreach ($data_tugas_belum_dikumpul as $tugas) : ?>
                        <div class="notif-item p-2 mb-2 rounded bg-light border-start border-4 border-warning" data-id="<?= $tugas['id'] ?>">
                            <div class="fw-bold text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($tugas['judul_tugas']) ?></div>
                            <div class="text-danger mt-1" style="font-size: 0.75rem; font-weight: 600;">Belum dikumpulkan!</div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center p-4 text-muted" style="font-size: 0.85rem;">
                        Semua tugas sudah beres. Santai dulu! ☕
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script src="../../assets/js/components/topbar.js"></script>