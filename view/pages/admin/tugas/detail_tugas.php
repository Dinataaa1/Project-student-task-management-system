<?php
    // $active_page = 'dashboard';
    include '../../components/header.php';
?>
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
                                        <input type="text" name="nilai[]" class="input-nilai" maxlength="3"> / 100
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
</body>
</html>