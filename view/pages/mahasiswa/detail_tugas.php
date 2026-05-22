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

// Data simulasi pengguna login. Akan diubah menjadi data dinamis melalui $_SESSION.
$mahasiswa_id = 1; 
$nama_user    = "Luthfi Bahrur R."; 

// Menangkap rujukan identitas tugas tunggal melalui parameter antarmuka (URL)
$tugas_id = isset($_GET['id']) ? $_GET['id'] : 0;

// ==========================================================================
// 2. LOGIKA BACKEND: PENANGANAN PROSES UNGGAH (UPLOAD) TUGAS
// ==========================================================================
if (isset($_POST['submit_tugas'])) {
    
    // Menetapkan konfigurasi referensi zona waktu penugasan operasional server
    date_default_timezone_set('Asia/Jakarta'); 
    
    // Proses pemecahan properti penanganan lampiran direktori file
    $nama_file = $_FILES['file_tugas']['name'];
    $tmp_file  = $_FILES['file_tugas']['tmp_name'];
    
    // Menetapkan relasi ke pemetaan titik direktori berkas target server
    $folder_tujuan = "uploads/" . time() . "_" . $nama_file; 
    
    // Menyelesaikan mekanisme relokasi lampiran penyimpanan dokumen
    if (move_uploaded_file($tmp_file, $folder_tujuan)) {
        // Melakukan penugasan data pengumpulan pada tabel pengumpulan tugas
        $query_insert = "INSERT INTO pengumpulan_tugas (tugas_id, mahasiswa_id, file_tugas) 
                         VALUES ('$tugas_id', '$mahasiswa_id', '$folder_tujuan')";
        mysqli_query($conn, $query_insert);
        
        // Memaksa penyegaran sesi halaman browser untuk memastikan komponen pengumpulan mutakhir
        header("Location: detail_tugas.php?id=$tugas_id&status=sukses");
        exit();
    }
}

// ==========================================================================
// 3. LOGIKA EVALUASI PEMUATAN DATA & STATUS TUGAS
// ==========================================================================
// Menarik data rincian satu buah tugas dari identitas database
$query_tugas = mysqli_query($conn, "SELECT * FROM tugas WHERE id = '$tugas_id'");
$tugas = mysqli_fetch_assoc($query_tugas);

// Memeriksa status keberadaan pengumpulan dokumen tugas spesifik oleh mahasiswa
$query_cek_kumpul = mysqli_query($conn, "SELECT * FROM pengumpulan_tugas WHERE tugas_id = '$tugas_id' AND mahasiswa_id = '$mahasiswa_id'");
$sudah_kumpul = mysqli_num_rows($query_cek_kumpul) > 0;
$data_kumpul = mysqli_fetch_assoc($query_cek_kumpul);

// Melakukan format penyelarasan tenggat waktu tugas berdasarkan operasi timestamp
$deadline_format = date('d F Y, H:i', strtotime($tugas['deadline']));
$tenggat_waktu = strtotime($tugas['deadline']);
$waktu_sekarang = time();
?>

<div class="dashboard-wrapper">
    
    <div class="sidebar">
        <div class="mb-4"><h4 style="color: #fa8c96; font-weight: 900;">V</h4></div>
        <div class="mb-4 text-center">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($nama_user) ?>&background=random&rounded=true" width="40">
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
            </div>
        </div>

        <div class="content-area position-relative" style="min-height: calc(100vh - 70px);">
            
            <h5 class="fw-bold mb-4" style="color: #444;">
                <?= $tugas['judul_tugas'] ?>
            </h5>

            <div class="detail-tugas-card">
                <div class="blob-hiasan-detail blob-orange"></div>
                
                <div class="detail-card-content">
                    <p class="text-muted" style="font-size: 0.95rem; max-width: 80%;">
                        <?= nl2br($tugas['deskripsi']) ?>
                    </p>
                    
                    <div class="badge-lampiran mt-2 mb-5"> Lampiran File </div>

                    <div class="d-flex justify-content-between align-items-end mt-4">
                        <?php if ($sudah_kumpul) : ?>
                            <div>
                                <span class="fw-bold text-success">Dikumpulkan</span>
                                <?php if (strtotime($data_kumpul['waktu_kumpul']) > $tenggat_waktu) : ?>
                                    <span class="fw-bold ms-2" style="color: #ff9800;">(Terlambat)</span>
                                <?php endif; ?>
                            </div>
                        <?php else : ?>
                            <div>
                                <span class="fw-bold text-danger">Belum Mengumpulkan</span>
                                <div class="mt-2" style="cursor:pointer; color:#00a0e3; font-weight:bold; font-size: 0.9rem;" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                    ^ Upload Tugas
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="fw-bold" style="color: #00a0e3;">
                            Deadline: <?= $deadline_format ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($sudah_kumpul) : ?>
            <div class="detail-tugas-card mt-4">
                <div class="blob-hiasan-detail blob-blue"></div>
                
                <div class="detail-card-content d-flex flex-column h-100">
                    <div class="badge-lampiran mb-4" style="width: fit-content;">
                        File Tugas: <?= basename($data_kumpul['file_tugas']) ?>
                    </div>
                    
                    <div class="text-end mt-auto fw-bold" style="color: #00d284; font-size: 1.1rem;">
                        Nilai: <?= $data_kumpul['nilai'] !== null ? $data_kumpul['nilai'] : 'Belum Dinilai' ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <a href="javascript:history.back()" class="position-absolute text-decoration-none" style="bottom: 0px; left: 0px; color: #205c54;">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16">
                  <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                </svg>
            </a>

        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal-content">
      
      <div class="modal-header border-0 pb-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body text-center pt-0">
        <form method="POST" action="" enctype="multipart/form-data">
            
            <label class="badge-lampiran mb-4" style="cursor: pointer; display: inline-block;">
                Input File
                <input type="file" name="file_tugas" class="d-none" required id="fileInput">
            </label>
            <div id="fileNameDisplay" class="text-muted mb-3" style="font-size: 0.8rem;">Belum ada file dipilih</div>

            <textarea class="form-control mb-4 bg-transparent" rows="3" placeholder="Tambahkan catatan tugas (opsional)..." style="border: none; border-bottom: 2px solid #555; border-radius: 0; box-shadow: none;"></textarea>

            <button type="submit" name="submit_tugas" class="btn btn-danger fw-bold px-4 rounded-pill" style="background-color: #d32f2f;">
                SUBMIT
            </button>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
    document.getElementById('fileInput').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : "Belum ada file dipilih";
        document.getElementById('fileNameDisplay').textContent = fileName;
    });
</script>

<?php include '../../components/footer.php'; ?>