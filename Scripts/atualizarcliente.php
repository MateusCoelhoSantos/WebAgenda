<header>
    <h3>Cliente Atualizado</h3>
</header>
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

            echo "O Registro Foi Atualizado Com Sucesso!";

?>