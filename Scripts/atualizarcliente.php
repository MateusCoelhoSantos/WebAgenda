<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizando cliente</title>

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
    <center><h3>Cliente Atualizado</h3></center>
</header>
<body>

<?php

    $idcli = mysqli_real_escape_string($conexao,$_POST["cliid"]);
    $clinome = mysqli_real_escape_string($conexao,$_POST["clinome"]);
    $clicpfcnpj = mysqli_real_escape_string($conexao,$_POST["clicpfcnpj"]);
    $clirgie = mysqli_real_escape_string($conexao,$_POST["clirgie"]);
    $clinasc = mysqli_real_escape_string($conexao,$_POST["clinasc"]);
    $cliemail = mysqli_real_escape_string($conexao,$_POST["cliemail"]);
    $clitel = mysqli_real_escape_string($conexao,$_POST["clitel"]);
    $clitipo = mysqli_real_escape_string($conexao,$_POST["clitipo"]);
    $cliorientacao = mysqli_real_escape_string($conexao,$_POST["cliorientacao"]);

    $sql = "UPDATE pessoas SET
            nome = '{$clinome}',
            cpfcnpj = '{$clicpfcnpj}',
            rgie = '{$clirgie}',
            nasc = '{$clinasc}',
            email = '{$cliemail}',
            telefone = '{$clitel}',
            f_j = '{$clitipo}',
            orientacaosex = '{$cliorientacao}'
            WHERE id_pessoa = {$idcli}
            ";
            mysqli_query($conexao,$sql) or die("Erro ao Executar a Atualização! " . mysqli_error($conexao));

            echo "<center>O Registro Foi Atualizado Com Sucesso!</center>";

?>
<div class="container">
    <div class="listagem">
        <center><a href="agenda.php?menuop=clientes"><button type="submit" class="btn btn-success">Voltar</button></a></center>   
    </div>
</div>

</body>
</html>