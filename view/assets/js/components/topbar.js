document.addEventListener('DOMContentLoaded', function() {
    const bellIcon = document.getElementById('bellIcon');
    const blueDot = document.getElementById('blueDot');
    
    // Elemen baru untuk sliding panel
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

    let hasNewUnread = currentTaskIds.some(id => !savedReadTasks.includes(id));

    if (hasNewUnread && currentTaskIds.length > 0) {
        blueDot.classList.remove('d-none');
    }

    // Fungsi untuk MENGELUARKAN panel dari kanan
    function openPanel() {
        notifPanel.classList.add('show');
        if (notifOverlay) notifOverlay.classList.add('show');
        
        // Matikan titik biru dan simpan memori
        blueDot.classList.add('d-none');
        localStorage.setItem(memoryKey, JSON.stringify(currentTaskIds));
    }

    // Fungsi untuk MEMASUKKAN kembali panel ke kanan
    function closePanel() {
        notifPanel.classList.remove('show');
        if (notifOverlay) notifOverlay.classList.remove('show');
    }

    // Aksi saat Lonceng diklik
    bellIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        openPanel();
    });

    // Menutup panel jika tombol X diklik
    if (closeNotifBtn) {
        closeNotifBtn.addEventListener('click', closePanel);
    }

    // Menutup panel jika area gelap di luar panel diklik
    if (notifOverlay) {
        notifOverlay.addEventListener('click', closePanel);
    }
});