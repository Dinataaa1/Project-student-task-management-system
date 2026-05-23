<?php
// Pemanggilan Header
include '../components/header.php';
?>

<div class="d-flex justify-content-center align-items-center vh-100 bg-white">
    
    <div class="kotak-login">
        
        <div class="text-center mb-4">
            <h1 class="teks-logo">LOL</h1>
            <p class="text-muted teks-kecil">Student Information System</p>
        </div>

        <div class="d-flex align-items-center mb-4">
            <hr class="flex-grow-1">
            <span class="mx-3 text-muted teks-garis">Sign in with email</span>
            <hr class="flex-grow-1">
        </div>

        <form action="" method="POST">
            
            <div class="mb-3">
                <label class="form-label fw-semibold teks-kecil">E-mail</label>
                <input type="email" name="email" class="form-control p-2 input-email" placeholder="Example@gmail.com">
            </div>

            <div class="mb-2">
                <label class="form-label fw-semibold teks-kecil">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="inputPassword" class="form-control p-2 input-password" placeholder="Enter your password">
                    
                    <span class="input-group-text bg-white ikon-mata" id="togglePassword">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#a0a0a0" viewBox="0 0 16 16">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="text-end mb-4">
                <a href="#" class="text-muted text-decoration-none teks-garis">Forgot my password?</a>
            </div>

            <button type="submit" class="btn w-100 text-white mb-4 fw-medium tombol-ungu">Sign in</button>

            <div class="text-center teks-kecil">
                <span class="text-muted">Dont have an account yet?</span> 
                <a href="#" class="text-decoration-none fw-semibold link-daftar">Creat an account</a>
            </div>
            
        </form>
    </div>
</div>

<script>
    // Mengambil elemen HTML berdasarkan ID yang sudah dibuat di atas
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('inputPassword');
    const eyeIcon = document.getElementById('eyeIcon');

    // Menjalankan fungsi jika ikon mata di-klik oleh user
    togglePassword.addEventListener('click', function () {
        
        // Cek tipe input saat ini. Jika 'password' ubah ke 'text', jika 'text' kembalikan ke 'password'
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Mengubah gambar interaktif ikon mata sesuai dengan status tipenya
        if (type === 'text') {
            // Jika password sedang kelihatan, ganti gambar jadi ikon mata tercoret
            eyeIcon.innerHTML = `
                <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
                <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
            `;
        } else {
            // Jika password disembunyikan lagi, kembalikan gambar ke ikon mata terbuka biasa
            eyeIcon.innerHTML = `
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
            `;
        }
    });
</script>

<?php
// Pemanggilan Footer
include '../components/footer.php';
?>