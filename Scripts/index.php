<?php
// A forma correta e segura de garantir que a sessão está ativa
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebAgenda - Boas-vindas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* PASSO 2: Adiciona um preenchimento no topo para não esconder o conteúdo atrás do menu */
        body {
            padding-top: 95px; 
        }
    </style>
</head>
<body style="background-color: #f8f9fa;">

<header>
    <nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php?menuop=home">
                <img src="../Imagens/LogoWebAgenda.png" alt="Logo WebAgenda" width="60" height="60" class="me-2">
                <span class="fs-4 fw-bold text-primary">WebAgenda</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?menuop=home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?menuop=cadastro">Cadastro</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary" href="index.php?menuop=login">
                           <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main>
    <?php
        $menuop = (isset($_GET["menuop"])) ? $_GET["menuop"] : "home";
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
            case 'insereusuario':
                include("insereusuario.php");
                break;
            default:
                include("home.php");
                break;
        }
    ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
// Este bloco verifica se existe uma mensagem na sessão
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    ?>
    <script>
        // Dispara o pop-up do SweetAlert2
        Swal.fire({
            icon: '<?= $message['type'] ?>', // 'success' ou 'error'
            title: '<?= $message['text'] ?>',
            showConfirmButton: false,
            timer: 3000 // Aumentei o tempo para dar tempo de ler
        });
    </script>
    <?php
    // Limpa a mensagem da sessão para que ela não apareça novamente
    unset($_SESSION['message']);
}
?>

</body>
</html>