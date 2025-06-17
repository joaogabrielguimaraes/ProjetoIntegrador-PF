<?php
session_start();
header('Content-Type: application/json');

// Confere se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Configurações do banco
$host = 'localhost';
$dbname = 'conta';
$username = 'root';
$password = '';

// Conecta
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['error' => 'Erro de conexão com o banco de dados']));
}

// Filtrar por usuário logado
$sqlConcluidas = "SELECT COUNT(*) AS total FROM enderecos WHERE concluido = 'sim' AND usuario_id = $usuario_id";
$sqlEmAndamento = "SELECT COUNT(*) AS total FROM enderecos WHERE (concluido != 'sim' OR concluido IS NULL) AND usuario_id = $usuario_id";
$sqlParticipantes = "SELECT COUNT(DISTINCT usuario_id) AS total FROM enderecos";

// Executa as consultas
$concluidas = $conn->query($sqlConcluidas)->fetch_assoc()['total'];
$emAndamento = $conn->query($sqlEmAndamento)->fetch_assoc()['total'];
$participantes = $conn->query($sqlParticipantes)->fetch_assoc()['total'];

// Últimas 3 coletas do usuário logado
$sqlUltimas = "SELECT data_coleta, bairro, tipo_coleta, concluido 
               FROM enderecos 
               WHERE usuario_id = $usuario_id 
               ORDER BY id DESC 
               LIMIT 3";

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

// Retorna JSON
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
