document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('nav ul');
    const overlay = document.querySelector('.overlay');
    let isMenuOpen = false;
    
    function toggleMenu(e) {
        e.stopPropagation();
        isMenuOpen = !isMenuOpen;
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = isMenuOpen ? 'hidden' : '';
    }

    function closeMenu() {
        if (!isMenuOpen) return;
        isMenuOpen = false;
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    hamburger.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', closeMenu);

    // Perbaikan untuk handling link clicks
    document.querySelectorAll('nav ul li a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.stopPropagation();
            const href = this.getAttribute('href');
            if (href) {
                window.location.href = href;
            }
            closeMenu();
        });
    });

    // Menutup menu saat resize window
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            closeMenu();
        }
    });

    // Mencegah event bubbling pada menu
    navMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
}); 