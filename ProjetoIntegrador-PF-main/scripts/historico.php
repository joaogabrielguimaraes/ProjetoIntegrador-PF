<?php
header('Content-Type: application/json');
session_start(); // Necessário para acessar a variável de sessão

$host     = 'localhost';
$dbname   = 'conta';
$username = 'root';
$password = '';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não está logado.']);
    exit;
}

$usuario_id = (int) $_SESSION['usuario_id'];

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Conexão falhou: ' . $conn->connect_error]);
    exit;
}

if ($usuario_id === 1) {
    $sql  = "SELECT * FROM enderecos ORDER BY data_coleta DESC";
    $stmt = $conn->prepare($sql);
} else {
    $sql  = "SELECT * FROM enderecos WHERE usuario_id = ? ORDER BY data_coleta DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
}

$stmt->execute();
$result = $stmt->get_result();

$agendamentos = [];
while ($row = $result->fetch_assoc()) {
    $agendamentos[] = $row;
}

echo json_encode(['success' => true, 'data' => $agendamentos]);

$stmt->close();
$conn->close();
?>
