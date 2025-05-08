document.addEventListener('DOMContentLoaded', () => {
    fetchDadosDashboard();
    carregarGraficoMateriais();
});

function fetchDadosDashboard() {
    fetch('../scripts/dados_dashboard.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao buscar dados');
            }
            return response.json();
        })
        .then(data => {
            preencherContadores(data.stats);
            preencherTabela(data.coletas);
            animarNumeros();
        })
        .catch(error => {
            console.error('Erro ao carregar dados do dashboard:', error);
        });
}

function preencherContadores(stats) {
    document.getElementById('concluidas').textContent = stats.concluidas;
    document.getElementById('emAndamento').textContent = stats.em_andamento;
    document.getElementById('participantes').textContent = stats.participantes;

    // Valor estimado manual até ter um campo no banco de dados
    document.getElementById('totalKg').textContent = stats.total_kg ?? 0;
}

function preencherTabela(coletas) {
    const tbody = document.querySelector('table tbody');
    tbody.innerHTML = ''; // Limpa antes de preencher

    coletas.forEach(coleta => {
        const linha = document.createElement('tr');
        linha.innerHTML = `
            <td>${coleta.data}</td>
            <td>${coleta.local}</td>
            <td><span class="text">${coleta.material}</span></td>
            <td><span class="badge ${coleta.status === 'Concluído' ? 'bg-success' : 'bg-warning'}">
                ${coleta.status}
            </span></td>
        `;
        tbody.appendChild(linha);
    });
}

function animarNumeros() {
    const elementos = document.querySelectorAll('.fs-4');

    elementos.forEach(el => {
        const valorFinal = parseInt(el.textContent);
        let valorAtual = 0;
        const duracao = 1500;
        const passo = valorFinal / (duracao / 16);

        function animar() {
            if (valorAtual < valorFinal) {
                valorAtual += passo;
                el.textContent = Math.round(valorAtual);
                requestAnimationFrame(animar);
            } else {
                el.textContent = valorFinal;
            }
        }

        animar();
    });
}

function preencherTabela(coletas) {
    const tbody = document.querySelector('table tbody');
    tbody.innerHTML = ''; // Limpa antes de preencher

    coletas.forEach(coleta => {
        const linha = document.createElement('tr');
        linha.innerHTML = `  
            <td>${coleta.data}</td>
            <td>${coleta.local}</td>
            <td><span class="text">${coleta.material}</span></td>
            <td><span class="badge ${coleta.status === 'Concluído' ? 'bg-success' : 'bg-warning'}">
                ${coleta.status}
            </span></td>
        `;
        tbody.appendChild(linha);
    });
}


function carregarGraficoMateriais() {
    fetch('../scripts/carregarTipo.php')
        .then(res => res.json())
        .then(data => {
            console.log('Dados recebidos do PHP:', data);

            const ctx = document.getElementById('materiaisChart')?.getContext('2d');
            if (!ctx) return console.error('Canvas não encontrado');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.valores,
                        backgroundColor: [
                            '#2ecc71',
                            '#3498db',
                            '#f1c40f',
                            '#e74c3c',
                            '#9b59b6',
                            '#1abc9c'
                        ],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        })
        .catch(err => console.error('Erro ao buscar dados para o gráfico:', err));
}
