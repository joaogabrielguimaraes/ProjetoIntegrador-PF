<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'conta';
$username = 'root';
$password = '';

// Conexão com o banco de dados
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Conexão falhou: ' . $conn->connect_error]);
    exit;
}

// Query para buscar os agendamentos
$sql = "SELECT * FROM enderecos ORDER BY data_coleta DESC";
$result = $conn->query($sql);

$agendamentos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $agendamentos[] = $row;
    }
}

echo json_encode(['success' => true, 'data' => $agendamentos]);

$conn->close();
?>