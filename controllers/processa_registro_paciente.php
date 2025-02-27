<?php
include '../config/database.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $genero = $_POST['genero'];
    $altura = $_POST['altura'];
    $peso = $_POST['peso'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    // Verificar se já existe um registo com o mesmo nome, telefone ou email
    $stmt = $conn->prepare("SELECT COUNT(*) FROM pacientes WHERE nome = ? OR telefone = ? OR email = ?");
    $stmt->bind_param("sss", $nome, $telefone, $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $response['status'] = 'error';
        $response['message'] = 'Já existe um paciente com o mesmo nome, telefone ou email.';
    } else {
        $stmt = $conn->prepare("INSERT INTO pacientes (nome, data_nascimento, genero, altura, peso, telefone, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nome, $data_nascimento, $genero, $altura, $peso, $telefone, $email);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Paciente registado com sucesso!';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Erro ao registar paciente.';
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
