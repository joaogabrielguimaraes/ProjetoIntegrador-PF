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

$id = intval($_GET['id'] ?? 0);

$sql = "SELECT * FROM enderecos WHERE id = $id";
$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(['erro' => 'Coleta não encontrada']);
}
?>
