<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incluir Agendamento</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        .container{
            margin-top: 15px;
            margin-bottom: 100px;
        }
        .botao{
            margin-top: 25px;
            margin-left: 94%; 
            margin-bottom: 50px;  
        }
        /* .container{
            display: flex;
            width: 100vw;
            height: 100px;
            justify-content: center;
            align-items: center;
        } */
        .cods{
            display: flex;    
        }
        .cods .cod{
            width: 100px;
            margin-right: 15px;   
        }
        .cods .nome{
            width: 100%;
        }
    </style>

</head>
<header>
    <center><h3>Cadastro de Agendamento</h3></center>
</header>
<body>
    <div class="container">
        <form action="agenda.php?menuop=inserequarto" method="post">
        
        <div class="cods">
            <div class="cod">
                <label for="codcliente">Código</label>
                <input type="text" class="form-control" name="codcliente" disabled required>
            </div>
            <div class="nome">
                <label for="nomecliente">Cliente</label>
                <input type="text"  class="form-control" name="nomecliente" required>
            </div>
            
        </div>

        <div class="cods">
            <div class="cod">
                <label for="numquarto">Número</label>
                <input type="text" class="form-control" name="numquarto" disabled required>
            </div>
            <div class="nome">
                <label for="descquarto">Quarto</label>
                <input type="text"  class="form-control" name="text" required>
            </div>
            
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>