<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 
include_once("funcoes.php"); // Inclui o arquivo com as funções de validação

// Inicia uma transação. Ou tudo funciona, ou nada é salvo.
mysqli_begin_transaction($conexao);

try {
    // --- 1. CAPTURA DADOS PESSOAIS ---
    $idcli      = $_POST["cliid"] ?? '';
    $clinome    = $_POST["clinome"] ?? '';
    $clicpfcnpj = $_POST["clicpfcnpj"] ?? '';
    $clirgie    = $_POST["clirgie"] ?? '';
    $clinasc    = $_POST["clinasc"] ?? '';
    $cliemail   = $_POST["cliemail"] ?? '';
    $clitel     = $_POST["clitel"] ?? '';
    $clitipo    = $_POST["clitipo"] ?? '0';
    $cligenero  = $_POST["cligenero"] ?? 'N';

    // --- 2. CAPTURA DADOS DE ENDEREÇO ---
    $cep         = $_POST['cep'] ?? '';
    $logradouro  = $_POST['logradouro'] ?? '';
    $numero      = $_POST['numero'] ?? '';
    $complemento = $_POST['complemento'] ?? '';
    $bairro      = $_POST['bairro'] ?? '';
    $cidade      = $_POST['cidade'] ?? '';
    $uf          = $_POST['uf'] ?? '';

    // --- 3. VALIDAÇÃO E LIMPEZA DOS DADOS ---
    if (empty($idcli) || empty($clinome) || empty($clicpfcnpj)) {
        throw new Exception("ID, Nome e CPF/CNPJ são obrigatórios.");
    }
    
    // Limpa e valida CPF/CNPJ
    $cpfCnpjLimpo = preg_replace('/\D/', '', $clicpfcnpj);
    if (strlen($cpfCnpjLimpo) == 11 && !validarCpf($cpfCnpjLimpo)) {
        throw new Exception("O CPF informado é inválido.");
    }
    if (strlen($cpfCnpjLimpo) == 14 && !validarCnpj($cpfCnpjLimpo)) {
        throw new Exception("O CNPJ informado é inválido.");
    }

    // Limpa o CEP e Telefone
    $cepLimpo = preg_replace('/\D/', '', $cep);
    $telLimpo = preg_replace('/\D/', '', $clitel);

    // --- 4. QUERY 1: ATUALIZA 'pessoas' ---
    $sql_pessoa = "UPDATE pessoas SET
                       nome = ?, cpfcnpj = ?, rgie = ?, nasc = ?,
                       email = ?, telefone = ?, f_j = ?, genero = ?
                   WHERE id_pessoa = ?";
    
    $stmt_pessoa = mysqli_prepare($conexao, $sql_pessoa);
    if ($stmt_pessoa === false) { throw new Exception("Erro ao preparar consulta de pessoa."); }

    mysqli_stmt_bind_param($stmt_pessoa, 'ssssssisi',
        $clinome, $cpfCnpjLimpo, $clirgie, $clinasc, $cliemail, $telLimpo, $clitipo, $cligenero, $idcli
    );
    
    if (!mysqli_stmt_execute($stmt_pessoa)) {
        if (mysqli_errno($conexao) == 1062) {
             throw new Exception("Este CPF/CNPJ ou E-mail já está em uso por outro cliente.");
        }
        throw new Exception("Erro ao atualizar dados pessoais: " . mysqli_stmt_error($stmt_pessoa));
    }
    mysqli_stmt_close($stmt_pessoa);

    // --- 5. QUERY 2: VERIFICA SE ENDEREÇO JÁ EXISTE ---
    $sql_check = "SELECT id_endereco FROM endereco WHERE id_pessoa = ?";
    $stmt_check = mysqli_prepare($conexao, $sql_check);
    mysqli_stmt_bind_param($stmt_check, 'i', $idcli);
    mysqli_stmt_execute($stmt_check);
    $endereco_existente = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_check));
    mysqli_stmt_close($stmt_check);
    
    // --- 6. QUERY 3: ATUALIZA OU INSERE O ENDEREÇO ---
    if ($endereco_existente) {
        // 6a. Se existe, ATUALIZA (UPDATE)
        $sql_endereco = "UPDATE endereco SET 
                            rua = ?, numero = ?, bairro = ?, cidade = ?, 
                            uf = ?, cep = ?, complemento = ?
                         WHERE id_pessoa = ?";
        $stmt_endereco = mysqli_prepare($conexao, $sql_endereco);
        mysqli_stmt_bind_param($stmt_endereco, 'sssssssi',
            $logradouro, $numero, $bairro, $cidade, $uf, $cepLimpo, $complemento, $idcli
        );
    } else {
        // 6b. Se não existe, INSERE (INSERT)
        $sql_endereco = "INSERT INTO endereco (id_pessoa, rua, numero, bairro, cidade, uf, cep, complemento) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_endereco = mysqli_prepare($conexao, $sql_endereco);
        mysqli_stmt_bind_param($stmt_endereco, 'isssssss',
            $idcli, $logradouro, $numero, $bairro, $cidade, $uf, $cepLimpo, $complemento
        );
    }

    if (!mysqli_stmt_execute($stmt_endereco)) {
        throw new Exception("Erro ao salvar o endereço: " . mysqli_stmt_error($stmt_endereco));
    }
    mysqli_stmt_close($stmt_endereco);

    // --- 7. COMMIT ---
    // Se tudo deu certo, confirma as alterações no banco
    mysqli_commit($conexao);
    $_SESSION['message'] = [
        'type' => 'success',
        'text' => 'Cliente e endereço atualizados com sucesso!'
    ];

} catch (Exception $e) {
    // --- 8. ROLLBACK ---
    // Se algo deu errado, desfaz todas as alterações
    mysqli_rollback($conexao);
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro ao atualizar: ' . $e->getMessage()
    ];
}

// --- 9. REDIRECIONAMENTO ---
mysqli_close($conexao);
header('Location: agenda.php?menuop=clientes');
exit(); 
?>