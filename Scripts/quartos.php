<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 
include_once("funcoes.php"); 
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Quartos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        .table td, .table th { vertical-align: middle; }
        .btn-icon { padding: .375rem .5rem; font-size: 1rem; }
        .room-thumbnail { width: 100px; height: 75px; object-fit: cover; border-radius: .375rem; }
    </style>
</head>
<body style="background-color: #f0f2f5;">

<div class="container mt-4 mb-5">
    
    <header class="mb-4">
        <h2 class="text-center text-secondary">Gerenciador de Quartos</h2>
    </header>

    <div class="card shadow-sm">
        
        <div class="card-header bg-white p-3">
            <div class="row align-items-center">
                <div class="col-12 col-md-8 mb-3 mb-md-0">
                    <form action="agenda.php?menuop=quartos" method="post">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input class="form-control" type="text" name="qrtpesquisa" placeholder="Pesquisar por número ou nome..." value="<?= htmlspecialchars($_POST['qrtpesquisa'] ?? '') ?>">
                            <button class="btn btn-primary" type="submit">Pesquisar</button>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <a href="agenda.php?menuop=cadastroquarto" class="btn btn-success w-100 w-md-auto">
                        <i class="bi bi-plus-lg"></i> Incluir Novo Quarto
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">Foto</th>
                            <th>Número</th>
                            <th>Nome / Tipo</th>
                            <th>Capacidade</th>
                            <th>Preço Diária</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $quantidade = 10;
                        $pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
                        $inicio = ($quantidade * $pagina) - $quantidade;
                        $pesquisa = $_POST["qrtpesquisa"] ?? "";

                        // CONSULTA SQL ATUALIZADA PARA BUSCAR NOVOS CAMPOS E PESQUISAR NO CAMPO CORRETO
                        $sql = "SELECT id_quarto, num_quarto, nome_quarto, capacidade_adultos, capacidade_criancas, preco_diaria, status,
                                    CASE 
                                        when status = 0 then 'Disponível' 
                                        when status = 1 then 'Ocupado'
                                        when status = 2 then 'Limpeza'
                                        when status = 3 then 'Manutenção'
                                    END as status_texto 
                                FROM quartos 
                                WHERE excluido <> 1 AND (num_quarto LIKE ? OR nome_quarto LIKE ?) 
                                ORDER BY CAST(num_quarto AS UNSIGNED) ASC
                                LIMIT ?, ?";
                        
                        $stmt = mysqli_prepare($conexao, $sql);
                        $termo_pesquisa = "%" . $pesquisa . "%";
                        mysqli_stmt_bind_param($stmt, 'ssii', $pesquisa, $termo_pesquisa, $inicio, $quantidade);
                        mysqli_stmt_execute($stmt);
                        $RS = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($RS) > 0) {
                            foreach ($RS as $dados) {
                                // DENTRO DO LOOP: Busca a imagem principal do quarto
                                $sql_img = "SELECT nome_arquivo FROM quarto_imagens WHERE id_quarto = ? LIMIT 1";
                                $stmt_img = mysqli_prepare($conexao, $sql_img);
                                mysqli_stmt_bind_param($stmt_img, 'i', $dados['id_quarto']);
                                mysqli_stmt_execute($stmt_img);
                                $rs_img = mysqli_stmt_get_result($stmt_img);
                                $imagem = mysqli_fetch_assoc($rs_img);

                                $caminho_foto = "../Imagens/Quartos/" . ($imagem['nome_arquivo'] ?? 'quarto-sem-foto.png');
                                if (empty($imagem['nome_arquivo']) || !file_exists($caminho_foto)) {
                                    $caminho_foto = "../Imagens/Quartos/quarto-sem-foto.png";
                                }

                                $badge_cor = $dados['status'] == 0 ? 'bg-success' : 'bg-secondary';
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <img src="<?= $caminho_foto ?>" alt="<?= htmlspecialchars($dados['nome_quarto']) ?>" class="room-thumbnail">
                                    </td>
                                    <td><?= htmlspecialchars($dados['num_quarto']) ?></td>
                                    <td><?= htmlspecialchars($dados['nome_quarto']) ?></td>
                                    <td>
                                        <i class="bi bi-person-fill"></i> <?= $dados['capacidade_adultos'] ?>
                                        <?php if($dados['capacidade_criancas'] > 0): ?>
                                            + <i class="bi bi-person"></i> <?= $dados['capacidade_criancas'] ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>R$ <?= number_format($dados['preco_diaria'], 2, ',', '.') ?></td>
                                    <td><span class="badge rounded-pill <?= $badge_cor ?>"><?= htmlspecialchars($dados['status_texto']) ?></span></td>
                                    <td class="text-end pe-3">
                                        <a href="agenda.php?menuop=editarquarto&idquarto=<?= $dados['id_quarto'] ?>" class='btn btn-outline-primary btn-icon' title='Alterar'><i class='bi bi-pencil-square'></i></a>
                                        <a href="agenda.php?menuop=excluirquarto&idquarto=<?= $dados['id_quarto'] ?>" class='btn btn-outline-danger btn-icon btn-excluir' title='Excluir'><i class='bi bi-trash3'></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            // Colspan atualizado para 7 colunas
                            echo "<tr><td colspan='7' class='text-center p-4'>Nenhum quarto encontrado.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        $sqltotal = "SELECT COUNT(id_quarto) as total FROM quartos WHERE excluido <> 1 AND (num_quarto LIKE ? OR nome_quarto LIKE ?)";
        $stmt_total = mysqli_prepare($conexao, $sqltotal);
        mysqli_stmt_bind_param($stmt_total, 'ss', $pesquisa, $termo_pesquisa);
        mysqli_stmt_execute($stmt_total);
        $rs_total = mysqli_stmt_get_result($stmt_total);
        $numtotal = mysqli_fetch_assoc($rs_total)['total'];
        $totalpagina = ceil($numtotal / $quantidade);

        if ($totalpagina > 1):
        ?>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <span class="text-muted">Total de Quartos: <?= $numtotal ?></span>
            <nav aria-label="Navegação da página">
                <ul class="pagination mb-0">
                    <?php
                    echo "<li class='page-item " . ($pagina == 1 ? 'disabled' : '') . "'><a class='page-link' href='?menuop=quartos&pagina=1'><i class='bi bi-chevron-bar-left'></i></a></li>";
                    for ($i = 1; $i <= $totalpagina; $i++) {
                        if ($i >= ($pagina - 2) && $i <= ($pagina + 2)) {
                            echo "<li class='page-item " . ($i == $pagina ? 'active' : '') . "'><a class='page-link' href='?menuop=quartos&pagina=$i'>$i</a></li>";
                        }
                    }
                    echo "<li class='page-item " . ($pagina == $totalpagina ? 'disabled' : '') . "'><a class='page-link' href='?menuop=quartos&pagina=$totalpagina'><i class='bi bi-chevron-bar-right'></i></a></li>";
                    ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () { /* ... seu JS de exclusão ... */ });
<?php if (isset($_SESSION['message'])) { /* ... seu JS de notificação ... */ } ?>
</script>

</body>
</html>