<?php
session_start();
include '../config/database.php';

header('Content-Type: application/json');

$response = array();

// Verifica se o usuário está autenticado ou redireciona para o login
if (!isset($_SESSION['admin_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'Acesso não autorizado. Faça login primeiro.';
    echo json_encode($response);
    exit;
}

// Verifica se todos os campos necessários foram passados
if (!isset($_POST['id']) || !isset($_POST['nome']) || !isset($_POST['data_nascimento']) || !isset($_POST['genero']) || !isset($_POST['altura']) || !isset($_POST['peso']) || !isset($_POST['telefone']) || !isset($_POST['email'])) {
    $response['status'] = 'error';
    $response['message'] = 'Dados incompletos. Por favor, forneça todos os dados necessários.';
    echo json_encode($response);
    exit;
}

$id = intval($_POST['id']);
$nome = $_POST['nome'];
$data_nascimento = $_POST['data_nascimento'];
$genero = $_POST['genero'];
$altura = floatval($_POST['altura']);
$peso = floatval($_POST['peso']);
$telefone = $_POST['telefone'];
$email = $_POST['email'];

// Consulta para atualizar os dados do paciente
$query = "UPDATE pacientes SET nome = ?, data_nascimento = ?, genero = ?, altura = ?, peso = ?, telefone = ?, email = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('sssdsssi', $nome, $data_nascimento, $genero, $altura, $peso, $telefone, $email, $id);

if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'Paciente atualizado com sucesso.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Erro ao atualizar paciente: ' . $stmt->error;
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
