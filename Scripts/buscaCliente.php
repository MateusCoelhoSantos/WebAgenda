<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 

$termo = $_GET['termo'] ?? '';

$sql = "SELECT id_pessoa, nome, cpfcnpj, telefone, email 
        FROM pessoas 
        WHERE nome LIKE '%$termo%'
        AND excluido <> 1 
        LIMIT 10";

$result = mysqli_query($conexao, $sql);

$clientes = [];
while($row = mysqli_fetch_assoc($result)){
    $clientes[] = $row;
}
echo json_encode($clientes);
