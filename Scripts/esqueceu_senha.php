<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebAgenda - Recuperar Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Estilo para centralizar o card verticalmente na página */
        .forgot-password-container {
            min-height: 80vh;
        }
    </style>
</head>
<body style="background-color: #f0f2f5;">

<div class="container">
    <div class="row justify-content-center align-items-center forgot-password-container">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    
                    <div class="text-center mb-4">
                        <i class="bi bi-key-fill text-primary" style="font-size: 3rem;"></i>
                        <h3 class="mt-2">Esqueceu sua senha?</h3>
                        <p class="text-muted">Sem problemas! Insira seu e-mail abaixo e enviaremos um link para você criar uma nova senha.</p>
                    </div>

                    <form method="post" action="processa_recuperacao.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">Seu e-mail de cadastro</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="exemplo@email.com">
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Enviar Link de Recuperação</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted">Lembrou a senha? <a href="index.php?menuop=login">Voltar para o Login</a></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>