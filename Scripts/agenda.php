<?php
    include("conexao.php")
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebAgenda - Agenda</title>

    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous"
    >
    
    <style>
        /* .quadro{
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            width: 300px;
            border-top: 1px solid #dee2e6;
            border-width: 1px solid #dee2e6;
            border-left: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
            margin-left: 5px;
            float: left;
        } */
        header {
            text-align: left;
            padding:15px;
            padding-left: 25px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
    </style>

</head>
<body>
    <header>
        <center>
            <h1>WEBAGENDA</h1>
            <nav>
                <a href="agenda.php?menuop=agendamento"><button type="submit" class="btn btn-primary">Agendamento</button></a>
                <a href="agenda.php?menuop=clientes"><button type="submit" class="btn btn-primary">Clientes</button></a>
                <a href="agenda.php?menuop=quartos"><button type="submit" class="btn btn-primary">Quartos</button></a>
                <a href="index.php"><button type="submit" class="btn btn-primary">sair</button></a>
            </nav>
        </center>
    </header>
    <main>  
        <?php
            $menuop = (isset($_GET["menuop"]))?$_GET["menuop"]:"agendamento";
            switch ($menuop) {
                case 'agendamento':
                    include("agendamento.php");
                    break;
                case 'editaragendamento':
                    include("editaragendamento.php");
                    break; 
                case 'excluiragendamento':
                    include("excluiragendamento.php");
                    break; 
                case 'cadastroagendamento':
                    include("cadastroagendamento.php");
                    break; 
                case 'clientes':
                    include("clientes.php");
                    break;
                case 'quartos':
                    include("quartos.php");
                    break;
                case 'atualizarquarto':
                    include("atualizarcliente.php");
                    break; 
                case 'cadastrocliente':
                    include("cadastrocliente.php");
                    break;
                case 'cadastroquarto':
                    include("cadastroquarto.php");
                    break;
                case 'inserecliente':
                    include("inserecliente.php");
                    break;
                case 'inserequarto':
                    include("inserequarto.php");
                    break;
                case 'editarcliente':
                    include("editarcliente.php");
                    break;
                case 'editarquarto':
                    include("editarquarto.php");
                    break;
                case 'excluirquarto':
                    include("excluirquarto.php");
                    break;
                case 'atualizarcliente':
                    include("atualizarcliente.php");
                    break;  
                case 'excluircliente':
                    include("excluircliente.php");
                    break;                   
                default:
                include("agendamento.php");
                    break;
            }
        ?>
    </main>
</body>
</html>