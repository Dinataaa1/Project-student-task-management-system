// view/assets/js/admin/detail_tugas.js

// Fungsi asinkron untuk mengirim nilai ke backend tanpa reload
async function simpanNilai(inputElement) {
    // Ambil ID dari atribut data-id dan nilai yang diketik
    const pengumpulanId = inputElement.getAttribute('data-id');
    const nilaiValue = inputElement.value;

    // Validasi dasar di sisi klien (frontend)
    if (nilaiValue === '' || nilaiValue < 0 || nilaiValue > 100) {
        alert("Nilai harus berupa angka antara 0 sampai 100!");
        inputElement.style.borderColor = 'red';
        return;
    }

    try {
       
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Kirim data ke backend menggunakan Fetch API
        const response = await fetch('../../../../controllers/admin/nilai_process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken 
            },
            body: JSON.stringify({
                pengumpulan_id: pengumpulanId,
                nilai: nilaiValue
            })
        });

        // Tangkap respon JSON dari backend
        const result = await response.json();

        if (result.success) {
            // Jika berhasil, beri efek visual hijau
            inputElement.style.borderColor = '#166534'; 
            inputElement.style.backgroundColor = '#dcfce7'; 
            
            // Kembalikan warna ke normal setelah 2 detik
            setTimeout(() => {
                inputElement.style.borderColor = 'var(--color-purple)';
                inputElement.style.backgroundColor = 'transparent';
            }, 2000);
            
            console.log("Sukses:", result.message);
        } else {
            alert("Gagal menyimpan nilai: " + result.message);
            inputElement.style.borderColor = 'red';
        }

    } catch (error) {
        alert("Terjadi kesalahan koneksi ke server!");
        console.error("Error:", error);
    }
}