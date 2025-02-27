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

// Verifica se o ID do paciente foi fornecido
if (!isset($_POST['id']) || empty($_POST['id'])) {
    $response['status'] = 'error';
    $response['message'] = 'ID do paciente não fornecido.';
    echo json_encode($response);
    exit;
}

$id = intval($_POST['id']);

// Consulta para buscar os detalhes do paciente com base no ID
$query = "SELECT id, nome, data_nascimento, genero, altura, peso, telefone, email FROM pacientes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);

$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $paciente = $result->fetch_assoc();

    $response['status'] = 'success';
    $response['paciente'] = array(
        'id' => $paciente['id'],
        'nome' => $paciente['nome'],
        'data_nascimento' => $paciente['data_nascimento'],
        'genero' => $paciente['genero'],
        'altura' => floatval($paciente['altura']),
        'peso' => floatval($paciente['peso']),
        'telefone' => $paciente['telefone'],
        'email' => $paciente['email']
    );
} else {
    $response['status'] = 'error';
    $response['message'] = 'Paciente não encontrado.';
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
