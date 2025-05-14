function fazerLoginMotorista() {
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;

    if (!email || !senha) {
        showMessage('Por favor, preencha todos os campos');
        return;
    }

    // Envia os dados do formulário para o PHP via fetch
    fetch('../scripts/loginMotorista.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `email=${email}&senha=${senha}`
    })
    .then(response => response.json())
    .then(data => {
        // Exibe a mensagem de retorno da API
        showMessage(data.mensagem);

        // Se o login for bem-sucedido, redireciona
        if (data.status === "sucesso") {
            setTimeout(() => {
                // Caminho relativo ou absoluto para a página de destino
                window.location.href = "../pages/inicialMotorista.html";  // Ajuste o caminho se necessário
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showMessage('Ocorreu um erro na requisição. Tente novamente.');
    });
}

// Função para exibir mensagens de erro ou sucesso
function showMessage(message) {
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255,255,255,0.95);
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        z-index: 1100;
        text-align: center;
        color: var(--dark-color);
    `;
    modal.innerHTML = `
        <i class="fas fa-info-circle" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
        <p style="margin: 0;">${message}</p>
    `;
    document.body.appendChild(modal);

    setTimeout(() => {
        modal.remove();
    }, 2000);
}
