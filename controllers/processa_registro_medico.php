<?php
include '../config/database.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cargo = $_POST['cargo'];
    $area = $_POST['area'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    // Verificar se já existe um registo com o mesmo email
    $stmt = $conn->prepare("SELECT COUNT(*) FROM medicos WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $response['status'] = 'error';
        $response['message'] = 'Já existe um médico com o mesmo email.';
    } else {
        $stmt = $conn->prepare("INSERT INTO medicos (nome, cargo, area, telefone, email, senha) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nome, $cargo, $area, $telefone, $email, $senha);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Médico registado com sucesso!';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Erro ao registar médico.';
        }

        $stmt->close();
    }

    $conn->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método de requisição inválido.';
}

echo json_encode($response);
?>
