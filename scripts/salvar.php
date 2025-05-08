<?php
header('Content-Type: application/json');
session_start(); // 🔑 Inicia a sessão para acessar o ID do usuário

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

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado.']);
    exit;
}

$usuario_id = intval($_SESSION['usuario_id']); // 🔐 Obtém o ID do usuário da sessão

// Obter o corpo da requisição
$input = file_get_contents("php://input");

// Decodificar o JSON
$data = json_decode($input);

// Verificar se o JSON foi decodificado corretamente
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'JSON inválido']);
    exit;
}

// Extrair os dados
$estado = $conn->real_escape_string($data->estado ?? '');
$rua = $conn->real_escape_string($data->rua ?? '');
$numero = $conn->real_escape_string($data->numero ?? '');
$cep = $conn->real_escape_string($data->cep ?? '');
$bairro = $conn->real_escape_string($data->bairro ?? '');
$cidade = $conn->real_escape_string($data->cidade ?? '');
$dataColeta = $conn->real_escape_string($data->dataColeta ?? '');
$horarioColeta = $conn->real_escape_string($data->horarioColeta ?? '');
$tipoColeta = $conn->real_escape_string($data->tipoColeta ?? '');
$observacaoColeta = $conn->real_escape_string($data->observacaoColeta ?? '');

// Inserir os dados no banco de dados
$sql = "INSERT INTO enderecos (
    estado, rua, numero, cep, bairro, cidade, 
    data_coleta, horario_coleta, tipo_coleta, observacao_coleta, usuario_id
) VALUES (
    '$estado', '$rua', '$numero', '$cep', '$bairro', '$cidade',
    '$dataColeta', '$horarioColeta', '$tipoColeta', '$observacaoColeta', $usuario_id
)";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$conn->close();
?>
