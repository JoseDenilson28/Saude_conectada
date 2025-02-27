<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Registo de Paciente</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/pages/login.css" />
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/sweetalert.js"></script>
</head>

<body class="bodyLogin">

    <div class="login">
    <h1 class="h1"><span>Registo de Paciente</span></h1>
    <form id="registerForm" class="form">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <br>
        <label for="data_nascimento">Data de Nascimento:</label>
        <input type="date" id="data_nascimento" name="data_nascimento" required>
        <br>
        <label for="genero">Género:</label>
        <select id="genero" name="genero" required>
            <option value="M">Masculino</option>
            <option value="F">Feminino</option>
        </select>
        <br>
        <label for="altura">Altura (m):</label>
        <input type="number" step="0.01" id="altura" name="altura" required>
        <br>
        <label for="peso">Peso (kg):</label>
        <input type="number" step="0.01" id="peso" name="peso" required>
        <br>
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone">
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
        <br>
        <button type="submit" class="botao">Registar</button>
        <button type="button" class="voltar" onclick="window.history.back()">Voltar</button>
    </form>

    <script>
    $(document).ready(function() {
        $('#registerForm').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../controllers/processa_registro_paciente.php',
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
                            // Limpa os campos do formulário
                            $('#registerForm')[0].reset();
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
                }
            });
        });
    });
    </script>
</body>

</html>