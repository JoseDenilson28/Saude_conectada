<?php
session_start();
include '../config/database.php';

header('Content-Type: application/json');

$response = array();

// Consulta SQL para contar o número total de pacientes
$sql = "SELECT COUNT(*) AS total_pacientes FROM pacientes";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $totalPacientes = $row['total_pacientes'];

    $response['status'] = 'success';
    $response['total_pacientes'] = $totalPacientes;
    $response['message'] = 'Total de pacientes obtido com sucesso.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Erro ao obter o total de pacientes: ' . $conn->error;
}

$conn->close();

echo json_encode($response);
?>
