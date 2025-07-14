<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluindo Cliente</title>

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
    <center><h3>Cliente Excluido</h3></center>
</header>
<body>
 
<?php
    $idcli = mysqli_real_escape_string($conexao,$_GET["idcli"]);
    $sql = "update pessoas set excluido = 1 WHERE id_pessoa = '{$idcli}'";

    mysqli_query($conexao,$sql) or die("Erro ao Exluir o Registro! " . mysqli_error($conexao)); 
    echo "<center>Registro Excluido com Sucesso!</center>";
?>
<div class="container">
    <a href="agenda.php?menuop=clientes"><button type="submit" class="btn btn-success">Voltar</button></a>
</div>

</body>
</html>


