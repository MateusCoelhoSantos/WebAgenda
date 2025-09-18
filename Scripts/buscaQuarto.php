<?php
header('Content-Type: application/json');
include("conexao.php");

$termo = $_GET['termo'] ?? '';
$termo_like = "%" . $termo . "%";

$sql = "SELECT 
            q.id_quarto, q.num_quarto, q.nome_quarto, q.capacidade_adultos, 
            q.capacidade_criancas, q.preco_diaria, q.tem_wifi, q.tem_ar_condicionado, q.tem_tv,
            GROUP_CONCAT(qi.nome_arquivo) as imagens
        FROM quartos q
        LEFT JOIN quarto_imagens qi ON q.id_quarto = qi.id_quarto
        WHERE q.excluido = 0 AND (q.num_quarto LIKE ? OR q.nome_quarto LIKE ?)
        GROUP BY q.id_quarto
        LIMIT 10";

$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, 'ss', $termo, $termo_like);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$quartos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['imagens'] = $row['imagens'] ? explode(',', $row['imagens']) : [];
    $quartos[] = $row;
}

echo json_encode($quartos);
?>