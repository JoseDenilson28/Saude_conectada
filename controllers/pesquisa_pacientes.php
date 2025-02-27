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

// Verifica se o termo de pesquisa foi fornecido
if (!isset($_POST['search']) || empty($_POST['search'])) {
    $response['status'] = 'error';
    $response['message'] = 'Termo de pesquisa não fornecido.';
    echo json_encode($response);
    exit;
}

$searchTerm = $_POST['search'];

// Consulta para buscar os pacientes com base no termo de pesquisa
$query = "SELECT id, nome, data_nascimento, genero, altura, peso, telefone, email FROM pacientes 
          WHERE nome LIKE ? OR email LIKE ? OR telefone LIKE ?";
$stmt = $conn->prepare($query);

// Adiciona caracteres curinga (%) para pesquisa parcial
$searchTerm = "%$searchTerm%";
$stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);

$stmt->execute();
$result = $stmt->get_result();

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
            'email' => $row['email']
        );
        $pacientes[] = $paciente;
    }

    $response['status'] = 'success';
    $response['pacientes'] = $pacientes;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Erro na pesquisa de pacientes: ' . $conn->error;
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
