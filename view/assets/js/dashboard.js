// ==========================================================================
// INISIALISASI VARIABEL WAKTU DASAR
// ==========================================================================
const namaBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
let tanggalReal = new Date();
let bulanIni = tanggalReal.getMonth(); 
let tahunIni = tanggalReal.getFullYear();

// Membatasi jangkauan navigasi kalender (Maksimal 1 tahun mundur/maju)
const batasTahunBawah = tahunIni - 1;
const batasTahunAtas = tahunIni + 1;

// ==========================================================================
// PENERIMAAN DATA DARI DATABASE (VIA PHP)
// ==========================================================================
const dataLibur = [
    "2026-5-1",  
    "2026-5-14", 
    "2026-5-23"  
]; 

// Mengambil variabel global berisi array tanggal tugas dari file PHP
const dataTugas = typeof dataTugasDB !== 'undefined' ? dataTugasDB : [];

const wadahTanggal = document.getElementById('wadahTanggal');
const displayBulanTahun = document.getElementById('displayBulanTahun');

// ==========================================================================
// FUNGSI RENDER KALENDER
// ==========================================================================
function renderKalender(bulan, tahun) {
    wadahTanggal.innerHTML = ''; 
    
    // Menampilkan informasi bulan dan tahun di bagian tengah header kalender
    displayBulanTahun.innerHTML = `${namaBulan[bulan]}<br><small>${tahun}</small>`;

    // Mencari indeks hari permulaan bulan dan total hari dalam bulan tersebut
    const hariPertama = new Date(tahun, bulan, 1).getDay();
    const totalHari = new Date(tahun, bulan + 1, 0).getDate();

    // Menentukan total slot grid agar struktur kalender selalu konsisten (7 kolom x 6 baris)
    const totalSlotKalender = 42;
    let slotTerhitung = 0;

    // Mencetak slot kosong untuk penyesuaian hari pertama
    for (let i = 0; i < hariPertama; i++) {
        wadahTanggal.innerHTML += `<div></div>`;
        slotTerhitung++;
    }

    // Mencetak elemen blok tanggal beserta aturan penanda warnanya
    for (let i = 1; i <= totalHari; i++) {
        let kelasCSS = "date-circle"; 
        let formatCek = `${tahun}-${bulan + 1}-${i}`;
        let hariDalamSeminggu = new Date(tahun, bulan, i).getDay();

        // Aturan 1: Memberikan penanda jika tanggal bertepatan dengan waktu saat ini
        if (i === tanggalReal.getDate() && bulan === tanggalReal.getMonth() && tahun === tanggalReal.getFullYear()) {
            kelasCSS += " hari-ini"; 
        } 
        // Aturan 2: Memberikan penanda jika bertepatan dengan hari libur nasional atau akhir pekan (Minggu)
        else if (hariDalamSeminggu === 0 || dataLibur.includes(formatCek)) {
            kelasCSS += " hari-libur";
        }

        // Aturan 3: Memberikan garis luar putih jika mahasiswa memiliki batas waktu tugas pada tanggal tersebut
        if (dataTugas.includes(formatCek)) {
            kelasCSS += " ada-tugas";
        }

        wadahTanggal.innerHTML += `<div class="${kelasCSS}">${i}</div>`;
        slotTerhitung++;
    }

    // Menggenapkan sisa slot matriks agar tinggi kalender tidak berubah dinamis
    while (slotTerhitung < totalSlotKalender) {
        wadahTanggal.innerHTML += `<div></div>`;
        slotTerhitung++;
    }
}

// ==========================================================================
// KONTROL NAVIGASI KALENDER MUNDUR DAN MAJU
// ==========================================================================
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

// Menjalankan fungsi penyusunan kalender pertama kali saat DOM dimuat
renderKalender(bulanIni, tahunIni);