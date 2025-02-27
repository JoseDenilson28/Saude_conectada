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
    <title>Lista de Pacientes</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/pages/login.css" />
    <link rel="stylesheet" href="../css/sweetalert2.min.css">
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/sweetalert.js"></script>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table,
    th,
    td {
        border: 1px solid var(--c-4);
        padding: 10px;
        text-align: left;
    }

    td {
        color: var(--c-f);
        font: 500 1rem/1 "arial";
    }

    th {
        background-color: var(--c-3);
        color: var(--c-1);
        font: 500 1.2rem/1 "arial";
    }

    .actions {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }

    .edit-modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .edit-modal input,
    .edit-modal select {
        display: block;
        margin-bottom: 10px;
        width: calc(100% - 20px);
        padding: 8px;
    }

    .cor {
        color: var(--c-4);
    }
    </style>
</head>

<body class="bodyLogin">
    <button type="button" onclick="window.history.back()" class="voltar">Voltar</button>
    <div class="container">

        <h1 class="h1"><span>Lista de Pacientes</span></h1>
        <br>
        <div class="actions">
            <input type="text" id="searchInput" placeholder="Pesquisar por Nome, Email ou Telefone">
            <button onclick="searchPacientes()">Pesquisar</button>
            <button onclick="resetSearch()" class="limpar">Limpar</button>
        </div>
        <table id="pacientesTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Data de Nascimento</th>
                    <th>Gênero</th>
                    <th>Altura (m)</th>
                    <th>Peso (kg)</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Data de Registro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aqui serão inseridas as linhas da tabela via JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Modal de edição class=""-->
    <div id="editModal" class="edit-modal">
        <h2>Editar Paciente</h2>
        <form id="editPacienteForm" class="form">
            <input type="hidden" id="editId">
            <div>
                <label for="editNome">Nome:</label>
                <input type="text" id="editNome" name="nome" required>
            </div>

            <div>
                <label for="editDataNascimento">Data de Nascimento:</label>
                <input type="date" id="editDataNascimento" name="data_nascimento" required>
            </div>

            <div>
                <label for="editGenero">Gênero:</label>
                <select id="editGenero" name="genero" required>
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                </select>
            </div>

            <div>
                <label for="editAltura">Altura (m):</label>
                <input type="number" id="editAltura" name="altura" step="0.10" required>
            </div>

            <div>
                <label for="editPeso">Peso (kg):</label>
                <input type="number" id="editPeso" name="peso" step="0.01" required>
            </div>

            <div>
                <label for="editTelefone">Telefone:</label>
                <input type="text" id="editTelefone" name="telefone">
            </div>

            <div>
                <label for="editEmail">Email:</label>
                <input type="email" id="editEmail" name="email" required>
            </div>
            <button type="button" onclick="savePaciente()" class="botao">Salvar</button>
            <button type="button" onclick="closeModal()" class="botaoS">Cancelar</button>
        </form>
    </div>

    <script>
    // Função para carregar a lista de pacientes ao carregar a página
    $(document).ready(function() {
        loadPacientes();
    });

    // Função para carregar os pacientes da base de dados
    function loadPacientes() {
        $.ajax({
            type: 'GET',
            url: '../controllers/consulta_lista_pacientes.php',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayPacientes(response.pacientes);
                } else {
                    console.error('Erro ao carregar lista de pacientes:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
            }
        });
    }

    // Função para exibir os pacientes na tabela
    function displayPacientes(pacientes) {
        var tableBody = $('#pacientesTable tbody');
        tableBody.empty(); // Limpa o conteúdo atual da tabela

        pacientes.forEach(function(paciente) {
            var row = $('<tr>');
            row.append($('<td>').text(paciente.id));
            row.append($('<td>').text(paciente.nome));
            row.append($('<td>').text(paciente.data_nascimento));
            row.append($('<td>').text(paciente.genero));
            var alturaFormatted = parseFloat(paciente.altura).toFixed(2);
            row.append($('<td>').text(alturaFormatted));
            row.append($('<td>').text(paciente.peso));
            row.append($('<td>').text(paciente.telefone));
            row.append($('<td>').text(paciente.email));
            row.append($('<td>').text(paciente.data_registo));

            // Coluna de ações (Editar e Excluir)
            var actions = $('<td>');
            var editButton = $('<button>').text('Editar').addClass('cor').click(function() {
                editPaciente(paciente.id);
            });
            var deleteButton = $('<button>').text('Excluir').addClass('cor').click(function() {
                deletePaciente(paciente.id, paciente.nome);
            });
            actions.append(editButton);
            actions.append(deleteButton);

            row.append(actions);
            tableBody.append(row);
        });
    }

    // Função para pesquisar pacientes por nome, email ou telefone
    function searchPacientes() {
        var searchTerm = $('#searchInput').val();
        $.ajax({
            type: 'POST',
            url: '../controllers/pesquisa_pacientes.php',
            data: {
                search: searchTerm
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayPacientes(response.pacientes);
                } else {
                    console.error('Erro na pesquisa de pacientes:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição de pesquisa:', error);
            }
        });
    }

    // Função para limpar o campo de pesquisa e recarregar todos os pacientes
    function resetSearch() {
        $('#searchInput').val('');
        loadPacientes();
    }

    // Função para editar um paciente
    function editPaciente(id) {
        $.ajax({
            type: 'POST',
            url: '../controllers/consulta_paciente.php',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var paciente = response.paciente;
                    $('#editId').val(paciente.id);
                    $('#editNome').val(paciente.nome);
                    $('#editDataNascimento').val(paciente.data_nascimento);
                    $('#editGenero').val(paciente.genero);
                    $('#editAltura').val(paciente.altura);
                    $('#editPeso').val(paciente.peso);
                    $('#editTelefone').val(paciente.telefone);
                    $('#editEmail').val(paciente.email);
                    $('#editModal').show();
                } else {
                    console.error('Erro ao buscar dados do paciente:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
            }
        });
    }

    // Função para salvar as alterações do paciente
    function savePaciente() {
        var id = $('#editId').val();
        var nome = $('#editNome').val();
        var dataNascimento = $('#editDataNascimento').val();
        var genero = $('#editGenero').val();
        var altura = $('#editAltura').val();
        var peso = $('#editPeso').val();
        var telefone = $('#editTelefone').val();
        var email = $('#editEmail').val();

        $.ajax({
            type: 'POST',
            url: '../controllers/atualiza_paciente.php',
            data: {
                id: id,
                nome: nome,
                data_nascimento: dataNascimento,
                genero: genero,
                altura: altura,
                peso: peso,
                telefone: telefone,
                email: email
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    closeModal();
                    loadPacientes();
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.message,
                        didOpen: () => {
                            document.body.classList.remove('swal2-shown', 'swal2-height-auto');
                        }
                    });
                } else {
                    console.error('Erro ao salvar paciente:', response.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Ocorreu um erro ao salvar o paciente.',
                        didOpen: () => {
                            document.body.classList.remove('swal2-shown', 'swal2-height-auto');
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Ocorreu um erro na requisição.',
                    didOpen: () => {
                        document.body.classList.remove('swal2-shown', 'swal2-height-auto');
                    }
                });
            }
        });
    }

    // Função para fechar o modal de edição
    function closeModal() {
        $('#editModal').hide();
    }

    // Função para confirmar e deletar um paciente
    function deletePaciente(id, nome) {
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Você deseja excluir o paciente ' + nome + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: '../controllers/exclui_paciente.php',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            loadPacientes();
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: response.message,
                                didOpen: () => {
                                    document.body.classList.remove('swal2-shown',
                                        'swal2-height-auto');
                                }
                            });
                        } else {
                            console.error('Erro ao excluir paciente:', response.message);
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Ocorreu um erro ao excluir o paciente.',
                                didOpen: () => {
                                    document.body.classList.remove('swal2-shown',
                                        'swal2-height-auto');
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro na requisição:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Ocorreu um erro na requisição.',
                            didOpen: () => {
                                document.body.classList.remove('swal2-shown',
                                    'swal2-height-auto');
                            }
                        });
                    }
                });
            }
        });
    }
    </script>
</body>

</html>