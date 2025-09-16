<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $token = $_POST['token'] ?? '';
        $nova_senha = $_POST['nova_senha'] ?? '';
        $confirma_senha = $_POST['confirma_senha'] ?? '';

        if (empty($token) || empty($nova_senha)) {
            throw new Exception("Dados inválidos.");
        }
        if ($nova_senha !== $confirma_senha) {
            throw new Exception("As senhas não coincidem.");
        }
        if (strlen($nova_senha) < 6) {
            throw new Exception("A senha deve ter no mínimo 6 caracteres.");
        }

        // Criptografa a nova senha (MUITO IMPORTANTE)
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        // Atualiza a senha e invalida o token para que não possa ser usado novamente
        $sql = "UPDATE usuarios SET senha = ?, reset_token = NULL, token_expira_em = NULL WHERE reset_token = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $senha_hash, $token);
        
        if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Sua senha foi redefinida com sucesso! Você já pode fazer o login.'
            ];
        } else {
            throw new Exception("Não foi possível atualizar sua senha. O token pode ser inválido.");
        }

    } catch (Exception $e) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Erro: ' . $e->getMessage()
        ];
    }
    
    header('Location: index.php?menuop=login');
    exit();
}
?>