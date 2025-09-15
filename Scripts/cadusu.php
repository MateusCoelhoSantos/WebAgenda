<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebAgenda - Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Garante que o card fique bem posicionado verticalmente */
        .register-container {
            min-height: 80vh;
        }
    </style>
</head>
<body style="background-color: #f0f2f5;">

<div class="container">
    <div class="row justify-content-center align-items-center register-container">
        <div class="col-12 col-md-10 col-lg-8 col-xl-7">
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
                        <h3 class="mt-2">Crie sua Conta</h3>
                        <p class="text-muted">É rápido e fácil.</p>
                    </div>

                    <form method="post" action="insereusuario.php">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" id="nome" name="nome" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                                    <input type="text" class="form-control" id="cpf" name="cpf" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                    <input type="text" class="form-control" id="telefone" name="telefone" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control" id="senha" name="senha" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="confirma_senha" class="form-label">Confirmar Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary btn-lg">Cadastrar</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted">Já tem uma conta? <a href="index.php?menuop=login">Faça o login</a></p>
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