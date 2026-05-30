const wadahTanggal = document.getElementById('wadahTanggal');
const displayBulanTahun = document.getElementById('displayBulanTahun');
const btnPrev = document.getElementById('btnPrev');
const btnNext = document.getElementById('btnNext');

let currentDate = new Date(); 
let navMonth = currentDate.getMonth();
let navYear = currentDate.getFullYear();

const namaBulan = ["JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"];

// =====================================================================
// KAMUS HARI LIBUR NASIONAL (Format: "Bulan-Tanggal")
// =====================================================================
const hariLiburNasional = {
    "1-1": "Tahun Baru Masehi",
    "5-1": "Hari Buruh Internasional",
    "5-14": "Kenaikan Yesus Kristus",
    "5-27": "Hari Raya Idul Adha",
    "6-1": "Hari Lahir Pancasila",
    "8-17": "Hari Kemerdekaan RI",
    "12-25": "Hari Raya Natal"
    // Kamu bisa tambahkan tanggal libur lainnya di sini!
};

function renderCalendar() {
    wadahTanggal.innerHTML = '';
    displayBulanTahun.innerText = `${namaBulan[navMonth]} ${navYear}`;

    const firstDay = new Date(navYear, navMonth, 1).getDay();
    const daysInMonth = new Date(navYear, navMonth + 1, 0).getDate();

    // 1. Memasukkan kotak kosong di awal (Mundur)
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.classList.add('cal-cell');
        wadahTanggal.appendChild(emptyCell);
    }

    // 2. Memasukkan kotak angka tanggal
    for (let i = 1; i <= daysInMonth; i++) {
        const cell = document.createElement('div');
        cell.classList.add('cal-cell');
        
        const currentDayOfWeek = new Date(navYear, navMonth, i).getDay();
        const monthDayCheck = (navMonth + 1) + "-" + i; // Contoh hasil: "5-14" atau "6-1"

        // CEK HARI LIBUR & HARI MINGGU (Warna Merah)
        if (currentDayOfWeek === 0 || hariLiburNasional[monthDayCheck]) {
            cell.classList.add('text-sun');
            // Menambahkan tooltip agar nama hari libur muncul saat kursor diarahkan ke tanggal
            if (hariLiburNasional[monthDayCheck]) {
                cell.title = hariLiburNasional[monthDayCheck]; 
            }
        } 
        // CEK HARI SABTU (Warna Biru)
        else if (currentDayOfWeek === 6) {
            cell.classList.add('text-sat');
        }

        // CEK TANGGAL HARI INI (Warna Teks Hijau - Menimpa warna lain)
        if (navYear === currentDate.getFullYear() && navMonth === currentDate.getMonth() && i === currentDate.getDate()) {
            cell.classList.remove('text-sun');
            cell.classList.remove('text-sat');
            cell.classList.add('text-today');
            cell.title = "Hari Ini";
        }

        cell.innerText = i;

        // CEK TUGAS DEADLINE (Titik Merah)
        const dateString = `${navYear}-${navMonth + 1}-${i}`;
        const deadlinesOnThisDay = dataTugasDB.filter(d => d === dateString).length;

        if (deadlinesOnThisDay > 0) {
            const dotsContainer = document.createElement('div');
            dotsContainer.classList.add('dots-container');
            
            // Mencetak titik sesuai jumlah tugas di hari tersebut (Contoh: Struktur Data ada 2 tugas = 2 titik merah)
            for (let d = 0; d < deadlinesOnThisDay; d++) {
                const dot = document.createElement('div');
                dot.classList.add('dot');
                dotsContainer.appendChild(dot);
            }
            cell.appendChild(dotsContainer);
        }

        wadahTanggal.appendChild(cell);
    }
    
    // 3. Memasukkan kotak kosong tambahan di akhir agar formasi bergaris rapi
    const totalCells = firstDay + daysInMonth;
    const remainingCells = (totalCells % 7 === 0) ? 0 : 7 - (totalCells % 7);
    for(let i=0; i < remainingCells; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.classList.add('cal-cell');
        wadahTanggal.appendChild(emptyCell);
    }
}

// Navigasi Bulan Mundur
btnPrev.addEventListener('click', () => {
    navMonth--;
    if (navMonth < 0) { navMonth = 11; navYear--; }
    renderCalendar();
});

// Navigasi Bulan Maju
btnNext.addEventListener('click', () => {
    navMonth++;
    if (navMonth > 11) { navMonth = 0; navYear++; }
    renderCalendar();
});

// Jalankan saat pertama kali halaman dimuat
renderCalendar();