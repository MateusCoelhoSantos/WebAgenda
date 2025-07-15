<?php
$idquarto = isset($_GET["id_quarto"]) ? $_GET["id_quarto"] : null;
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
    <center><h3>Cadastro de Quarto</h3></center>
</header>
<body>

<div class="container">
    <div class="container mt-3">
        <form action="agenda.php?menuop=inserequarto" method="post">
            <div>
                <label for="numquarto">Número do Quarto</label>
                <input type="number"  class="form-control" name="numquarto" required>
            </div>
            
            <div>
                <label for="descricao">Descrição</label>
                <input type="text" class="form-control" name="descricao" required>
            </div>
            <div class ="botao">
                <input class="btn btn-success" type="submit" value="incluir" nome="incluir">
            </div>
        </form>
    </div>   
</div>
    
</body>
</html>