document.addEventListener('DOMContentLoaded', function() {
    const bellIcon = document.getElementById('bellIcon');
    const blueDot = document.getElementById('blueDot');
    
    const bellTop = document.querySelector('.bell-top');
    const bellBot = document.querySelector('.bell-bot');
    
    const notifPanel = document.getElementById('notifPanel');
    const notifOverlay = document.getElementById('notifOverlay');
    const closeNotifBtn = document.getElementById('closeNotifBtn');

    if (!bellIcon || !notifPanel) return;

    const userId = bellIcon.getAttribute('data-userid');

    // 1. Kumpulkan ID dari card notifikasi
    const notifCards = document.querySelectorAll('.notif-card');
    let currentTaskIds = [];
    notifCards.forEach(card => {
        currentTaskIds.push(card.getAttribute('data-id'));
    });

    const memoryKey = 'read_tasks_' + userId;
    let savedReadTasks = JSON.parse(localStorage.getItem(memoryKey)) || [];

    // =========================================================================
    // FITUR BARU: Menghitung jumlah tugas yang belum dibaca
    // =========================================================================
    let unreadTasks = currentTaskIds.filter(id => !savedReadTasks.includes(id));
    let unreadCount = unreadTasks.length; 
    let hasNewUnread = unreadCount > 0;

    // FUNGSI UNTUK MEMAINKAN ANIMASI (Bisa dipanggil berulang kali)
    function playBellAnimation() {
        if (bellTop) {
            bellTop.classList.remove('bell-top-anim');
            void bellTop.offsetWidth; // Trik JS untuk mereset animasi
            bellTop.classList.add('bell-top-anim');
        }
        if (bellBot) {
            bellBot.classList.remove('bell-bot-anim');
            void bellBot.offsetWidth;
            bellBot.classList.add('bell-bot-anim');
        }
    }

    // Jika ada tugas baru, tampilkan angka dan mainkan animasi!
    if (hasNewUnread) {
        blueDot.innerText = unreadCount; // Masukkan angka ke dalam bulatan biru
        blueDot.classList.remove('d-none');
        blueDot.classList.add('new-not'); 
        playBellAnimation(); // Mainkan otomatis saat halaman dimuat
    }

    function openPanel() {
        notifPanel.classList.add('show');
        if (notifOverlay) notifOverlay.classList.add('show');
        
        // Sembunyikan bulatan biru setelah panel dibuka
        blueDot.classList.add('d-none');
        blueDot.classList.remove('new-not');
        
        // Simpan semua ID tugas ke memori agar dianggap "sudah dibaca"
        localStorage.setItem(memoryKey, JSON.stringify(currentTaskIds));
    }

    function closePanel() {
        notifPanel.classList.remove('show');
        if (notifOverlay) notifOverlay.classList.remove('show');
    }

    // Mainkan animasi saat mouse diarahkan ke lonceng (Hover)
    bellIcon.addEventListener('mouseenter', function() {
        playBellAnimation();
    });

    // Buka panel DAN mainkan animasi saat lonceng diklik
    bellIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        playBellAnimation(); 
        openPanel();
    });

    if (closeNotifBtn) {
        closeNotifBtn.addEventListener('click', closePanel);
    }

    if (notifOverlay) {
        notifOverlay.addEventListener('click', closePanel);
    }
});