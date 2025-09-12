<?php
    include("conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebAgenda - Agenda</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


</head>
<body>
    <?php
        // Inclui a estrutura do menu lateral oculto
        require_once('menu_lateral.php'); 
    ?>

    <header>
        <?php 
            // Inclui o botão que vai abrir o menu
            require_once('icone_menu.php'); 
        ?>
    </header>

    <main class="container">
        <?php
            // O seu sistema de rotas PHP para incluir as páginas pode continuar aqui
            $menuop = (isset($_GET['menuop'])) ? $_GET['menuop'] : 'agendamento';
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
                    include("atualizarquarto.php");
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>