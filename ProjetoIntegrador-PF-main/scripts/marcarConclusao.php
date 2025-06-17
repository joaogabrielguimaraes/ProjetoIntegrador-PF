<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'conta';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['erro' => 'Erro na conexão']);
    exit;
}

$id = intval($_POST['id'] ?? 0);

$sql = "UPDATE enderecos SET concluido = 'Sim' WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    echo json_encode(['sucesso' => true]);
} else {
    echo json_encode(['erro' => 'Erro ao atualizar']);
}
?>
