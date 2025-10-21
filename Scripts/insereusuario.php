<?php
// Adiciona o report de erros no topo para forçar a exibição
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php");

/**
 * Função para validar CPF.
 * Verifica se a função já existe antes de tentar declará-la
 */
if (!function_exists('validarCpf')) {
    function validarCpf($cpf) {
        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}


// Bloco try-catch para um tratamento de erros mais robusto
try {
    
    // 1. Captura os dados do POST
    $nome           = $_POST['nome'] ?? '';
    $email          = $_POST['email'] ?? '';
    $telefone       = $_POST['telefone'] ?? '';
    $cpf            = $_POST['cpf'] ?? '';
    $senha          = $_POST['senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';

    // 2. Validação dos dados
    if (empty($nome) || empty($email) || empty($cpf) || empty($senha)) {
        throw new Exception("Todos os campos são obrigatórios.");
    }
    
    if (!validarCpf($cpf)) {
        throw new Exception("O CPF informado é inválido.");
    }

    if ($senha !== $confirma_senha) {
        throw new Exception("As senhas não coincidem. Tente novamente.");
    }
    if (strlen($senha) < 6) {
        throw new Exception("A senha deve ter no mínimo 6 caracteres.");
    }

    // 3. CRIPTOGRAFA A SENHA
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // 4. Prepara a consulta SQL
    $sql = "INSERT INTO usuarios (nome, email, telefone, cpf, senha, excluido) VALUES (?, ?, ?, ?, ?, 0)";
    
    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt === false) {
        throw new Exception("Erro ao preparar a consulta: ".mysqli_error($conexao));
    }
    
    // 5. Associa os parâmetros
    mysqli_stmt_bind_param($stmt, 'sssss',
        $nome,
        $email,
        $telefone,
        $cpf,
        $senha_hash
    );
    
    // 6. Executa a consulta
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Usuário cadastrado com sucesso! Você já pode fazer o login.'
        ];
    } else {
        if (mysqli_errno($conexao) == 1062) {
            if (strpos(mysqli_error($conexao), 'email') !== false) {
                 throw new Exception("Este e-mail já está cadastrado.");
            } else if (strpos(mysqli_error($conexao), 'cpf') !== false) {
                 throw new Exception("Este CPF já está cadastrado.");
            } else {
                 throw new Exception("Este e-mail ou CPF já está cadastrado.");
            }
        } else {
            throw new Exception("Não foi possível executar o cadastro: ".mysqli_stmt_error($stmt));
        }
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conexao);

} catch (Exception $e) {
    // Se deu qualquer erro, cria a mensagem de erro
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro ao cadastrar: ' . $e->getMessage()
    ];
}

// 7. Redireciona o usuário de volta para a página de cadastro
header('Location: index.php?menuop=cadastro');
exit();
?>