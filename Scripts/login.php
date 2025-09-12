<?php
require 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebAgenda - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    
</head>
<body>
    <center><h1>LOGIN</h1></center>
        <div class="container mt-5">
            <form method="post" action="agenda.php">
                <div class="form-group">
                    <label for="usuario">Usuário:</label>
                    <input type="text" class="form-control" id="usuario" name="usuario">
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" class="form-control" id="senha" name="senha"> 
                </div>
                <center>
                    <br>
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </center> 
            </form> 
        </div>
</body>
</html>