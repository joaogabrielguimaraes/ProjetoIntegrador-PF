<?php
header('Content-Type: application/json');

// Configurações do banco de dados
$host = 'localhost';
$dbname = 'conta';
$username = 'root';
$password = '';

// Estabelecendo a conexão
$conn = new mysqli($host, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die(json_encode(['error' => 'Erro de conexão com o banco de dados: ' . $conn->connect_error]));
}

// Contadores principais
$sqlConcluidas = "SELECT COUNT(*) AS total FROM enderecos WHERE concluido = 'sim'";
$sqlEmAndamento = "SELECT COUNT(*) AS total FROM enderecos WHERE concluido != 'sim' OR concluido IS NULL";
$sqlParticipantes = "SELECT COUNT(id) AS total FROM usuarios";

$concluidas = $conn->query($sqlConcluidas)->fetch_assoc()['total'];
$emAndamento = $conn->query($sqlEmAndamento)->fetch_assoc()['total'];
$participantes = $conn->query($sqlParticipantes)->fetch_assoc()['total'];

// Últimas 3 coletas
$sqlUltimas = "SELECT data_coleta, bairro, tipo_coleta, concluido FROM enderecos ORDER BY id DESC  LIMIT 3;";
$result = $conn->query($sqlUltimas);

$ultimas = [];
while ($row = $result->fetch_assoc()) {
    $ultimas[] = [
        'data' => date('d/m/Y', strtotime($row['data_coleta'])),
        'local' => $row['bairro'],
        'material' => $row['tipo_coleta'],
        'status' => strtolower($row['concluido']) === 'sim' ? 'Concluído' : 'Pendente'
    ];
}

echo json_encode([
    'stats' => [
        'concluidas' => $concluidas,
        'em_andamento' => $emAndamento,
        'participantes' => $participantes
    ],
    'coletas' => $ultimas
]);

$conn->close();
?>
