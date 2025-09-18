<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Quarto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="background-color: #f0f2f5;">

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="text-center mb-0">Cadastrar Novo Quarto</h3>
                </div>
                <div class="card-body p-4">
                    <form action="agenda.php?menuop=inserequarto" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="numquarto" class="form-label">Número do Quarto</label>
                                <input type="text" class="form-control" id="numquarto" name="numquarto" required>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="nome_quarto" class="form-label">Nome / Tipo do Quarto</label>
                                <input type="text" class="form-control" id="nome_quarto" name="nome_quarto" placeholder="Ex: Suíte Master, Quarto Família" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="capacidade_adultos" class="form-label">Capacidade (Adultos)</label>
                                <input type="number" class="form-control" id="capacidade_adultos" name="capacidade_adultos" value="2" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="capacidade_criancas" class="form-label">Capacidade (Crianças)</label>
                                <input type="number" class="form-control" id="capacidade_criancas" name="capacidade_criancas" value="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="preco_diaria" class="form-label">Preço da Diária (R$)</label>
                                <input type="number" step="0.01" class="form-control" id="preco_diaria" name="preco_diaria" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição Detalhada</label>
                            <textarea class="form-control" name="descricao" id="descricao" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Comodidades</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="tem_wifi" name="comodidades[wifi]" value="1" checked>
                                <label class="form-check-label" for="tem_wifi">Wi-Fi</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="tem_ar" name="comodidades[ar]" value="1" checked>
                                <label class="form-check-label" for="tem_ar">Ar Condicionado</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="tem_tv" name="comodidades[tv]" value="1" checked>
                                <label class="form-check-label" for="tem_tv">TV</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="fotos_quarto" class="form-label">Fotos do Quarto (até 3)</label>
                            <input class="form-control" type="file" id="fotos_quarto" name="fotos_quarto[]" multiple accept="image/png, image/jpeg, image/gif">
                        </div>
                        
                        <div class="card-footer text-end bg-white px-0 pt-3">
                            <a href="agenda.php?menuop=quartos" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success" name="incluir">
                                <i class="bi bi-check-circle"></i> Cadastrar Quarto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>