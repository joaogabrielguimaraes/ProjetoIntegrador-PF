<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$host = 'localhost';
$dbname = 'conta';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Erro na conexão: ' . $conn->connect_error]);
    exit;
}

$sql = "SELECT tipo_coleta, COUNT(*) AS total 
        FROM enderecos 
        WHERE usuario_id = ? 
        GROUP BY tipo_coleta";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$labels = [];
$valores = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['tipo_coleta'];
    $valores[] = (int)$row['total'];
}

echo json_encode([
    'labels' => $labels,
    'valores' => $valores
]);

$conn->close();
?>
