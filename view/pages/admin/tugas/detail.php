<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas Mata Kuliah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset Dasar */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; height: 100vh; background-color: #fdfdfd; overflow: hidden; }

        /* Sidebar Kiri */
        .sidebar { 
            width: 80px; 
            background-color: #fff; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            padding-top: 20px; 
            border-right: 1px solid #eee;
            z-index: 10; 
        }
        .profile-area { text-align: center; margin-bottom: 30px; }
        .profile-area img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .profile-area p { font-size: 11px; font-weight: bold; margin-top: 5px; text-transform: uppercase; color: #000; }
        
        .nav-menu { display: flex; flex-direction: column; width: 100%; }
        .nav-item { 
            width: 100%; height: 60px; 
            display: flex; justify-content: center; align-items: center; 
            text-decoration: none; color: #009ef7; font-size: 22px; 
            transition: 0.2s; 
        }
        /* Sidebar Item yang Aktif (Berpindah ke icon tugas) */
        .nav-item.active { background-color: #009ef7; color: #fff; }
        .nav-item:hover:not(.active) { background-color: #f0f8ff; }

        /* Area Konten Utama */
        .main-content { flex: 1; display: flex; flex-direction: column; position: relative; }
        
        /* Header Atas */
        .header { 
            height: 60px; 
            background-color: #d8d8d8; 
            display: flex; align-items: center; 
            padding: 0 30px; 
        }
        .header h1 { font-size: 22px; color: #444; font-weight: 800; letter-spacing: 1px; margin: 0; }

        /* Area Scroll Konten */
        .content-area { padding: 40px; flex: 1; overflow-y: auto; position: relative; }
        .greeting { font-size: 18px; font-weight: 700; margin-bottom: 30px; color: #333; }

        /* Grid Kartu Tugas */
        .grid-container { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); 
            gap: 30px; 
            padding-bottom: 80px; /* Ruang ekstra di bawah agar kartu tidak tertutup tombol fixed */
        }
        .card { 
            background: #fff; 
            border-radius: 20px; 
            padding: 25px; 
            position: relative; 
            overflow: hidden; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.06); 
            min-height: 160px; 
            display: flex; 
            flex-direction: column; 
            justify-content: flex-end; /* Memposisikan teks di bagian bawah */
        }
        
        .card-title { font-size: 22px; font-weight: bold; color: #000; }

        /* Dekorasi Sudut Kartu (Hanya Oranye) */
        .blob { 
            position: absolute; 
            top: 0; right: 0; 
            width: 110px; height: 110px; 
            border-bottom-left-radius: 100%; 
            background: linear-gradient(135deg, #fde4c3 0%, #fad19b 100%);
        }

        /* Tombol Tambah Mengambang (FAB) */
        .fab { 
            position: fixed; 
            bottom: 40px; right: 40px; 
            width: 50px; height: 50px; 
            background-color: #0088ff; 
            color: white; 
            border-radius: 12px; 
            display: flex; justify-content: center; align-items: center; 
            font-size: 24px; 
            box-shadow: 0 4px 10px rgba(0, 136, 255, 0.4); 
            cursor: pointer; border: none; 
            transition: 0.2s; 
        }
        .fab:hover { background-color: #0066cc; transform: translateY(-3px); }

        /* Tombol Kembali (Back) */
        .btn-back {
            position: fixed;
            bottom: 40px;
            left: 120px; /* Offset dari sidebar (80px) + margin (40px) */
            width: 40px;
            height: 40px;
            background-color: #3b505c; /* Warna abu-abu kebiruan gelap */
            color: white;
            border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            font-size: 16px;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: 0.2s;
        }
        .btn-back:hover { background-color: #2a3a44; transform: scale(1.05); color: white; }

        /* --- Modal Styling --- */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            display: none; /* Sembunyikan default */
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #f4f5f7;
            width: 500px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Tombol Lampiran (File Input) */
        .btn-lampiran {
            background-color: #d8c6d4; /* Warna keunguan pudar sesuai gambar */
            color: #000;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
            transition: 0.2s;
        }
        .btn-lampiran:hover { background-color: #c9b5c5; }
        .file-name-display { font-size: 11px; color: #666; margin-bottom: 25px; min-height: 15px; }

        /* Area Input Text & Tanggal */
        .input-row {
            display: flex;
            align-items: center;
            width: 100%;
            border-bottom: 1px solid #333;
            margin-bottom: 20px;
            padding-bottom: 5px;
        }
        
        .input-row input[type="text"] {
            flex: 1;
            border: none;
            background-color: transparent;
            font-size: 16px;
            font-weight: bold;
            outline: none;
        }

        /* Trick untuk membuat input kalender (date) berupa icon */
        .date-wrapper {
            position: relative;
            width: 24px;
            height: 24px;
            cursor: pointer;
        }
        .date-wrapper i {
            font-size: 20px;
            color: #555;
            position: absolute;
            top: 0; left: 0;
            pointer-events: none; /* Biarkan klik tembus ke input date */
        }
        .date-wrapper input[type="date"] {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            opacity: 0; /* Sembunyikan input default, sisakan area kliknya saja */
            cursor: pointer;
        }

        /* Textarea untuk deskripsi */
        .input-desc {
            width: 100%;
            border: none;
            border-bottom: 1px solid #333;
            background-color: transparent;
            font-size: 13px;
            outline: none;
            resize: none;
            margin-bottom: 30px;
            font-family: inherit;
        }

        /* Tombol Submit */
        .btn-submit {
            background-color: #c62828;
            color: #fff;
            border: none;
            padding: 10px 40px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-submit:hover { background-color: #b71c1c; }

        /* --- Menu Titik Tiga --- */
        .menu-container {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 5;
        }
        .menu-icon {
            font-size: 20px;
            color: #333;
            cursor: pointer;
            padding: 5px;
            transition: 0.2s;
        }
        .menu-icon:hover { color: #000; }
        
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 25px;
            background: #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            width: 100px;
            overflow: hidden;
            z-index: 10;
        }
        .dropdown-menu.show { display: block; }
        .dropdown-menu a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            font-size: 13px;
            font-weight: bold;
        }
        .dropdown-menu a:hover { background: #f4f5f7; }
        .dropdown-menu a.text-danger { color: #c62828; }
    </style>
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