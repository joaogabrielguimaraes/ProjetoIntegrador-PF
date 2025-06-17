<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'conta';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['erro' => 'Erro na conexão']));
}

$sql = "SELECT tipo_coleta, COUNT(*) as total FROM enderecos GROUP BY tipo_coleta";
$result = $conn->query($sql);

$labels = [];
$valores = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['tipo_coleta'];
    $valores[] = $row['total'];
}

echo json_encode([
    'labels' => $labels,
    'valores' => $valores
]);
?>
