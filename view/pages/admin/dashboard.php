<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <link rel="stylesheet" href="../../assets/css/pages/admin/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
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

        <script src="../../assets/js/admin/dashboard.js"></script>
</body>
</html>