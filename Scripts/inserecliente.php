<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 
include_once("funcoes.php"); // Inclui o arquivo com as funções de validação

// Inicia uma transação. Ou tudo funciona, ou nada é salvo.
mysqli_begin_transaction($conexao);

// Define a URL de redirecionamento padrão (para erros)
$redirect_url = 'agenda.php?menuop=cadastrocliente'; // Volta para o cadastro de cliente

try {
    // 1. Captura os dados do POST (Dados Pessoais)
    $clinome    = $_POST["clinome"] ?? '';
    $clicpfcnpj = $_POST["clicpfcnpj"] ?? '';
    $clirgie    = $_POST["clirgie"] ?? '';
    $clinasc    = $_POST["clinasc"] ?? '';
    $cliemail   = $_POST["cliemail"] ?? '';
    $clitel     = $_POST["clitel"] ?? '';
    $clitipo    = $_POST["clitipo"] ?? '0'; // Padrão '0' (Física)
    $cligenero  = $_POST["cligenero"] ?? 'N'; // Padrão 'N' (Não informado)

    // Captura os dados do POST (Endereço)
    $cep         = $_POST['cep'] ?? '';
    $logradouro  = $_POST['logradouro'] ?? '';
    $numero      = $_POST['numero'] ?? '';
    $complemento = $_POST['complemento'] ?? '';
    $bairro      = $_POST['bairro'] ?? '';
    $cidade      = $_POST['cidade'] ?? '';
    $uf          = $_POST['uf'] ?? '';

    // 2. Validação dos dados essenciais
    if (empty($clinome) || empty($clicpfcnpj)) {
        throw new Exception("Nome e CPF/CNPJ são obrigatórios.");
    }

    // Valida CPF/CNPJ
    $cpfCnpjLimpo = preg_replace('/\D/', '', $clicpfcnpj);
    $isCpf = strlen($cpfCnpjLimpo) == 11;
    $isCnpj = strlen($cpfCnpjLimpo) == 14;
    
    if ($isCpf && !validarCpf($cpfCnpjLimpo)) { throw new Exception("O CPF informado é inválido."); }
    if ($isCnpj && !validarCnpj($cpfCnpjLimpo)) { throw new Exception("O CNPJ informado é inválido."); }
    if (!$isCpf && !$isCnpj) { throw new Exception("O documento deve ser um CPF (11 dígitos) ou CNPJ (14 dígitos)."); }
    
    // 3. Insere na tabela 'pessoas'
    $sql_pessoa = "INSERT INTO pessoas (
                       nome, cpfcnpj, rgie, nasc, email, telefone,
                       f_j, genero, tipopessoa, excluido
                   ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, 0)";
    
    $stmt_pessoa = mysqli_prepare($conexao, $sql_pessoa);
    if ($stmt_pessoa === false) { throw new Exception("Erro ao preparar a consulta de pessoa: " . mysqli_error($conexao)); }
    
    // Associa os parâmetros (bind) com os tipos corretos
    mysqli_stmt_bind_param($stmt_pessoa, 'ssssssis',
        $clinome, $cpfCnpjLimpo, $clirgie, $clinasc, $cliemail, $clitel, $clitipo, $cligenero
    );
    
    if (!mysqli_stmt_execute($stmt_pessoa)) {
        if (mysqli_errno($conexao) == 1062) { // Erro de duplicidade
             throw new Exception("Este CPF/CNPJ ou E-mail já está cadastrado.");
        } else {
             throw new Exception("Não foi possível cadastrar a pessoa: ".mysqli_stmt_error($stmt_pessoa));
        }
    }

    // 4. Pega o ID da pessoa recém-inserida
    $id_nova_pessoa = mysqli_insert_id($conexao);
    if ($id_nova_pessoa <= 0) {
        throw new Exception("Falha ao obter o ID da pessoa cadastrada.");
    }
    
    // 5. Insere na tabela 'endereco'
    $sql_endereco = "INSERT INTO endereco (
                         id_pessoa, rua, numero, bairro, cidade, uf, cep, complemento
                     ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt_endereco = mysqli_prepare($conexao, $sql_endereco);
    if ($stmt_endereco === false) { throw new Exception("Erro ao preparar a consulta de endereço: " . mysqli_error($conexao)); }

    // Limpa o CEP para salvar apenas números (opcional, mas recomendado)
    $cep_limpo = preg_replace('/\D/', '', $cep);
    
    // Associa os parâmetros (bind)
    mysqli_stmt_bind_param($stmt_endereco, 'isssssss',
        $id_nova_pessoa, $logradouro, $numero, $bairro, $cidade, $uf, $cep_limpo, $complemento
    );

    if (!mysqli_stmt_execute($stmt_endereco)) {
        throw new Exception("Não foi possível cadastrar o endereço: ".mysqli_stmt_error($stmt_endereco));
    }

    // 6. Se ambas as inserções funcionaram, confirma a transação
    mysqli_commit($conexao);
    
    $_SESSION['message'] = [
        'type' => 'success',
        'text' => 'Cliente cadastrado com sucesso!'
    ];
    // Altera o redirecionamento para a LISTA em caso de sucesso
    $redirect_url = 'agenda.php?menuop=clientes'; 

} catch (Exception $e) {
    // Se deu qualquer erro, desfaz TUDO
    mysqli_rollback($conexao);
    
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro ao cadastrar: ' . $e->getMessage()
    ];
    // Em caso de erro, o $redirect_url continua sendo 'agenda.php?menuop=cadastrocliente'
}

// Fecha os statements e a conexão
if (isset($stmt_pessoa)) mysqli_stmt_close($stmt_pessoa);
if (isset($stmt_endereco)) mysqli_stmt_close($stmt_endereco);
mysqli_close($conexao);

// Redireciona para a URL definida
header("Location: " . $redirect_url);
exit();
?>