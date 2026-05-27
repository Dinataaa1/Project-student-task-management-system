<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil Dosen</title>
    <link rel="stylesheet" href="../../assets/css/pages/admin/setting.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="profile-area">
            <img src="https://ui-avatars.com/api/?name=Lulu&background=random&color=fff" alt="Profile">
            <p>Nama</p>
        </div>
        <div class="nav-menu">
            <a href="dashboard.php" class="nav-item"><i class="fa-solid fa-house"></i></a>
            <a href="tugas/detail.php" class="nav-item"><i class="fa-solid fa-address-card"></i></a>
            <a href="setting.php" class="nav-item active"><i class="fa-solid fa-gear"></i></a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>LOL</h1>
        </div>

        <div class="content-area">
            <h2 class="page-title">Profil Dosen</h2>
            
            <div class="profile-card">
                
                <div class="profile-header">
                    <div class="avatar-container">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=200" alt="Foto Profil">
                    </div>
                    <div class="profile-name-tag">
                        <h3>NAMA</h3>
                        <p>email</p>
                    </div>
                </div>

                <div class="profile-info-grid">
                    <div class="info-item">
                        <label>Nama Lengkap:</label>
                        <span>Nama Lengkap Dosen, S.T., M.T.</span>
                    </div>
                    <div class="info-item">
                        <label>Jabatan:</label>
                        <span>Dosen Pembimbing / Lektor</span>
                    </div>
                    <div class="info-item">
                        <label>NIP:</label>
                        <span>199501232026031001</span>
                    </div>
                    <div class="info-item">
                        <label>Email:</label>
                        <span>dosen@pens.ac.id</span>
                    </div>
                </div>

                <button class="btn-edit-profile">Edit Profil</button>
            </div>

            <a href="javascript:history.back()" class="btn-back">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>
    </div>
</body>
</html>