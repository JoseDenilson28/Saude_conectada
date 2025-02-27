<?php
session_start();

if (!isset($_SESSION['medico_id'])) {
    header('Location: ../index.php');
    exit;
}

// Inclui o arquivo de configuração do banco de dados
include '../config/database.php';

// Obtém o nome e o cargo do médico da sessão
$nomeMedico = htmlspecialchars($_SESSION['medico_nome']);
$cargoMedico = htmlspecialchars($_SESSION['medico_cargo']);

// Consulta para buscar as consultas marcadas que não foram concluídas
$query = "SELECT c.id, c.paciente_id, c.data_consulta, c.motivo, p.nome AS nome_paciente
          FROM consultas c
          INNER JOIN pacientes p ON c.paciente_id = p.id
          WHERE NOT EXISTS (
              SELECT 1 FROM consultas_concluidas cc
              WHERE cc.consulta_id = c.id
          )
          ORDER BY c.data_consulta ASC"; // Pode ajustar a ordem conforme necessário

$result = $conn->query($query);

if (!$result) {
    die('Erro na consulta: ' . $conn->error);
}

// Array para armazenar as consultas
$consultas = array();

while ($row = $result->fetch_assoc()) {
    $consultas[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaúdeConectada - Consultas Marcadas</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/sweetalert2.min.css">
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/sweetalert.js"></script>
</head>

<body class="bodyLogin">
    <header class="header content-max">
        <h1 class="logomarca"><span>Saúde</span>Conectada</h1>
        <nav class="menu">
            <ul class="menu-list">
                <li><a href="cadastro_paciente.php">Cadastro de paciente</a></li>
                <li><a href="agenda_consulta.php">Agendamento de consulta</a></li>
            </ul>

            <button class="loginclicar">
                <a href="#" id="logoutLink" class="loginclicar">Logout</a>
            </button>
        </nav>
    </header>

    <main class="main-content">
        <h1>Seja Bem-vindo(a), <?php echo $cargoMedico . ' ' . $nomeMedico; ?>!</h1>
        <p>Aqui está o total de consultas feitas pelo <?php echo $cargoMedico . ' ' . $nomeMedico; ?>:</p>
        <h2><span id="TconsultasC"></span></h2>
        <p>Continue se esforçando para um mundo melhor. Tenha um bom dia de trabalho, <?php echo $cargoMedico; ?>!</p>

        <div class="doutor">
            <h2>Consultas Marcadas</h2>

            <?php foreach ($consultas as $consulta) : ?>
            <div class="consulta" data-id="<?php echo $consulta['id']; ?>"
                data-paciente-id="<?php echo $consulta['paciente_id']; ?>"
                data-nome="<?php echo $consulta['nome_paciente']; ?>">
                <p><strong>Paciente:</strong> <?php echo $consulta['nome_paciente']; ?></p>
                <p><strong>Data da Consulta:</strong> <?php echo $consulta['data_consulta']; ?></p>
                <p><strong>Motivo:</strong> <?php echo $consulta['motivo']; ?></p>
                <button class="receitaBtn">Receita</button>
                <button class="concluidoBtn">Concluído</button>
            </div>
            <?php endforeach; ?>
        </div>
    </main>


    <script>
    document.addEventListener('DOMContentLoaded', function() {

        // Função para carregar o total de consultas do médico
        function carregarTotalConsultasMedico() {
            $.ajax({
                type: 'POST',
                url: '../controllers/total_consultas_medico.php', // Caminho para o script PHP que conta as consultas
                data: {
                    medico_id: <?php echo $_SESSION['medico_id']; ?> // Substitua pelo ID do médico dinamicamente
                },
                dataType: 'json',
                success: function(response) {
                    // Verifica se a resposta contém o campo 'total_consultas'
                    if (response.hasOwnProperty('total_consultas')) {
                        $('#TconsultasC').text(response
                            .total_consultas); // Atualiza o span com o total de consultas
                    } else {
                        console.error('Resposta do servidor incompleta ou inválida:', response);
                        // Trate aqui qualquer erro de resposta inesperada
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição AJAX:', error);
                    // Aqui você pode adicionar um tratamento de erro, se necessário
                }
            });
        }

        // Chama a função para carregar o total de consultas ao carregar a página
        carregarTotalConsultasMedico();

        // Event listener para o clique no link de logout
        document.getElementById('logoutLink').addEventListener('click', function(event) {
            event.preventDefault();

            // Exibir SweetAlert para confirmar o logout
            Swal.fire({
                title: 'Tem a certeza que deseja sair?',
                text: 'Será redirecionado para a página de login.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, sair',
                cancelButtonText: 'Cancelar',
                didOpen: () => {
                    document.body.classList.remove('swal2-shown', 'swal2-height-auto');
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirecionar para a página de logout
                    window.location.href = '../controllers/logout.php';
                }
            });
        });

        // Event listener para o botão Receita
        const receitaBtns = document.querySelectorAll('.receitaBtn');
        receitaBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const nomePaciente = this.parentElement.getAttribute('data-nome');
                const googleDocsUrl =
                    'https://docs.google.com/document/'; // Substitua pelo seu link do Google Docs

                // Abrir o Google Docs em uma nova aba com o nome do paciente como parâmetro na URL
                const newUrl =
                    `${googleDocsUrl}`;
                window.open(newUrl, '_blank');
            });
        });

        // Event listener para o botão Concluído
        $(document).on('click', '.concluidoBtn', function() {
            const consultaId = $(this).closest('.consulta').data('id');
            const pacienteId = $(this).closest('.consulta').data('paciente-id');

            if (!consultaId || !pacienteId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'ID da consulta ou ID do paciente não encontrado.',
                    didOpen: () => {
                        document.body.classList.remove('swal2-shown', 'swal2-height-auto');
                    }
                });
                return;
            }

            // Enviar requisição AJAX para marcar a consulta como concluída
            $.ajax({
                url: '../controllers/concluir_consulta.php',
                method: 'POST',
                data: {
                    //Desenvolvido por:Deny28
                    consulta_id: consultaId,
                    paciente_id: pacienteId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Consulta Concluída',
                            text: 'Consulta marcada como concluída.',
                            didOpen: () => {
                                document.body.classList.remove('swal2-shown',
                                    'swal2-height-auto');
                            }
                        }).then(() => {
                            $(`.consulta[data-id="${consultaId}"]`).remove();
                            carregarTotalConsultasMedico
                                (); // Chama a função para atualizar o total de consultas
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: response.message ||
                                'Erro ao concluir a consulta.',
                            didOpen: () => {
                                document.body.classList.remove('swal2-shown',
                                    'swal2-height-auto');
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Erro ao concluir a consulta.',
                        didOpen: () => {
                            document.body.classList.remove('swal2-shown',
                                'swal2-height-auto');
                        }
                    });
                }
            });

        });

    });
    </script>
</body>

</html>