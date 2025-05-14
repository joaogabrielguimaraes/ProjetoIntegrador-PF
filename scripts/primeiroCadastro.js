// Atribuindo evento ao botão de envio
document.getElementById('submitBtn').addEventListener('click', submitForm);

// Função para formatar o CPF automaticamente
document.getElementById('cpf').addEventListener('input', function () {
    let cpf = this.value.replace(/\D/g, ''); // Remove tudo que não for número

    if (cpf.length > 11) cpf = cpf.slice(0, 11); // Limita a 11 dígitos

    // Aplica a máscara do CPF conforme a digitação
    if (cpf.length > 9) {
        cpf = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
    } else if (cpf.length > 6) {
        cpf = cpf.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
    } else if (cpf.length > 3) {
        cpf = cpf.replace(/(\d{3})(\d+)/, '$1.$2');
    }

    this.value = cpf;
});

// Formatar telefone automaticamente enquanto digita
document.getElementById('telefone').addEventListener('input', function () {
    let tel = this.value.replace(/\D/g, ''); // Remove tudo que não for número

    if (tel.length > 11) tel = tel.slice(0, 11); // Máximo de 11 dígitos

    // Aplica a máscara: (XX)9XXXX-XXXX
    if (tel.length > 6) {
        tel = tel.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1)$2-$3');
    } else if (tel.length > 2) {
        tel = tel.replace(/(\d{2})(\d{0,5})/, '($1)$2');
    } else {
        tel = tel.replace(/(\d{0,2})/, '($1');
    }

    this.value = tel;
});

// Função para enviar o formulário de cadastro
function submitForm() {
    const nome = document.getElementById('nome').value;
    const cpf = document.getElementById('cpf').value;
    const telefone = document.getElementById('telefone').value;
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const confirmarSenha = document.getElementById('confirmar-senha').value;

    // Validação de senha
    if (senha !== confirmarSenha) {
        alert('As senhas não coincidem!');
        return;
    }

    if (senha.length < 8) {
        alert('A senha deve ter no mínimo 8 caracteres!');
        return;
    }

    // Enviar os dados para o PHP via fetch
    fetch('../scripts/primeiroCadastro.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `nome=${encodeURIComponent(nome)}&cpf=${encodeURIComponent(cpf)}&telefone=${encodeURIComponent(telefone)}&email=${encodeURIComponent(email)}&senha=${encodeURIComponent(senha)}`
    })
    .then(response => response.json())
    .then(data => {
        alert(data.mensagem);
        if (data.sucesso) {
            setTimeout(() => {
                window.location.href = '../pages/login.html';
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao realizar o cadastro, tente novamente.');
    });
}
