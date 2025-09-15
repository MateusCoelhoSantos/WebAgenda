<?php
session_start();
include("conexao.php");

try {
    if (empty($_POST["idquarto"]) || empty($_POST["numquarto"])) {
        throw new Exception("Dados essenciais não fornecidos.");
    }

    $idquarto   = $_POST["idquarto"];
    $numquarto  = $_POST["numquarto"];
    $descricao  = $_POST["descricao"];

    $sql = "UPDATE quartos SET num_quarto = ?, descricao = ? WHERE id_quarto = ?";
    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt === false) {
        throw new Exception("Erro ao preparar a consulta.");
    }
    
    mysqli_stmt_bind_param($stmt, 'ssi', $numquarto, $descricao, $idquarto);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Quarto atualizado com sucesso!'
        ];
    } else {
        throw new Exception("Não foi possível executar a atualização.");
    }

} catch (Exception $e) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro: ' . $e->getMessage()
    ];
}

// Redireciona de volta para a lista
header('Location: agenda.php?menuop=quartos');
exit();
?>