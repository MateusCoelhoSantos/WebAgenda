<?php 
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
    <title>WebAgenda - Organização e Agendamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="background-color: #f0f2f5;">

<div class="container py-5">

    <div class="p-5 mb-5 bg-light rounded-3 text-center">
        <div class="container-fluid py-5">
            <h1 class="display-4 fw-bold">Organize suas reservas de forma inteligente</h1>
            <p class="fs-4 mt-3">WebAgenda é a solução completa para gerenciar seus agendamentos, clientes e quartos com facilidade e eficiência.</p>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-5">
                <a href="index.php?menuop=login" class="btn btn-primary btn-lg px-4 gap-3">Fazer Login</a>
                <a href="index.php?menuop=cadastro" class="btn btn-outline-secondary btn-lg px-4">Criar uma Conta</a>
            </div>
        </div>
    </div>
    
    <div class="row text-center">
        <h2 class="text-secondary mb-4">Tudo que você precisa em um só lugar</h2>
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body p-4">
                    <i class="bi bi-calendar-check text-primary" style="font-size: 3rem;"></i>
                    <h4 class="card-title mt-3">Agendamentos Simplificados</h4>
                    <p class="card-text">Crie e visualize reservas em um calendário intuitivo. Evite conflitos de horário e tenha uma visão clara da sua ocupação.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body p-4">
                    <i class="bi bi-people-fill text-primary" style="font-size: 3rem;"></i>
                    <h4 class="card-title mt-3">Gestão de Clientes</h4>
                    <p class="card-text">Mantenha um cadastro completo de seus clientes. Acesse o histórico e as informações de contato com rapidez e segurança.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body p-4">
                    <i class="bi bi-key-fill text-primary" style="font-size: 3rem;"></i>
                    <h4 class="card-title mt-3">Controle de Quartos</h4>
                    <p class="card-text">Gerencie o status de seus quartos ou espaços, sabendo exatamente o que está disponível, ocupado ou em manutenção.</p>
                </div>
            </div>
        </div>
    </div>

</div>

<footer class="py-3 mt-4 bg-white text-center">
    <p class="mb-0 text-muted">&copy; 2025 WebAgenda. Todos os direitos reservados.</p>
</footer>

</body>
</html>