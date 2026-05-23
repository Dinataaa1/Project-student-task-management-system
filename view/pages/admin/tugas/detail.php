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
                <div class="card">
                    <div class="blob"></div>
                    <div class="card-title">Tugas 1</div>
                </div>
                <div class="card">
                    <div class="blob"></div>
                    <div class="card-title">Tugas 2</div>
                </div>
                <div class="card">
                    <div class="blob"></div>
                    <div class="card-title">Tugas</div>
                </div>
                <div class="card">
                    <div class="blob"></div>
                    <div class="card-title">Tugas</div>
                </div>
                <div class="card">
                    <div class="blob"></div>
                    <div class="card-title">Tugas</div>
                </div>
                <div class="card">
                    <div class="blob"></div>
                    <div class="card-title">Tugas</div>
                </div>
            </div>

            <a href="../dashboard.php" class="btn-back">
                <i class="fa-solid fa-chevron-left"></i>
            </a>

            <button class="fab" onclick="window.location.href='create.php'">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>
</body>
</html>