<?php
// (Caminho para sua conexão)
include_once("conexao.php"); 
header('Content-Type: application/json');

// Proteção básica
if (!isset($_GET['termo'])) {
    echo json_encode([]);
    exit();
}

$termo = $_GET['termo'];
$termo_like = "%" . $termo . "%";

// SQL COM LEFT JOIN para buscar o endereço
$sql = "SELECT 
            p.id_pessoa, p.nome, p.cpfcnpj, p.telefone, p.email,
            e.rua, e.numero, e.bairro, e.cidade, e.uf, e.cep, e.complemento
        FROM 
            pessoas AS p
        LEFT JOIN 
            endereco AS e ON p.id_pessoa = e.id_pessoa
        WHERE 
            (p.excluido <> 1) AND p.tipopessoa = 1 
            AND (p.nome LIKE ? OR p.cpfcnpj LIKE ? OR p.id_pessoa = ?)
        LIMIT 10";

$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, 'ssi', $termo_like, $termo_like, $termo);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$clientes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $clientes[] = $row;
}

echo json_encode($clientes);
mysqli_close($conexao);
?>