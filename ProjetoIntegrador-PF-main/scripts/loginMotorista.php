<?php
session_start();
require './config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        echo json_encode(["status" => "erro", "mensagem" => "Por favor, preencha todos os campos"]);
        exit();
    }

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT email, senha FROM loginmotorista WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Aqui idealmente use password_verify se as senhas forem hash
            if ($senha === $usuario['senha']) {
                $_SESSION['email_motorista'] = $usuario['email']; 
                echo json_encode(["status" => "sucesso", "mensagem" => "Login realizado com sucesso!"]);
            } else {
                echo json_encode(["status" => "erro", "mensagem" => "Senha incorreta!"]);
            }
        } else {
            echo json_encode(["status" => "erro", "mensagem" => "E-mail não encontrado!"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "erro", "mensagem" => "Erro na conexão: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Método inválido"]);
}
?>
