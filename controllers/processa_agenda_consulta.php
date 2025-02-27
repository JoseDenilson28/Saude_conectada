<?php
session_start();
include '../config/database.php'; // Inclua o arquivo de configuração do seu banco de dados

header('Content-Type: application/json');

$response = array();

// Verifica se o usuário está autenticado ou redireciona para o login
if (!isset($_SESSION['medico_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'Acesso não autorizado. Faça login primeiro.';
    echo json_encode($response);
    exit;
}

// Verifica se todos os campos necessários foram passados
if (!isset($_POST['paciente_id']) || !isset($_POST['data_consulta']) || !isset($_POST['motivo'])) {
    $response['status'] = 'error';
    $response['message'] = 'Dados incompletos. Por favor, forneça todos os dados necessários.';
    echo json_encode($response);
    exit;
}

$paciente_id = intval($_POST['paciente_id']);
$data_consulta = $_POST['data_consulta'];
$motivo = $_POST['motivo'];

// Consulta para inserir a consulta agendada no banco de dados
$query = "INSERT INTO consultas (paciente_id, data_consulta, motivo) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('iss', $paciente_id, $data_consulta, $motivo);

if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'Consulta agendada com sucesso.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Erro ao agendar consulta: ' . $stmt->error;
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
