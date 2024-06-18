<header>
    <h3>Cliente Inserido</h3>
</header>
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
            1
            )";
            mysqli_query($conexao,$sql) or die("Erro ao Executar a Consulta!" . mysqli_error($conexao));

            echo "O Registro Foi Inserido Com Sucesso!";

?>