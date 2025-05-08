AOS.init({
    duration: 1000,
    easing: 'ease-out-cubic',
    once: true
});

document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelector('.btn.active').classList.remove('active');
        btn.classList.add('active');
    });
});

document.querySelectorAll('.btn_resgatar').forEach(btn => {
    btn.addEventListener('click', function() {
        this.innerHTML = '<i class="fas fa-check"></i> Resgatado!';
        this.style.background = '#4CAF50';
        setTimeout(() => {
            this.innerHTML = 'Resgatar Agora';
            this.style.background = '';
        }, 2000);
    });
});