// ==============================================================
// LOGIKA KALENDER DENGAN UKURAN UTUH & PENANDA HARI INI
// ==============================================================

const namaBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
// Mengambil waktu real-time dari sistem
let tanggalReal = new Date();
let bulanIni = tanggalReal.getMonth(); 
let tahunIni = tanggalReal.getFullYear();

// Pembatasan navigasi waktu (Maksimal maju/mundur 1 Tahun)
const batasTahunBawah = tahunIni - 1;
const batasTahunAtas = tahunIni + 1;

// Data Simulasi Hari Libur & Tugas (Format: YYYY-M-D)
const dataLibur = [
    "2026-5-1",  
    "2026-5-14", 
    "2026-5-23"  
]; 

const dataTugas = [
    "2026-5-10", 
    "2026-5-15", 
    "2026-5-23"  
];

const wadahTanggal = document.getElementById('wadahTanggal');
const displayBulanTahun = document.getElementById('displayBulanTahun');

function renderKalender(bulan, tahun) {
    wadahTanggal.innerHTML = ''; 
    
    displayBulanTahun.innerHTML = `${namaBulan[bulan]}<br><small>${tahun}</small>`;

    const hariPertama = new Date(tahun, bulan, 1).getDay();
    const totalHari = new Date(tahun, bulan + 1, 0).getDate();

    const totalSlotKalender = 42;
    let slotTerhitung = 0;

    // 1. Cetak slot kosong awal
    for (let i = 0; i < hariPertama; i++) {
        wadahTanggal.innerHTML += `<div></div>`;
        slotTerhitung++;
    }

    // 2. Cetak tanggal aktif
    for (let i = 1; i <= totalHari; i++) {
        let kelasCSS = "date-circle";
        let formatCek = `${tahun}-${bulan + 1}-${i}`;
        let hariDalamSeminggu = new Date(tahun, bulan, i).getDay();
        let styleInline = ""; // Variabel khusus untuk warna kustom

        // ATURAN 1: Cek Hari Ini (Prioritas Utama)
        // Jika tanggal, bulan, dan tahun persis sama dengan hari ini
        if (i === tanggalReal.getDate() && bulan === tanggalReal.getMonth() && tahun === tanggalReal.getFullYear()) {
            kelasCSS += " text-dark font-weight-bold";
            styleInline = "background-color: #a8e6cf;"; // Hijau Pastel
        } 
        // ATURAN 2: Cek Hari Libur / Hari Minggu (Warna Pink)
        else if (hariDalamSeminggu === 0 || dataLibur.includes(formatCek)) {
            kelasCSS += " filled-pink text-white border-0";
        }

        // ATURAN 3: Cek Tugas (Garis Putih luar)
        // Ini tidak ditaruh di if/else supaya hari libur atau hari ini TETAP bisa ada tugasnya
        if (dataTugas.includes(formatCek)) {
            kelasCSS += " outline";
        }

        // Cetak elemen beserta kelas CSS dan style inline-nya
        wadahTanggal.innerHTML += `<div class="${kelasCSS}" style="${styleInline}">${i}</div>`;
        slotTerhitung++;
    }

    // 3. Cetak slot kosong akhir supaya tetap 42 kotak
    while (slotTerhitung < totalSlotKalender) {
        wadahTanggal.innerHTML += `<div></div>`;
        slotTerhitung++;
    }
}

// Navigasi Tombol Mundur (Prev)
document.getElementById('btnPrev').addEventListener('click', () => {
    if (tahunIni > batasTahunBawah || (tahunIni === batasTahunBawah && bulanIni > 0)) {
        bulanIni--;
        if (bulanIni < 0) {
            bulanIni = 11;
            tahunIni--;
        }
        renderKalender(bulanIni, tahunIni);
    } else {
        alert("Batas maksimal histori kalender adalah 1 tahun ke belakang.");
    }
});

// Navigasi Tombol Maju (Next)
document.getElementById('btnNext').addEventListener('click', () => {
    if (tahunIni < batasTahunAtas || (tahunIni === batasTahunAtas && bulanIni < 11)) {
        bulanIni++;
        if (bulanIni > 11) {
            bulanIni = 0;
            tahunIni++;
        }
        renderKalender(bulanIni, tahunIni);
    } else {
        alert("Batas maksimal jadwal kalender adalah 1 tahun ke depan.");
    }
});

// Eksekusi render pertama kali
renderKalender(bulanIni, tahunIni);

// Logika Sidebar Interaktif
const menuItems = document.querySelectorAll('.menu-item');
menuItems.forEach(item => {
    item.addEventListener('click', function() {
        menuItems.forEach(m => m.classList.remove('active'));
        this.classList.add('active');
    });
});