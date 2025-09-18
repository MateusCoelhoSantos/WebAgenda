<?php
// Garante que a sessão está ativa e o usuário logado
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) { header('Location: index.php?menuop=login'); exit(); }

include_once("conexao.php"); 

$dados = null;
$imagens_quarto = [];
try {
    $idquarto = $_GET["idquarto"] ?? null;
    if ($idquarto === null || !is_numeric($idquarto)) {
        throw new Exception("ID do Quarto inválido.");
    }

    // Busca os dados principais do quarto
    $sql = "SELECT * FROM quartos WHERE id_quarto = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idquarto);
    mysqli_stmt_execute($stmt);
    $rs = mysqli_stmt_get_result($stmt);
    $dados = mysqli_fetch_assoc($rs);

    if (!$dados) {
        throw new Exception("Nenhum quarto encontrado com o ID fornecido.");
    }

    // Busca as imagens associadas a este quarto
    $sql_img = "SELECT id_imagem, nome_arquivo FROM quarto_imagens WHERE id_quarto = ?";
    $stmt_img = mysqli_prepare($conexao, $sql_img);
    mysqli_stmt_bind_param($stmt_img, 'i', $idquarto);
    mysqli_stmt_execute($stmt_img);
    $rs_img = mysqli_stmt_get_result($stmt_img);
    while($imagem = mysqli_fetch_assoc($rs_img)){
        $imagens_quarto[] = $imagem;
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
        <div class="col-12 col-lg-10 col-xl-8">
            
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h3 class="text-center mb-0">Editar Quarto</h3></div>
                <div class="card-body p-4">
                    <form action="agenda.php?menuop=atualizarquarto" method="post">
                        <input type="hidden" name="idquarto" value="<?= htmlspecialchars($dados["id_quarto"]) ?>">
                        <div class="row">
                            <div class="col-md-4 mb-3"><label for="numquarto" class="form-label">Número</label><input type="text" class="form-control" id="numquarto" name="numquarto" value="<?= htmlspecialchars($dados["num_quarto"]) ?>" required></div>
                            <div class="col-md-8 mb-3"><label for="nome_quarto" class="form-label">Nome / Tipo</label><input type="text" class="form-control" id="nome_quarto" name="nome_quarto" value="<?= htmlspecialchars($dados["nome_quarto"]) ?>" required></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3"><label for="capacidade_adultos" class="form-label">Cap. Adultos</label><input type="number" class="form-control" id="capacidade_adultos" name="capacidade_adultos" value="<?= htmlspecialchars($dados["capacidade_adultos"]) ?>" required></div>
                            <div class="col-md-4 mb-3"><label for="capacidade_criancas" class="form-label">Cap. Crianças</label><input type="number" class="form-control" id="capacidade_criancas" name="capacidade_criancas" value="<?= htmlspecialchars($dados["capacidade_criancas"]) ?>" required></div>
                            <div class="col-md-4 mb-3"><label for="preco_diaria" class="form-label">Preço Diária (R$)</label><input type="number" step="0.01" class="form-control" id="preco_diaria" name="preco_diaria" value="<?= htmlspecialchars($dados["preco_diaria"]) ?>" required></div>
                        </div>
                        <div class="mb-3"><label for="descricao" class="form-label">Descrição Detalhada</label><textarea class="form-control" name="descricao" id="descricao" rows="3"><?= htmlspecialchars($dados["descricao"]) ?></textarea></div>
                        <div class="mb-3"><label class="form-label d-block">Comodidades</label>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="tem_wifi" name="comodidades[wifi]" value="1" <?php if($dados['tem_wifi']) echo 'checked'; ?>><label class="form-check-label" for="tem_wifi">Wi-Fi</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="tem_ar" name="comodidades[ar]" value="1" <?php if($dados['tem_ar_condicionado']) echo 'checked'; ?>><label class="form-check-label" for="tem_ar">Ar Condicionado</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="tem_tv" name="comodidades[tv]" value="1" <?php if($dados['tem_tv']) echo 'checked'; ?>><label class="form-check-label" for="tem_tv">TV</label></div>
                        </div>
                        <div class="card-footer text-end bg-white px-0 pt-3"><a href="agenda.php?menuop=quartos" class="btn btn-secondary">Cancelar</a><button type="submit" class="btn btn-primary" name="atualizar"><i class="bi bi-check-circle"></i> Salvar Dados</button></div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-images"></i> Gerenciar Imagens</h5></div>
                <div class="card-body p-4">
                    <h6>Imagens Atuais</h6>
                    <div class="row">
                        <?php if (empty($imagens_quarto)): ?>
                            <p class="text-muted">Nenhuma imagem cadastrada para este quarto.</p>
                        <?php else: ?>
                            <?php foreach ($imagens_quarto as $imagem): ?>
                                <div class="col-6 col-md-4 mb-3 text-center">
                                    <img src="../Imagens/Quartos/<?= htmlspecialchars($imagem['nome_arquivo']) ?>" class="img-thumbnail mb-2" style="height: 100px; object-fit: cover;">
                                    <a href="remover_imagem_quarto.php?id_imagem=<?= $imagem['id_imagem'] ?>&id_quarto=<?= $idquarto ?>" class="btn btn-sm btn-outline-danger btn-excluir-img" title="Excluir Imagem"><i class="bi bi-trash3"></i></a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <h6>Adicionar Novas Imagens (até 3)</h6>
                    <form action="adicionar_imagem_quarto.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="idquarto" value="<?= htmlspecialchars($dados["id_quarto"]) ?>">
                        <div class="input-group"><input class="form-control" type="file" name="fotos_quarto[]" multiple accept="image/png, image/jpeg, image/gif"><button class="btn btn-success" type="submit"><i class="bi bi-upload"></i> Enviar</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Pop-up de confirmação para exclusão de imagem
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-excluir-img');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); 
            const deleteUrl = this.href;
            Swal.fire({
                title: 'Tem certeza?', text: "Deseja realmente excluir esta imagem?", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, excluir!', cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) { window.location.href = deleteUrl; }
            });
        });
    });
});
</script>
</body>
</html>