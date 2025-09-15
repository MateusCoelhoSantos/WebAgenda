<?php
// 1. Inicia a sessão para usar as mensagens de feedback
session_start();
include("conexao.php"); // Inclui seu arquivo de conexão

// 2. Bloco try-catch para um tratamento de erros mais robusto
try {
    // 3. Validação dos dados essenciais
    if (empty($_POST["numquarto"]) || empty($_POST["descricao"])) {
        throw new Exception("Número do quarto e descrição são obrigatórios.");
    }

    // Captura os dados do POST
    $numquarto = $_POST["numquarto"];
    $descricao = $_POST["descricao"];

    // 4. Prepara a consulta SQL com placeholders (?) para máxima segurança
    $sql = "INSERT INTO quartos (num_quarto, descricao, status, excluido) VALUES (?, ?, 0, 0)";
    
    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt === false) {
        throw new Exception("Erro ao preparar a consulta: " . mysqli_error($conexao));
    }

    // 5. Associa os parâmetros (bind) com os tipos corretos (s = string)
    mysqli_stmt_bind_param($stmt, 'ss', $numquarto, $descricao);
    
    // 6. Executa a consulta
    if (mysqli_stmt_execute($stmt)) {
        // Se deu certo, cria a mensagem de sucesso
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Quarto cadastrado com sucesso!'
        ];
    } else {
        throw new Exception("Não foi possível executar o cadastro.");
    }

} catch (Exception $e) {
    // Se deu qualquer erro, cria a mensagem de erro
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro ao cadastrar o quarto: ' . $e->getMessage()
    ];
}

// 7. Redireciona o usuário de volta para a lista de quartos
header('Location: agenda.php?menuop=quartos');
exit(); // Garante que o script pare após o redirecionamento
?>