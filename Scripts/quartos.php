<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 
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
        /* Estilos para alinhar ícones e texto na tabela e para um hover mais suave */
        .table td, .table th {
            vertical-align: middle;
        }
        .btn-icon {
            padding: .375rem .5rem;
            font-size: 1rem;
        }
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
                            <input class="form-control" type="text" name="qrtpesquisa" placeholder="Pesquisar por número ou descrição..." value="<?= htmlspecialchars($_POST['qrtpesquisa'] ?? '') ?>">
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
                            <th class="ps-3">ID</th>
                            <th>Número</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $quantidade = 10;
                        $pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
                        $inicio = ($quantidade * $pagina) - $quantidade;
                        $pesquisa = (isset($_POST["qrtpesquisa"])) ? $_POST["qrtpesquisa"] : "";

                        $sql = "SELECT *, CASE 
                                            when status = 0 then 'Disponível' 
                                            when status = 1 then 'Indisponível' 
                                          END as status_texto 
                                FROM quartos 
                                WHERE excluido <> 1 AND (num_quarto LIKE ? OR descricao LIKE ?) 
                                ORDER BY id_quarto LIMIT ?, ?";
                        
                        $stmt = mysqli_prepare($conexao, $sql);
                        $termo_pesquisa = "%" . $pesquisa . "%";
                        $num_pesquisa = $pesquisa;
                        mysqli_stmt_bind_param($stmt, 'ssii', $num_pesquisa, $termo_pesquisa, $inicio, $quantidade);
                        mysqli_stmt_execute($stmt);
                        $RS = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($RS) > 0) {
                            foreach ($RS as $dados) {
                                // Define a cor do badge com base no status
                                $badge_cor = $dados['status'] == 0 ? 'bg-success' : 'bg-secondary';

                                echo "<tr>";
                                echo "<td class='ps-3'>" . htmlspecialchars($dados['id_quarto']) . "</td>";
                                echo "<td>" . htmlspecialchars($dados['num_quarto']) . "</td>";
                                echo "<td>" . htmlspecialchars($dados['descricao']) . "</td>";
                                // NOVO: Badge colorido para o status
                                echo "<td><span class='badge rounded-pill " . $badge_cor . "'>" . htmlspecialchars($dados['status_texto']) . "</span></td>";
                                echo "<td class='text-end pe-3'>
                                        <a href='agenda.php?menuop=editarquarto&idquarto=" . $dados['id_quarto'] . "' class='btn btn-outline-primary btn-icon' title='Alterar'>
                                            <i class='bi bi-pencil-square'></i>
                                        </a>
                                        <a href='agenda.php?menuop=excluirquarto&idquarto=" . $dados['id_quarto'] . "' class='btn btn-outline-danger btn-icon btn-excluir' title='Excluir'>
                                            <i class='bi bi-trash3'></i>
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center p-4'>Nenhum quarto encontrado.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        $sqltotal = "SELECT id_quarto FROM quartos WHERE excluido = 0 AND (num_quarto LIKE ? OR descricao LIKE ?)";
        $stmt_total = mysqli_prepare($conexao, $sqltotal);
        mysqli_stmt_bind_param($stmt_total, 'ss', $num_pesquisa, $termo_pesquisa);
        mysqli_stmt_execute($stmt_total);
        mysqli_stmt_store_result($stmt_total);
        $numtotal = mysqli_stmt_num_rows($stmt_total);
        $totalpagina = ceil($numtotal / $quantidade);

        if ($totalpagina > 1):
        ?>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <span class="text-muted">Total de Registros: <?= $numtotal ?></span>
            <nav aria-label="Navegação da página">
                <ul class="pagination mb-0">
                    <?php
                    // Botão Primeira
                    echo "<li class='page-item " . ($pagina == 1 ? 'disabled' : '') . "'><a class='page-link' href='?menuop=quartos&pagina=1'><i class='bi bi-chevron-bar-left'></i></a></li>";
                    
                    // Botões numéricos
                    for ($i = 1; $i <= $totalpagina; $i++) {
                        if ($i >= ($pagina - 2) && $i <= ($pagina + 2)) {
                            echo "<li class='page-item " . ($i == $pagina ? 'active' : '') . "'><a class='page-link' href='?menuop=quartos&pagina=$i'>$i</a></li>";
                        }
                    }
                    
                    // Botão Última
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
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-excluir');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            // Previne a exclusão imediata
            event.preventDefault(); 
            
            const deleteUrl = this.href; // Guarda o link de exclusão

            Swal.fire({
                title: 'Você tem certeza?',
                text: "Esta ação não poderá ser revertida!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                // Se o usuário confirmar, o script o redireciona para o link de exclusão
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
    });
});
</script>

<?php
// Este bloco para mostrar a mensagem de SUCESSO/ERRO você já tem, e está perfeito!
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    ?>
    <script>
        Swal.fire({
            icon: '<?= $message['type'] ?>',
            title: '<?= $message['text'] ?>',
            showConfirmButton: false,
            timer: 2500
        });
    </script>
    <?php
    unset($_SESSION['message']);
}
?>

</body>
</html>