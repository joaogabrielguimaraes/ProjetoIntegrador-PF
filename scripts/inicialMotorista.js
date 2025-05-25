async function carregarNomeMotorista() {
    try {
        const resposta = await fetch('../scripts/inicialMotorista.php'); // ajuste o caminho
        const data = await resposta.json();

        if (data.status === "sucesso") {
            // Se quiser mostrar o nome, use data.nome, se não, mostra o email
            const nomeOuEmail = data.nome ? data.nome : data.email;
            document.getElementById('welcome-msg').textContent = `Olá, ${nomeOuEmail}!`;
        } else {
            console.log("Usuário não autenticado");
        }
    } catch (error) {
        console.error("Erro ao buscar nome do motorista:", error);
    }
}

window.onload = carregarNomeMotorista;