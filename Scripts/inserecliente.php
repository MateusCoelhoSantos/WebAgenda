<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 

include("funcoes.php"); // Inclui o arquivo com as novas funções

// 2. Bloco try-catch para um tratamento de erros mais robusto
try {
    // ... (captura dos outros dados do POST) ...
    $clicpfcnpj = $_POST["clicpfcnpj"];
    
    // LIMPA E VALIDA O CPF/CNPJ
    $cpfCnpjLimpo = preg_replace('/\D/', '', $clicpfcnpj);
    
    $isCpf = strlen($cpfCnpjLimpo) == 11;
    $isCnpj = strlen($cpfCnpjLimpo) == 14;

    if ($isCpf && !validarCpf($cpfCnpjLimpo)) {
        throw new Exception("O CPF informado é inválido.");
    }
    if ($isCnpj && !validarCnpj($cpfCnpjLimpo)) {
        throw new Exception("O CNPJ informado é inválido.");
    }
    if (!$isCpf && !$isCnpj) {
        throw new Exception("O documento deve ser um CPF (11 dígitos) ou CNPJ (14 dígitos).");
    }
    // 3. Validação dos dados essenciais
    if (empty($_POST["clinome"]) || empty($_POST["clicpfcnpj"])) {
        throw new Exception("Nome e CPF/CNPJ são obrigatórios.");
    }

    // Captura os dados do POST
    $clinome    = $_POST["clinome"];
    $clicpfcnpj = $_POST["clicpfcnpj"];
    $clirgie    = $_POST["clirgie"];
    $clinasc    = $_POST["clinasc"];
    $cliemail   = $_POST["cliemail"];
    $clitel     = $_POST["clitel"];
    $clitipo    = $_POST["clitipo"];
    $cligenero  = $_POST["cligenero"]; // Correção do nome do campo

    // 4. Prepara a consulta SQL com placeholders (?) para máxima segurança
    $sql = "INSERT INTO pessoas (
                nome, cpfcnpj, rgie, nasc, email, telefone,
                f_j, genero, tipopessoa, excluido
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, 0)";
    
    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt === false) {
        throw new Exception("Erro ao preparar a consulta: " . mysqli_error($conexao));
    }

    // 5. Associa os parâmetros (bind) com os tipos corretos
    // s = string, i = integer
    mysqli_stmt_bind_param($stmt, 'ssssssis',
        $clinome,
        $clicpfcnpj,
        $clirgie,
        $clinasc,
        $cliemail,
        $clitel,
        $clitipo,
        $cligenero
    );
    
    // 6. Executa a consulta
    if (mysqli_stmt_execute($stmt)) {
        // Se deu certo, cria a mensagem de sucesso
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Cliente cadastrado com sucesso!'
        ];
    } else {
        throw new Exception("Não foi possível executar o cadastro.");
    }

} catch (Exception $e) {
    // Se deu qualquer erro, cria a mensagem de erro
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro ao cadastrar o cliente: ' . $e->getMessage()
    ];
}

// 7. Redireciona o usuário de volta para a lista de clientes
header('Location: agenda.php?menuop=clientes');
exit(); // Garante que o script pare após o redirecionamento
?>