<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluindo Quarto</title>

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
    <center><h3>Quarto Excluido</h3></center>
</header>
<body>
 
<?php
    $id_quarto = mysqli_real_escape_string($conexao,$_GET["idquarto"]);
    $sql = "update quartos set excluido = 1 WHERE id_quarto = '{$id_quarto}'";

    mysqli_query($conexao,$sql) or die("Erro ao Exluir o Registro! " . mysqli_error($conexao)); 
    echo "<center>Registro Excluido com Sucesso!</center>";
?>
<div class="container">
    <a href="agenda.php?menuop=quartos"><button type="submit" class="btn btn-success">Voltar</button></a>
</div>

</body>
</html>


