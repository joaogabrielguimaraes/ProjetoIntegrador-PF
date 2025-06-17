<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['usuario_id'])) {
    echo json_encode([
        "status" => "sucesso",
        "email" => $_SESSION['usuario_id']
    ]);
} else {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Usuário não autenticado"
    ]);
}
?>
