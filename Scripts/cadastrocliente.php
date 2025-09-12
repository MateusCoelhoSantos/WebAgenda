<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Clientes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
        /* .container mt-3{
            display: flex;
            width: 100vw;
            height: 100px;
            justify-content: center;
            align-items: center;
        } */
    </style>
</head>

<Header>
    <center><h3>Cadastro de Clientes</h3></center>
</Header>
<body>
    
<div class="container mt-3">
    <form action="agenda.php?menuop=inserecliente" method="post">
        <div>
            <label for="clinome">Nome</label>
            <input type="text"  class="form-control" name="clinome" required>
        </div>
        
        <div>
            <label for="clicpfcnpj">CPF/CNPJ</label>
            <input type="text" class="form-control" name="clicpfcnpj" required>
        </div>

        <div>
            <label for="clirgie">RG/IE</label>
            <input type="text" class="form-control" name="clirgie">
        </div>

        <div>
            <label for="clinasc">Data de Nascimento</label>
            <input type="date" class="form-control" name="clinasc">
        </div>

        <div>
            <label for="cliemail">E-mail</label>
            <input type="text" class="form-control" name="cliemail">
        </div>

        <div>
            <label for="clitel">Telefone</label>
            <input type="text" class="form-control" name="clitel">
        </div>

        <fieldset class="form-group">
            <legend class="col-form-label pt-0">Tipo Pessoa:</legend>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="pessoafisica" name="clitipo" value="0">
                <label class="form-check-label" for="pessoafisica">Pessoa Fisica</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="pessoajuridica" name="clitipo" value="1">
                <label class="form-check-label" for="pessoajuridica">Pessoa Juridica</label>
            </div>
        </fieldset>

        <fieldset class="form-group2">
            <legend class="col-form-label pt-0">Sexo:</legend>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="masculino" name="cliorientacao" value="M" >
                <label class="form-check-label" for="masculino">Masculino</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="feninino" name="cliorientacao" value="F">
                <label class="form-check-label" for="feninino">Feminino</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="naoidentificado" name="cliorientacao" value="N">
                <label class="form-check-label" for="naoidentificado">Não Identificado</label>
            </div>
        </fieldset>
        <div class ="botao">
            <input class="btn btn-success" type="submit" value="incluir" nome="incluir">
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>