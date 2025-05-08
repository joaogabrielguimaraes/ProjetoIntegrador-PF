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

// Coletando os filtros da URL
$filtro_bairro = $_GET['bairro'] ?? '';
$filtro_material = $_GET['material'] ?? '';
$filtro_data = $_GET['data'] ?? '';

// Construindo a query
$sql = "SELECT id, data_coleta, bairro, tipo_coleta, concluido FROM enderecos WHERE 1=1";

// Aplicando filtros, se existirem
if (!empty($filtro_bairro)) {
    $sql .= " AND bairro = '" . $conn->real_escape_string($filtro_bairro) . "'";
}
if (!empty($filtro_material)) {
    $sql .= " AND tipo_coleta = '" . $conn->real_escape_string($filtro_material) . "'";
}
if (!empty($filtro_data)) { 
    $sql .= " AND data_coleta = '" . $conn->real_escape_string($filtro_data) . "'";
}

// Ordenando por data da coleta
$sql .= " ORDER BY data_coleta ASC";

$result = $conn->query($sql);

$coletas = [];
while ($row = $result->fetch_assoc()) {
    $coletas[] = $row;
}

// Enviando os dados como JSON
echo json_encode($coletas);
?>
