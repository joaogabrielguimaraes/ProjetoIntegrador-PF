<?php
session_start();

// Opcional: conectar ao banco e registrar logout
include '../scripts/config.php'; // sua conexão com o MySQL

if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];

    // Exemplo: atualizar última hora de logout
    $sql = "UPDATE usuarios SET ultimo_logout = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
}

// Destruir a sessão
session_unset();
session_destroy();

// Redirecionar para o login
header("Location: ../pages/loginMotorist.html");
exit;
?>
