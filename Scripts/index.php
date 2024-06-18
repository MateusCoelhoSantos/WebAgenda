<?php
    include("conexao.php")
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebAgenda - Centro</title>

    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous"
    >

    <style>
        body {
            font-family: Arial, sans-serif;
        }
        header {
            text-align: left;
            padding:30px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .content {
            margin: 20px;
        }
        img {
          float: left; /* Faz a imagem flutuar à esquerda do texto */
          margin-right: 5px; /* Adiciona espaço entre a imagem e o texto */
        }

    </style>
</head>
<body>
    <header>
        <img src="LogoWebAgenda.png" width="120" height="120"> 
        <br>
        <b><h1>WebAgenda</h1></b>
        <nav>
            <a href="index.php?menuop=home"><button type="submit" class="btn btn-primary">Home</button></a>
            <a href="index.php?menuop=login"><button type="submit" class="btn btn-primary">Login</button></a>
            <a href="index.php?menuop=cadastro"><button type="submit" class="btn btn-primary">Cadastro</button></a>
        </nav>
    </header>
    <main>  
        <?php
            $menuop = (isset($_GET["menuop"]))?$_GET["menuop"]:"home";
            switch ($menuop) {
                case 'home':
                    include("home.php");
                    break;
                case 'login':
                    include("login.php");
                    break;
                case 'cadastro':
                    include("cadusu.php");
                    break;
                default:
                include("home.php");
                    break;
            }

        ?>
    </main>
</body>
</html>