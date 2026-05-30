<?php
// Mencegah error jika koneksi belum dipanggil di file utama
include_once __DIR__ . '/../../config/koneksi.php';

// Ambil ID user dari sesi, fallback ke ID 3 (Andi Saputra) jika belum login
$user_id_topbar = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 3;

// Query khusus Reminders (dibuat mandiri agar jalan di semua halaman)
$query_reminders_topbar = mysqli_query($conn, "
    SELECT t.id, mk.nama_matkul, t.judul_tugas, t.deadline, d.nama_dosen
    FROM tugas t
    JOIN mata_kuliah mk ON t.matkul_id = mk.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    JOIN dosen d ON mk.dosen_id = d.id
    JOIN mahasiswa m ON m.id = k.mahasiswa_id
    LEFT JOIN pengumpulan_tugas pt ON pt.tugas_id = t.id AND pt.mahasiswa_id = k.mahasiswa_id
    WHERE m.user_id = $user_id_topbar 
    AND pt.id IS NULL
    ORDER BY t.deadline ASC
    LIMIT 3
");

$data_reminders = [];
if ($query_reminders_topbar) {
    while ($row = mysqli_fetch_assoc($query_reminders_topbar)) {
        $data_reminders[] = $row;
    }
}
?>

<div class="topbar">
    <h2 class="m-0 fw-bold" style="color: #555;">LOL</h2>
    
    <div class="position-relative" style="cursor: pointer;" data-bs-toggle="offcanvas" data-bs-target="#remindersPanel">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#888" class="bi bi-bell-fill" viewBox="0 0 16 16">
            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
        </svg>
        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-info border border-light rounded-circle"></span>
    </div>
</div>

<div class="offcanvas offcanvas-end custom-offcanvas" tabindex="-1" id="remindersPanel">
    <div class="offcanvas-header pb-0 mt-3">
        <h2 class="fw-bold m-0" style="color: #4a4a4a; font-size: 2.2rem;">Reminders</h2>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body pt-2">
        <hr class="reminders-divider">

        <?php 
        $warna_blob = ['blob-orange', 'blob-blue'];
        $index = 0;
        ?>

        <?php if (!empty($data_reminders)) : ?>
            <?php foreach($data_reminders as $rem) : ?>
                <a href="detail_tugas.php?id=<?= $rem['id'] ?>" class="rem-card">
                    <div class="rem-blob <?= $warna_blob[$index % 2] ?>"></div>
                    
                    <div class="rem-title"><?= htmlspecialchars($rem['nama_matkul']) ?></div>
                    <div class="rem-subtitle"><?= htmlspecialchars($rem['judul_tugas']) ?></div>
                    
                    <div class="rem-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/></svg>
                        Deadline: <?= date('d M Y, H:i', strtotime($rem['deadline'])) ?>
                    </div>
                    
                    <div class="rem-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16"><path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/></svg>
                        Dosen: <?= htmlspecialchars($rem['nama_dosen']) ?>
                    </div>
                </a>
                <?php $index++; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center text-muted mt-5">
                <p>Belum ada pengingat tugas baru.</p>
            </div>
        <?php endif; ?>

        <div class="text-end mt-4">
            <a href="daftar_tugas.php" class="text-decoration-none fw-bold" style="color: #7dd3fc;">See all</a>
        </div>
        
    </div>
</div>