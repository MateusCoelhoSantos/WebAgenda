<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 

function formatarTelefone($telefone) { /* ... sua função ... */ }
function formatarCpfCnpj($numero) { /* ... sua função ... */ }

// --- LÓGICA DE FILTRO ---
// Detecta se o formulário foi enviado ou se é a carga inicial da página
$is_post_request = ($_SERVER['REQUEST_METHOD'] === 'POST');
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Agendamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        .table td, .table th { vertical-align: middle; }
        .details-img { max-width: 100%; height: auto; max-height: 250px; object-fit: cover; border-radius: .375rem; }
    </style>
</head>
<body style="background-color: #f0f2f5;">

<div class="container mt-4 mb-5">
    
    <header class="mb-4">
        <h2 class="text-center text-secondary">Gerenciador de Agendamentos</h2>
    </header>

    <div class="card shadow-sm">
        
        <div class="card-header bg-white p-3">
            <form action="agenda.php?menuop=agendamento" method="post">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-lg-4"><label for="pesquisa" class="form-label fw-bold">Pesquisar</label><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input class="form-control" type="text" id="pesquisa" name="pesquisa" placeholder="Nome do cliente ou nº do quarto..." value="<?= htmlspecialchars($_POST['pesquisa'] ?? '') ?>"></div></div>
                    <div class="col-12 col-md-6 col-lg-3"><label for="dataini" class="form-label fw-bold">De</label><input class="form-control" type="date" name="dataini" id="dataini" value="<?= htmlspecialchars($_POST['dataini'] ?? '') ?>"></div>
                    <div class="col-12 col-md-6 col-lg-3"><label for="datafin" class="form-label fw-bold">Até</label><input class="form-control" type="date" name="datafin" id="datafin" value="<?= htmlspecialchars($_POST['datafin'] ?? '') ?>"></div>
                    <div class="col-12 col-lg-2"><button class="btn btn-primary w-100" type="submit">Filtrar</button></div>
                </div>
                <div class="row mt-3">
                    <div class="col-12"><label class="form-label fw-bold">Status</label><div class="d-flex">
                        <?php $status_filtro = $_POST['status_filtro'] ?? '0'; ?>
                        <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="status_filtro" id="status_ativos" value="0" <?php if ($status_filtro === '0') echo 'checked'; ?>><label class="form-check-label" for="status_ativos">Ativos</label></div>
                        <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="status_filtro" id="status_finalizados" value="1" <?php if ($status_filtro === '1') echo 'checked'; ?>><label class="form-check-label" for="status_finalizados">Finalizados</label></div>
                        <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="status_filtro" id="status_todos" value="all" <?php if ($status_filtro === 'all') echo 'checked'; ?>><label class="form-check-label" for="status_todos">Todos</label></div>
                    </div></div>
                </div>
            </form>
        </div>
        <div class="card-header bg-white p-3 border-top text-end">
             <a href="agenda.php?menuop=cadastroagendamento" class="btn btn-success"><i class="bi bi-plus-lg"></i> Incluir Agendamento</a>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Cliente</th>
                            <th>Quarto</th>
                            <th>Período</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $quantidade = 10;
                        $pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
                        $inicio = ($quantidade * $pagina) - $quantidade;
                        
                        // --- CORREÇÃO PRINCIPAL NA LÓGICA SQL ---
                        $base_sql = "SELECT r.*, p.nome, p.cpfcnpj, p.telefone, p.email, p.nasc, q.num_quarto, q.descricao, q.imagem 
                                     FROM reservas r 
                                     INNER JOIN pessoas p ON r.id_pessoa = p.id_pessoa 
                                     INNER JOIN quartos q ON r.id_quarto = q.id_quarto
                                     WHERE r.excluido <> 1";

                        if ($is_post_request) {
                            // MODO FILTRO (POST): Usa todos os filtros
                            $pesquisa = $_POST["pesquisa"] ?? ""; 
                            $dataini = $_POST["dataini"] ?? date('Y-m-d');
                            $datafin = $_POST["datafin"] ?? date('Y-m-d');
                            $status_filtro = $_POST['status_filtro'] ?? '0';

                            $sql_status_condition = "";
                            if ($status_filtro === '0') $sql_status_condition = "AND r.finalizado = 0";
                            elseif ($status_filtro === '1') $sql_status_condition = "AND r.finalizado = 1";
                            
                            $sql = $base_sql . " AND (q.num_quarto LIKE ? OR p.nome LIKE ?) AND (r.horarioini BETWEEN ? AND ?) {$sql_status_condition} ORDER BY r.horarioini ASC LIMIT ?, ?";
                            
                            $stmt = mysqli_prepare($conexao, $sql);
                            $termo_pesquisa = "%" . $pesquisa . "%";
                            $datafim_filtro = $datafin . ' 23:59:59';
                            mysqli_stmt_bind_param($stmt, 'ssssii', $num_pesquisa, $termo_pesquisa, $dataini, $datafim_filtro, $inicio, $quantidade);
                        } else {
                            // MODO PADRÃO (GET): Mostra apenas os ativos, ignorando outros filtros
                            $sql = $base_sql . " AND r.finalizado = 0 ORDER BY r.horarioini ASC LIMIT ?, ?";
                            $stmt = mysqli_prepare($conexao, $sql);
                            mysqli_stmt_bind_param($stmt, 'ii', $inicio, $quantidade);
                        }

                        mysqli_stmt_execute($stmt);
                        $RS = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($RS) > 0) {
                            foreach ($RS as $reserva) {
                                // ... (o resto do seu loop foreach continua aqui, sem alterações)
                                $status_badge = $reserva['finalizado'] == 0 
                                    ? "<span class='badge bg-success'>Ativo</span>" 
                                    : "<span class='badge bg-secondary'>Finalizado</span>";
                                ?>
                                <tr>
                                    <td class="ps-3"><?= htmlspecialchars($reserva['nome']) ?></td>
                                    <td>Quarto <?= htmlspecialchars($reserva['num_quarto']) ?></td>
                                    <td><?= date("d/m H:i", strtotime($reserva['horarioini'])) ?> até <?= date("d/m H:i", strtotime($reserva['horariofin'])) ?></td>
                                    <td><?= $status_badge ?></td>
                                    <td class="text-end pe-3">
                                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#detalhes<?= $reserva['id_reserva'] ?>"><i class="bi bi-info-circle"></i> Detalhes</button>
                                        <a href="agenda.php?menuop=editaragendamento&idreserva=<?= $reserva["id_reserva"] ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil-square"></i></a>
                                        <a href="agenda.php?menuop=excluiragendamento&idreserva=<?= $reserva["id_reserva"] ?>" class="btn btn-outline-danger btn-sm btn-excluir" title="Excluir"><i class="bi bi-trash3"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="p-0 border-0"><div id="detalhes<?= $reserva['id_reserva'] ?>" class="collapse"><div class="p-3 bg-light"><div class="row align-items-center">
                                        <div class="col-md-4 text-center"><img src="../Imagens/<?= htmlspecialchars($reserva['imagem']) ?>" class="details-img img-thumbnail mb-3 mb-md-0"></div>
                                        <div class="col-md-8"><h5>Detalhes do Cliente</h5>
                                            <p class="mb-1"><strong>CPF/CNPJ:</strong> <?= formatarCpfCnpj($reserva['cpfcnpj']) ?></p>
                                            <p class="mb-1"><strong>Telefone:</strong> <?= formatarTelefone($reserva['telefone']) ?></p>
                                            <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($reserva['email']) ?></p>
                                            <p class="mb-0"><strong>Nascimento:</strong> <?= date("d/m/Y", strtotime($reserva['nasc'])) ?></p>
                                        </div></div></div></div></td>
                                </tr>
                                <?php
                            }
                        } else {
                            $mensagem = $is_post_request ? "Nenhum agendamento encontrado para os filtros." : "Nenhum agendamento ativo encontrado.";
                            echo "<tr><td colspan='5' class='text-center p-4'>{$mensagem}</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Um único listener para garantir que o DOM está pronto
document.addEventListener('DOMContentLoaded', function () {

    // --- Script de confirmação para exclusão ---
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

    // --- Script para preencher as datas no carregamento inicial (GET) ---
    <?php if (!$is_post_request): ?>
        const hojeFormatado = new Date().toISOString().slice(0, 10);
        const dataIniInput = document.querySelector('[name="dataini"]');
        const dataFinInput = document.querySelector('[name="datafin"]');

        if (dataIniInput && !dataIniInput.value) { dataIniInput.value = hojeFormatado; }
        if (dataFinInput && !dataFinInput.value) { dataFinInput.value = hojeFormatado; }
    <?php endif; ?>

});

// --- Script para exibir mensagens de sucesso/erro da sessão ---
// Este pode ficar fora do DOMContentLoaded, pois ele só precisa do Swal que já foi carregado
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