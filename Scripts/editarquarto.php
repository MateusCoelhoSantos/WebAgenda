<?php
include_once("conexao.php");

$dados = null;
try {
    $idquarto = $_GET["idquarto"] ?? null;
    if ($idquarto === null || !is_numeric($idquarto)) {
        throw new Exception("ID do Quarto inválido ou não fornecido.");
    }

    $sql = "SELECT * FROM quartos WHERE id_quarto = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idquarto);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);
    $dados = mysqli_fetch_assoc($rs);

    if (!$dados) {
        throw new Exception("Nenhum quarto encontrado com o ID fornecido.");
    }
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Quarto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="background-color: #f0f2f5;">

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="text-center mb-0">Editar Quarto</h3>
                </div>
                <div class="card-body p-4">
                    <form action="agenda.php?menuop=atualizarquarto" method="post">
                        
                        <input type="hidden" name="idquarto" value="<?= htmlspecialchars($dados["id_quarto"]) ?>">

                        <div class="mb-3">
                            <label for="numquarto" class="form-label">Número do Quarto</label>
                            <input type="text" class="form-control" id="numquarto" name="numquarto" value="<?= htmlspecialchars($dados["num_quarto"]) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição (Tipo do Quarto)</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" value="<?= htmlspecialchars($dados["descricao"]) ?>" required>
                        </div>

                        <div class="card-footer text-end bg-white px-0 pt-3">
                            <a href="agenda.php?menuop=quartos" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success" name="atualizar">
                                <i class="bi bi-check-circle"></i> Salvar Alterações
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