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
if (!isset($_POST['id']) || !isset($_POST['nome']) || !isset($_POST['cargo']) || !isset($_POST['area']) || !isset($_POST['email'])) {
    $response['status'] = 'error';
    $response['message'] = 'Dados incompletos. Por favor, forneça todos os dados necessários.';
    echo json_encode($response);
    exit;
}

$id = intval($_POST['id']);
$nome = $_POST['nome'];
$cargo = $_POST['cargo'];
$area = $_POST['area'];
$email = $_POST['email'];

// Opcional: Verifica se o campo telefone foi enviado
$telefone = isset($_POST['telefone']) ? $_POST['telefone'] : null;

// Consulta para atualizar os dados do médico
$query = "UPDATE medicos SET nome = COALESCE(?, nome), cargo = COALESCE(?, cargo), area = COALESCE(?, area), telefone = COALESCE(?, telefone), email = COALESCE(?, email) WHERE id = ?";
$stmt = $conn->prepare($query);

// Binding dos parâmetros
$stmt->bind_param('sssssi', $nome, $cargo, $area, $telefone, $email, $id);

if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'Médico atualizado com sucesso.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Erro ao atualizar médico: ' . $stmt->error;
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
