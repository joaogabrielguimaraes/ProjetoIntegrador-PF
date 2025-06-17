<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'conta';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['erro' => 'Erro na conexão com o banco de dados']));
}

$sql = "SELECT DISTINCT bairro FROM enderecos ORDER BY bairro";
$result = $conn->query($sql);

$bairros = [];
while ($row = $result->fetch_assoc()) {
    $bairros[] = $row['bairro'];
}

echo json_encode($bairros);
?>
