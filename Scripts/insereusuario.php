<?php
require("conexao.php");
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$senha = $_POST['senha'];

$inserecaduso = "INSERT INTO usuarios (nome, email, telefone, senha) VALUES ('$nome','$email','$telefone','$senha')";
$operacaoSQL = mysqli_query($conexao, $inserecaduso);

if (mysqli_affected_rows($conexao) != 0) {
echo "Usuário cadastrado com Sucesso!";
header("Location: login.php");
} 
else {
echo " O Usuário não foi cadastrado com Sucesso!";
header("Location: login.php");
}
?>