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
        const monthDayCheck = (navMonth + 1) + "-" + i;

        // CEK HARI LIBUR & HARI MINGGU
        if (currentDayOfWeek === 0 || hariLiburNasional[monthDayCheck]) {
            cell.classList.add('text-sun');
            if (hariLiburNasional[monthDayCheck]) {
                cell.title = hariLiburNasional[monthDayCheck]; 
            }
        } 
        // CEK HARI SABTU
        else if (currentDayOfWeek === 6) {
            cell.classList.add('text-sat');
        }

        // ===============================================================
        // PERUBAHAN: Membuat Wadah Angka Tanggal agar bisa dibikin bulat
        // ===============================================================
        const dateNum = document.createElement('div');
        dateNum.innerText = i;
        dateNum.style.width = '32px';
        dateNum.style.height = '32px';
        dateNum.style.display = 'flex';
        dateNum.style.alignItems = 'center';
        dateNum.style.justifyContent = 'center';
        dateNum.style.margin = '0 auto'; // Pusatkan ke tengah sel
        dateNum.style.borderRadius = '50%';
        
        // CEK TANGGAL HARI INI (Lingkaran Hijau, Teks Putih)
        if (navYear === currentDate.getFullYear() && navMonth === currentDate.getMonth() && i === currentDate.getDate()) {
            cell.classList.remove('text-sun');
            cell.classList.remove('text-sat');
            
            dateNum.style.backgroundColor = '#28a745'; // Warna hijau
            dateNum.style.color = '#ffffff';          // Angka warna putih
            dateNum.style.fontWeight = 'bold';
            cell.title = "Hari Ini";
        }
        
        // Masukkan angka (beserta lingkarannya) ke dalam sel
        cell.appendChild(dateNum);

        // CEK TUGAS DEADLINE (Titik Merah)
        const dateString = `${navYear}-${(navMonth + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
        
        // Kita gunakan dataNotesDB karena sudah terbukti akurat memuat seluruh tugas
        if (typeof dataNotesDB !== 'undefined') {
            const deadlinesOnThisDay = dataNotesDB.filter(note => {
                // Mengambil bagian "YYYY-MM-DD" dari string "YYYY-MM-DD HH:MM:SS"
                return note.deadline.split(' ')[0] === dateString; 
            }).length;

            if (deadlinesOnThisDay > 0) {
                const dotsContainer = document.createElement('div');
                // Styling paksa agar berjajar rapi di tengah bawah angka
                dotsContainer.style.display = 'flex';
                dotsContainer.style.justifyContent = 'center';
                dotsContainer.style.gap = '3px';
                dotsContainer.style.marginTop = '4px';
                
                // Buat titik merah sejumlah tugas di hari itu
                for (let d = 0; d < deadlinesOnThisDay; d++) {
                    const dot = document.createElement('div');
                    dot.style.width = '6px';
                    dot.style.height = '6px';
                    dot.style.backgroundColor = '#ff4d4f';
                    dot.style.borderRadius = '50%';
                    dotsContainer.appendChild(dot);
                }
                cell.appendChild(dotsContainer);
            }
        }

        wadahTanggal.appendChild(cell);
    }
    
    // 3. Memasukkan kotak kosong tambahan di akhir
    const totalCells = firstDay + daysInMonth;
    const remainingCells = (totalCells % 7 === 0) ? 0 : 7 - (totalCells % 7);
    for(let i=0; i < remainingCells; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.classList.add('cal-cell');
        wadahTanggal.appendChild(emptyCell);
    }

    // 4. Update daftar notes
    renderNotesList(navMonth, navYear);
}

// Fungsi untuk memfilter dan menampilkan Notes berdasarkan bulan kalender
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

        // ===============================================================
        // PERUBAHAN: Membagi desain menjadi 2 Card dengan ukuran FIX (Anti Lubèr)
        // ===============================================================
        htmlContent += `
            <a href="detail_tugas.php?id=${note.id}" style="display: flex; gap: 8px; margin-bottom: 12px; text-decoration: none; color: white; transition: transform 0.2s; width: 100%; box-sizing: border-box;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                
                <div style="flex: 1; min-width: 0; display: flex; align-items: center; gap: 10px; background-color: rgba(255, 255, 255, 0.25); padding: 10px 12px; border-radius: 12px;">
                    
                    <div style="width: 10px; height: 10px; background-color: #ff4d4f; border-radius: 50%; box-shadow: 0 0 5px rgba(255, 77, 79, 0.5); flex-shrink: 0;"></div>
                    
                    <span style="font-weight: 700; font-size: 0.85rem; text-shadow: 0 1px 2px rgba(0,0,0,0.1); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; width: 100%;">${note.nama_matkul}</span>
                </div>

                <div style="width: 48px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background-color: rgba(255, 255, 255, 0.25); padding: 10px 0; border-radius: 12px; font-size: 1.1rem; font-weight: 900; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                    ${day}
                </div>
                
            </a>
        `;
    });

    container.innerHTML = htmlContent;
}

// Navigasi Bulan
btnPrev.addEventListener('click', () => {
    navMonth--;
    if (navMonth < 0) { navMonth = 11; navYear--; }
    renderCalendar();
});

btnNext.addEventListener('click', () => {
    navMonth++;
    if (navMonth > 11) { navMonth = 0; navYear++; }
    renderCalendar();
});

document.addEventListener('DOMContentLoaded', () => {
    renderCalendar();
});

