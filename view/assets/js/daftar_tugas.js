// ==========================================================================
// ANIMASI & FILTER DROPDOWN MATA KULIAH
// ==========================================================================

// Fungsi buka/tutup animasi dropdown
function toggleDropdown() {
    const dropdown = document.getElementById('matkulDropdown');
    if (dropdown) {
        dropdown.classList.toggle('open');
    }
}

// Fungsi klik item (Redirect untuk eksekusi filter)
function selectOption(id) {
    // Arahkan ke URL sesuai ID Matkul
    window.location.href = 'daftar_tugas.php?matkul_id=' + id;
}

// Menutup dropdown secara otomatis kalau user nge-klik sembarang tempat di luar area dropdown
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('matkulDropdown');
    // Jika dropdown ada, dan yang diklik BUKAN bagian dari dropdown itu sendiri
    if (dropdown && !dropdown.contains(event.target)) {
        dropdown.classList.remove('open');
    }
});