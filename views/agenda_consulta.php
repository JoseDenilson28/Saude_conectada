<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Agendar Consulta</title>
    <link rel="stylesheet" href="../css/pages/login.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/sweetalert2.min.css">
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/sweetalert.js"></script>
</head>

<body class="bodyLogin">
    
    <div class="login">
        
        <h1 class="h1"><span>Agendar Consulta</span></h1>
        <form id="consultaForm" action="../controllers/processa_agenda_consulta.php" method="POST" class="form">
            <div>
                <label for="paciente_id">Paciente:</label>
                <select id="paciente_id" name="paciente_id" required>
                    <!-- Aqui você deve carregar os pacientes do banco de dados -->
                </select>
            </div>
            <div>
                <label for="data_consulta">Data da Consulta:</label>
                <input type="datetime-local" id="data_consulta" name="data_consulta" required>
            </div>
            <div style="margin-bottom: 15px;">
                <label for="motivo">Motivo da Consulta:</label>
                <textarea id="motivo" name="motivo" rows="3" required></textarea>
            </div>
            <button type="submit" class="botao">Agendar Consulta</button>
            <button type="button" onclick="window.history.back()" class="voltar">Voltar</button>
        </form>
    </div>

    <script>
    // Script para carregar os pacientes no select
    document.addEventListener('DOMContentLoaded', function() {
        fetchPacientes();
    });

    function fetchPacientes() {
        fetch('../controllers/consulta_pacientes.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const selectPaciente = document.getElementById('paciente_id');
                    selectPaciente.innerHTML = ''; // Limpa as opções existentes

                    data.pacientes.forEach(paciente => {
                        const option = document.createElement('option');
                        option.value = paciente.id;
                        option.textContent = paciente.nome;
                        selectPaciente.appendChild(option);
                    });
                } else {
                    console.error('Erro ao carregar pacientes:', data.message);
                }
            })
            .catch(error => console.error('Erro na requisição:', error));
    }

    // Intercepta o envio do formulário via AJAX
    $('#consultaForm').submit(function(event) {
        event.preventDefault(); // Previne o envio padrão do formulário

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso',
                        text: response.message,
                didOpen: () => {
                    document.body.classList.remove('swal2-shown', 'swal2-height-auto');
                }
                    }).then(() => {
                        // Limpa o formulário após o sucesso
                        $('#consultaForm')[0].reset();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: response.message,
                didOpen: () => {
                    document.body.classList.remove('swal2-shown', 'swal2-height-auto');
                }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Erro ao processar a solicitação.',
                didOpen: () => {
                    document.body.classList.remove('swal2-shown', 'swal2-height-auto');
                }
                });
            }
        });
    });
    </script>
</body>

</html>