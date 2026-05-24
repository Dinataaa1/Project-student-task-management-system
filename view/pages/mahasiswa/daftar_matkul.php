<?php
// ==========================================================================
// 1. OTENTIKASI & KONEKSI BASIS DATA
// ==========================================================================
// session_start();
// if (!isset($_SESSION['mahasiswa_id'])) {
//     header("Location: ../../login.php"); 
//     exit();
// }

include '../../../config/koneksi.php';

include '../../components/header.php';

// Data simulasi pengguna login. Akan diubah menjadi data dinamis melalui $_SESSION.
$mahasiswa_id = 1; 
$nama_user = "Luthfi Bahrur R."; 

// ==========================================================================
// 2. LOGIKA PENGAMBILAN DATA (QUERY)
// ==========================================================================
// Menarik data seluruh mata kuliah yang ditempuh oleh pengguna
$query_matkul = mysqli_query($conn, "
    SELECT mk.id, mk.nama_matkul 
    FROM mata_kuliah mk
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id
");

// Menyiapkan urutan gaya presentasi blob yang dinamis dan terstruktur
$pilihan_warna = ["blob-orange", "blob-blue"];
$index_warna = 0;
?>

<div class="dashboard-wrapper">
    
    <div class="sidebar">
        <div class="mb-4">
            <h4 style="color: #fa8c96; font-weight: 900;">V</h4>
        </div>
        <div class="mb-4 text-center">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($nama_user) ?>&background=random&rounded=true" alt="Profile" width="40">
            <div style="font-size: 0.7rem; font-weight: bold; margin-top: 5px;">NAMA</div>
        </div>
        
        <a href="dashboard.php" class="sidebar-item text-decoration-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16"><path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5Z"/></svg>
        </a>
        
        <a href="daftar_matkul.php" class="sidebar-item active text-decoration-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-journal-bookmark-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M6 1h6v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8V1z"/><path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/></svg>
        </a>
        
        <a href="daftar_tugas.php" class="sidebar-item text-decoration-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard-check-fill" viewBox="0 0 16 16"><path d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3Zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3Z"/><path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5v-1Zm6.854 7.354-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708Z"/></svg>
        </a>
        <a href="#" class="sidebar-item text-decoration-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16"><path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/></svg>
        </a>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h2 class="m-0 fw-bold" style="color: #555;">LOL</h2>
            <div class="position-relative" style="cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#888" class="bi bi-bell-fill" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/></svg>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-info border border-light rounded-circle"></span>
            </div>
        </div>

        <div class="content-area">
            <h5 class="fw-bold mb-4" style="color: #444;">
                Hai, <?= $nama_user ?>
            </h5>

            <div class="d-flex gap-4 flex-wrap pb-5">
                <?php if (mysqli_num_rows($query_matkul) > 0) : ?>
                    <?php while($matkul = mysqli_fetch_assoc($query_matkul)) : ?>
                        <a href="daftar_tugas.php?matkul=<?= $matkul['id'] ?>" class="matkul-card-lg">
                            <div class="blob-hiasan-lg <?= $pilihan_warna[$index_warna % 2] ?>"></div>
                            <div class="d-flex justify-content-between align-items-end w-100" style="z-index: 2;">
                                <span class="judul-matkul"><?= $matkul['nama_matkul'] ?></span>
                                <span class="see-detail">See Detail &rarr;</span>
                            </div>
                        </a>
                        <?php $index_warna++; ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="alert alert-light w-100 fw-bold text-muted border-0 shadow-sm">
                        Belum ada mata kuliah yang diambil.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../../components/footer.php'; ?>