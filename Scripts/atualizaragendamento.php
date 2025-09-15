<?php
include("conexao.php");

$idreserva = $_GET['idreserva'] ?? 0;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['alterar'])) {
    $codcliente    = $_POST['codcliente'];
    $numquarto     = $_POST['numquarto'];
    $horarioini    = $_POST['horarioini'];
    $horariofin    = $_POST['horariofin'];
    $valor         = $_POST['valor'];
    $quant_pessoas = $_POST['quant_pessoas'];
    $obs           = $_POST['obs'];

    // Atualiza os dados no banco
    $sql = "UPDATE reservas 
               SET id_pessoa = ?, 
                   id_quarto = ?, 
                   horarioini = ?, 
                   horariofin = ?, 
                   valor = ?, 
                   quant_pessoas = ?, 
                   obs = ?
             WHERE id_reserva = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param(
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

    if ($stmt->execute()) {
        echo "<script>
                alert('Reserva atualizada com sucesso!');
                window.location.href='agenda.php?menuop=agendamento';
              </script>";
    } else {
        echo "<script>
                alert('Erro ao atualizar a reserva.');
                window.history.back();
              </script>";
    }
}
?>
