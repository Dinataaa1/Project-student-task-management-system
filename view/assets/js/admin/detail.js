// view/assets/js/admin/detail.js

const modal = document.getElementById('modalTugas');
const form = document.getElementById('formTugas');
const gridContainer = document.querySelector('.grid-container');
let editingCard = null;

function bukaModalTugas() {
    editingCard = null;
    form.reset();
    document.getElementById('fileNameDisplay').innerText = "Belum ada file dipilih";
    modal.style.display = 'flex';
}

function tampilkanNamaFile(input) {
    const display = document.getElementById('fileNameDisplay');
    if (input.files && input.files.length > 0) display.innerText = input.files[0].name;
    else display.innerText = "Belum ada file dipilih";
}

function toggleMenu(event, element) {
    event.stopPropagation();
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (menu !== element.nextElementSibling) menu.classList.remove('show');
    });
    element.nextElementSibling.classList.toggle('show');
}

function hapusCard(event, element) {
    event.stopPropagation();
    if (confirm("Apakah Anda yakin ingin menghapus tugas ini?")) {
        element.closest('.card').remove();
    }
}

function editCard(event, element) {
    event.stopPropagation();
    editingCard = element.closest('.card');
    document.getElementById('inputJudulTugas').value = editingCard.querySelector('.tugas-text').innerText;
    element.parentElement.classList.remove('show');
    modal.style.display = 'flex';
}

window.onclick = function(event) {
    if (event.target == modal) modal.style.display = 'none';
    if (!event.target.matches('.menu-icon')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
    }
}

form.addEventListener('submit', function(e) {
    e.preventDefault(); 
    const judulVal = document.getElementById('inputJudulTugas').value;

    if (editingCard !== null) {
        editingCard.querySelector('.tugas-text').innerText = judulVal;
    } else {
        const kartuBaru = document.createElement('div');
        kartuBaru.className = 'card';
        kartuBaru.style.cursor = 'pointer';
        kartuBaru.onclick = function() { window.location.href = 'detail_tugas.php'; };
    }

    form.reset();
    document.getElementById('fileNameDisplay').innerText = "Belum ada file dipilih";
    modal.style.display = 'none';
});

// Logika Interaktif Dropdown Filter Matkul
const matkulFilter = document.getElementById('matkulFilter');
const namaMatkulText = document.getElementById('namaMatkulText');

if(matkulFilter && namaMatkulText) {
    matkulFilter.addEventListener('change', function() {
        const selectedText = this.options[this.selectedIndex].text;
        namaMatkulText.innerText = selectedText;
        
        namaMatkulText.style.opacity = 0;
        setTimeout(() => {
            namaMatkulText.style.opacity = 1;
            namaMatkulText.style.transition = "opacity 0.3s ease";
        }, 100);
    });
}