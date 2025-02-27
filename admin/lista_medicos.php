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
    <title>Lista de Médicos</title>
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

    .edit-modal input {
        display: block;
        margin-bottom: 10px;
        width: 100%;
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
        <h1 class="h1"><span>Lista de Médicos</span></h1>
        <br>

        <div class="actions">
            <input type="text" id="searchInput" placeholder="Pesquisar por Nome, Cargo ou Área">
            <button onclick="searchMedicos()">Pesquisar</button>
            <button class="limpar" onclick="resetSearch()">Limpar</button>
        </div>
        <table id="medicosTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cargo</th>
                    <th>Área</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aqui serão inseridas as linhas da tabela via JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Modal de edição  class=""-->
    <div id="editModal" class="edit-modal">
        <h2>Editar Médico</h2>
        <form id="editMedicoForm" class="form">
            <input type="hidden" id="editId">
            <div>
                <label for="editNome">Nome:</label>
                <input type="text" id="editNome" name="nome" required>
            </div>

            <div>
                <label for="editCargo">Cargo:</label>
                <input type="text" id="editCargo" name="cargo" required>
            </div>

            <div>
                <label for="editArea">Área:</label>
                <input type="text" id="editArea" name="area" required>
            </div>

            <div>
                <label for="editTelefone">Telefone:</label>
                <input type="text" id="editTelefone" name="telefone">
            </div>

            <div>
                <label for="editEmail">Email:</label>
                <input type="email" id="editEmail" name="email" required>
            </div>
            <button type="button" onclick="saveMedico()" class="botao">Salvar</button>
            <button type="button" onclick="closeModal()" class="botaoS">Cancelar</button>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        loadMedicos();
    });

    function loadMedicos() {
        $.ajax({
            type: 'GET',
            url: '../controllers/consulta_lista_medicos.php',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayMedicos(response.medicos);
                } else {
                    console.error('Erro ao carregar lista de médicos:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
            }
        });
    }

    function displayMedicos(medicos) {
        var tableBody = $('#medicosTable tbody');
        tableBody.empty();
        medicos.forEach(function(medico) {
            var row = $('<tr>');
            row.append($('<td>').text(medico.id));
            row.append($('<td>').text(medico.nome));
            row.append($('<td>').text(medico.cargo));
            row.append($('<td>').text(medico.area));
            row.append($('<td>').text(medico.telefone));
            row.append($('<td>').text(medico.email));
            var actions = $('<td>');
            var editButton = $('<button>').text('Editar').addClass('cor').click(function() {
                editMedico(medico.id);
            });
            var deleteButton = $('<button>').text('Excluir').addClass('cor').click(function() {
                deleteMedico(medico.id, medico.nome);
            });
            actions.append(editButton);
            actions.append(deleteButton);
            row.append(actions);
            tableBody.append(row);
        });
    }

    function searchMedicos() {
        var searchTerm = $('#searchInput').val();
        $.ajax({
            type: 'POST',
            url: '../controllers/pesquisa_medicos.php',
            data: {
                search: searchTerm
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayMedicos(response.medicos);
                } else {
                    console.error('Erro na pesquisa de médicos:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição de pesquisa:', error);
            }
        });
    }

    function resetSearch() {
        $('#searchInput').val('');
        loadMedicos();
    }

    function editMedico(id) {
        $.ajax({
            type: 'POST',
            url: '../controllers/consulta_medico.php',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var medico = response.medico;
                    $('#editId').val(medico.id);
                    $('#editNome').val(medico.nome);
                    $('#editCargo').val(medico.cargo);
                    $('#editArea').val(medico.area);
                    $('#editTelefone').val(medico.telefone);
                    $('#editEmail').val(medico.email);
                    $('#editModal').show();
                } else {
                    console.error('Erro ao buscar dados do médico:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
            }
        });
    }

    function saveMedico() {
        var id = $('#editId').val();
        var nome = $('#editNome').val();
        var cargo = $('#editCargo').val();
        var area = $('#editArea').val();
        var telefone = $('#editTelefone').val();
        var email = $('#editEmail').val();
        $.ajax({
            type: 'POST',
            url: '../controllers/atualiza_medico.php',
            data: {
                id: id,
                nome: nome,
                cargo: cargo,
                area: area,
                telefone: telefone,
                email: email
            },
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
                        $('#editModal').hide();
                        loadMedicos();
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
                console.error('Erro na requisição:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Erro ao atualizar médico.',
                    didOpen: () => {
                        document.body.classList.remove('swal2-shown', 'swal2-height-auto');
                    }
                });
            }
        });
    }

    function deleteMedico(id, nome) {
        Swal.fire({
            title: 'Tem certeza?',
            text: `Deseja excluir o médico ${nome}? Esta ação não pode ser desfeita.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar',
            didOpen: () => {
                document.body.classList.remove('swal2-shown', 'swal2-height-auto');
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: '../controllers/exclui_medico.php',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                text: response.message,
                                didOpen: () => {
                                    document.body.classList.remove('swal2-shown',
                                        'swal2-height-auto');
                                }
                            }).then(() => {
                                loadMedicos();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro',
                                text: response.message,
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
                            title: 'Erro',
                            text: 'Erro ao excluir médico.',
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

    function closeModal() {
        $('#editModal').hide();
    }
    </script>
</body>

</html>