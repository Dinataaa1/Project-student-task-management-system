<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas Mata Kuliah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/pages/admin/detail.css">
</head>
<body>
    <div class="sidebar">
        <div class="profile-area">
            <img src="https://ui-avatars.com/api/?name=Lulu&background=random&color=fff" alt="Profile">
            <p>Nama</p>
        </div>
        <div class="nav-menu">
            <a href="../dashboard.php" class="nav-item"><i class="fa-solid fa-house"></i></a>
            <a href="#" class="nav-item active"><i class="fa-solid fa-address-card"></i></a>
            <a href="#" class="nav-item"><i class="fa-solid fa-gear"></i></a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>LOL</h1>
        </div>

        <div class="content-area">
            <h2 class="greeting">Hai, Lulu! Ini adalah kelas 1 D4 IT C</h2>
            
            <div class="grid-container">
                <div class="card" onclick="window.location.href='detail_tugas.php'" style="cursor: pointer;">
                    <div class="blob"></div>
                    
                    <div class="menu-container">
                        <i class="fa-solid fa-ellipsis-vertical menu-icon" onclick="toggleMenu(event, this)"></i>
                        <div class="dropdown-menu">
                            <a href="#" onclick="editCard(event, this)">Edit</a>
                            <a href="#" onclick="hapusCard(event, this)" class="text-danger">Hapus</a>
                        </div>
                    </div>
                    
                    <div class="card-title tugas-text">Tugas 1</div>
                </div>
                <div class="card" onclick="window.location.href='detail_tugas.php'" style="cursor: pointer;">
                    <div class="blob"></div>
                    
                    <div class="menu-container">
                        <i class="fa-solid fa-ellipsis-vertical menu-icon" onclick="toggleMenu(event, this)"></i>
                        <div class="dropdown-menu">
                            <a href="#" onclick="editCard(event, this)">Edit</a>
                            <a href="#" onclick="hapusCard(event, this)" class="text-danger">Hapus</a>
                        </div>
                    </div>
                    
                    <div class="card-title tugas-text">Tugas 2</div>
                </div>
                <div class="card" onclick="window.location.href='detail_tugas.php'" style="cursor: pointer;">
                    <div class="blob"></div>
                    
                    <div class="menu-container">
                        <i class="fa-solid fa-ellipsis-vertical menu-icon" onclick="toggleMenu(event, this)"></i>
                        <div class="dropdown-menu">
                            <a href="#" onclick="editCard(event, this)">Edit</a>
                            <a href="#" onclick="hapusCard(event, this)" class="text-danger">Hapus</a>
                        </div>
                    </div>
                    
                    <div class="card-title tugas-text">Tugas 3</div>
                </div>
                <div class="card" onclick="window.location.href='detail_tugas.php'" style="cursor: pointer;">
                    <div class="blob"></div>
                    
                    <div class="menu-container">
                        <i class="fa-solid fa-ellipsis-vertical menu-icon" onclick="toggleMenu(event, this)"></i>
                        <div class="dropdown-menu">
                            <a href="#" onclick="editCard(event, this)">Edit</a>
                            <a href="#" onclick="hapusCard(event, this)" class="text-danger">Hapus</a>
                        </div>
                    </div>
                    
                    <div class="card-title tugas-text">Tugas 4</div>
                </div>
                <div class="card" onclick="window.location.href='detail_tugas.php'" style="cursor: pointer;">
                    <div class="blob"></div>
                    
                    <div class="menu-container">
                        <i class="fa-solid fa-ellipsis-vertical menu-icon" onclick="toggleMenu(event, this)"></i>
                        <div class="dropdown-menu">
                            <a href="#" onclick="editCard(event, this)">Edit</a>
                            <a href="#" onclick="hapusCard(event, this)" class="text-danger">Hapus</a>
                        </div>
                    </div>
                    
                    <div class="card-title tugas-text">Tugas 5</div>
                </div>
                <div class="card" onclick="window.location.href='detail_tugas.php'" style="cursor: pointer;">
                    <div class="blob"></div>
                    
                    <div class="menu-container">
                        <i class="fa-solid fa-ellipsis-vertical menu-icon" onclick="toggleMenu(event, this)"></i>
                        <div class="dropdown-menu">
                            <a href="#" onclick="editCard(event, this)">Edit</a>
                            <a href="#" onclick="hapusCard(event, this)" class="text-danger">Hapus</a>
                        </div>
                    </div>
                    
                    <div class="card-title tugas-text">Tugas 6</div>
                </div>
            </div>

            <a href="../dashboard.php" class="btn-back">
                <i class="fa-solid fa-chevron-left"></i>
            </a>

            <button class="fab" onclick="bukaModalTugas()">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>
    <div id="modalTugas" class="modal-overlay">
        <div class="modal-content">
            <form id="formTugas" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                
                <label class="btn-lampiran">
                    <i class="fa-solid fa-chevron-up"></i> Lampiran
                    <input type="file" id="inputFile" style="display: none;" onchange="tampilkanNamaFile(this)">
                </label>
                <span id="fileNameDisplay" class="file-name-display">Belum ada file dipilih</span>

                <div class="input-row">
                    <input type="text" id="inputJudulTugas" placeholder="Judul Tugas" required autocomplete="off">
                    
                    <div class="date-wrapper" title="Pilih Deadline">
                        <i class="fa-regular fa-calendar"></i>
                        <input type="date" id="inputDeadline" required>
                    </div>
                </div>

                <textarea id="inputDeskripsi" class="input-desc" rows="3" placeholder="Deskripsi Tugas" required></textarea>

                <button type="submit" class="btn-submit">SUBMIT</button>
            </form>
        </div>
    </div>
    <script>
        const modal = document.getElementById('modalTugas');
        const form = document.getElementById('formTugas');
        const gridContainer = document.querySelector('.grid-container');
        
        let editingCard = null; // Variabel penanda mode edit

        // 1. Fungsi buka modal untuk TAMBAH tugas baru
        function bukaModalTugas() {
            editingCard = null; // Reset penanda
            form.reset();
            document.getElementById('fileNameDisplay').innerText = "Belum ada file dipilih";
            modal.style.display = 'flex';
        }

        // Fungsi menampilkan nama file
        function tampilkanNamaFile(input) {
            const display = document.getElementById('fileNameDisplay');
            if (input.files && input.files.length > 0) {
                display.innerText = input.files[0].name;
            } else {
                display.innerText = "Belum ada file dipilih";
            }
        }

        // 2. Fungsi buka/tutup menu titik tiga
        function toggleMenu(event, element) {
            event.stopPropagation(); // Cegah pindah halaman
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== element.nextElementSibling) menu.classList.remove('show');
            });
            element.nextElementSibling.classList.toggle('show');
        }

        // 3. Fungsi Hapus Tugas
        function hapusCard(event, element) {
            event.stopPropagation();
            if (confirm("Apakah Anda yakin ingin menghapus tugas ini?")) {
                element.closest('.card').remove();
            }
        }

        // 4. Fungsi Edit Tugas
        function editCard(event, element) {
            event.stopPropagation();
            editingCard = element.closest('.card');
            
            // Ambil judul dari kartu dan masukkan ke input form modal
            document.getElementById('inputJudulTugas').value = editingCard.querySelector('.tugas-text').innerText;
            
            // Tutup menu dan buka modal
            element.parentElement.classList.remove('show');
            modal.style.display = 'flex';
        }

        // 5. Menutup modal atau dropdown saat klik area luar
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
            if (!event.target.matches('.menu-icon')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
            }
        }

        // 6. Aksi Submit Form (Menangani Tambah & Edit)
        form.addEventListener('submit', function(e) {
            e.preventDefault(); 
            const judulVal = document.getElementById('inputJudulTugas').value;

            if (editingCard !== null) {
                // JIKA MODE EDIT
                editingCard.querySelector('.tugas-text').innerText = judulVal;
            } else {
                // JIKA MODE TAMBAH BARU
                const kartuBaru = document.createElement('div');
                kartuBaru.className = 'card';
                kartuBaru.style.cursor = 'pointer';
                kartuBaru.onclick = function() { window.location.href = 'detail_tugas.php'; };
                
                // Struktur kartu baru dengan titik tiga
                kartuBaru.innerHTML = `
                    <div class="blob"></div>
                    <div class="menu-container">
                        <i class="fa-solid fa-ellipsis-vertical menu-icon" onclick="toggleMenu(event, this)"></i>
                        <div class="dropdown-menu">
                            <a href="#" onclick="editCard(event, this)">Edit</a>
                            <a href="#" onclick="hapusCard(event, this)" class="text-danger">Hapus</a>
                        </div>
                    </div>
                    <div class="card-title tugas-text">${judulVal}</div>
                `;
                gridContainer.appendChild(kartuBaru);
            }

            // Reset dan tutup modal
            form.reset();
            document.getElementById('fileNameDisplay').innerText = "Belum ada file dipilih";
            modal.style.display = 'none';
        });
    </script>
</body>
</html>