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

// Consulta para buscar todos os pacientes
$query = "SELECT id, nome FROM pacientes";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $pacientes = array();

    while ($row = $result->fetch_assoc()) {
        $pacientes[] = array(
            'id' => $row['id'],
            'nome' => $row['nome']
        );
    }

    $response['status'] = 'success';
    $response['pacientes'] = $pacientes;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Nenhum paciente encontrado.';
}

echo json_encode($response);

$conn->close();
?>
