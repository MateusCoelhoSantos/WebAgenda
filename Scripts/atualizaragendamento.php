<?php
// Garante que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php");

// Inicia uma transação. Ou todas as consultas funcionam, ou nenhuma é salva.
mysqli_begin_transaction($conexao);

try {
    // Validação dos dados essenciais
    $idreserva = $_GET['idreserva'] ?? 0;
    if ($idreserva <= 0) {
        throw new Exception("ID da reserva inválido.");
    }

    // 1. Busca a reserva original para saber qual era o quarto antigo
    $sql_original = "SELECT id_quarto FROM reservas WHERE id_reserva = ?";
    $stmt_original = mysqli_prepare($conexao, $sql_original);
    mysqli_stmt_bind_param($stmt_original, "i", $idreserva);
    mysqli_stmt_execute($stmt_original);
    $reserva_original = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_original));
    
    if (!$reserva_original) {
        throw new Exception("Reserva original não encontrada.");
    }
    $id_quarto_antigo = $reserva_original['id_quarto'];

    // Captura os dados do formulário
    $id_quarto_novo = $_POST['numquarto'] ?? '';
    $id_pessoa      = $_POST['codcliente'] ?? '';
    $horarioini     = $_POST['horarioini'] ?? '';
    $horariofin     = $_POST['horariofin'] ?? '';
    $valor          = $_POST['valor'] ?? 0;
    $quant_pessoas  = $_POST['quant_pessoas'] ?? 1;
    $obs            = $_POST['obs'] ?? '';

    // 2. Executa a atualização principal na tabela de reservas
    $sql_update_reserva = "UPDATE reservas SET 
                            id_pessoa = ?, id_quarto = ?, horarioini = ?, horariofin = ?, 
                            valor = ?, quant_pessoas = ?, obs = ?
                           WHERE id_reserva = ?";
    $stmt_update_reserva = mysqli_prepare($conexao, $sql_update_reserva);
    mysqli_stmt_bind_param($stmt_update_reserva, "iissdisi", $id_pessoa, $id_quarto_novo, $horarioini, $horariofin, $valor, $quant_pessoas, $obs, $idreserva);
    
    if (!mysqli_stmt_execute($stmt_update_reserva)) {
        throw new Exception("Falha ao atualizar os dados da reserva.");
    }

    // 3. Compara o quarto antigo com o novo para ver se houve mudança
    if ($id_quarto_novo != $id_quarto_antigo) {
        
        // 4a. Se o quarto mudou, libera o ANTIGO (status = 0)
        $sql_libera_antigo = "UPDATE quartos SET status = 0 WHERE id_quarto = ?";
        $stmt_libera_antigo = mysqli_prepare($conexao, $sql_libera_antigo);
        mysqli_stmt_bind_param($stmt_libera_antigo, "i", $id_quarto_antigo);
        if (!mysqli_stmt_execute($stmt_libera_antigo)) {
            throw new Exception("Falha ao liberar o status do quarto antigo.");
        }
        
        // 4b. E ocupa o NOVO (status = 1)
        $sql_ocupa_novo = "UPDATE quartos SET status = 1 WHERE id_quarto = ?";
        $stmt_ocupa_novo = mysqli_prepare($conexao, $sql_ocupa_novo);
        mysqli_stmt_bind_param($stmt_ocupa_novo, "i", $id_quarto_novo);
        if (!mysqli_stmt_execute($stmt_ocupa_novo)) {
            throw new Exception("Falha ao ocupar o status do novo quarto.");
        }
    }

    // 5. Se tudo deu certo até aqui, efetiva as mudanças no banco de dados
    mysqli_commit($conexao);
    $_SESSION['message'] = ['type' => 'success', 'text' => 'Reserva atualizada com sucesso!'];

} catch (Exception $e) {
    // Se algo deu errado em qualquer etapa, desfaz todas as alterações
    mysqli_rollback($conexao);
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Erro ao atualizar a reserva: ' . $e->getMessage()];
}

// Redireciona de volta para a lista de agendamentos
header('Location: agenda.php?menuop=agendamento');
exit();
?>