<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Garante que o usuário está logado para acessar esta funcionalidade
if (!isset($_SESSION['user_id'])) {
    die("Acesso negado."); // Ou redirecionar para o login
}
include("conexao.php");

try {
    if (empty($_POST["nome"]) || empty($_POST["email"])) {
        throw new Exception("Nome e E-mail são obrigatórios.");
    }

    $user_id = $_SESSION['user_id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ? WHERE id_usuario = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'sssi', $nome, $email, $telefone, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Atualiza o nome na sessão para refletir a mudança imediatamente
        $_SESSION['user_name'] = $nome;
        
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Dados atualizados com sucesso!'
        ];
    } else {
        throw new Exception("Não foi possível atualizar os dados.");
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