document.addEventListener('DOMContentLoaded', () => {
    // Inicializar AOS de forma assíncrona
    AOS.init({
        duration: 1200,
        once: false,
        mirror: true,
        anchorPlacement: 'center-bottom',
        disable: 'mobile',  // Desativa animações em dispositivos móveis para melhorar performance
    });

    // Otimizar a animação do cabeçalho
    const header = document.querySelector('header');
    let lastScrollY = 0; // Variável para detectar a direção do scroll

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50 && window.scrollY > lastScrollY) { // Detecta rolagem para baixo
            gsap.to(header, {
                duration: 0.4,
                padding: '0.5rem 1rem',
                backgroundColor: 'rgba(44, 62, 80, 0.95)',
                boxShadow: '0 4px 30px rgba(0,0,0,0.1)',
                ease: 'power2.out'
            });
        } else if (window.scrollY <= 50 || window.scrollY < lastScrollY) { // Detecta rolagem para cima
            gsap.to(header, {
                duration: 0.4,
                padding: '1rem',
                backgroundColor: 'rgba(44, 62, 80, 0.85)',
                boxShadow: 'none',
                ease: 'power2.out'
            });
        }
        lastScrollY = window.scrollY; // Atualiza o valor de scroll
    });

    // Lazy loading para elementos de contagem (stats)
    const stats = document.querySelectorAll('.stat-number');
    const observerOptions = { threshold: 0.5 };
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseInt(entry.target.getAttribute('data-count'));
                gsap.to(entry.target, {
                    duration: 2,
                    textContent: target,
                    roundProps: 'textContent', // Garante que a contagem é um número inteiro
                    ease: 'power2.out',
                    snap: { textContent: 1 }, // Ajusta o número de forma suave
                    onUpdate: function() {
                        // Formata o número com vírgulas para facilitar a leitura
                        entry.target.textContent = parseInt(entry.target.textContent).toLocaleString();
                    }
                });
                statsObserver.unobserve(entry.target); // Para de observar o item após a animação
            }
        });
    }, observerOptions);

    stats.forEach(stat => statsObserver.observe(stat));
});
