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
    $pasta_uploads = "../uploads/fotos_perfil/"; // Confirme se este caminho está correto

    // 1. Busca o nome do arquivo da foto atual no banco de dados
    $sql_get = "SELECT foto_perfil FROM usuarios WHERE id_usuario = ?";
    $stmt_get = mysqli_prepare($conexao, $sql_get);
    mysqli_stmt_bind_param($stmt_get, 'i', $user_id);
    mysqli_stmt_execute($stmt_get);
    $result = mysqli_stmt_get_result($stmt_get);
    $nome_arquivo_antigo = mysqli_fetch_assoc($result)['foto_perfil'] ?? null;

    // 2. Atualiza o banco de dados, definindo a foto como NULL
    $sql_update = "UPDATE usuarios SET foto_perfil = NULL WHERE id_usuario = ?";
    $stmt_update = mysqli_prepare($conexao, $sql_update);
    mysqli_stmt_bind_param($stmt_update, 'i', $user_id);
    
    if (mysqli_stmt_execute($stmt_update)) {
        // 3. Se o banco foi atualizado, remove o arquivo físico do servidor
        if ($nome_arquivo_antigo && file_exists($pasta_uploads . $nome_arquivo_antigo)) {
            unlink($pasta_uploads . $nome_arquivo_antigo);
        }

        $_SESSION['user_photo'] = null;
        
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Foto de perfil removida com sucesso!'
        ];
    } else {
        throw new Exception("Não foi possível remover a foto do banco de dados.");
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