<?php
session_start();
include("conexao.php");

$token_valido = false;
$token = $_GET['token'] ?? '';

if (!empty($token)) {
    $sql = "SELECT id_usuario FROM usuarios WHERE reset_token = ? AND token_expira_em > NOW()";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 's', $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        $token_valido = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebAgenda - Redefinir Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="background-color: #f0f2f5;">
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <?php if ($token_valido): ?>
                    <div class="text-center mb-4"><i class="bi bi-key-fill text-primary" style="font-size: 3rem;"></i><h3 class="mt-2">Crie sua Nova Senha</h3></div>
                    <form method="post" action="atualiza_senha.php">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        <div class="mb-3">
                            <label for="nova_senha" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirma_senha" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" required>
                        </div>
                        <div class="d-grid mt-4"><button type="submit" class="btn btn-primary btn-lg">Salvar Nova Senha</button></div>
                    </form>
                <?php else: ?>
                    <div class="text-center"><i class="bi bi-x-circle-fill text-danger" style="font-size: 3rem;"></i><h3 class="mt-2 text-danger">Link Inválido ou Expirado</h3>
                        <p class="text-muted">Este link de recuperação não é mais válido. Por favor, solicite um novo.</p>
                        <a href="index.php?menuop=login" class="btn btn-secondary mt-3">Voltar para o Login</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>