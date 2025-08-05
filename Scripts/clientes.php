<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>

    <!-- <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous"
    > -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        } */
        .row{
            display: flex;
            margin-top: 25px;
            margin-bottom: 25px;
        }
        .row .pesquisa{
            width: 89%;
        }
        .row .novocli{
            width: 11%;
        }
        .a{
            margin-trim: all;
        }
        .form{
            display: flex;
        }
        .form-control{
            margin-right: 10px;
            width: 250px;
        }

    </style>
</head>
<header>
    <center><h3>Clientes</h3></center>
</header>
<body class="bg-light">
<div class="container mt-5">
    <div class="row align-items-center mb-4">
        <!-- Campo de pesquisa -->
        <div class="col-md-9">
            <form class="d-flex" action="agenda.php?menuop=clientes" method="post">
                <input class="form-control me-2" type="text" name="pesquisa" placeholder="Pesquisar">
                <input class="btn btn-success" type="submit" value="Pesquisar">
            </form>
        </div>

        <!-- Botão novo cliente -->
        <div class="col-md-3 text-end">
            <a href="agenda.php?menuop=cadastrocliente" class="btn btn-success">Incluir Cliente</a>
        </div>
    </div>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPf/CNPJ</th>
                <th>RG/IE</th>
                <th>Tipo Pessoa</th>
                <th>Data de Nascimento</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Sexo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody class="bg-light">
        <?php

        $quantidade = 20;

        $pagina = (isset($_GET['pagina']))?(int)$_GET['pagina']:1;

        $inicio = ($quantidade * $pagina) - $quantidade;

        $pesquisa = (isset($_POST["pesquisa"]))?$_POST["pesquisa"]:"";

        $sql = "SELECT *, UPPER(nome) as nome, LOWER(email) AS email,
                CASE 
                    WHEN f_j = 0 THEN 'Pessoa Fisica' 
                    WHEN f_j = 1 THEN 'Pessoa Juridica' 
                    END AS 'tipagem_pessoa',
                CASE
                    WHEN orientacaosex = 'M' THEN 'Masculino'
                    WHEN orientacaosex = 'F' THEN 'Feminino' 
                    WHEN orientacaosex = 'N' THEN 'Não Identificado'
                    END AS 'orientacao',
                DATE_FORMAT (nasc,'%d/%m/%Y') as nasc
                FROM pessoas
                WHERE (excluido <> 1) AND tipopessoa = 1 AND (id_pessoa = '{$pesquisa}' OR nome LIKE '%{$pesquisa}%')
                ORDER BY id_pessoa
                LIMIT $inicio , $quantidade
                ";
        $RS = mysqli_query($conexao,$sql) or die("Erro ao Executar a Consulta!" . mysqli_error($conexao));

        if (mysqli_num_rows($RS) > 0) {
            foreach ($RS as $dados) {
            
        ?>
        <tr>
            <td><?=$dados["id_pessoa"] ?></td>
            <td><?=$dados["nome"] ?></td>
            <td><?=$dados["cpfcnpj"] ?></td>
            <td><?=$dados["rgie"] ?></td>
            <td><?=$dados["tipagem_pessoa"] ?></td>
            <td><?=$dados["nasc"] ?></td>
            <td><?=$dados["email"] ?></td>
            <td><?=$dados["telefone"] ?></td>
            <td><?=$dados["orientacao"] ?></td>
            <td><a href="agenda.php?menuop=editarcliente&idcli=<?=$dados["id_pessoa"] ?>" class="btn btn-primary">Alterar</a>
                <a href="agenda.php?menuop=excluircliente&idcli=<?=$dados["id_pessoa"] ?>" class="btn btn-danger">Excluir</a></td>
        </tr>
    <?php
        }
        } else {
            echo "Nenhum Cliente cadastrado.";    
        }
        ?>

    </tbody>
</table>
</div>

<br>
<?php
$sqltotal = "SELECT id_pessoa FROM pessoas";
$qrtotal = mysqli_query($conexao,$sqltotal) or die(mysqli_error($conexao));
$numtotal = mysqli_num_rows($qrtotal);
$totalpagina = ceil($numtotal/$quantidade); 
echo "Total de Registros: $numtotal <br>";
echo '<a href="?menuop=clientes&pagina=1">Primeira Pagina</a>';

if ($pagina>6) {
    ?>
        <a href="?menuop=clientes&pagina=<?php echo $pagina-1?>"> << </a>
    <?php
}

for ($i=1; $i <=$totalpagina; $i++) { 
    
    if ($i>=($pagina-5) && $i <= ($pagina+5)) {
        if ($i==$pagina) {
            echo $i;
        } else {
            echo "<a href=\"?menuop=clientes&pagina=$i\">$i</a> ";
        }
    }
}

if ($pagina< ($totalpagina-5)) {
    ?>
        <a href="?menuop=clientes&pagina=<?php echo $pagina+1?>"> >> </a>
    <?php
}


echo "<a href=\"?menuop=clientes&pagina=$totalpagina\">Ultima Pagina</a>";

?>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
