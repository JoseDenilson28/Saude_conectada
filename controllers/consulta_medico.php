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

// Verifica se o ID do médico foi passado
if (!isset($_POST['id'])) {
    $response['status'] = 'error';
    $response['message'] = 'ID do médico não fornecido.';
    echo json_encode($response);
    exit;
}

$id = intval($_POST['id']);

// Consulta para buscar os dados do médico específico
$query = "SELECT id, nome, cargo, area, telefone, email FROM medicos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    if ($row = $result->fetch_assoc()) {
        $response['status'] = 'success';
        $response['medico'] = array(
            'id' => $row['id'],
            'nome' => $row['nome'],
            'cargo' => $row['cargo'],
            'area' => $row['area'],
            'telefone' => $row['telefone'],
            'email' => $row['email']
        );
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Médico não encontrado.';
    }
    echo json_encode($response);
} else {
    $response['status'] = 'error';
    $response['message'] = 'Erro ao buscar dados do médico: ' . $conn->error;
    echo json_encode($response);
}

$stmt->close();
$conn->close();
?>
