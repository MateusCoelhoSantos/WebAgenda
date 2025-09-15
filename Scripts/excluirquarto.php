<?php
// 1. Inicia a sessão para usar as mensagens de feedback
session_start();
include("conexao.php"); // Inclui seu arquivo de conexão

// 2. Bloco try-catch para um tratamento de erros mais robusto
try {
    // 3. Validação do ID recebido via GET
    $id_quarto = $_GET['idquarto'] ?? null;
    if ($id_quarto === null || !is_numeric($id_quarto)) {
        throw new Exception("ID do quarto é inválido ou não foi fornecido.");
    }

    // 4. Prepara a consulta SQL com placeholders (?) para máxima segurança
    $sql = "UPDATE quartos SET excluido = 1 WHERE id_quarto = ?";
    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt === false) {
        throw new Exception("Erro ao preparar a consulta: " . mysqli_error($conexao));
    }
    
    // 5. Associa o parâmetro (bind) com o tipo correto (i = integer)
    mysqli_stmt_bind_param($stmt, "i", $id_quarto);
    
    // 6. Executa a consulta
    if (mysqli_stmt_execute($stmt)) {
        // Se a execução foi bem-sucedida, define a mensagem de sucesso
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Quarto excluído com sucesso!'
        ];
    } else {
        // Se falhou, lança uma exceção
        throw new Exception("Não foi possível excluir o quarto.");
    }

} catch (Exception $e) {
    // Se qualquer erro ocorreu, define a mensagem de erro
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro: ' . $e->getMessage()
    ];
}

// 7. Redirecionamento
// Independentemente do resultado, redireciona o usuário de volta para a lista de quartos
header('Location: agenda.php?menuop=quartos');
exit(); // Encerra o script para garantir que o redirecionamento ocorra
?>