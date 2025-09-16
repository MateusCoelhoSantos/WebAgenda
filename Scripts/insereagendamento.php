<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 

// 2. Bloco try-catch para um tratamento de erros mais robusto
try {
    // Captura os dados do POST
    $id_pessoa     = $_POST['codcliente'] ?? '';
    $id_quarto     = $_POST['numquarto'] ?? '';
    $horarioini    = $_POST['horarioini'] ?? '';
    $horariofin    = $_POST['horariofin'] ?? '';
    $valor         = $_POST['valor'] ?? 0;
    $quant_pessoas = $_POST['quant_pessoas'] ?? 1;
    $obs           = $_POST['obs'] ?? ''; 

    // 3. Validação dos dados essenciais
    if (empty($id_pessoa) || empty($id_quarto) || empty($horarioini) || empty($horariofin)) {
        throw new Exception("Todos os campos obrigatórios devem ser preenchidos.");
    }

    // Define a data da reserva como a data atual
    $data_reserva = date('Y-m-d');

    // 4. Sua consulta segura com Prepared Statements
    $sql = "INSERT INTO reservas
            (id_pessoa, tiporeserva, id_quarto, horarioini, horariofin, valor, quant_pessoas, obs, finalizado, excluido, data_reserva)
            VALUES (?, 0, ?, ?, ?, ?, ?, ?, 0, 0, ?)"; 

    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt === false) {
        throw new Exception("Erro ao preparar a consulta: " . mysqli_error($conexao));
    }

    // 5. Associa os parâmetros (bind)
    mysqli_stmt_bind_param($stmt, "iissdiss", 
        $id_pessoa, 
        $id_quarto, 
        $horarioini, 
        $horariofin, 
        $valor, 
        $quant_pessoas, 
        $obs,
        $data_reserva
    );
    
    // 6. Executa a consulta
    if (mysqli_stmt_execute($stmt)) {
        // Se deu certo, cria a mensagem de sucesso
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Reserva cadastrada com sucesso!'
        ];
    } else {
        throw new Exception("Não foi possível executar o cadastro da reserva.");
    }

} catch (Exception $e) {
    // Se deu qualquer erro, cria a mensagem de erro
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro ao cadastrar a reserva: ' . $e->getMessage()
    ];
}

// 7. Redireciona o usuário de volta para a lista de agendamentos
header('Location: agenda.php?menuop=agendamento');
exit(); // Garante que o script pare após o redirecionamento
?>