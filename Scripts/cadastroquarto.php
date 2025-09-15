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
        <div class="col-12 col-md-8 col-lg-6">
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="text-center mb-0">Cadastrar Novo Quarto</h3>
                </div>
                <div class="card-body p-4">
                    <form action="agenda.php?menuop=inserequarto" method="post">
                        
                        <div class="mb-3">
                            <label for="numquarto" class="form-label">Número do Quarto</label>
                            <input type="number" class="form-control" id="numquarto" name="numquarto" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição (Ex: Suíte Master, Quarto Simples)</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
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