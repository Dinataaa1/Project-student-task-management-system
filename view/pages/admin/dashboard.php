<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/pages/admin/dashboard.css">
</head>
<body>
    <div class="sidebar">
        <div class="profile-area">
            <img src="https://ui-avatars.com/api/?name=Lulu&background=random&color=fff" alt="Profile">
            <p>Nama</p>
        </div>
        <div class="nav-menu">
            <a href="dashboard.php" class="nav-item active"><i class="fa-solid fa-house"></i></a>
            <a href="tugas/index.php" class="nav-item"><i class="fa-solid fa-list-check"></i></a>
            <a href="#" class="nav-item"><i class="fa-solid fa-gear"></i></a>
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
        
        let editingCard = null; // Variabel penanda apakah sedang mode edit atau tidak

        // 1. Fungsi buka modal untuk TAMBAH matkul
        function bukaModalAdd() {
            editingCard = null; // Reset penanda edit
            form.reset(); // Kosongkan isi form
            modal.style.display = 'flex';
        }

        // 2. Fungsi buka/tutup dropdown menu titik tiga
        function toggleMenu(event, element) {
            event.stopPropagation(); // MENCEGAH agar kartu tidak diklik (tidak pindah halaman)
            
            // Tutup semua menu lain yang mungkin sedang terbuka
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== element.nextElementSibling) menu.classList.remove('show');
            });
            
            // Munculkan menu pada titik tiga yang diklik
            element.nextElementSibling.classList.toggle('show');
        }

        // 3. Fungsi Hapus
        function hapusCard(event, element) {
            event.stopPropagation();
            // Munculkan pesan verifikasi
            if (confirm("Apakah Anda yakin ingin menghapus mata kuliah ini?")) {
                element.closest('.card').remove(); // Hapus elemen kartu dari layar
            }
        }

        // 4. Fungsi Edit
        function editCard(event, element) {
            event.stopPropagation();
            
            // Tandai kartu mana yang sedang mau diedit
            editingCard = element.closest('.card');
            
            // Ambil teks dari kartu dan masukkan ke dalam form modal
            document.getElementById('inputMatkul').value = editingCard.querySelector('.matkul-text').innerText;
            document.getElementById('inputKelas').value = editingCard.querySelector('.kelas-text').innerText;
            document.getElementById('inputJadwal').value = editingCard.querySelector('.jadwal-text').innerText;
            
            // Sembunyikan menu titik tiga, lalu buka modal
            element.parentElement.classList.remove('show');
            modal.style.display = 'flex';
        }

        // 5. Menutup modal jika klik di area gelap atau menutup menu jika klik di sembarang tempat
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
            // Tutup dropdown jika mengklik area lain di layar
            if (!event.target.matches('.menu-icon')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
            }
        }

        // 6. Aksi saat tombol SUBMIT ditekan
        form.addEventListener('submit', function(e) {
            e.preventDefault(); 

            const matkulVal = document.getElementById('inputMatkul').value;
            const kelasVal = document.getElementById('inputKelas').value;
            const jadwalVal = document.getElementById('inputJadwal').value;

            if (editingCard !== null) {
                // JIKA MODE EDIT: Update teks di kartu yang sedang diedit
                editingCard.querySelector('.matkul-text').innerText = matkulVal;
                editingCard.querySelector('.kelas-text').innerText = kelasVal;
                editingCard.querySelector('.jadwal-text').innerText = jadwalVal;
            } else {
                // JIKA MODE TAMBAH BARU: Buat elemen kartu baru dari nol
                const isOrange = gridContainer.children.length % 2 === 0;
                const blobClass = isOrange ? 'orange' : 'blue';

                const kartuBaru = document.createElement('div');
                kartuBaru.className = 'card';
                kartuBaru.style.cursor = 'pointer';
                kartuBaru.onclick = function() { window.location.href = 'tugas/detail.php'; };

                // Struktur HTML kartu baru harus sama persis dengan yang asli (termasuk titik tiga)
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