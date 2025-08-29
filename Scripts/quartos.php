<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quartos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .row{
            display: flex;
            margin-top: 25px;
            margin-bottom: 25px;
        }
        .row .pesquisa{
            width: 89%;
        }
        .form{
            display: flex;
        }
        .form-control{
            margin-right: 10px;
            width: 250px;
        }
        .row .novoqrt{
            width: 11%;
        }
    </style>
</head>
<header>
    <center><h3>Quartos</h3></center>
</header>
<body class="bg-light">
<div class="container mt-5">
    <div class="row align-items-center mb-4">
        <!-- Campo de pesquisa -->
        <div class="col-md-9">
            <form class="d-flex" action="agenda.php?menuop=quartos" method="post">
                <input class="form-control me-2" type="text" name="qrtpesquisa" placeholder="Pesquisar">
                <input class="btn btn-success" type="submit" value="Pesquisar">
            </form>
        </div>

        <!-- Botão de novo quarto -->
        <div class="col-md-3 text-end">
            <a href="agenda.php?menuop=cadastroquarto" class="btn btn-success">Incluir Quarto</a>
        </div>
    </div>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Número</th>
                <th>Tipo</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $quantidade = 10;

            $pagina = (isset($_GET['pagina']))?(int)$_GET['pagina']:1;

            $inicio = ($quantidade * $pagina) - $quantidade;

            $pesquisa = (isset($_POST["qrtpesquisa"]))?$_POST["qrtpesquisa"]:"";

            $sql = "SELECT *, descricao, num_quarto,
                    CASE
                        when status = 0 then 'Disponível'
                        when status = 1 then 'Indisponível'
                    END as status
                    FROM quartos
                    WHERE excluido <> 1 and (num_quarto = '{$pesquisa}' OR descricao LIKE '%{$pesquisa}%')
                    ORDER BY id_quarto
                    LIMIT $inicio , $quantidade
                    ";
            $RS = mysqli_query($conexao,$sql) or die("Erro ao Executar a Consulta!" . mysqli_error($conexao));

            if (mysqli_num_rows($RS) > 0) {
                foreach ($RS as $dados) {

                    echo "<tr>";
                    echo "<td>" . $dados['id_quarto'] . "</td>";
                    echo "<td>" . $dados['num_quarto'] . "</td>";
                    echo "<td>" . $dados['descricao'] . "</td>";
                    echo "<td>" . $dados['status'] . "</td>";
                    echo "<td>
                        <a href='agenda.php?menuop=editarquarto&idquarto=" . $dados['id_quarto'] . "' class='btn btn-primary btn-sm me-1'>Alterar</a>
                        <a href='agenda.php?menuop=excluirquarto&idquarto=" . $dados['id_quarto'] . "' class='btn btn-danger btn-sm' onclick='return confirm(Deseja realmente excluir?)'>Excluir</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>Nenhum quarto cadastrado.</td></tr>";
            }
            
            ?>
        </tbody>
    </table>
</div>

<br>
<center>
<?php
$sqltotal = "SELECT id_quarto FROM quartos where excluido = 0";
$qrtotal = mysqli_query($conexao,$sqltotal) or die(mysqli_error($conexao));
$numtotal = mysqli_num_rows($qrtotal);
$totalpagina = ceil($numtotal/$quantidade); 
echo "Total de Registros: $numtotal <br>";
echo '<a href="?menuop=quartos&pagina=1">Primeira Pagina</a>';

if ($pagina>6) {
    ?>
        <a href="?menuop=quartos&pagina=<?php echo $pagina-1?>"> << </a>
    <?php
}

for ($i=1; $i <=$totalpagina; $i++) { 
    
    if ($i>=($pagina-5) && $i <= ($pagina+5)) {
        if ($i==$pagina) {
            echo $i;
        } else {
            echo "<a href=\"?menuop=quartos&pagina=$i\">$i</a> ";
        }
    }
}

if ($pagina< ($totalpagina-5)) {
    ?>
        <a href="?menuop=quartos&pagina=<?php echo $pagina+1?>"> >> </a>
    <?php
}


echo "<a href=\"?menuop=quartos&pagina=$totalpagina\">Ultima Pagina</a>";

?>
</center>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>