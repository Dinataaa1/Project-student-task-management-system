<?php
// ==========================================================================
// 1. OTENTIKASI & KONEKSI BASIS DATA
// ==========================================================================
// session_start();
// if (!isset($_SESSION['mahasiswa_id'])) {
//     header("Location: ../../login.php"); 
//     exit();
// }

include '../../components/header.php';
include '../../../config/koneksi.php'; 

// Data simulasi pengguna login.
$mahasiswa_id = 1; 
$nama_user = "Luthfi Bahrur R."; 

// ==========================================================================
// 2. LOGIKA PENGAMBILAN DATA (QUERY)
// ==========================================================================
// Menarik data nama mata kuliah (SUDAH TERMASUK ID UNTUK LINK)
$query_matkul = mysqli_query($conn, "
    SELECT mk.id, mk.nama_matkul 
    FROM mata_kuliah mk
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id
    LIMIT 4
");

$data_matkul = [];
$pilihan_warna = ["blob-orange", "blob-blue"];
$index_warna = 0;

while ($row = mysqli_fetch_assoc($query_matkul)) {
    $data_matkul[] = [
        "id" => $row['id'], // ID disimpan untuk link
        "nama" => $row['nama_matkul'],
        "warna" => $pilihan_warna[$index_warna % 2] 
    ];
    $index_warna++;
}

// Menarik seluruh data batas waktu tugas
$query_tugas = mysqli_query($conn, "
    SELECT DATE(t.deadline) as tgl_deadline 
    FROM tugas t
    JOIN mata_kuliah mk ON t.matkul_id = mk.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id
");

$array_deadline = [];
while ($row = mysqli_fetch_assoc($query_tugas)) {
    $tanggal_mentah = date("Y-n-j", strtotime($row['tgl_deadline'])); 
    $array_deadline[] = $tanggal_mentah;
}

// Menarik dua data tugas dengan tenggat waktu terdekat
$query_dl_terdekat = mysqli_query($conn, "
    SELECT t.judul_tugas, t.deadline 
    FROM tugas t
    JOIN mata_kuliah mk ON t.matkul_id = mk.id
    JOIN krs k ON mk.id = k.mata_kuliah_id
    WHERE k.mahasiswa_id = $mahasiswa_id AND t.deadline >= NOW()
    ORDER BY t.deadline ASC
    LIMIT 2
");

$tanggal_sekarang = date('d'); 
$bulan_sekarang = date('M');   
$tahun_sekarang = date('Y');   
?>

<script>
    const dataTugasDB = <?= json_encode($array_deadline); ?>;
</script>

<div class="dashboard-wrapper">
    
    <div class="sidebar">
        <div class="mb-4">
            <h4 style="color: #fa8c96; font-weight: 900;">V</h4>
        </div>
        <div class="mb-4 text-center">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($nama_user) ?>&background=random&rounded=true" alt="Profile" width="40">
            <div style="font-size: 0.7rem; font-weight: bold; margin-top: 5px;">NAMA</div>
        </div>
        
        <a href="dashboard.php" class="sidebar-item active text-decoration-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16"><path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5Z"/></svg>
        </a>
        <a href="daftar_matkul.php" class="sidebar-item text-decoration-none">
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
            <div class="position-relative" style="cursor: pointer;" onclick="alert('Belum ada notifikasi baru.')">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#888" class="bi bi-bell-fill" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/></svg>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-info border border-light rounded-circle"></span>
            </div>
        </div>

        <div class="content-area">
            <h4 class="fw-bold mb-4">Hai, <?= $nama_user ?></h4>

            <div class="d-flex gap-3 align-items-center flex-wrap">
                <?php foreach($data_matkul as $matkul) : ?>
                    <a href="daftar_tugas.php?matkul=<?= $matkul['id'] ?>" class="matkul-card text-decoration-none">
                        <div class="blob-hiasan <?= $matkul['warna'] ?>"></div>
                        <span><?= $matkul['nama'] ?></span>
                    </a>
                <?php endforeach; ?>
                <a href="daftar_tugas.php" class="ms-3 fw-bold text-decoration-none" style="color: #00a0e3;">See all ></a>
            </div>

            <div class="calendar-widget">
                <div class="cal-left">
                    <div class="d-flex align-items-center gap-2">
                        <h1 class="display-4 fw-bold m-0 text-white"><?= $tanggal_sekarang ?></h1>
                        <div class="lh-sm">
                            <div class="fw-bold fs-5"><?= strtoupper($bulan_sekarang) ?></div>
                            <div class="fs-6"><?= $tahun_sekarang ?></div>
                        </div>
                    </div>
                    
                    <button class="btn-notes" onclick="alert('Fitur tambah catatan tugas sedang disiapkan!')">NOTES TO BE MADE</button>
                    
                    <div class="mt-4">
                        <div class="bg-light text-dark fw-bold px-2 py-1 mb-2 text-center" style="border-radius: 4px; font-size: 0.85rem;">DL Terdekat</div>
                        
                        <?php if (mysqli_num_rows($query_dl_terdekat) > 0) : ?>
                            <?php while($dl = mysqli_fetch_assoc($query_dl_terdekat)) : ?>
                                <?php $tgl_format = date('d M', strtotime($dl['deadline'])); ?>
                                <div class="dl-box d-flex justify-content-between align-items-center px-2" style="font-size: 0.75rem; color: #b02a37;">
                                    <span class="text-truncate fw-bold" style="max-width: 65%;"><?= $dl['judul_tugas'] ?></span>
                                    <span class="fw-bold"><?= $tgl_format ?></span>
                                </div>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <div class="dl-box d-flex justify-content-center align-items-center px-2" style="font-size: 0.75rem; color: #b02a37;">
                                <span class="fw-bold">Hore! Tidak ada DL</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="cal-right">
                    <div class="fw-bold mb-3" style="font-size: 0.8rem;">CALENDAR</div>
                    
                    <div class="d-flex justify-content-center gap-4 mb-4 text-center align-items-center">
                        <div id="btnPrev" class="fw-bold" style="cursor: pointer; opacity: 0.7; font-size: 1.2rem;">&lt;</div>
                        <div class="fw-bold" id="displayBulanTahun" style="min-width: 80px;"></div>
                        <div id="btnNext" class="fw-bold" style="cursor: pointer; opacity: 0.7; font-size: 1.2rem;">&gt;</div>
                    </div>

                    <div class="cal-grid fw-bold" style="font-size: 0.85rem; padding-bottom: 10px;">
                        <div class="text-danger">SUN</div><div>MON</div><div>TUE</div><div>WED</div><div>THU</div><div>FRI</div><div>SAT</div>
                    </div>

                    <div class="cal-grid" id="wadahTanggal"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/Project-student-task-management-system/view/assets/js/dashboard.js"></script>

<?php include '../../components/footer.php'; ?>