// Membuka/Menutup Dropdown Menu (Titik Tiga)
function toggleMenu(event, element) {
    event.stopPropagation(); 
    const menu = element.nextElementSibling;
    
    // Tutup semua menu lain yang sedang terbuka
    document.querySelectorAll('.dropdown-menu').forEach(m => {
        if (m !== menu) m.style.display = 'none';
    });
    
    // Buka/tutup menu yang diklik
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

// Buka Modal untuk Tambah Data Baru
function bukaModalAdd() {
    document.getElementById('modalMatkul').style.display = 'flex';
    document.getElementById('formMatkul').reset(); 
    
    // Setel ulang teks dan aksi untuk Create
    document.getElementById('modalTitle').innerText = 'TAMBAH MATKUL';
    document.getElementById('formAction').value = 'create_matkul'; 
    document.getElementById('matkulId').value = ''; 
}

// Buka Modal untuk Edit Data 
function editCard(event, element) {
    event.preventDefault(); // Mencegah halaman melompat ke atas karena href="#"
    event.stopPropagation(); 

    // Ambil data dari atribut HTML di card (data-*)
    const card = element.closest('.card');
    const id = card.getAttribute('data-id');
    const matkul = card.getAttribute('data-matkul');
    const kelasId = card.getAttribute('data-kelas-id'); // Mengambil ID Kelas
    const ruangan = card.getAttribute('data-ruangan');
    const jadwal = card.getAttribute('data-jadwal');

    // Isi data teks & dropdown ke dalam Form Modal
    // Memastikan nilai tidak error jika kosong (null)
    document.getElementById('inputMatkul').value = matkul || '';
    document.getElementById('selectKelas').value = kelasId || ''; 
    document.getElementById('inputRuangan').value = ruangan || '';
    
    // Memisahkan jadwal (misal: "Kamis, 13:00") menjadi form Hari dan Jam
    if(jadwal) {
        let pisahJadwal = jadwal.split(', ');
        if(pisahJadwal.length === 2) {
            document.querySelector('select[name="hari"]').value = pisahJadwal[0];
            document.querySelector('input[name="jam"]').value = pisahJadwal[1];
        }
    }

    document.getElementById('matkulId').value = id;
    
    // Setel teks dan kirim sinyal ke matkul_controler.php untuk Update
    document.getElementById('modalTitle').innerText = 'EDIT MATKUL';
    document.getElementById('formAction').value = 'edit_matkul';

    // Tampilkan Modal & Sembunyikan menu titik tiga
    document.getElementById('modalMatkul').style.display = 'flex';
    element.closest('.dropdown-menu').style.display = 'none';
}

// Menutup modal atau dropdown jika klik di area luar
window.onclick = function(event) {
    const modal = document.getElementById('modalMatkul');
    
    // Menutup Modal jika klik background overlay
    if (event.target === modal) {
        modal.style.display = "none";
    }
    
    // Menutup menu dropdown jika klik di luar ikon titik tiga atau kotak menu
    if (!event.target.matches('.menu-icon') && !event.target.closest('.dropdown-menu')) {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
    }
}