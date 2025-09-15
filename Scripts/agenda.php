<?php
session_start();
include_once("conexao.php");

// --- INÍCIO DO BLOCO DE AUTENTICAÇÃO ---

// Verifica se o formulário de login foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    
    $usuario = $_POST['email'];
    $senha_digitada = $_POST['senha'];

    // 1. Busca o usuário no banco de dados de forma segura
    // Usaremos a tabela 'usuarios' como exemplo
    $sql = "SELECT id_usuario, nome, senha FROM usuarios WHERE (email = ? OR senha = ?) AND excluido = 0";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $usuario, $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    // 2. Verifica se o usuário existe E se a senha está correta
    if ($user && password_verify($senha_digitada, $user['senha'])) {
        // --- SUCESSO NO LOGIN ---

        // 3. Regenera a sessão para segurança (previne session fixation)
        session_regenerate_id(true);

        // 4. Guarda os dados do usuário na sessão
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['user_name'] = $user['nome'];
        
        // Redireciona para a página principal do sistema (dashboard)
        header("Location: agenda.php?menuop=home");
        exit();

    } else {
        // --- FALHA NO LOGIN ---
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Usuário ou senha inválidos.'
        ];
        // Redireciona de volta para a página de login para mostrar o erro
        header("Location: index.php?menuop=login");
        exit();
    }
}
// --- FIM DO BLOCO DE AUTENTICAÇÃO ---


// --- INÍCIO DA PROTEÇÃO DE PÁGINA ---
if (!isset($_SESSION['user_id'])) {
    // Se o usuário não estiver logado, redireciona para a página de login
    header("Location: index.php?menuop=login");
    exit();
}
// --- FIM DA PROTEÇÃO DE PÁGINA ---

// Se o código chegou até aqui, significa que o usuário está logado.
// Agora você pode carregar o resto da sua página de agenda.
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
                case 'insereagendamento':
                    include("insereagendamento.php");
                    break; 
                case 'atualizaragendamento':
                    include("atualizaragendamento.php");
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