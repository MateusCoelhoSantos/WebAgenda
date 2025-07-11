<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserindo cliente</title>

    <style>
        .container{
            display: flex;
            width: 100vw;
            height: 100px;
            justify-content: center;
            align-items: center;
        }
        .voltar{
            margin-right: 50px;
        }
    </style>
</head>
<header>
    <center><h3>Cliente Inserido</h3></center>
</header>
<body>

<?php

    $clinome = mysqli_real_escape_string($conexao,$_POST["clinome"]);
    $clicpfcnpj = mysqli_real_escape_string($conexao,$_POST["clicpfcnpj"]);
    $clirgie = mysqli_real_escape_string($conexao,$_POST["clirgie"]);
    $clinasc = mysqli_real_escape_string($conexao,$_POST["clinasc"]);
    $cliemail = mysqli_real_escape_string($conexao,$_POST["cliemail"]);
    $clitel = mysqli_real_escape_string($conexao,$_POST["clitel"]);
    $clitipo = mysqli_real_escape_string($conexao,$_POST["clitipo"]);
    $cliorientacao = mysqli_real_escape_string($conexao,$_POST["cliorientacao"]);

    $sql = "INSERT INTO pessoas (
            nome,
            cpfcnpj,
            rgie,
            nasc,
            email,
            telefone,
            f_j,
            orientacaosex,
            tipopessoa) 
            VALUES (
            '{$clinome}',
            '{$clicpfcnpj}',
            '{$clirgie}',
            '{$clinasc}',
            '{$cliemail}',
            '{$clitel}',
            '{$clitipo}',
            '{$cliorientacao}',
            1)";
            mysqli_query($conexao,$sql) or die("Erro ao Executar a Consulta!" . mysqli_error($conexao));

            echo " <center> O Registro Foi Inserido Com Sucesso! </center>";

?>
<div class="container">
    <div class="voltar">
        <center><a href="agenda.php?menuop=cadastrocliente"><button type="submit" class="btn btn-success">Voltar</button></a></center>
    </div>
    <div class="listagem">
        <center><a href="agenda.php?menuop=clientes"><button type="submit" class="btn btn-success">Listagem</button></a></center>   
    </div>
</div>

</body>
</html>
