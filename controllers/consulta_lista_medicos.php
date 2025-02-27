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
// Consulta para selecionar todos os médicos
$query = "SELECT id, nome, cargo, area, telefone, email FROM medicos";
$result = $conn->query($query);

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
    echo json_encode($response);
} else {
    $response['status'] = 'error';
    $response['message'] = 'Erro ao buscar médicos: ' . $conn->error;
    echo json_encode($response);
}

$conn->close();
?>
