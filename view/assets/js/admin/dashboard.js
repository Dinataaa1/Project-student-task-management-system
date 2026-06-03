const modal = document.getElementById('modalMatkul');
const form = document.getElementById('formMatkul');
const gridContainer = document.querySelector('.grid-container');
let editingCard = null; 

function bukaModalAdd() {
    editingCard = null; 
    form.reset(); 
    modal.style.display = 'flex';
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
    if (confirm("Apakah Anda yakin ingin menghapus mata kuliah ini?")) {
        element.closest('.card').remove(); 
    }
}

function editCard(event, element) {
    event.stopPropagation();
    editingCard = element.closest('.card');
    
    document.getElementById('inputMatkul').value = editingCard.querySelector('.matkul-text').innerText;
    document.getElementById('inputKelas').value = editingCard.querySelector('.kelas-text').innerText;
    document.getElementById('inputJadwal').value = editingCard.querySelector('.jadwal-text').innerText;
    
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
    const matkulVal = document.getElementById('inputMatkul').value;
    const kelasVal = document.getElementById('inputKelas').value;
    const jadwalVal = document.getElementById('inputJadwal').value;

    if (editingCard !== null) {
        editingCard.querySelector('.matkul-text').innerText = matkulVal;
        editingCard.querySelector('.kelas-text').innerText = kelasVal;
        editingCard.querySelector('.jadwal-text').innerText = jadwalVal;
    } else {
        const isOrange = gridContainer.children.length % 2 === 0;
        const blobClass = isOrange ? 'orange' : 'blue';
        const kartuBaru = document.createElement('div');
        kartuBaru.className = 'card';
        kartuBaru.style.cursor = 'pointer';
        kartuBaru.onclick = function() { window.location.href = 'tugas/detail.php'; };

        kartuBaru.innerHTML = `
            <div class="blob ${blobClass}"></div>
            <div class="menu-container">
                <i class="fa-solid fa-ellipsis-vertical menu-icon" onclick="toggleMenu(event, this)"></i>
                <div class="dropdown-menu">
                    <a href="#" onclick="editCard(event, this)">Edit</a>
                    <a href="#" onclick="hapusCard(event, this)" class="text-danger">Hapus</a>
                </div>
            </div>
            <div class="card-info">
                <p class="kelas-text">${kelasVal}</p>
                <p class="jadwal-text">${jadwalVal}</p>
            </div>
            <div class="card-title matkul-text">${matkulVal}</div>
        `;
        gridContainer.appendChild(kartuBaru);
    }
    modal.style.display = 'none';
    form.reset();
});