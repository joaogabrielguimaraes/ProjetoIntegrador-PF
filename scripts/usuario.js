
AOS.init({
    duration: 1000,
    once: true
});

// Variáveis globais para armazenar seleções
let horarioSelecionado = '';
let tipoColetaSelecionado = '';

window.addEventListener("DOMContentLoaded", function () {
    const inputData = document.getElementById("data");

    const hoje = new Date();
    const ano = hoje.getFullYear();
    const mes = String(hoje.getMonth() + 1).padStart(2, '0');
    const dia = String(hoje.getDate()).padStart(2, '0');

    const dataAtual = `${ano}-${mes}-${dia}`;
    inputData.setAttribute("min", dataAtual);
});

function buscarCEP() {
    const cep = document.getElementById("cep").value.replace(/\D/g, '');

    if (cep.length !== 8) {
        showMessage("CEP inválido. Digite os 8 números.");
        return;
    }

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                showMessage("CEP não encontrado.");
                return;
            }

            // Preenchendo os campos com os dados retornados
            document.getElementById("rua").value = data.logradouro || "";
            document.getElementById("bairro").value = data.bairro || "";
            document.getElementById("cidade").value = data.localidade || "";
            document.getElementById("Estado").value = data.uf || "";
        })
        .catch(() => {
            showMessage("Erro ao buscar o CEP. Tente novamente.");
        });
}

// Função para selecionar o horário
function selectHorario(element) {
    const horarios = document.querySelectorAll('.horario');
    horarios.forEach(horario => {
        horario.classList.remove('selecionado');
    });

    element.classList.add('selecionado');
    horarioSelecionado = element.querySelector('p').textContent;
}

// Função para selecionar o tipo de coleta
function selectTipo(element) {
    element.classList.toggle('selecionado');

    if (element.classList.contains('selecionado')) {
        element.classList.add('tipo-selecionado');
        element.classList.remove('desmarcado');
        tipoColetaSelecionado = element.querySelector('h3').textContent;
    } else {
        element.classList.remove('tipo-selecionado');
        element.classList.add('desmarcado');
        tipoColetaSelecionado = '';
    }
}

// Função para salvar o endereço e agendamento
function salvarEndereco() {
    // Obtendo os dados dos campos do formulário
    const estado = document.getElementById("Estado").value.trim();
    const rua = document.getElementById("rua").value.trim();
    const numero = document.getElementById("numero").value.trim();
    const cep = document.getElementById("cep").value.trim();
    const bairro = document.getElementById("bairro").value.trim();
    const cidade = document.getElementById("cidade").value.trim();
    const dataColeta = document.getElementById("data").value.trim();
    const observacaoColeta = document.getElementById("observacoes").value.trim();

    // Verificando se todos os campos obrigatórios foram preenchidos
    if (estado && rua && numero && cep && bairro && cidade && dataColeta && horarioSelecionado && tipoColetaSelecionado) {
        const endereco = {
            estado: estado,
            rua: rua,
            numero: numero,
            cep: cep,
            bairro: bairro,
            cidade: cidade,
            dataColeta: dataColeta,
            horarioColeta: horarioSelecionado,
            tipoColeta: tipoColetaSelecionado,
            observacaoColeta: observacaoColeta
        };

        // Enviando os dados para o backend (PHP)
        fetch('../scripts/salvar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(endereco)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Endereço salvo com sucesso!");
                carregarAgendamentos(); // Carregar os agendamentos atualizados
            } else {
                alert("Erro ao salvar o endereço: " + data.error);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert("Erro na comunicação com o servidor.");
        });

        // Limpar os campos após o envio
        limparCampos();
    } else {
        alert("Por favor, preencha todos os campos obrigatórios (endereço, data da coleta, horário e tipo de coleta).");
    }
}

function limparCampos() {
    document.getElementById("Estado").value = "";
    document.getElementById("rua").value = "";
    document.getElementById("numero").value = "";
    document.getElementById("cep").value = "";
    document.getElementById("bairro").value = "";
    document.getElementById("cidade").value = "";
    document.getElementById("data").value = "";
    document.getElementById("observacoes").value = "";

    // Resetando a seleção de horário
    const horarios = document.querySelectorAll('.horario');
    horarios.forEach(horario => {
        horario.classList.remove('selecionado');
    });

    // Resetando a seleção do tipo de coleta
    const tipos = document.querySelectorAll('.reciclagem');
    tipos.forEach(tipo => {
        tipo.classList.remove('selecionado', 'tipo-selecionado', 'desmarcado');
    });

    // Resetando as variáveis
    horarioSelecionado = '';
    tipoColetaSelecionado = '';
}

// Função para carregar os agendamentos salvos
function carregarAgendamentos() {
    fetch('../scripts/historico.php')
        .then(response => response.json())
        .then(data => {
            console.log('Dados recebidos:', data); // Log para depuração
            if (data.success && data.data.length > 0) {
                preencherTabela(data.data);
            } else {
                console.error('Nenhum agendamento encontrado.');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar agendamentos:', error);
            alert("Erro ao carregar agendamentos.");
        });
}

function preencherTabela(agendamentos) {
    const tbody = document.getElementById('tabela-agendamentos');
    tbody.innerHTML = '';

    agendamentos.forEach((agendamento, index) => {
        const row = document.createElement('tr');

        // const concluirNormalizado = (agendamento.concluir || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        // const status = agendamento.concluir === 'sim' ? 'Concluído' : 'Pendente';
        // const statusClass = agendamento.concluir === 'sim' ? 'status-concluido' : 'status-pendente';

        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${agendamento.estado || 'N/A'}</td>
            <td>${agendamento.rua}, ${agendamento.numero || 'N/A'}</td>
            <td>${agendamento.cidade || 'N/A'}</td>
            <td>${agendamento.data_coleta || 'N/A'}</td>
            <td>${agendamento.horario_coleta || 'N/A'}</td>
            <td>${agendamento.tipo_coleta || 'N/A'}</td>
            <td>${agendamento.concluido || 'N/A'}</td>
            <td>
                <button class="btn btn-sm btn-outline-danger" onclick="cancelarAgendamento(${agendamento.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(row);
    });
}


// Função para cancelar um agendamento
function cancelarAgendamento(id) {
    if (confirm("Tem certeza que deseja cancelar este agendamento?")) {
        fetch(`../scripts/cancelar.php?id=${id}`, {
            method: 'DELETE',
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Agendamento cancelado com sucesso!");
                carregarAgendamentos(); // Atualiza a tabela após a exclusão
            } else {
                alert("Erro ao cancelar agendamento: " + data.error);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert("Erro na comunicação com o servidor.");
        });
    }
}

// Carregar os agendamentos ao iniciar a página
document.addEventListener('DOMContentLoaded', () => {
    carregarAgendamentos();
});