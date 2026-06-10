// view/assets/js/admin/detail.js

const modal = document.getElementById('modalTugas');
const form = document.getElementById('formTugas');

function toggleMenu(event, element) {
    event.stopPropagation();
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (menu !== element.nextElementSibling) menu.classList.remove('show');
    });
    element.nextElementSibling.classList.toggle('show');
}

function bukaModalTugas() {
    form.reset();
    document.getElementById('modalTitleTugas').innerText = 'TAMBAH TUGAS';
    document.getElementById('formActionTugas').value = 'create_tugas';
    document.getElementById('tugasId').value = '';
    modal.style.display = 'flex';
}

function tutupModalTugas() {
    modal.style.display = 'none';
}

function editTugas(event, element) {
    event.stopPropagation();
    const card = element.closest('.card');
    
    document.getElementById('tugasId').value = card.getAttribute('data-id');
    document.getElementById('inputMatkulId').value = card.getAttribute('data-matkul-id');
    document.getElementById('inputJudulTugas').value = card.getAttribute('data-judul');
    document.getElementById('inputDeskripsi').value = card.getAttribute('data-deskripsi');
    
    // Konversi YYYY-MM-DD HH:MM:SS ke YYYY-MM-DDTHH:MM (Format input datetime-local)
    let deadline = card.getAttribute('data-deadline');
    if (deadline) {
        document.getElementById('inputDeadline').value = deadline.replace(' ', 'T').slice(0, 16);
    }

    document.getElementById('modalTitleTugas').innerText = 'EDIT TUGAS';
    document.getElementById('formActionTugas').value = 'edit_tugas';
    
    modal.style.display = 'flex';
    element.closest('.dropdown-menu').classList.remove('show');
}

window.onclick = function(event) {
    // 1. Menutup Modal jika klik background overlay
    if (event.target === modal) {
        modal.style.display = 'none';
    }
    
    // 2. Menutup menu dropdown titik tiga jika klik di area kosong
    if (!event.target.matches('.menu-icon') && !event.target.closest('.dropdown-menu')) {
        document.querySelectorAll('.dropdown-menu').forEach(m => {
            m.style.display = 'none'; // Ini yang akan menutup menu Anda
            m.classList.remove('show'); // Tetap dibiarkan untuk berjaga-jaga
        });
    }
}

// Logika Dropdown Filter (Tetap dipertahankan)
const matkulFilter = document.getElementById('matkulFilter');
const namaMatkulText = document.getElementById('namaMatkulText');
if(matkulFilter && namaMatkulText) {
    matkulFilter.addEventListener('change', function() {
        namaMatkulText.innerText = this.options[this.selectedIndex].text;
    });
}