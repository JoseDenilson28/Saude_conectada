<?php
session_start();
include '../config/database.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT id, nome, senha FROM administradores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $nome, $senha_hash);
    $stmt->fetch();
    $stmt->close();

    if ($id && password_verify($senha, $senha_hash)) {
        $_SESSION['admin_id'] = $id;
        $_SESSION['admin_nome'] = $nome;
        $response['status'] = 'success';
        $response['message'] = 'Login efetuado com sucesso!';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Email ou senha incorretos.';
    }

    $conn->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método de requisição inválido.';
}

echo json_encode($response);
?>
