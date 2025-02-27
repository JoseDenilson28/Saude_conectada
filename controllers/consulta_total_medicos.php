<?php
session_start();
include '../config/database.php';

header('Content-Type: application/json');

$response = array();

// Consulta SQL para contar o número total de médicos
$sql = "SELECT COUNT(*) AS total_medicos FROM medicos";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $totalMedicos = $row['total_medicos'];

    $response['status'] = 'success';
    $response['total_medicos'] = $totalMedicos;
    $response['message'] = 'Total de médicos obtido com sucesso.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Erro ao obter o total de médicos: ' . $conn->error;
}

$conn->close();

echo json_encode($response);
?>
