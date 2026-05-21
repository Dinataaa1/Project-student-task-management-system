<?php
// ==============================================================
// 1. MEMANGGIL BAGIAN ATAS (HEADER & BOOTSTRAP CSS)
// ==============================================================
include '../components/header.php';

// ==============================================================
// 2. DATA DINAMIS SIMULASI (SIAP DIHUBUNGKAN KE DATABASE)
// ==============================================================
$nama_user = "Luthfi Bahrur R."; 

// Daftar Mata Kuliah otomatis (bisa ditambah/dikurangi di sini)
$data_matkul = [
    ["nama" => "Struktur Data", "warna" => "blob-orange"],
    ["nama" => "Basis Data", "warna" => "blob-blue"],
    ["nama" => "Pemrograman Web", "warna" => "blob-orange"],
    ["nama" => "Sistem Operasi", "warna" => "blob-blue"],
    ["nama" => "Aljabar Linear", "warna" => "blob-orange"]
];

// Mengambil waktu real-time hari ini dari sistem laptop/server
$tanggal_sekarang = date('d'); 
$bulan_sekarang = date('M');   
$tahun_sekarang = date('Y');   
?>

<div class="dashboard-wrapper">
    
    <div class="sidebar" id="sidebarMenu">
        <div class="mb-4">
            <h4 style="color: #fa8c96; font-weight: 900;">V</h4>
        </div>
        <div class="mb-4 text-center">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($nama_user) ?>&background=random&rounded=true" alt="Profile" width="40">
            <div style="font-size: 0.7rem; font-weight: bold; margin-top: 5px;"><?= strtoupper($nama_user) ?></div>
        </div>
        
        <div class="sidebar-item menu-item active">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16"><path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5Z"/></svg>
        </div>
        <div class="sidebar-item menu-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-newspaper" viewBox="0 0 16 16"><path d="M0 2.5A1.5 1.5 0 0 1 1.5 1h11A1.5 1.5 0 0 1 14 2.5v10.528c0 .3-.05.654-.238.972h.738a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 1 1 0v9a1.5 1.5 0 0 1-1.5 1.5H1.497A1.497 1.497 0 0 1 0 13.5v-11zM12 14c.37 0 .654-.211.853-.441.092-.106.147-.279.147-.531V2.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5v11c0 .278.223.5.497.5H12z"/><path d="M2 3h10v2H2V3zm0 3h4v3H2V6zm0 4h4v1H2v-1zm0 2h4v1H2v-1zm5-6h2v1H7V6zm3 0h2v1h-2V6zM7 8h2v1H7V8zm3 0h2v1h-2V8zm-3 2h2v1H7v-1zm3 0h2v1h-2v-1zm-3 2h2v1H7v-1zm3 0h2v1h-2v-1z"/></svg>
        </div>
        <div class="sidebar-item menu-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16"><path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/></svg>
        </div>
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
                    <div class="matkul-card">
                        <div class="blob-hiasan <?= $matkul['warna'] ?>"></div>
                        <span><?= $matkul['nama'] ?></span>
                    </div>
                <?php endforeach; ?>
                
                <a href="#" class="ms-3 fw-bold text-decoration-none" style="color: #00a0e3;">See all ></a>
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
                        <div class="dl-box"></div>
                        <div class="dl-box"></div>
                    </div>
                </div>

                <div class="cal-right">
                    <div class="fw-bold mb-3" style="font-size: 0.8rem;">CALENDAR</div>
                    
                    <div class="d-flex justify-content-center gap-4 mb-4 text-center align-items-center">
                        <div id="btnPrev" class="fw-bold" style="cursor: pointer; opacity: 0.7; font-size: 1.2rem;">&lt;</div>
                        <div class="fw-bold" id="displayBulanTahun" style="min-width: 80px;">
                            </div>
                        <div id="btnNext" class="fw-bold" style="cursor: pointer; opacity: 0.7; font-size: 1.2rem;">&gt;</div>
                    </div>

                    <div class="cal-grid fw-bold" style="font-size: 0.85rem; padding-bottom: 10px;">
                        <div class="text-danger">SUN</div><div>MON</div><div>TUE</div><div>WED</div><div>THU</div><div>FRI</div><div>SAT</div>
                    </div>

                    <div class="cal-grid" id="wadahTanggal">
                        </div>
                </div>
                
            </div>

        </div>
    </div>
</div>

<script src="/Project-student-task-management-system/view/assets/js/dashboard.js"></script>

<?php
// ==============================================================
// 5. MEMANGGIL BAGIAN BAWAH (FOOTER & SCRIPT BOOTSTRAP)
// ==============================================================
include '../components/footer.php';
?>