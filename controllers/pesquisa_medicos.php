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

// Verifica se foi passado algum termo de pesquisa
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $searchTerm = '%' . $_POST['search'] . '%';

    // Consulta para pesquisar médicos pelo nome, cargo ou área
    $stmt = $conn->prepare("SELECT id, nome, cargo, area, telefone, email FROM medicos WHERE nome LIKE ? OR cargo LIKE ? OR area LIKE ?");
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
} else {
    // Se não houver termo de pesquisa, seleciona todos os médicos
    $stmt = $conn->prepare("SELECT id, nome, cargo, area, telefone, email FROM medicos");
}

$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    $medicos = array();

    // Itera sobre os resultados da consulta
    while ($row = $result->fetch_assoc()) {
        $medicos[] = array(
            'id' => $row['id'],
            'nome' => $row['nome'],
            'cargo' => $row['cargo'],
            'area' => $row['area'],
            'telefone' => $row['telefone'],
            'email' => $row['email']
        );
    }

    $response['status'] = 'success';
    $response['medicos'] = $medicos;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Erro ao buscar médicos: ' . $conn->error;
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
