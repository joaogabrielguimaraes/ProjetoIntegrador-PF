<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'conta';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Conexão falhou: ' . $conn->connect_error]);
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'error' => 'ID do agendamento não fornecido']);
    exit;
}

$id = intval($_GET['id']); 

$sql = "DELETE FROM enderecos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Erro ao excluir agendamento: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>