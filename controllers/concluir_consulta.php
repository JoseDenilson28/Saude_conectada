<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['medico_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado']);
    exit;
}

// Inclui o arquivo de configuração do banco de dados
include '../config/database.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $consultaId = $_POST['consulta_id'];
        $medicoId = $_SESSION['medico_id'];
        $pacienteId = $_POST['paciente_id'];
        $dataHora = date('Y-m-d H:i:s');

        // Insere os dados na tabela de consultas concluídas
        $queryInsert = "INSERT INTO consultas_concluidas (consulta_id, medico_id, paciente_id, data_hora)
                        VALUES (?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($queryInsert);
        $stmtInsert->bind_param("iiis", $consultaId, $medicoId, $pacienteId, $dataHora);

        if ($stmtInsert->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $stmtInsert->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
