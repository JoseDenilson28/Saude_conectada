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

// Consulta para excluir o paciente
$query = "DELETE FROM pacientes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'Paciente excluído com sucesso.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Erro ao excluir paciente: ' . $stmt->error;
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
