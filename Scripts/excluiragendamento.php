<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 

try {
    $idreserva = $_GET['idreserva'] ?? null;
    if ($idreserva === null || !is_numeric($idreserva)) {
        throw new Exception("ID do agendamento é inválido.");
    }

    $sql = "UPDATE reservas SET excluido = 1 WHERE id_reserva = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $idreserva);
    
    if ($stmt->execute()) {
        // MENSAGEM DE SUCESSO
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Agendamento excluído com sucesso!'
        ];
    } else {
        throw new Exception("Não foi possível excluir o agendamento.");
    }

} catch (Exception $e) {
    // MENSAGEM DE ERRO
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro: ' . $e->getMessage()
    ];
}

// Redireciona de volta para a lista
header('Location: agenda.php?menuop=agendamento');
exit();
?>