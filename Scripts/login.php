<?php
// A conexão com o banco será usada no processamento do login, não aqui.
// É bom mantê-la se o seu 'agenda.php' espera essa inclusão.
require 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebAgenda - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Estilo para centralizar o card de login verticalmente na página */
        .login-container {
            min-height: 80vh;
        }
    </style>
</head>
<body style="background-color: #f0f2f5;">

<div class="container">
    <div class="row justify-content-center align-items-center login-container">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    
                    <div class="text-center mb-4">
                        <i class="bi bi-calendar-check text-primary" style="font-size: 3rem;"></i>
                        <h3 class="mt-2">Acesse o WebAgenda</h3>
                        <p class="text-muted">Bem-vindo(a) de volta!</p>
                    </div>

                    <form method="post" action="agenda.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control" id="senha" name="senha" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    Lembrar-me
                                </label>
                            </div>
                            <a href="esqueceu_senha.php" class="small">Esqueceu a senha?</a>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted">Não tem uma conta? <a href="index.php?menuop=cadastro">Cadastre-se</a></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
// Este bloco verifica se existe uma mensagem na sessão
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    ?>
    <script>
        // Dispara o pop-up do SweetAlert2
        Swal.fire({
            icon: '<?= $message['type'] ?>', // 'success' ou 'error'
            title: '<?= $message['text'] ?>',
            showConfirmButton: false,
            timer: 3000 // Aumentei o tempo para dar tempo de ler
        });
    </script>
    <?php
    // Limpa a mensagem da sessão para que ela não apareça novamente
    unset($_SESSION['message']);
}
?>

</body>
</html>