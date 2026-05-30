<?php
    // $active_page = 'dashboard';
    include_once '../../../config/koneksi.php'; 
    include '../../components/header.php';
?>

<body>
    <div class="sidebar">
        <div class="profile-area">
            <img src="https://ui-avatars.com/api/?name=Lulu&background=random&color=fff" alt="Profile">
            <p>Nama</p>
        </div>
        <div class="nav-menu">
            <a href="dashboard.php" class="nav-item active"><i class="fa-solid fa-house"></i></a>
            <a href="tugas/detail.php" class="nav-item"><i class="fa-solid fa-address-card"></i></a>
            <a href="setting.php" class="nav-item"><i class="fa-solid fa-gear"></i></a>
            <a href="../../../controllers/logout.php" class="nav-item logout-btn" onclick="return confirm('Apakah Anda yakin ingin keluar dari aplikasi?');">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>LOL</h1>
        </div>

        <div class="content-area">
            <h2 class="greeting">Hai, Lulu</h2>
            
            <div class="grid-container">
                <div class="card" onclick="window.location.href='tugas/detail.php'" style="cursor: pointer;">
                    <div class="blob orange"></div>
                    <div class="menu-container">
                        <i class="fa-solid fa-ellipsis-vertical menu-icon" onclick="toggleMenu(event, this)"></i>
                        <div class="dropdown-menu">
                            <a href="#" onclick="editCard(event, this)">Edit</a>
                            <a href="#" onclick="hapusCard(event, this)" class="text-danger">Hapus</a>
                        </div>
                    </div>
                    <div class="card-info">
                        <p class="kelas-text">Kelas</p>
                        <p class="jadwal-text">Jadwal</p>
                    </div>
                    <div class="card-title matkul-text">Matkul</div>
                </div>
                
                <div class="card" onclick="window.location.href='tugas/detail.php'" style="cursor: pointer;">
                    <div class="blob blue"></div>
                    <div class="menu-container">
                        <i class="fa-solid fa-ellipsis-vertical menu-icon" onclick="toggleMenu(event, this)"></i>
                        <div class="dropdown-menu">
                            <a href="#" onclick="editCard(event, this)">Edit</a>
                            <a href="#" onclick="hapusCard(event, this)" class="text-danger">Hapus</a>
                        </div>
                    </div>
                    <div class="card-info">
                        <p class="kelas-text">Kelas</p>
                        <p class="jadwal-text">Jadwal</p>
                    </div>
                    <div class="card-title matkul-text">Matkul</div>
                </div>
            </div>

            <button class="fab" onclick="bukaModalAdd()">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>

    <div id="modalMatkul" class="modal-overlay">
        <div class="modal-content">
            <form id="formMatkul">
                <div class="form-group">
                    <label for="inputMatkul">MATKUL</label>
                    <input type="text" id="inputMatkul" name="matkul" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="inputKelas">KELAS</label>
                    <input type="text" id="inputKelas" name="kelas" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="inputJadwal">JADWAL</label>
                    <input type="text" id="inputJadwal" name="jadwal" required autocomplete="off">
                </div>
                <button type="submit" class="btn-submit">SUBMIT</button>
            </form>
        </div>
    </div>

        <script>
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
        </script>
</body>
</html>