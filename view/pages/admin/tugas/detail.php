<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas Mata Kuliah</title>
    <link rel="stylesheet" href="../../../assets/css/pages/admin/detail.css">
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
            <a href="logout.php" class="nav-item logout-btn" onclick="return confirm('Apakah Anda yakin ingin keluar dari aplikasi?');">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </a>
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

                <textarea id="inputDeskripsi" class="input-desc" rows="3" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit..." required></textarea>

                <button type="submit" class="btn-submit">SUBMIT</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalTugas');
        const form = document.getElementById('formTugas');
        const gridContainer = document.querySelector('.grid-container');
        let editingCard = null;

        function bukaModalTugas() {
            editingCard = null;
            form.reset();
            document.getElementById('fileNameDisplay').innerText = "Belum ada file dipilih";
            modal.style.display = 'flex';
        }

        function tampilkanNamaFile(input) {
            const display = document.getElementById('fileNameDisplay');
            if (input.files && input.files.length > 0) display.innerText = input.files[0].name;
            else display.innerText = "Belum ada file dipilih";
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
            if (confirm("Apakah Anda yakin ingin menghapus tugas ini?")) {
                element.closest('.card').remove();
            }
        }

        function editCard(event, element) {
            event.stopPropagation();
            editingCard = element.closest('.card');
            document.getElementById('inputJudulTugas').value = editingCard.querySelector('.tugas-text').innerText;
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
            const judulVal = document.getElementById('inputJudulTugas').value;

            if (editingCard !== null) {
                editingCard.querySelector('.tugas-text').innerText = judulVal;
            } else {
                const kartuBaru = document.createElement('div');
                kartuBaru.className = 'card';
                kartuBaru.style.cursor = 'pointer';
                kartuBaru.onclick = function() { window.location.href = 'detail_tugas.php'; };
                
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

            form.reset();
            document.getElementById('fileNameDisplay').innerText = "Belum ada file dipilih";
            modal.style.display = 'none';
        });
    </script>
</body>
</html>