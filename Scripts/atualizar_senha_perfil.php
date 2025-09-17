<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    die("Acesso negado.");
}
include("conexao.php");

try {
    $user_id = $_SESSION['user_id'];
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';

    if (empty($senha_atual) || empty($nova_senha) || empty($confirma_senha)) {
        throw new Exception("Todos os campos de senha são obrigatórios.");
    }
    if ($nova_senha !== $confirma_senha) {
        throw new Exception("A nova senha e a confirmação não coincidem.");
    }
    if (strlen($nova_senha) < 6) {
        throw new Exception("A nova senha deve ter no mínimo 6 caracteres.");
    }

    // 1. Busca a senha atual (hash) do usuário no banco
    $sql_get_pass = "SELECT senha FROM usuarios WHERE id_usuario = ?";
    $stmt_get = mysqli_prepare($conexao, $sql_get_pass);
    mysqli_stmt_bind_param($stmt_get, 'i', $user_id);
    mysqli_stmt_execute($stmt_get);
    $result = mysqli_stmt_get_result($stmt_get);
    $user = mysqli_fetch_assoc($result);

    // 2. Verifica se a senha atual digitada está correta
    if (!$user || !password_verify($senha_atual, $user['senha'])) {
        throw new Exception("A senha atual está incorreta.");
    }

    // 3. Criptografa a nova senha
    $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

    // 4. Atualiza a nova senha no banco
    $sql_update = "UPDATE usuarios SET senha = ? WHERE id_usuario = ?";
    $stmt_update = mysqli_prepare($conexao, $sql_update);
    mysqli_stmt_bind_param($stmt_update, 'si', $nova_senha_hash, $user_id);

    if (mysqli_stmt_execute($stmt_update)) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Senha alterada com sucesso!'
        ];
    } else {
        throw new Exception("Não foi possível alterar a senha.");
    }

} catch (Exception $e) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro: ' . $e->getMessage()
    ];
}

header('Location: agenda.php?menuop=perfil');
exit();
?>