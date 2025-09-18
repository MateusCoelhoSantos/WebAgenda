<?php
session_start();
include("conexao.php");

try {
    if (empty($_POST["idquarto"])) { throw new Exception("ID do quarto não fornecido."); }
    
    $idquarto = $_POST["idquarto"];
    $numquarto = $_POST["numquarto"];
    $nome_quarto = $_POST["nome_quarto"];
    $descricao = $_POST["descricao"];
    $capacidade_adultos = $_POST["capacidade_adultos"];
    $capacidade_criancas = $_POST["capacidade_criancas"];
    $preco_diaria = $_POST["preco_diaria"];
    $comodidades = $_POST['comodidades'] ?? [];
    $tem_wifi = isset($comodidades['wifi']) ? 1 : 0;
    $tem_ar = isset($comodidades['ar']) ? 1 : 0;
    $tem_tv = isset($comodidades['tv']) ? 1 : 0;

    $sql = "UPDATE quartos SET num_quarto=?, nome_quarto=?, descricao=?, capacidade_adultos=?, 
            capacidade_criancas=?, preco_diaria=?, tem_wifi=?, tem_ar_condicionado=?, tem_tv=? 
            WHERE id_quarto = ?";
    
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'sssiidiiii', $numquarto, $nome_quarto, $descricao, $capacidade_adultos, $capacidade_criancas, $preco_diaria, $tem_wifi, $tem_ar, $tem_tv, $idquarto);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Dados do quarto atualizados com sucesso!'];
    } else {
        throw new Exception("Não foi possível atualizar os dados do quarto.");
    }

} catch (Exception $e) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Erro: ' . $e->getMessage()];
}

header('Location: agenda.php?menuop=editarquarto&idquarto=' . $idquarto);
exit();
?>