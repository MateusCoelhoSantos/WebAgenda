<header>
    <h3>Cliente Excluido</h3>
</header>
<?php
$idcli = mysqli_real_escape_string($conexao,$_GET["idcli"]);
$sql = "DELETE FROM pessoas WHERE id_pessoa = '{$idcli}'";

mysqli_query($conexao,$sql) or die("Erro ao Exluir o Registro! " . mysqli_error($conexao)); 
echo "Registro Excluido com Sucesso!";
?>