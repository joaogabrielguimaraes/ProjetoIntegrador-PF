<?php
$host = 'localhost';
$dbname = 'conta';
$username = 'root';  
$password = ''; 

// Criar a conexão
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Buscar dados do histórico
$sql = "SELECT * FROM enderecos";
$result = $conn->query($sql);

$enderecos = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $enderecos[] = $row;
    }
}

echo json_encode(['enderecos' => $enderecos]);

$conn->close();

?>
