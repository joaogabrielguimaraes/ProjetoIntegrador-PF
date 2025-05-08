<?php
require './config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitização e validação dos dados recebidos
    $email = $_POST['email']; // Não há necessidade de filtrar com FILTER_VALIDATE_EMAIL
    $senha = $_POST['senha'];

    // Verifica se o e-mail ou senha estão vazios
    if (empty($email) || empty($senha)) {
        echo json_encode(["status" => "erro", "mensagem" => "Por favor, preencha todos os campos"]);
        exit();
    }

    // Conectar ao banco de dados
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erro na conexão: " . $e->getMessage());
    }

    // Verifica se o e-mail existe no banco de dados
    $stmt = $conn->prepare("SELECT email, senha FROM loginmotorista WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Se o e-mail existir, verifica se a senha fornecida corresponde ao valor armazenado
        if ($senha === $usuario['senha']) {
            echo json_encode(["status" => "sucesso", "mensagem" => "Login realizado com sucesso!"]);
        } else {
            echo json_encode(["status" => "erro", "mensagem" => "Senha incorreta!"]);
        }
    } else {
        // Se o e-mail não existir no banco
        echo json_encode(["status" => "erro", "mensagem" => "E-mail não encontrado!"]);
    }
}
?>
