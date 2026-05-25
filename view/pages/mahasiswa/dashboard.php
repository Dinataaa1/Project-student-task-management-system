<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// ==========================================================================
// MENGHUBUNGKAN FRONTEND DENGAN BACKEND (CONTROLLER)
// ==========================================================================
require_once '../../../controllers/mahasiswa/dashboard.php';

// Menyiapkan variabel untuk komponen Header & Sidebar
$active_page = 'dashboard'; // Memberi tahu sidebar untuk menyalakan ikon Home

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
    
    <?php include '../../components/sidebar.php'; ?>

    <div class="main-content">
        
        <div class="topbar">
            <h2 class="m-0 fw-bold" style="color: #666; font-size: 1.8rem;">LOL</h2>
            <div class="position-relative" style="cursor: pointer;" onclick="alert('Belum ada notifikasi baru.')">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#999" class="bi bi-bell-fill" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/></svg>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-info border border-light rounded-circle" style="width: 12px; height: 12px; transform: translate(-30%, -30%) !important;"></span>
            </div>
        </div>

        <div class="content-area">
            <?php 
                // Pencegahan error null jika session nama kosong
                $tampil_nama = $nama_user ?? 'Mahasiswa'; 
            ?>
            <h4 class="fw-bold mb-4" style="font-size: 1.4rem;">Hai, <?= htmlspecialchars($tampil_nama) ?></h4>

            <div class="d-flex gap-3 align-items-center flex-wrap mb-4">
                <?php if (!empty($data_matkul)) : ?>
                    <?php foreach($data_matkul as $matkul) : ?>
                        <a href="daftar_tugas.php?matkul=<?= $matkul['id'] ?>" class="matkul-card text-decoration-none">
                            <div class="blob-hiasan <?= $matkul['warna'] ?>"></div>
                            <span><?= htmlspecialchars($matkul['nama']) ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <a href="daftar_tugas.php" class="ms-3 fw-bold text-decoration-none see-all-link">See all <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg></a>
            </div>

            <div class="calendar-widget mt-4">
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
                        
                        <?php if (!empty($data_dl_terdekat)) : ?>
                            <?php foreach($data_dl_terdekat as $dl) : ?>
                                <?php $tgl_format = date('d M', strtotime($dl['deadline'])); ?>
                                <div class="dl-empty-box d-flex justify-content-between px-2 align-items-center">
                                    <span class="text-truncate fw-bold text-dark" style="max-width: 65%; font-size: 0.75rem; line-height: 25px;"><?= htmlspecialchars($dl['judul_tugas']) ?></span>
                                    <span class="fw-bold text-danger" style="font-size: 0.75rem;"><?= $tgl_format ?></span>
                                </div>
                            <?php endforeach; ?>
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