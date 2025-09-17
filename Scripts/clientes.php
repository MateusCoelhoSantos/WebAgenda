<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 
include_once("funcoes.php"); // PASSO 1: INCLUÍDO O ARQUIVO DE FUNÇÕES
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        .table td, .table th { vertical-align: middle; }
        .btn-icon { padding: .375rem .5rem; font-size: 1rem; }
    </style>
</head>
<body style="background-color: #f0f2f5;">

<div class="container mt-4 mb-5">
    
    <header class="mb-4">
        <h2 class="text-center text-secondary">Gerenciador de Clientes</h2>
    </header>

    <div class="card shadow-sm">
        
        <div class="card-header bg-white p-3">
            <div class="row align-items-center">
                <div class="col-12 col-md-8 mb-3 mb-md-0">
                    <form action="agenda.php?menuop=clientes" method="post">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input class="form-control" type="text" name="pesquisa" placeholder="Pesquisar por nome ou ID..." value="<?= htmlspecialchars($_POST['pesquisa'] ?? '') ?>">
                            <button class="btn btn-primary" type="submit">Pesquisar</button>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <a href="agenda.php?menuop=cadastrocliente" class="btn btn-success w-100 w-md-auto">
                        <i class="bi bi-person-plus"></i> Incluir Cliente
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
                            <th>Nome</th>
                            <th>CPF/CNPJ</th>
                            <th>Telefone</th> <th>Tipo</th>
                            <th>Gênero</th>
                            <th class="text-end pe-3">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $quantidade = 10;
                        $pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
                        $inicio = ($quantidade * $pagina) - $quantidade;
                        $pesquisa = $_POST["pesquisa"] ?? "";

                        // Adicionei a busca do telefone na query, caso não estivesse
                        $sql = "SELECT id_pessoa, nome, cpfcnpj, telefone, nasc, f_j, genero, 
                                CASE WHEN f_j = 0 THEN 'Pessoa Física' WHEN f_j = 1 THEN 'Pessoa Jurídica' END AS 'tipagem_pessoa',
                                CASE WHEN genero = 'M' THEN 'Masculino' WHEN genero = 'F' THEN 'Feminino' WHEN genero = 'N' THEN 'Não Informado' END AS 'genero_texto',
                                DATE_FORMAT(nasc, '%d/%m/%Y') as data_nasc
                                FROM pessoas
                                WHERE (excluido <> 1) AND tipopessoa = 1 AND (id_pessoa = ? OR nome LIKE ?)
                                ORDER BY nome ASC
                                LIMIT ?, ?";
                        
                        $stmt = mysqli_prepare($conexao, $sql);
                        $termo_pesquisa = "%" . $pesquisa . "%";
                        mysqli_stmt_bind_param($stmt, 'ssii', $pesquisa, $termo_pesquisa, $inicio, $quantidade);
                        mysqli_stmt_execute($stmt);
                        $RS = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($RS) > 0) {
                            foreach ($RS as $dados) {
                                // CÓDIGO NOVO COM TONS PASTEL AINDA MAIS SUAVES

                                // Define a cor do badge de Gênero
                                $badge_genero_cor = 'bg-secondary-subtle text-secondary-emphasis'; // Padrão: Cinza bem claro
                                if ($dados['genero'] == 'M') {
                                    $badge_genero_cor = 'bg-primary-subtle text-primary-emphasis'; // Azul pastel
                                } elseif ($dados['genero'] == 'F') {
                                    $badge_genero_cor = 'bg-danger-subtle text-danger-emphasis'; // Rosa / Vermelho pastel
                                }

                                // Define a cor do badge de Tipo de Pessoa
                                $badge_pessoa_cor = ($dados['f_j'] == 0) 
                                    ? 'bg-success-subtle text-success-emphasis' // Verde pastel
                                    : 'bg-dark-subtle text-dark-emphasis';      // Grafite bem claro

                                echo "<tr>";
                                echo "<td class='ps-3'>" . htmlspecialchars($dados['id_pessoa']) . "</td>";
                                echo "<td>" . htmlspecialchars($dados['nome']) . "</td>";
                                // APLICAÇÃO DA FORMATAÇÃO
                                echo "<td>" . htmlspecialchars(formatarCpfCnpj($dados['cpfcnpj'])) . "</td>";
                                echo "<td>" . htmlspecialchars(formatarTelefone($dados['telefone'])) . "</td>";
                                echo "<td><span class='badge " . $badge_pessoa_cor . "'>" . htmlspecialchars($dados['tipagem_pessoa']) . "</span></td>";
                                echo "<td><span class='badge " . $badge_genero_cor . "'>" . htmlspecialchars($dados['genero_texto']) . "</span></td>";
                                echo "<td class='text-end pe-3'>
                                        <a href='agenda.php?menuop=editarcliente&idcli=" . $dados['id_pessoa'] . "' class='btn btn-outline-primary btn-icon' title='Alterar'>
                                            <i class='bi bi-pencil-square'></i>
                                        </a>
                                        <a href='agenda.php?menuop=excluircliente&idcli=" . $dados['id_pessoa'] . "' class='btn btn-outline-danger btn-icon btn-excluir' title='Excluir'>
                                            <i class='bi bi-trash3'></i>
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center p-4'>Nenhum cliente encontrado.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        $sqltotal = "SELECT COUNT(id_pessoa) as total FROM pessoas WHERE (excluido <> 1) AND tipopessoa = 1 AND (id_pessoa = ? OR nome LIKE ?)";
        $stmt_total = mysqli_prepare($conexao, $sqltotal);
        mysqli_stmt_bind_param($stmt_total, 'ss', $pesquisa, $termo_pesquisa);
        mysqli_stmt_execute($stmt_total);
        $rs_total = mysqli_stmt_get_result($stmt_total);
        $row_total = mysqli_fetch_assoc($rs_total);
        $numtotal = $row_total['total'];
        $totalpagina = ceil($numtotal / $quantidade);

        if ($totalpagina > 1):
        ?>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <span class="text-muted">Total de Clientes: <?= $numtotal ?></span>
            <nav aria-label="Navegação da página">
                <ul class="pagination mb-0">
                    <?php
                    echo "<li class='page-item " . ($pagina == 1 ? 'disabled' : '') . "'><a class='page-link' href='?menuop=clientes&pagina=1'><i class='bi bi-chevron-bar-left'></i></a></li>";
                    for ($i = 1; $i <= $totalpagina; $i++) {
                        if ($i >= ($pagina - 2) && $i <= ($pagina + 2)) {
                            echo "<li class='page-item " . ($i == $pagina ? 'active' : '') . "'><a class='page-link' href='?menuop=clientes&pagina=$i'>$i</a></li>";
                        }
                    }
                    echo "<li class='page-item " . ($pagina == $totalpagina ? 'disabled' : '') . "'><a class='page-link' href='?menuop=clientes&pagina=$totalpagina'><i class='bi bi-chevron-bar-right'></i></a></li>";
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
    // Pop-up de confirmação para exclusão
    const deleteButtons = document.querySelectorAll('.btn-excluir');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); 
            const deleteUrl = this.href;
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
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });
    });
});

// Pop-up de feedback (sucesso/erro)
<?php
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    ?>
    Swal.fire({
        icon: '<?= $message['type'] ?>',
        title: '<?= $message['text'] ?>',
        showConfirmButton: false,
        timer: 2500
    });
    <?php
    unset($_SESSION['message']);
}
?>
</script>

</body>
</html>