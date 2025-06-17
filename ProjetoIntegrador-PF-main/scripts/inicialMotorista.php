<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['email_motorista'])) {
    echo json_encode([
        "status" => "sucesso",
        "email" => $_SESSION['email_motorista']
    ]);
} else {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Usuário não autenticado"
    ]);
}
?>
