<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tugas & Penilaian</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/pages/admin/detail_tugas.css?v=3">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="profile-area">
            <img src="https://ui-avatars.com/api/?name=Lulu&background=random&color=fff" alt="Profile">
            <p>Nama</p>
        </div>
        <div class="nav-menu">
            <a href="../dashboard.php" class="nav-item"><i class="fa-solid fa-house"></i></a>
            <a href="detail.php" class="nav-item active"><i class="fa-solid fa-address-card"></i></a>
            <a href="../setting.php" class="nav-item"><i class="fa-solid fa-gear"></i></a>
            <a href="../../../../controllers/logout.php" class="nav-item logout-btn" onclick="return confirm('Apakah Anda yakin ingin keluar dari aplikasi?');">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>LOL</h1>
        </div>

        <div class="content-area">
            <h2 class="page-title">Tugas 1</h2>
            
            <div class="task-detail-card">
                <div class="blob-large"></div>
                <div class="task-desc">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </div>
                <div class="task-footer">
                    <a href="#" class="btn-lampiran-view">Lampiran</a>
                    <span class="deadline-text">Deadline</span>
                </div>
            </div>

            <div class="table-container">
                <form action="" method="POST">
                    <table>
                        <thead>
                            <tr>
                                <th>NRP</th>
                                <th>Nama</th>
                                <th>Lampiran</th>
                                <th>Nilai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>3125600075</td>
                                <td>Lulu'atul Mahfudoh</td>
                                <td><a href="#" class="btn-lihat"><i class="fa-regular fa-eye"></i> Lihat</a></td>
                                <td>
                                    <div class="nilai-wrapper">
                                        <input type="number" 
                                            class="input-nilai" 
                                            value="<?= htmlspecialchars($row['nilai'] ?? '') ?>" 
                                            data-id="<?= $row['pengumpulan_id'] ?>" 
                                            onchange="simpanNilai(this)" 
                                            min="0" max="100" 
                                            placeholder="..."> / 100
                                    </div>
                                </td>
                                <td><span class="status-badge status-terlambat">Terlambat</span></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <a href="detail.php" class="btn-back">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </div>
    </div>
    <script src="../../../assets/js/admin/detail_tugas.js?v=1"></script>
</body>
</html>