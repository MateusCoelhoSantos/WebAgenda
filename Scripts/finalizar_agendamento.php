<?php
// Garante que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php");

// Inicia uma transação para garantir a consistência dos dados
mysqli_begin_transaction($conexao);

try {
    // 1. Validação do ID da reserva
    $idreserva = $_GET['idreserva'] ?? null;
    if ($idreserva === null || !is_numeric($idreserva)) {
        throw new Exception("ID do agendamento é inválido.");
    }

    // 2. Busca a reserva para descobrir qual quarto (id_quarto) precisa ser liberado
    $sql_fetch = "SELECT id_quarto FROM reservas WHERE id_reserva = ?";
    $stmt_fetch = mysqli_prepare($conexao, $sql_fetch);
    mysqli_stmt_bind_param($stmt_fetch, "i", $idreserva);
    mysqli_stmt_execute($stmt_fetch);
    $reserva = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_fetch));
    
    if (!$reserva) {
        throw new Exception("Reserva não encontrada.");
    }
    $id_quarto_a_liberar = $reserva['id_quarto'];

    // 3. Marca a reserva como finalizada (finalizado = 1)
    $sql_finalize_reserva = "UPDATE reservas SET finalizado = 1 WHERE id_reserva = ?";
    $stmt_finalize_reserva = $conexao->prepare($sql_finalize_reserva);
    $stmt_finalize_reserva->bind_param("i", $idreserva);
    if (!$stmt_finalize_reserva->execute()) {
        throw new Exception("Não foi possível finalizar o agendamento.");
    }

    // 4. Atualiza o status do quarto para 'Disponível' (status = 0)
    $sql_update_quarto = "UPDATE quartos SET status = 0 WHERE id_quarto = ?";
    $stmt_update_quarto = $conexao->prepare($sql_update_quarto);
    $stmt_update_quarto->bind_param("i", $id_quarto_a_liberar);
    if (!$stmt_update_quarto->execute()) {
        throw new Exception("Não foi possível atualizar o status do quarto.");
    }
    
    // 5. Se tudo deu certo, efetiva as mudanças no banco
    mysqli_commit($conexao);
    $_SESSION['message'] = [
        'type' => 'success',
        'text' => 'Agendamento finalizado com sucesso!'
    ];

} catch (Exception $e) {
    // Se algo deu errado, desfaz todas as operações
    mysqli_rollback($conexao);
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro: ' . $e->getMessage()
    ];
}

// Redireciona de volta para a lista
header('Location: agenda.php?menuop=agendamento');
exit();
?>