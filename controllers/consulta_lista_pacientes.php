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

// Consulta para buscar os pacientes
$query = "SELECT id, nome, data_nascimento, genero, altura, peso, telefone, email, data_registo FROM pacientes";
$result = $conn->query($query);

if ($result) {
    $pacientes = array();

    while ($row = $result->fetch_assoc()) {
        $paciente = array(
            'id' => $row['id'],
            'nome' => $row['nome'],
            'data_nascimento' => $row['data_nascimento'],
            'genero' => $row['genero'],
            'altura' => floatval($row['altura']),
            'peso' => floatval($row['peso']),
            'telefone' => $row['telefone'],
            'email' => $row['email'],
            'data_registo' => $row['data_registo']
        );
        $pacientes[] = $paciente;
    }

    $response['status'] = 'success';
    $response['pacientes'] = $pacientes;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Erro ao consultar pacientes: ' . $conn->error;
}

echo json_encode($response);

$conn->close();
?>
