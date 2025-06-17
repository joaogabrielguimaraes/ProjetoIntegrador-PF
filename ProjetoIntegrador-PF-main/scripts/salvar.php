<?php
header('Content-Type: application/json');
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Configurações do banco de dados
$host = 'localhost';
$dbname = 'conta';
$username = 'root';
$password = '';

// Conecta ao banco de dados
$conn = new mysqli($host, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Conexão falhou: ' . $conn->connect_error]);
    exit;
}

// Lê os dados recebidos
$input = file_get_contents("php://input");
$data = json_decode($input);

// Valida o JSON
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'JSON inválido']);
    exit;
}

// Escapa os dados para evitar SQL injection
$complemento = $conn->real_escape_string($data->complemento ?? '');
$rua = $conn->real_escape_string($data->rua ?? '');
$numero = $conn->real_escape_string($data->numero ?? '');
$cep = $conn->real_escape_string($data->cep ?? '');
$bairro = $conn->real_escape_string($data->bairro ?? '');
$cidade = $conn->real_escape_string($data->cidade ?? '');
$dataColeta = $conn->real_escape_string($data->dataColeta ?? '');
$horarioColeta = $conn->real_escape_string($data->horarioColeta ?? '');
$tipoColeta = $conn->real_escape_string($data->tipoColeta ?? '');
$observacaoColeta = $conn->real_escape_string($data->observacaoColeta ?? '');

// Validação mínima de campos obrigatórios
if (empty($rua) || empty($numero) || empty($cep) || empty($bairro) || empty($cidade) || empty($dataColeta) || empty($horarioColeta) || empty($tipoColeta)) {
    echo json_encode(['success' => false, 'error' => 'Campos obrigatórios estão faltando']);
    exit;
}

// Query de inserção
$sql = "INSERT INTO enderecos (complemento, rua, numero, cep, bairro, cidade, data_coleta, horario_coleta, tipo_coleta, observacao_coleta, usuario_id)
        VALUES ('$complemento', '$rua', '$numero', '$cep', '$bairro', '$cidade', '$dataColeta', '$horarioColeta', '$tipoColeta', '$observacaoColeta', '$usuario_id')";

// Executa a query
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

// Fecha a conexão
$conn->close();
?>
