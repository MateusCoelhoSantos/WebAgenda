<?php
// Garante que a sessão está ativa e o usuário logado
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) { header('Location: index.php?menuop=login'); exit(); }

include_once("conexao.php"); 

$errors = [];
$idquarto = $_GET["idquarto"] ?? null;

// --- LÓGICA DE ATUALIZAÇÃO DOS DADOS DO QUARTO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar_dados'])) {
    // Captura os dados do POST
    $idquarto            = $_POST["idquarto"];
    $num_quarto          = $_POST["num_quarto"] ?? '';
    $nome_quarto         = $_POST["nome_quarto"] ?? '';
    $capacidade_adultos  = $_POST["capacidade_adultos"] ?? '';
    $capacidade_criancas = $_POST["capacidade_criancas"] ?? 0;
    $preco_diaria        = $_POST["preco_diaria"] ?? '';
    $descricao           = $_POST["descricao"] ?? '';
    $tem_wifi            = isset($_POST['comodidades']['wifi']) ? 1 : 0;
    $tem_ar_condicionado = isset($_POST['comodidades']['ar']) ? 1 : 0;
    $tem_tv              = isset($_POST['comodidades']['tv']) ? 1 : 0;

    // Validação campo a campo
    if (empty($num_quarto)) { $errors['num_quarto'] = "O número do quarto é obrigatório."; }
    if (empty($nome_quarto)) { $errors['nome_quarto'] = "O nome/tipo do quarto é obrigatório."; }
    if (!is_numeric($capacidade_adultos) || $capacidade_adultos < 1) { $errors['capacidade_adultos'] = "A capacidade de adultos deve ser um número maior que zero."; }
    if (!is_numeric($preco_diaria) || $preco_diaria < 0) { $errors['preco_diaria'] = "O preço deve ser um número válido."; }

    if (empty($errors)) {
        try {
            $sql = "UPDATE quartos SET num_quarto = ?, nome_quarto = ?, descricao = ?, capacidade_adultos = ?, capacidade_criancas = ?, preco_diaria = ?, tem_wifi = ?, tem_ar_condicionado = ?, tem_tv = ? WHERE id_quarto = ?";
            $stmt = mysqli_prepare($conexao, $sql);
            mysqli_stmt_bind_param($stmt, 'sssiidiiii', $num_quarto, $nome_quarto, $descricao, $capacidade_adultos, $capacidade_criancas, $preco_diaria, $tem_wifi, $tem_ar_condicionado, $tem_tv, $idquarto);
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Dados do quarto atualizados com sucesso!'];
                header('Location: agenda.php?menuop=quartos');
                exit();
            } else {
                throw new Exception("Não foi possível executar a atualização.");
            }
        } catch (Exception $e) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Erro de banco de dados: ' . $e->getMessage()];
            header('Location: agenda.php?menuop=quartos');
            exit();
        }
    }
}

// --- LÓGICA PARA CARREGAR OS DADOS NA PRIMEIRA VISITA (GET) ---
try {
    if ($idquarto === null || !is_numeric($idquarto)) { throw new Exception("ID do Quarto inválido."); }

    $sql = "SELECT * FROM quartos WHERE id_quarto = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idquarto);
    mysqli_stmt_execute($stmt);
    $dados = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$dados) { throw new Exception("Nenhum quarto encontrado com o ID fornecido."); }

    $sql_img = "SELECT id_imagem, nome_arquivo FROM quarto_imagens WHERE id_quarto = ?";
    $stmt_img = mysqli_prepare($conexao, $sql_img);
    mysqli_stmt_bind_param($stmt_img, 'i', $idquarto);
    mysqli_stmt_execute($stmt_img);
    $imagens_quarto = mysqli_fetch_all(mysqli_stmt_get_result($stmt_img), MYSQLI_ASSOC);

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
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger"><p class="mb-0">Por favor, corrija os campos indicados abaixo.</p></div>
                    <?php endif; ?>

                    <form action="agenda.php?menuop=editarquarto&idquarto=<?= (int)$idquarto ?>" method="post">
                        <input type="hidden" name="idquarto" value="<?= htmlspecialchars($dados["id_quarto"]) ?>">
                        <div class="row">
                            <div class="col-md-4 mb-3"><label for="num_quarto" class="form-label">Número</label><input type="text" class="form-control <?= isset($errors['num_quarto']) ? 'is-invalid' : '' ?>" id="num_quarto" name="num_quarto" value="<?= htmlspecialchars($_POST['num_quarto'] ?? $dados["num_quarto"]) ?>" required><?php if (isset($errors['num_quarto'])): ?><div class="invalid-feedback"><?= $errors['num_quarto'] ?></div><?php endif; ?></div>
                            <div class="col-md-8 mb-3"><label for="nome_quarto" class="form-label">Nome / Tipo</label><input type="text" class="form-control <?= isset($errors['nome_quarto']) ? 'is-invalid' : '' ?>" id="nome_quarto" name="nome_quarto" value="<?= htmlspecialchars($_POST['nome_quarto'] ?? $dados["nome_quarto"]) ?>" required><?php if (isset($errors['nome_quarto'])): ?><div class="invalid-feedback"><?= $errors['nome_quarto'] ?></div><?php endif; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3"><label for="capacidade_adultos" class="form-label">Cap. Adultos</label><input type="number" class="form-control <?= isset($errors['capacidade_adultos']) ? 'is-invalid' : '' ?>" id="capacidade_adultos" name="capacidade_adultos" value="<?= htmlspecialchars($_POST['capacidade_adultos'] ?? $dados["capacidade_adultos"]) ?>" required><?php if (isset($errors['capacidade_adultos'])): ?><div class="invalid-feedback"><?= $errors['capacidade_adultos'] ?></div><?php endif; ?></div>
                            <div class="col-md-4 mb-3"><label for="capacidade_criancas" class="form-label">Cap. Crianças</label><input type="number" class="form-control" id="capacidade_criancas" name="capacidade_criancas" value="<?= htmlspecialchars($_POST['capacidade_criancas'] ?? $dados["capacidade_criancas"]) ?>" required></div>
                            <div class="col-md-4 mb-3"><label for="preco_diaria" class="form-label">Preço Diária (R$)</label><input type="number" step="0.01" class="form-control <?= isset($errors['preco_diaria']) ? 'is-invalid' : '' ?>" id="preco_diaria" name="preco_diaria" value="<?= htmlspecialchars($_POST['preco_diaria'] ?? $dados["preco_diaria"]) ?>" required><?php if (isset($errors['preco_diaria'])): ?><div class="invalid-feedback"><?= $errors['preco_diaria'] ?></div><?php endif; ?></div>
                        </div>
                        <div class="mb-3"><label for="descricao" class="form-label">Descrição Detalhada</label><textarea class="form-control" name="descricao" id="descricao" rows="3"><?= htmlspecialchars($_POST['descricao'] ?? $dados["descricao"]) ?></textarea></div>
                        <div class="mb-3"><label class="form-label d-block">Comodidades</label>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="tem_wifi" name="comodidades[wifi]" value="1" <?php if(isset($_POST['comodidades']['wifi']) || (!isset($_POST['comodidades']) && $dados['tem_wifi'])) echo 'checked'; ?>><label class="form-check-label" for="tem_wifi">Wi-Fi</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="tem_ar" name="comodidades[ar]" value="1" <?php if(isset($_POST['comodidades']['ar']) || (!isset($_POST['comodidades']) && $dados['tem_ar_condicionado'])) echo 'checked'; ?>><label class="form-check-label" for="tem_ar">Ar Condicionado</label></div>
                            <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="tem_tv" name="comodidades[tv]" value="1" <?php if(isset($_POST['comodidades']['tv']) || (!isset($_POST['comodidades']) && $dados['tem_tv'])) echo 'checked'; ?>><label class="form-check-label" for="tem_tv">TV</label></div>
                        </div>
                        <div class="card-footer text-end bg-white px-0 pt-3">
                            <a href="agenda.php?menuop=quartos" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success m-2" name="atualizar_dados">
                                <i class="bi bi-check-circle"></i> Salvar Alterações
                            </button>
                        </div>
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
                                    <a href="agenda.php?menuop=remover_imagem_quarto&id_imagem=<?= $imagem['id_imagem'] ?>&id_quarto=<?= $idquarto ?>" class="btn btn-sm btn-outline-danger btn-excluir-img" title="Excluir Imagem"><i class="bi bi-trash3"></i></a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <h6>Adicionar Novas Imagens</h6>
                    <form action="agenda.php?menuop=adicionar_imagem_quarto" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="idquarto" value="<?= htmlspecialchars($dados["id_quarto"]) ?>">
                        <div class="input-group">
                            <input class="form-control" type="file" name="fotos_quarto[]" multiple accept="image/png, image/jpeg, image/gif">
                            <button class="btn btn-success" type="submit"><i class="bi bi-upload"></i> Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// SCRIPT PARA CONFIRMAÇÃO DE EXCLUSÃO DE IMAGEM
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-excluir-img');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); 
            const deleteUrl = this.href;
            Swal.fire({
                title: 'Tem certeza?',
                text: "Deseja realmente excluir esta imagem?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
    });
});
</script>

</body>
</html>