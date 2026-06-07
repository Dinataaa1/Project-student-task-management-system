// view/assets/js/admin/dashboard.js

// Membuka/Menutup Dropdown Menu (Titik Tiga)
function toggleMenu(event, element) {
    event.stopPropagation(); 
    const menu = element.nextElementSibling;
    
    document.querySelectorAll('.dropdown-menu').forEach(m => {
        if (m !== menu) m.classList.remove('show');
    });
    
    menu.classList.toggle('show');
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
    event.stopPropagation(); 

    // Ambil data dari atribut HTML di card (yang Anda set di dashboard.php)
    const card = element.closest('.card');
    const id = card.getAttribute('data-id');
    const matkul = card.getAttribute('data-matkul');
    const ruangan = card.getAttribute('data-ruangan');
    const jadwal = card.getAttribute('data-jadwal');

    // Isi data ke dalam Form Modal
    document.getElementById('inputMatkul').value = matkul;
    document.getElementById('inputKelas').value = ruangan;
    document.getElementById('inputJadwal').value = jadwal;
    document.getElementById('matkulId').value = id;
    
    // Setel teks dan kirim sinyal ke matkul_controler.php untuk Update
    document.getElementById('modalTitle').innerText = 'EDIT MATKUL';
    document.getElementById('formAction').value = 'edit_matkul';

    // Tampilkan Modal & Sembunyikan menu titik tiga
    document.getElementById('modalMatkul').style.display = 'flex';
    element.closest('.dropdown-menu').classList.remove('show');
}

// Menutup modal atau dropdown jika klik di area luar
window.onclick = function(event) {
    const modal = document.getElementById('modalMatkul');
    
    // Menutup Modal jika klik background abu-abu
    if (event.target === modal) {
        modal.style.display = "none";
    }
    
    // Menutup menu dropdown jika klik di luar ikon titik tiga
    if (!event.target.matches('.menu-icon')) {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
    }
}