<?php
include '../../components/header.php';
include '../../../config/koneksi.php'; 

$mahasiswa_id = 1; 
$nama_user = "Luthfi Bahrur R."; 

$matkul_aktif = isset($_GET['matkul']) ? $_GET['matkul'] : '';

$query_matkul = mysqli_query($conn, "
    SELECT mk.id, mk.nama_matkul 
    FROM mata_kuliah mk
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id
");

$nama_matkul_terpilih = "Semua";
if ($matkul_aktif != '') {
    $cari_nama = mysqli_query($conn, "SELECT nama_matkul FROM mata_kuliah WHERE id = '$matkul_aktif'");
    if($baris = mysqli_fetch_assoc($cari_nama)) {
        $nama_matkul_terpilih = $baris['nama_matkul'];
    }
}

$kondisi_filter = $matkul_aktif != '' ? "AND t.matkul_id = '$matkul_aktif'" : "";
$query_tugas = mysqli_query($conn, "
    SELECT t.id, t.judul_tugas 
    FROM tugas t
    JOIN mata_kuliah mk ON t.matkul_id = mk.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id $kondisi_filter
");
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
        
        <a href="daftar_matkul.php" class="sidebar-item text-decoration-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-journal-bookmark-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M6 1h6v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8V1z"/><path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/></svg>
        </a>
        
        <a href="daftar_tugas.php" class="sidebar-item active text-decoration-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard-check-fill" viewBox="0 0 16 16"><path d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3Zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3Z"/><path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5v-1Zm6.854 7.354-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708Z"/></svg>
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

        <div class="content-area position-relative" style="min-height: calc(100vh - 70px);">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0" style="color: #444;">
                    Hai, <?= $nama_user ?>! Ini adalah tugas mata kuliah <?= $nama_matkul_terpilih ?>
                </h5>
                
                <form method="GET" action="daftar_tugas.php">
                    <select name="matkul" class="form-select border-2 shadow-sm fw-semibold" style="border-color: #00a0e3;" onchange="this.form.submit()">
                        <option value="">Semua Mata Kuliah</option>
                        <?php while($mk = mysqli_fetch_assoc($query_matkul)) : ?>
                            <option value="<?= $mk['id'] ?>" <?= $matkul_aktif == $mk['id'] ? 'selected' : '' ?>>
                                <?= $mk['nama_matkul'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </form>
            </div>

            <div class="d-flex gap-4 flex-wrap pb-5">
                <?php if (mysqli_num_rows($query_tugas) > 0) : ?>
                    <?php while($tugas = mysqli_fetch_assoc($query_tugas)) : ?>
                        <a href="detail_tugas.php?id=<?= $tugas['id'] ?>" class="tugas-card blob-orange">
                            <div class="blob-hiasan blob-orange"></div>
                            <span><?= $tugas['judul_tugas'] ?></span>
                        </a>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="alert alert-light w-100 text-center fw-bold text-muted border-0 shadow-sm" role="alert">
                        Yeay! Belum ada tugas untuk mata kuliah ini.
                    </div>
                <?php endif; ?>
            </div>

            <a href="dashboard.php" class="position-absolute text-decoration-none" style="bottom: 30px; left: 30px; color: #205c54;">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
                  <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                </svg>
            </a>
        </div>
    </div>
</div>

<?php include '../../components/footer.php'; ?>