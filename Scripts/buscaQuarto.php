<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 

$termo = $_GET['termo'] ?? '';

$sql = "SELECT id_quarto, num_quarto, descricao 
        FROM quartos 
        WHERE descricao LIKE '%$termo%' OR num_quarto LIKE '%$termo%'
        AND excluido <> 1 
        AND status = 0 
        LIMIT 10";

$result = mysqli_query($conexao, $sql);

$quartos = [];
while($row = mysqli_fetch_assoc($result)){
    $quartos[] = $row;
}
echo json_encode($quartos);
