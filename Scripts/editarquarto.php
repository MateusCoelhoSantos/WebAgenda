<?php
$idquarto = isset($_GET["idquarto"]) ? $_GET["idquarto"] : null;
if ($idquarto === null) {
    die("ID do Quarto não foi fornecido.");
}
$sql = "SELECT * FROM quartos WHERE id_quarto = {$idquarto}";
$rs = mysqli_query($conexao,$sql) or die("Erro ao Recuperar os Dados do Registro! " . mysqli_error($conexao));
$dados = mysqli_fetch_assoc($rs);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editando Quarto</title>

    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous"
    >

    <style>
        .container{
            margin-top: 15px;
            margin-bottom: 100px;
        }
        .botao{
            margin-top: 25px;
            margin-left: 1025px; 
            margin-bottom: 50px;  
        }
        /* .container{
            display: flex;
            width: 100vw;
            height: 100px;
            justify-content: center;
            align-items: center;
        } */
    </style>

</head>
<header>
    <center><h3>Editar Quarto</h3></center>
</header>
<body>

<div class="container">
    <form action="agenda.php?menuop=inserequarto" method="post">
        <div>
            <label for="numquarto">ID</label>
            <input type="number"  class="form-control" name="idquarto" value="<?=$dados["id_quarto"]?>" required>
        </div>

        <div>
            <label for="numquarto">Número do Quarto</label>
            <input type="number"  class="form-control" name="numquarto" value="<?=$dados["num_quarto"]?>" required>
        </div>
        
        <div>
            <label for="descricao">Descrição</label>
            <input type="text" class="form-control" name="descricao"  value="<?=$dados["descricao"]?>" required>
        </div>
        <div class ="botao">
            <input type="submit" class="btn btn-success" value="atualizar" nome="atualizar">
        </div>
    </form>
</div>
    
</body>
</html>