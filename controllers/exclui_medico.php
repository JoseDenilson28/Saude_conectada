<?php
session_start();
include '../config/database.php';

header('Content-Type: application/json');

$response = array();

// Verifica se o usuário está autenticado ou redireciona para o login
if (!isset($_SESSION['medico_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'Acesso não autorizado. Faça login primeiro.';
    echo json_encode($response);
    exit;
}

// Verifica se o ID do médico foi fornecido via POST
if (!isset($_POST['id'])) {
    $response['status'] = 'error';
    $response['message'] = 'ID do médico não fornecido.';
    echo json_encode($response);
    exit;
}

$id = intval($_POST['id']);

// Query para excluir o médico pelo ID
$query = "DELETE FROM medicos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'Médico excluído com sucesso.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Erro ao excluir médico: ' . $conn->error;
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
