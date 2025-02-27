<?php
session_start();
include '../config/database.php';

// Verifica se o ID do médico foi passado via POST
if (isset($_POST['medico_id'])) {
    $medico_id = $_POST['medico_id'];

    // Consulta SQL para contar o número de consultas do médico
    $sql = "SELECT COUNT(*) AS total_consultas FROM consultas_concluidas WHERE medico_id = $medico_id";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_consultas = $row['total_consultas'];

        // Fecha a conexão
        $conn->close();

        // Retorna o total de consultas como JSON
        echo json_encode(['total_consultas' => $total_consultas]);
        exit; // Importante: encerra o script após enviar a resposta JSON
    } else {
        // Se não houver resultados, retorne um JSON indicando zero consultas
        echo json_encode(['total_consultas' => 0]);
        exit;
    }
} else {
    // Se o ID do médico não foi recebido, retorna um erro JSON
    echo json_encode(['error' => 'ID do médico não recebido']);
    exit;
}
?>
