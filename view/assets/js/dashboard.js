const wadahTanggal = document.getElementById('wadahTanggal');
const displayBulanTahun = document.getElementById('displayBulanTahun');
const btnPrev = document.getElementById('btnPrev');
const btnNext = document.getElementById('btnNext');

let currentDate = new Date(); 
let navMonth = currentDate.getMonth();
let navYear = currentDate.getFullYear();

const namaBulan = [
    "JANUARY", "FEBRUARY", "MARCH", "APRIL", 
    "MAY", "JUNE", "JULY", "AUGUST", 
    "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"
];

/* Kamus Hari Libur Nasional (Bulan-Tanggal) */
const hariLiburNasional = {
    "1-1": "Tahun Baru Masehi",
    "5-1": "Hari Buruh Internasional",
    "5-14": "Kenaikan Yesus Kristus",
    "5-27": "Hari Raya Idul Adha",
    "6-1": "Hari Lahir Pancasila",
    "8-17": "Hari Kemerdekaan RI",
    "12-25": "Hari Raya Natal"
};

function renderCalendar() {
    wadahTanggal.innerHTML = '';
    displayBulanTahun.innerText = `${namaBulan[navMonth]} ${navYear}`;

    const firstDay = new Date(navYear, navMonth, 1).getDay();
    const daysInMonth = new Date(navYear, navMonth + 1, 0).getDate();

    /* Pilihan warna untuk outline gradien dan titik tugas */
    const warnaPilihan = ['#4F46E5', '#7E52E8', '#EC4899'];
    const kelasTitik = ['dot-purple', 'dot-yellow', 'dot-green', 'dot-blue'];

    /* Memasukkan kotak kosong di awal bulan */
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'cal-cell empty';
        wadahTanggal.appendChild(emptyCell);
    }

    /* Memasukkan kotak angka tanggal aktif */
    for (let i = 1; i <= daysInMonth; i++) {
        const currentDayOfWeek = new Date(navYear, navMonth, i).getDay();
        const monthDayCheck = (navMonth + 1) + "-" + i;
        
        /* Cek tanggal hari ini */
        let isTodayClass = '';
        if (navYear === currentDate.getFullYear() && navMonth === currentDate.getMonth() && i === currentDate.getDate()) {
            isTodayClass = 'today';
        }

        /* Cek teks hari libur (Minggu/Nasional) atau hari Sabtu */
        let textClass = '';
        let titleText = '';
        if (currentDayOfWeek === 0 || hariLiburNasional[monthDayCheck]) {
            textClass = 'text-sun';
            if (hariLiburNasional[monthDayCheck]) {
                titleText = hariLiburNasional[monthDayCheck]; 
            }
        } else if (currentDayOfWeek === 6) {
            textClass = 'text-sat';
        }

        /* Menyiapkan elemen titik deadline jika ada tugas di tanggal ini */
        let dotsHTML = '';
        const dateString = `${navYear}-${(navMonth + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
        
        if (typeof dataNotesDB !== 'undefined') {
            const deadlinesOnThisDay = dataNotesDB.filter(note => {
                return note.deadline.split(' ')[0] === dateString; 
            }).length;

            if (deadlinesOnThisDay > 0) {
                for (let d = 0; d < deadlinesOnThisDay; d++) {
                    // Memilih warna titik secara bergantian (Ungu, Kuning, Hijau, Biru)
                    const warnaTitik = kelasTitik[d % kelasTitik.length];
                    dotsHTML += `<div class="dl-dot-new ${warnaTitik}"></div>`;
                }
            }
        }

        /* Hitung Keterlambatan Animasi (Surprise Wave Effect) */
        const delayAnimasi = (i * 0.03).toFixed(2);

        /* Menyusun struktur HTML kotak tanggal secara utuh */
        const cellHTML = `
            <div class="cal-cell ${isTodayClass} ${textClass}" 
                 style="animation-delay: ${delayAnimasi}s;" 
                 title="${titleText}">
                 
                <div class="event-dots-container">
                    ${dotsHTML}
                </div>
                
                <span class="date-num">${i}</span>
                
            </div>
        `;
        
        wadahTanggal.innerHTML += cellHTML;
    }
    
    /* Memasukkan kotak kosong tambahan di akhir bulan */
    const totalCells = firstDay + daysInMonth;
    const remainingCells = (totalCells % 7 === 0) ? 0 : 7 - (totalCells % 7);
    
    for(let i=0; i < remainingCells; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'cal-cell empty';
        wadahTanggal.appendChild(emptyCell);
    }

    /* Update daftar notes di bawah kalender */
    renderNotesList(navMonth, navYear);
}

/* Fungsi menampilkan list tugas di catatan bawah */
function renderNotesList(viewMonth, viewYear) {
    const container = document.getElementById('notesContainer');
    if (!container || typeof dataNotesDB === 'undefined') return;

    const filteredNotes = dataNotesDB.filter(note => {
        const dateObj = new Date(note.deadline);
        return dateObj.getMonth() === viewMonth && dateObj.getFullYear() === viewYear;
    });

    if (filteredNotes.length === 0) {
        container.innerHTML = '<div class="text-white opacity-75 small mt-3">Yeay! Tidak ada tugas untuk bulan ini.</div>';
        return;
    }

    let htmlContent = '';
    filteredNotes.forEach(note => {
        const dateObj = new Date(note.deadline);
        const day = String(dateObj.getDate()).padStart(2, '0'); 

        htmlContent += `
            <a href="detail_tugas.php?id=${note.id}" 
               style="display: flex; gap: 8px; margin-bottom: 12px; text-decoration: none; color: white; transition: transform 0.2s; width: 100%; box-sizing: border-box;" 
               onmouseover="this.style.transform='scale(1.02)'" 
               onmouseout="this.style.transform='scale(1)'">
                
                <div style="flex: 1; min-width: 0; display: flex; align-items: center; gap: 10px; background-color: rgba(255, 255, 255, 0.25); padding: 10px 12px; border-radius: 12px;">
                    <div style="width: 10px; height: 10px; background-color: #ff4d4f; border-radius: 50%; box-shadow: 0 0 5px rgba(255, 77, 79, 0.5); flex-shrink: 0;"></div>
                    <span style="font-weight: 700; font-size: 0.85rem; text-shadow: 0 1px 2px rgba(0,0,0,0.1); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; width: 100%;">
                        ${note.nama_matkul}
                    </span>
                </div>

                <div style="width: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background-color: rgba(255, 255, 255, 0.25); padding: 10px 0; border-radius: 12px; font-size: 1.1rem; font-weight: 900; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                    ${day}
                </div>
            </a>
        `;
    });

    container.innerHTML = htmlContent;
}

/* Event listener tombol panah navigasi bulan */
btnPrev.addEventListener('click', () => {
    navMonth--;
    if (navMonth < 0) { 
        navMonth = 11; 
        navYear--; 
    }
    renderCalendar();
});

btnNext.addEventListener('click', () => {
    navMonth++;
    if (navMonth > 11) { 
        navMonth = 0; 
        navYear++; 
    }
    renderCalendar();
});

/* Menjalankan fungsi render kalender saat halaman dimuat pertama kali */
document.addEventListener('DOMContentLoaded', () => {
    renderCalendar();
});