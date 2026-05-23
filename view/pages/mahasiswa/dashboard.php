<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// ==========================================================================
// 1. OTENTIKASI & KONEKSI BASIS DATA
// ==========================================================================
// session_start();
// if (!isset($_SESSION['mahasiswa_id'])) {
//     header("Location: ../../login.php"); 
//     exit();
// }
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../../auth/login.php"); 
    exit(); 
}

include '../../components/header.php';
include '../../../config/koneksi.php'; 

// Data simulasi pengguna login.
$user_id = $_SESSION['user_id']; 
$nama_user = $_SESSION['nama'];

$result_mhs = mysqli_query($conn, "SELECT id FROM mahasiswa WHERE user_id = $user_id");
$row_mhs = mysqli_fetch_assoc($result_mhs);
$mahasiswa_id = $row_mhs['id'];

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
        <div class="mb-4 mt-3">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 22L2 6L12 2L22 6L12 22Z" fill="#ff6b6b"/>
                <path d="M12 18L6 8L12 5L18 8L12 18Z" fill="#ffa06b"/>
            </svg>
        </div>
        <div class="mb-5 text-center">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($nama_user) ?>&background=random&rounded=true" alt="Profile" width="40" class="rounded-circle shadow-sm">
            <div style="font-size: 0.75rem; font-weight: 800; margin-top: 8px;">NAMA</div>
        </div>
        
        <a href="dashboard.php" class="sidebar-item active text-decoration-none mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16"><path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5Z"/></svg>
        </a>
        <a href="daftar_matkul.php" class="sidebar-item text-decoration-none mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-journal-text" viewBox="0 0 16 16"><path d="M5 10.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/><path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/></svg>
        </a>
        <a href="#" class="sidebar-item text-decoration-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16"><path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/></svg>
        </a>
    </div>

    <div class="main-content">
        
        <div class="topbar">
            <h2 class="m-0 fw-bold" style="color: #666; font-size: 1.8rem;">LOL</h2>
            <div class="position-relative" style="cursor: pointer;" onclick="alert('Belum ada notifikasi baru.')">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#999" class="bi bi-bell-fill" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/></svg>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-info border border-light rounded-circle" style="width: 12px; height: 12px; transform: translate(-30%, -30%) !important;"></span>
            </div>
        </div>

        <div class="content-area">
            <h4 class="fw-bold mb-4" style="font-size: 1.4rem;">Hai, <?= $nama_user ?></h4>

            <div class="d-flex gap-3 align-items-center flex-wrap mb-4">
                <?php foreach($data_matkul as $matkul) : ?>
                    <a href="daftar_tugas.php?matkul=<?= $matkul['id'] ?>" class="matkul-card text-decoration-none">
                        <div class="blob-hiasan <?= $matkul['warna'] ?>"></div>
                        <span>Matkul</span>
                    </a>
                <?php endforeach; ?>
                <a href="daftar_tugas.php" class="ms-3 fw-bold text-decoration-none see-all-link">See all <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg></a>
            </div>

            <div class="calendar-widget">
                <div class="cal-left">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="big-date-circle">
                            <?= $tanggal_sekarang ?>
                        </div>
                        <div class="lh-sm text-white">
                            <div class="fw-bold" style="font-size: 1.2rem;"><?= ucfirst($bulan_sekarang) ?></div>
                            <div style="font-size: 1rem; opacity: 0.9;"><?= $tahun_sekarang ?></div>
                        </div>
                    </div>
                    
                    <button class="btn-notes" onclick="alert('Fitur tambah catatan tugas sedang disiapkan!')">NOTES TO BE MADE</button>
                    
                    <div class="mt-4">
                        <div class="dl-box fw-bold text-center mb-2">DL Terdekat</div>
                        
                        <?php if (mysqli_num_rows($query_dl_terdekat) > 0) : ?>
                            <?php while($dl = mysqli_fetch_assoc($query_dl_terdekat)) : ?>
                                <?php $tgl_format = date('d M', strtotime($dl['deadline'])); ?>
                                <div class="dl-empty-box d-flex justify-content-between px-2">
                                    <span class="text-truncate fw-bold text-dark" style="max-width: 65%; font-size: 0.75rem; line-height: 25px;"><?= $dl['judul_tugas'] ?></span>
                                </div>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <div class="dl-empty-box"></div>
                            <div class="dl-empty-box"></div>
                            <div class="dl-empty-box"></div>
                            <div class="dl-empty-box"></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="cal-right position-relative">
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex position-relative me-2">
                            <div class="avatar-circle bg-success text-white">G</div>
                            <div class="avatar-circle bg-danger text-white" style="margin-left: -10px;">L</div>
                        </div>
                        <div class="fw-bold text-white" style="font-size: 0.9rem; letter-spacing: 1px;">ALENDAR</div>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-5 mb-4 text-center align-items-end text-white">
                        <div class="d-flex flex-column align-items-center" style="opacity: 0.7; cursor: pointer;" id="btnPrev">
                            <div style="font-size: 0.9rem;">Oct</div>
                            <div class="fw-bold" style="font-size: 0.8rem;">2021</div>
                        </div>
                        <div class="d-flex flex-column align-items-center fw-bold" id="displayBulanTahun" style="cursor: pointer; transform: scale(1.2);">
                            <div style="font-size: 1rem;">Nov</div>
                            <div style="font-size: 0.8rem;">2021</div>
                        </div>
                        <div class="d-flex flex-column align-items-center" style="opacity: 0.7; cursor: pointer;" id="btnNext">
                            <div style="font-size: 0.9rem;">Dec</div>
                            <div class="fw-bold" style="font-size: 0.8rem;">2021</div>
                        </div>
                    </div>

                    <div class="cal-grid text-white fw-bold" style="font-size: 0.8rem; padding-bottom: 10px;">
                        <div>SUN</div><div>MON</div><div>TUS</div><div>WED</div><div>THUR</div><div>FRI</div><div>SAT</div>
                    </div>

                    <div class="cal-grid" id="wadahTanggal"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/dashboard.js"></script>

<?php include '../../components/footer.php'; ?>