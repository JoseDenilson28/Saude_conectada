<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/style.css" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/sweetalert2.min.css">
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/sweetalert.js"></script>
</head>

<body>
    <header class="header">
        <h1 class="h1-admin">Dashboard
            <p>Bem-vindo, <span><?php echo htmlspecialchars($_SESSION['admin_nome']); ?></span>!</p>
        </h1>

        <nav>
            <ul>
                <li>
                    <a href="#" id="logoutLink" class="loginclicar">Logout</a>
                </li>
            </ul>
        </nav>
    </header>
    <main class="DashboardMain">
        <div class="Dashboard-menu">
            <a href="register_medico.php">Registro de medicos</a>


            <a href="register.php">Cadastro de Administrador</a>



            <a href="lista_medicos.php">Total de médicos <span id="Tmedicos"></span> </a>

            <a href="lista_pacientes.php">Total de pacientes <span id="Tpacientes"></span></a>

        </div>

    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '../controllers/consulta_total_medicos.php',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var totalMedicos = response.total_medicos;
                    // Atualiza o conteúdo do span com o total de médicos
                    $('#Tmedicos').text(totalMedicos);
                } else {
                    console.error('Erro ao obter o total de médicos:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
            }
        });
        // Função para obter o total de pacientes
        $.ajax({
            type: 'GET',
            url: '../controllers/consulta_total_pacientes.php', // Ajuste o caminho conforme necessário
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var totalPacientes = response.total_pacientes;
                    console.log('Total de pacientes:', totalPacientes);
                    // Atualiza o conteúdo do span com o total de pacientes
                    $('#Tpacientes').text(totalPacientes);
                } else {
                    console.error('Erro ao obter o total de pacientes:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição de pacientes:', error);
            }
        });
    });
    </script>
</body>

</html>