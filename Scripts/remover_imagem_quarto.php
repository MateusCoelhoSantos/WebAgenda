<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 
$idquarto = $_GET['id_quarto'] ?? 0;

try {
    $id_imagem = $_GET['id_imagem'] ?? 0;
    if ($id_imagem == 0) throw new Exception("ID da imagem inválido.");

    $pasta_uploads = "../Imagens/Quartos/";

    $sql_get = "SELECT nome_arquivo FROM quarto_imagens WHERE id_imagem = ?";
    $stmt_get = mysqli_prepare($conexao, $sql_get);
    mysqli_stmt_bind_param($stmt_get, 'i', $id_imagem);
    mysqli_stmt_execute($stmt_get);
    $nome_arquivo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_get))['nome_arquivo'] ?? null;

    $sql_delete = "DELETE FROM quarto_imagens WHERE id_imagem = ?";
    $stmt_delete = mysqli_prepare($conexao, $sql_delete);
    mysqli_stmt_bind_param($stmt_delete, 'i', $id_imagem);

    if (mysqli_stmt_execute($stmt_delete)) {
        if ($nome_arquivo && file_exists($pasta_uploads . $nome_arquivo)) {
            unlink($pasta_uploads . $nome_arquivo);
        }
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Imagem removida com sucesso!'];
    } else {
        throw new Exception("Não foi possível remover a imagem.");
    }
} catch (Exception $e) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Erro: ' . $e->getMessage()];
}

header('Location: agenda.php?menuop=editarquarto&idquarto=' . $idquarto);
exit();
?>