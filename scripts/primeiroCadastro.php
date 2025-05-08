<?php
$host = 'localhost';
$dbname = 'conta';
$username = 'root';
$password = '';

// Criar conexão com o banco de dados
$conexao = mysqli_connect($host, $username, $password, $dbname);

// Verificar conexão
if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Receber dados do formulário
$nome = mysqli_real_escape_string($conexao, $_POST['nome']);
$cpf = mysqli_real_escape_string($conexao, $_POST['cpf']);
$telefone = mysqli_real_escape_string($conexao, $_POST['telefone']);
$email = mysqli_real_escape_string($conexao, $_POST['email']);
$senha = mysqli_real_escape_string($conexao, $_POST['senha']);

// Verificar se o e-mail ou CPF já existem
$verifica = "SELECT * FROM usuarios WHERE email = '$email' OR cpf = '$cpf'";
$resultado = mysqli_query($conexao, $verifica);

if (mysqli_num_rows($resultado) > 0) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail ou CPF já está cadastrado!']);
    mysqli_close($conexao);
    exit;
}

// Preparar a query para inserir os dados na tabela 'usuarios'
$query = "INSERT INTO usuarios (nome, cpf, telefone, email, senha)
          VALUES ('$nome', '$cpf', '$telefone', '$email', '$senha')";

// Executar a query
if (mysqli_query($conexao, $query)) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Cadastro realizado com sucesso!']);
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao realizar o cadastro: ' . mysqli_error($conexao)]);
}

// Fechar a conexão
mysqli_close($conexao);
?>
