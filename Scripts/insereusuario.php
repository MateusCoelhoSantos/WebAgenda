<?php
// 1. Inicia a sessão para usar as mensagens de feedback
session_start();
include("conexao.php"); // Inclui seu arquivo de conexão

// 2. Bloco try-catch para um tratamento de erros mais robusto
try {
    // Captura os dados do POST
    $nome           = $_POST['nome'] ?? '';
    $email          = $_POST['email'] ?? '';
    $telefone       = $_POST['telefone'] ?? '';
    $cpf            = $_POST['cpf'] ?? '';
    $senha          = $_POST['senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';

    // 3. Validação dos dados
    if (empty($nome) || empty($email) || empty($cpf) || empty($senha)) {
        throw new Exception("Todos os campos são obrigatórios.");
    }
    if ($senha !== $confirma_senha) {
        throw new Exception("As senhas não coincidem. Tente novamente.");
    }
    if (strlen($senha) < 6) {
        throw new Exception("A senha deve ter no mínimo 6 caracteres.");
    }

    // 4. CRIPTOGRAFA A SENHA (Passo de segurança mais importante)
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // 5. Prepara a consulta SQL com placeholders (?) para segurança
    $sql = "INSERT INTO usuarios (nome, email, telefone, cpf, senha, excluido) VALUES (?, ?, ?, ?, ?, 0)";
    
    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt === false) {
        throw new Exception("Erro ao preparar a consulta: " . mysqli_error($conexao));
    }

    // 6. Associa os parâmetros (bind) com os tipos corretos
    mysqli_stmt_bind_param($stmt, 'sssss',
        $nome,
        $email,
        $telefone,
        $cpf,
        $senha_hash // Salva a senha criptografada
    );
    
    // 7. Executa a consulta
    if (mysqli_stmt_execute($stmt)) {
        // Se deu certo, cria a mensagem de sucesso
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Usuário cadastrado com sucesso! Você já pode fazer o login.'
        ];
    } else {
        // Verifica se o erro é de e-mail duplicado
        if (mysqli_errno($conexao) == 1062) {
            throw new Exception("Este e-mail já está cadastrado.");
        } else {
            throw new Exception("Não foi possível executar o cadastro.");
        }
    }

} catch (Exception $e) {
    // Se deu qualquer erro, cria a mensagem de erro
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro ao cadastrar: ' . $e->getMessage()
    ];
}

// 8. Redireciona o usuário de volta para a página de login
header('Location: index.php?menuop=login');
exit(); // Garante que o script pare após o redirecionamento
?>