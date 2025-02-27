<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Login de Administrador</title>
    <link rel="stylesheet" href="../css/pages/login.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/sweetalert2.min.css">
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/sweetalert.js"></script>
</head>

<body class="bodyLogin">
    <div class="login">
        <h1 class="h1"><span>Login de Administrador</span></h1>
        <form id="loginAdminForm" class="form">
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div>
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <button type="submit" class="botao">Entrar</button>
            <button type="button" onclick="window.history.back()" class="voltar">Voltar</button>
        </form>
    </div>
    <script>
    $(document).ready(function() {
        $('#loginAdminForm').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../controllers/processa_login_admin.php',
                data: $(this).serialize(),
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
                            window.location.href = 'dash.php';
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
                }
            });
        });
    });
    </script>
</body>

</html>