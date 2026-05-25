<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
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
        .nav-item.active { background-color: #009ef7; color: #fff; }
        .nav-item:hover:not(.active) { background-color: #f0f8ff; }

        /* Area Konten Utama */
        .main-content { flex: 1; display: flex; flex-direction: column; }
        
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
        .greeting { font-size: 20px; font-weight: 700; margin-bottom: 30px; color: #222; }

        /* Grid Kartu Mata Kuliah */
        .grid-container { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); 
            gap: 30px; 
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
            justify-content: space-between; 
        }
        
        .card-info p { font-size: 13px; color: #333; margin-bottom: 5px; }
        .card-title { font-size: 24px; font-weight: bold; color: #000; margin-top: 15px; }

        /* Dekorasi Sudut Kartu (Blob) */
        .blob { 
            position: absolute; 
            top: 0; right: 0; 
            width: 110px; height: 110px; 
            border-bottom-left-radius: 100%; 
        }
        .blob.orange { background: linear-gradient(135deg, #fde4c3 0%, #fad19b 100%); }
        .blob.blue { background: linear-gradient(135deg, #cde9fc 0%, #8acff3 100%); }

        /* Tombol Tambah Mengambang (FAB) */
        .fab { 
            position: fixed; 
            bottom: 40px; right: 40px; 
            width: 55px; height: 55px; 
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

        /* --- Modal Styling --- */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* Efek gelap transparan */
            display: none; /* Sembunyikan secara default */
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
        }

        /* Form Input Styling */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 5px;
            color: #000;
        }

        .form-group input {
            width: 100%;
            border: none;
            border-bottom: 1px solid #333; /* Hanya garis bawah */
            background-color: transparent;
            font-size: 16px;
            padding: 5px 0;
            outline: none;
        }

        .form-group input:focus {
            border-bottom: 2px solid #009ef7;
        }

        .btn-submit {
            background-color: #c62828; /* Merah sesuai desain */
            color: #fff;
            border: none;
            padding: 10px 40px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            display: block;
            margin: 35px auto 0;
            transition: 0.2s;
        }

        .btn-submit:hover {
            background-color: #b71c1c;
        }

        /* --- Menu Titik Tiga --- */
        .menu-container {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 5; /* Agar berada di atas dekorasi blob */
        }
        .menu-icon {
            font-size: 20px;
            color: #333;
            cursor: pointer;
            padding: 5px;
            transition: 0.2s;
        }
        .menu-icon:hover {
            color: #000;
        }
        .dropdown-menu {
            display: none; /* Disembunyikan secara default */
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
        .dropdown-menu.show { 
            display: block; 
        }
        .dropdown-menu a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            font-size: 13px;
            font-weight: bold;
        }
        .dropdown-menu a:hover { 
            background: #f4f5f7; 
        }
        .dropdown-menu a.text-danger { 
            color: #c62828; 
        }
    </style>
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