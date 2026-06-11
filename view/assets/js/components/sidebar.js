document.addEventListener('DOMContentLoaded', () => {
    const menuLinks = document.querySelectorAll('.sidebar-item');
    
    // KUNCI PERUBAHAN: Target animasinya sekarang difokuskan HANYA ke .content-area
    const contentArea = document.querySelector('.content-area'); 
    
    const indicator = document.getElementById('activeIndicator');

    // ==========================================================================
    // 1. ATUR ANIMASI MASUK KONTEN (SAAT HALAMAN BARU DIMUAT)
    // ==========================================================================
    if (contentArea) {
        const navDirection = sessionStorage.getItem('navDirection') || 'down';
        
        if (navDirection === 'down') {
            contentArea.classList.add('anim-slide-in-up');
        } else {
            contentArea.classList.add('anim-slide-in-down');
        }
    }

    if (menuLinks.length > 0 && indicator) {
        
        // ==========================================================================
        // 2. KEMBALIKAN POSISI KOTAK UNGU (MAGIC LINE)
        // ==========================================================================
        let activeItem = document.querySelector('.sidebar-item.active');
        const lastPos = sessionStorage.getItem('lastMenuPos'); 
        
        if (activeItem) {
            if (lastPos !== null) {
                indicator.style.transition = 'none';
                indicator.style.transform = `translateY(${lastPos}px)`;
                indicator.offsetHeight; // Force reflow
                indicator.style.transition = 'transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1)';
            }
            const currentPos = activeItem.offsetTop;
            indicator.style.transform = `translateY(${currentPos}px)`;
            sessionStorage.setItem('lastMenuPos', currentPos);
        }

        // ==========================================================================
        // 3. DETEKSI ARAH KLIK & ATUR ANIMASI KELUAR
        // ==========================================================================
        menuLinks.forEach((link, index) => {
            link.addEventListener('click', function(e) {
                const targetUrl = this.getAttribute('href');
                
                if (targetUrl && targetUrl !== '#' && !targetUrl.startsWith('javascript')) {
                    e.preventDefault(); 
                    
                    let activeIndex = 0;
                    menuLinks.forEach((el, i) => {
                        if(el.classList.contains('active')) activeIndex = i;
                    });

                    // Tentukan arah animasi pada contentArea (bukan lagi mainContent)
                    let direction = 'down';
                    if (index > activeIndex) {
                        direction = 'down'; 
                        if (contentArea) contentArea.classList.add('anim-slide-out-up'); 
                    } else if (index < activeIndex) {
                        direction = 'up'; 
                        if (contentArea) contentArea.classList.add('anim-slide-out-down'); 
                    } else {
                        if (contentArea) contentArea.classList.add('anim-slide-out-up'); 
                    }

                    sessionStorage.setItem('navDirection', direction);

                    // Pindahkan Magic Line
                    document.querySelectorAll('.sidebar-item').forEach(el => el.classList.remove('active'));
                    this.classList.add('active');
                    const newPos = this.offsetTop;
                    indicator.style.transform = `translateY(${newPos}px)`;
                    sessionStorage.setItem('lastMenuPos', newPos);
                    
                    setTimeout(() => {
                        window.location.href = targetUrl;
                    }, 300);
                }
            });
        });
    }
});