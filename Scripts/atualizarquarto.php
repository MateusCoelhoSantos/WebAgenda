<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizando Quarto</title>

    <style>
        .container{
            display: flex;
            width: 100vw;
            height: 100px;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<header>
    <center><h3>Quarto Atualizado</h3></center>
</header>
<body>

<?php

    $id_quarto = mysqli_real_escape_string($conexao,$_POST["idquarto"]);
    $num_quarto = mysqli_real_escape_string($conexao,$_POST["numquarto"]);
    $descricao = mysqli_real_escape_string($conexao,$_POST["descricao"]);
    // $status = mysqli_real_escape_string($conexao,$_POST[""]);
    // $excluido = mysqli_real_escape_string($conexao,$_POST[""]);
    // $imagem = mysqli_real_escape_string($conexao,$_POST["img"]);

    $sql = "UPDATE quartos SET
            num_quarto = '{$num_quarto}',
            descricao = '{$descricao}'
            WHERE id_quarto = {$id_quarto}
            ";
            mysqli_query($conexao,$sql) or die("Erro ao Executar a Atualização! " . mysqli_error($conexao));

            echo "<center>O Registro Foi Atualizado Com Sucesso!</center>";

?>
<div class="container">
    <div class="listagem">
        <center><a href="agenda.php?menuop=quartos"><button type="submit" class="btn btn-success">Voltar</button></a></center>   
    </div>
</div>

</body>
</html>