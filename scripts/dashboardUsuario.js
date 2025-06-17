document.addEventListener('DOMContentLoaded', () => {
  carregarDashboard();
  carregarGraficoMateriais();
});

async function carregarDashboard() {
  try {
    const response = await fetch('../scripts/dados_dashboardUsuario.php');
    if (!response.ok) throw new Error('Erro ao buscar dados do dashboard');

    const data = await response.json();
    preencherContadores(data.stats);
    preencherTabela(data.coletas);
    animarNumeros();
  } catch (error) {
    console.error('Erro ao carregar dados do dashboard:', error);
  }
}

function preencherContadores(stats) {
  const setTexto = (id, valor) => {
    const el = document.getElementById(id);
    if (el) el.textContent = valor;
  };

  setTexto('concluidas', stats.concluidas);
  setTexto('emAndamento', stats.em_andamento);
  setTexto('participantes', stats.participantes);
}

function preencherTabela(coletas) {
  const tbody = document.querySelector('table tbody');
  if (!tbody) return;

  tbody.innerHTML = '';

  coletas.forEach(({ data, local, material, status }) => {
    const linha = document.createElement('tr');
    linha.innerHTML = `
      <td>${data}</td>
      <td>${local}</td>
      <td><span class="text">${material}</span></td>
      <td><span class="badge ${status === 'Concluído' ? 'bg-success' : 'bg-warning'}">${status}</span></td>
    `;
    tbody.appendChild(linha);
  });
}

function animarNumeros() {
  document.querySelectorAll('.fs-4').forEach(el => {
    const valorFinal = parseInt(el.textContent);
    if (isNaN(valorFinal)) return;

    let valorAtual = 0;
    const duracao = 1500;
    const passo = valorFinal / (duracao / 16);

    function animar() {
      valorAtual += passo;
      if (valorAtual < valorFinal) {
        el.textContent = Math.round(valorAtual);
        requestAnimationFrame(animar);
      } else {
        el.textContent = valorFinal;
      }
    }
    animar();
  });
}

