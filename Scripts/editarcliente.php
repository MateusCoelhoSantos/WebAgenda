<?php
$idcli = isset($_GET["idcli"]) ? $_GET["idcli"] : null;
if ($idcli === null) {
    die("ID do cliente não foi fornecido.");
}
$sql = "SELECT * FROM pessoas WHERE id_pessoa = {$idcli}";
$rs = mysqli_query($conexao,$sql) or die("Erro ao Recuperar os Dados do Registro! " . mysqli_error($conexao));
$dados = mysqli_fetch_assoc($rs);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>

    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous"
    >
    <style>
        .form-group{
            float: left;
            margin-top: 25px;
        }
        .form-group2{
            float: left;
            margin-top: 25px;
            margin-left: 25px;
        }
        .botao{
            margin-top: 25px;
            margin-left: 1025px;   
        }
    </style>
</head>

<Header>
    <h3>Editar Cliente</h3>
</Header>
<div class="container mt-3">
    <form action="agenda.php?menuop=atualizarcliente" method="post">
        <div>
            <label for="cliid">ID</label>
            <input type="text"  class="form-control" name="cliid" value="<?=$dados["id_pessoa"]?>" required>
        </div>

        <div>
            <label for="clinome">Nome</label>
            <input type="text"  class="form-control" name="clinome" value="<?=$dados["nome"]?>" required>
        </div>
        
        <div>
            <label for="clicpfcnpj">CPF/CNPJ</label>
            <input type="text" class="form-control" name="clicpfcnpj" value="<?=$dados["cpfcnpj"]?>" required>
        </div>

        <div>
            <label for="clirgie">RG/IE</label>
            <input type="text" class="form-control" name="clirgie" value="<?=$dados["rgie"]?>">
        </div>

        <div>
            <label for="clinasc">Data de Nascimento</label>
            <input type="date" class="form-control" name="clinasc" value="<?=$dados["nasc"]?>">
        </div>

        <div>
            <label for="cliemail">E-mail</label>
            <input type="text" class="form-control" name="cliemail" value="<?=$dados["email"]?>">
        </div>

        <div>
            <label for="clitel">Telefone</label>
            <input type="text" class="form-control" name="clitel" value="<?=$dados["telefone"]?>">
        </div>

        <fieldset class="form-group">
            <legend class="col-form-label pt-0">Tipo Pessoa:</legend>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="pessoafisica" name="clitipo" value="0" >
                <label class="form-check-label" for="pessoafisica">Pessoa Fisica</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="pessoajuridica" name="clitipo" value="1">
                <label class="form-check-label" for="pessoajuridica">Pessoa Juridica</label>
            </div>
        </fieldset>

        <fieldset class="form-group2">
            <legend class="col-form-label pt-0">Orientação Sexual:</legend>
            <div class="form-check">
                <? if ($dados["orientacaosex"] = 'M') {?>
                <input class="form-check-input" type="radio" id="masculino" name="cliorientacao" value="M" checked>
                <?}else {?>
                <input class="form-check-input" type="radio" id="masculino" name="cliorientacao" value="M" >
                <?}?>
                <label class="form-check-label" for="masculino">Masculino</label>
            </div>
            <div class="form-check">
                <? if ($dados["orientacaosex"] = 'F') {?>
                <input class="form-check-input" type="radio" id="feninino" name="cliorientacao" value="F" checked>
                <?}else {?>
                <input class="form-check-input" type="radio" id="feninino" name="cliorientacao" value="F">
                <?}?>
                <label class="form-check-label" for="feninino">Feminino</label>
            </div>
            <div class="form-check">
                <? if ($dados["orientacaosex"] = 'N') {?>
                <input class="form-check-input" type="radio" id="naoidentificado" name="cliorientacao" value="N" checked>
                <?}else {?>
                <input class="form-check-input" type="radio" id="naoidentificado" name="cliorientacao" value="N">
                <?}?>
                <label class="form-check-label" for="naoidentificado">Não Identificado</label>
            </div>
        </fieldset>
        <div class ="botao">
            <input type="submit" value="Atualizar" nome="atualizar">
        </div>
    </form>
</div>
</html>