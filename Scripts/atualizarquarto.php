<?php
// 1. Inicia a sessão para usar as mensagens de feedback
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("conexao.php");

// 2. Bloco try-catch para um tratamento de erros mais robusto
try {
    // 3. Validação dos dados essenciais
    if (empty($_POST["idquarto"]) || empty($_POST["num_quarto"])) {
        throw new Exception("Dados essenciais do quarto não foram fornecidos.");
    }

    // Captura os dados do POST
    $idquarto           = $_POST["idquarto"];
    $num_quarto         = $_POST["num_quarto"];
    $nome_quarto        = $_POST["nome_quarto"];
    $capacidade_adultos = $_POST["capacidade_adultos"];
    $capacidade_criancas= $_POST["capacidade_criancas"];
    $preco_diaria       = $_POST["preco_diaria"];
    $descricao          = $_POST["descricao"];

    // Lógica especial para capturar os valores dos checkboxes
    // Se o checkbox foi marcado, o valor será 1 (true). Se não, será 0 (false).
    $tem_wifi           = isset($_POST['comodidades']['wifi']) ? 1 : 0;
    $tem_ar_condicionado= isset($_POST['comodidades']['ar']) ? 1 : 0;
    $tem_tv             = isset($_POST['comodidades']['tv']) ? 1 : 0;
    
    // 4. Prepara a consulta SQL com placeholders (?) para segurança
    $sql = "UPDATE quartos SET 
                num_quarto = ?, 
                nome_quarto = ?, 
                descricao = ?,
                capacidade_adultos = ?,
                capacidade_criancas = ?,
                preco_diaria = ?,
                tem_wifi = ?,
                tem_ar_condicionado = ?,
                tem_tv = ?
            WHERE id_quarto = ?";
    
    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt === false) {
        throw new Exception("Erro ao preparar a consulta: " . mysqli_error($conexao));
    }

    // 5. Associa os parâmetros (bind) com os tipos corretos
    // s = string, i = integer, d = double (decimal)
    mysqli_stmt_bind_param($stmt, 'sssiidiiii',
        $num_quarto,
        $nome_quarto,
        $descricao,
        $capacidade_adultos,
        $capacidade_criancas,
        $preco_diaria,
        $tem_wifi,
        $tem_ar_condicionado,
        $tem_tv,
        $idquarto
    );
    
    // 6. Executa a consulta
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Dados do quarto atualizados com sucesso!'
        ];
    } else {
        throw new Exception("Não foi possível executar a atualização.");
    }

} catch (Exception $e) {
    // Se deu qualquer erro, cria a mensagem de erro
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro ao atualizar o quarto: ' . $e->getMessage()
    ];
}

// 7. Redireciona o usuário de volta para a lista de quartos
header('Location: agenda.php?menuop=quartos');
exit();
?>