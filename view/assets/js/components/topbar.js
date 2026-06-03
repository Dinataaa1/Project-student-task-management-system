// view/assets/js/components/topbar.js

document.addEventListener('DOMContentLoaded', function() {
    const bellIcon = document.getElementById('bellIcon');
    const blueDot = document.getElementById('blueDot');
    const notifDropdown = document.getElementById('notifDropdown');

    // Jika elemen tidak ditemukan (misal di halaman login), hentikan script agar tidak error
    if (!bellIcon || !blueDot || !notifDropdown) return; 

    // MENGAMBIL USER ID DARI ATRIBUT HTML (Sebagai ganti PHP)
    const userId = bellIcon.getAttribute('data-userid');

    // 1. Kumpulkan semua ID tugas yang tampil di kotak reminder saat ini
    const notifItems = document.querySelectorAll('.notif-item');
    let currentTaskIds = [];
    notifItems.forEach(item => {
        currentTaskIds.push(item.getAttribute('data-id'));
    });

    // 2. Cek memori browser menggunakan ID pengguna yang aktif
    const memoryKey = 'read_tasks_' + userId;
    let savedReadTasks = JSON.parse(localStorage.getItem(memoryKey)) || [];

    // 3. Apakah ada ID tugas di layar yang belum ada di dalam memori browser?
    let hasNewUnread = currentTaskIds.some(id => !savedReadTasks.includes(id));

    // Jika ADA tugas baru (dan daftarnya tidak kosong), nyalakan titik biru!
    if (hasNewUnread && currentTaskIds.length > 0) {
        blueDot.classList.remove('d-none');
    }

    // 4. Aksi saat Lonceng diklik
    bellIcon.addEventListener('click', function(e) {
        e.stopPropagation(); // Mencegah bentrok dengan klik lain di layar
        
        // Buka / Tutup kotak pesan
        notifDropdown.classList.toggle('d-none'); 

        // Jika kotak pesan sedang DIBUKA
        if (!notifDropdown.classList.contains('d-none')) {
            // Matikan titik biru
            blueDot.classList.add('d-none'); 

            // Simpan seluruh ID tugas yang ada saat ini ke memori browser
            localStorage.setItem(memoryKey, JSON.stringify(currentTaskIds));
        }
    });

    // 5. Menutup kotak pesan secara otomatis jika user ngeklik area kosong di luar lonceng
    document.addEventListener('click', function(e) {
        if (!bellIcon.contains(e.target) && !notifDropdown.contains(e.target)) {
            notifDropdown.classList.add('d-none');
        }
    });
});