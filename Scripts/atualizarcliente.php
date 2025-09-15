<?php
// 1. Inicia a sessão para usar as mensagens de feedback
session_start();
include("conexao.php"); // Inclui seu arquivo de conexão

// 2. Bloco try-catch para tratamento de erros
try {
    // 3. Validação dos dados recebidos do formulário
    if (empty($_POST["cliid"]) || empty($_POST["clinome"])) {
        throw new Exception("ID ou Nome do cliente não fornecidos.");
    }

    // Captura os dados do POST
    $idcli      = $_POST["cliid"];
    $clinome    = $_POST["clinome"];
    $clicpfcnpj = $_POST["clicpfcnpj"];
    $clirgie    = $_POST["clirgie"];
    $clinasc    = $_POST["clinasc"];
    $cliemail   = $_POST["cliemail"];
    $clitel     = $_POST["clitel"];
    $clitipo    = $_POST["clitipo"];
    $cligenero  = $_POST["cligenero"];

    // 4. Prepara a consulta SQL com placeholders (?) para segurança
    $sql = "UPDATE pessoas SET
                nome = ?, cpfcnpj = ?, rgie = ?, nasc = ?,
                email = ?, telefone = ?, f_j = ?, genero = ?
            WHERE id_pessoa = ?";
    
    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt === false) {
        throw new Exception("Erro ao preparar a consulta: " . mysqli_error($conexao));
    }

    // 5. Associa os parâmetros (bind) com os tipos corretos
    // s = string, i = integer
    mysqli_stmt_bind_param($stmt, 'ssssssisi',
        $clinome,
        $clicpfcnpj,
        $clirgie,
        $clinasc,
        $cliemail,
        $clitel,
        $clitipo,
        $cligenero,
        $idcli
    );
    
    // 6. Executa a consulta
    if (mysqli_stmt_execute($stmt)) {
        // Se deu certo, cria a mensagem de sucesso
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Cliente atualizado com sucesso!'
        ];
    } else {
        throw new Exception("Não foi possível executar a atualização.");
    }

} catch (Exception $e) {
    // Se deu qualquer erro, cria a mensagem de erro
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro ao atualizar o cliente: ' . $e->getMessage()
    ];
}

// 7. Redireciona o usuário de volta para a lista
header('Location: agenda.php?menuop=clientes');
exit(); // Garante que o script pare após o redirecionamento
?>