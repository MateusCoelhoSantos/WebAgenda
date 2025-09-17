<?php
// 1. Inicia a sessão para usar as mensagens de feedback
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php");

// 2. Bloco try-catch para um tratamento de erros mais robusto
try {
    // 3. Validação dos dados essenciais
    $idreserva = $_GET['idreserva'] ?? 0; // Pega o ID da URL
    if ($idreserva == 0 || empty($_POST['codcliente']) || empty($_POST['numquarto'])) {
        throw new Exception("Dados essenciais da reserva não foram fornecidos.");
    }

    // Captura os dados do POST
    $codcliente    = $_POST['codcliente'];
    $numquarto     = $_POST['numquarto'];
    $horarioini    = $_POST['horarioini'];
    $horariofin    = $_POST['horariofin'];
    $valor         = $_POST['valor'];
    $quant_pessoas = $_POST['quant_pessoas'];
    $obs           = $_POST['obs'];

    // 4. Sua consulta segura com Prepared Statements
    $sql = "UPDATE reservas SET 
                id_pessoa = ?, 
                id_quarto = ?, 
                horarioini = ?, 
                horariofin = ?, 
                valor = ?, 
                quant_pessoas = ?, 
                obs = ?
            WHERE id_reserva = ?";

    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt === false) {
        throw new Exception("Erro ao preparar a consulta: " . mysqli_error($conexao));
    }

    // 5. Associa os parâmetros (bind)
    mysqli_stmt_bind_param(
        $stmt, 
        "iissdisi", 
        $codcliente, 
        $numquarto, 
        $horarioini, 
        $horariofin, 
        $valor, 
        $quant_pessoas, 
        $obs, 
        $idreserva
    );
    
    // 6. Executa a consulta
    if (mysqli_stmt_execute($stmt)) {
        // Se deu certo, cria a mensagem de sucesso
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Reserva atualizada com sucesso!'
        ];
    } else {
        throw new Exception("Não foi possível executar a atualização.");
    }

} catch (Exception $e) {
    // Se deu qualquer erro, cria a mensagem de erro
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro ao atualizar a reserva: ' . $e->getMessage()
    ];
}

// 7. Redireciona o usuário de volta para a lista de agendamentos
header('Location: agenda.php?menuop=agendamento');
exit(); // Garante que o script pare após o redirecionamento
?>