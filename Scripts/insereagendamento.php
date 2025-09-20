<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 

// Inicia uma transação. Ou tudo funciona, ou nada é salvo.
mysqli_begin_transaction($conexao);

try {
    $id_pessoa     = $_POST['codcliente'] ?? '';
    $id_quarto     = $_POST['numquarto'] ?? '';
    $horarioini    = $_POST['horarioini'] ?? '';
    $horariofin    = $_POST['horariofin'] ?? '';
    $valor         = $_POST['valor'] ?? 0;
    $quant_pessoas = $_POST['quant_pessoas'] ?? 1;
    $obs           = $_POST['obs'] ?? ''; 

    if (empty($id_pessoa) || empty($id_quarto) || empty($horarioini) || empty($horariofin)) {
        throw new Exception("Todos os campos obrigatórios devem ser preenchidos.");
    }
    
    // VERIFICAÇÃO DE CONFLITO: Checa se já não existe uma reserva para este quarto neste período
    $sql_check = "SELECT id_reserva FROM reservas WHERE id_quarto = ? AND excluido = 0 AND NOT (horariofin <= ? OR horarioini >= ?)";
    $stmt_check = mysqli_prepare($conexao, $sql_check);
    mysqli_stmt_bind_param($stmt_check, 'iss', $id_quarto, $horarioini, $horariofin);
    mysqli_stmt_execute($stmt_check);
    if (mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_check))) {
        throw new Exception("Este quarto já está reservado para o período selecionado.");
    }

    // 1. INSERE A RESERVA
    $data_reserva = date('Y-m-d');
    $sql_insert = "INSERT INTO reservas (id_pessoa, tiporeserva, id_quarto, horarioini, horariofin, valor, quant_pessoas, obs, finalizado, excluido, data_reserva)
                   VALUES (?, 0, ?, ?, ?, ?, ?, ?, 0, 0, ?)"; 
    $stmt_insert = mysqli_prepare($conexao, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "iissdiss", $id_pessoa, $id_quarto, $horarioini, $horariofin, $valor, $quant_pessoas, $obs, $data_reserva);
    if (!mysqli_stmt_execute($stmt_insert)) {
        throw new Exception("Não foi possível inserir a reserva.");
    }

    // 2. ATUALIZA O STATUS DO QUARTO PARA INDISPONÍVEL
    $sql_update = "UPDATE quartos SET status = 1 WHERE id_quarto = ?";
    $stmt_update = mysqli_prepare($conexao, $sql_update);
    mysqli_stmt_bind_param($stmt_update, 'i', $id_quarto);
    if (!mysqli_stmt_execute($stmt_update)) {
        throw new Exception("Não foi possível atualizar o status do quarto.");
    }

    // Se tudo deu certo, efetiva as alterações no banco
    mysqli_commit($conexao);
    $_SESSION['message'] = ['type' => 'success', 'text' => 'Reserva cadastrada com sucesso!'];

} catch (Exception $e) {
    // Se algo deu errado, desfaz todas as alterações
    mysqli_rollback($conexao);
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Erro: ' . $e->getMessage()];
}

header('Location: agenda.php?menuop=agendamento');
exit();
?>