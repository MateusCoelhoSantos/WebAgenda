<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserindo Quarto</title>

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
    <center><h3>Quarto Inserido</h3></center>
</header>
<body>
    <?php

        $numquarto = mysqli_real_escape_string($conexao,$_POST["numquarto"]);;
        $descricao = mysqli_real_escape_string($conexao,$_POST["descricao"]);;

        $sql = "INSERT INTO  quartos (
        num_quarto,
        descricao,
        status,
        excluido,
        imagem)
        VALUES (
        '{$numquarto}',
        '{$descricao}',
        0,
        0,
        '')";
        mysqli_query($conexao,$sql) or die("Erro ao Executar a Consulta!" . mysqli_error($conexao));

        echo "<center>O Registro Foi Inserido Com Sucesso!</center>";
    ?>
<div class="container">
    <div class="voltar">
        <center><a href="agenda.php?menuop=cadastroquarto"><button type="submit" class="btn btn-success">Voltar</button></a></center>
    </div>
    <div class="listagem">
        <center><a href="agenda.php?menuop=quartos"><button type="submit" class="btn btn-success">Listagem</button></a></center>   
    </div>
</div>
</body>
</html>