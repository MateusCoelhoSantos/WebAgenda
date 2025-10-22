<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 
include_once("funcoes.php"); 

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
        .details-img { width: 100%; height: 360px; object-fit: cover; border-radius: .375rem; }
         /* Estilos adicionados para o collapse */
        .collapse-row td {
            padding: 0 !important;
            border-top: none; 
        }
        .collapse-content {
            background-color: #f8f9fa; /* Um cinza bem claro */
            padding: 1.5rem; /* Aumentei o padding */
        }
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

                        // --- LÓGICA DE FILTRO ---
                        // --------------------------------------------------------------------
                        // ALTERAÇÃO 1: Adicionado LEFT JOIN com 'endereco e'
                        // --------------------------------------------------------------------
                        $from_clause = "FROM reservas r 
                                        INNER JOIN pessoas p ON r.id_pessoa = p.id_pessoa 
                                        INNER JOIN quartos q ON r.id_quarto = q.id_quarto
                                        LEFT JOIN endereco e ON p.id_pessoa = e.id_pessoa"; // <-- JOIN adicionado aqui
                        
                        $where_conditions = ["r.excluido <> 1"];
                        $params = [];
                        $types = '';
                        
                        $pesquisa = $_POST["pesquisa"] ?? ""; 
                        $status_filtro = $_POST['status_filtro'] ?? '0';
                        $hoje = date('Y-m-d');
                        
                        $dataini = $is_post_request ? ($_POST["dataini"] ?? '') : $hoje;
                        $datafin = $is_post_request ? ($_POST["datafin"] ?? '') : $hoje;

                        if (!empty($pesquisa)) {
                            $where_conditions[] = "(q.num_quarto = ? OR p.nome LIKE ?)";
                            $types .= 'ss';
                            $params[] = $pesquisa;
                            $params[] = "%" . $pesquisa . "%";
                        }

                        if (!empty($dataini) && !empty($datafin)) {
                            $where_conditions[] = "(DATE(r.horarioini) BETWEEN ? AND ?)";
                            $types .= 'ss';
                            $params[] = $dataini;
                            $params[] = $datafin;
                        }

                        if ($status_filtro !== 'all') {
                            $where_conditions[] = "r.finalizado = ?";
                            $types .= 'i';
                            $params[] = $status_filtro;
                        }

                        $where_clause = "WHERE " . implode(" AND ", $where_conditions);

                        $count_sql = "SELECT COUNT(r.id_reserva) as total " . $from_clause . " " . $where_clause;
                        $stmt_total = mysqli_prepare($conexao, $count_sql);
                        if (!empty($types)) { mysqli_stmt_bind_param($stmt_total, $types, ...$params); }
                        mysqli_stmt_execute($stmt_total);
                        $numtotal = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_total))['total'];
                        $totalpagina = ceil($numtotal / $quantidade);

                        // --------------------------------------------------------------------
                        // ALTERAÇÃO 2: Adicionados campos de endereço (e.*) ao SELECT
                        // --------------------------------------------------------------------
                        $select_clause = "SELECT r.*, p.nome, p.cpfcnpj, p.telefone, p.email, p.nasc, 
                                                e.rua, e.numero, e.bairro, e.cidade, e.uf, e.cep, e.complemento, 
                                                q.num_quarto, q.nome_quarto, q.preco_diaria, q.tem_wifi,q.capacidade_adultos,q.capacidade_criancas, q.tem_ar_condicionado, q.tem_tv,
                                                (SELECT qi.nome_arquivo FROM quarto_imagens qi WHERE qi.id_quarto = q.id_quarto LIMIT 1) as imagem_quarto";
                        
                        $sql_data = $select_clause . " " . $from_clause . " " . $where_clause . " ORDER BY r.horarioini ASC LIMIT ?, ?";
                        $types_data = $types . 'ii';
                        $params_data = array_merge($params, [$inicio, $quantidade]);

                        $stmt_data = mysqli_prepare($conexao, $sql_data);
                        if (!empty($types_data)) { mysqli_stmt_bind_param($stmt_data, $types_data, ...$params_data); }
                        mysqli_stmt_execute($stmt_data);
                        $RS = mysqli_stmt_get_result($stmt_data);

                        if (mysqli_num_rows($RS) > 0) {
                            foreach ($RS as $reserva) {
                                $caminho_foto_quarto = "../Imagens/Quartos/" . ($reserva['imagem_quarto'] ?? 'quarto-sem-foto.png');
                                if (empty($reserva['imagem_quarto']) || !file_exists($caminho_foto_quarto)) {
                                    $caminho_foto_quarto = "../Imagens/Quartos/quarto-sem-foto.png";
                                }
                                $status_badge = $reserva['finalizado'] == 0 ? "<span class='badge bg-success'>Ativo</span>" : "<span class='badge bg-secondary'>Finalizado</span>";
                                
                                // --------------------------------------------------------------------
                                // ALTERAÇÃO 3: Monta a string de endereço do cliente
                                // --------------------------------------------------------------------
                                $endereco_cliente = "Endereço não cadastrado.";
                                if (!empty($reserva['rua'])) {
                                    $endereco_cliente = htmlspecialchars($reserva['rua']);
                                    if (!empty($reserva['numero'])) $endereco_cliente .= ", " . htmlspecialchars($reserva['numero']);
                                    if (!empty($reserva['complemento'])) $endereco_cliente .= " (" . htmlspecialchars($reserva['complemento']) . ")";
                                    if (!empty($reserva['bairro'])) $endereco_cliente .= " - " . htmlspecialchars($reserva['bairro']);
                                    if (!empty($reserva['cidade'])) $endereco_cliente .= ".<br>" . htmlspecialchars($reserva['cidade']);
                                    if (!empty($reserva['uf'])) $endereco_cliente .= " / " . htmlspecialchars($reserva['uf']);
                                    if (!empty($reserva['cep'])) $endereco_cliente .= "<br>CEP: " . htmlspecialchars($reserva['cep']);
                                }
                                ?>
                                
                                <tr>
                                    <td class="ps-3"><?= htmlspecialchars($reserva['nome']) ?></td>
                                    <td><?= htmlspecialchars($reserva['num_quarto']) ?> - <?= htmlspecialchars($reserva['nome_quarto']) ?></td>
                                    <td><?= date("d/m H:i", strtotime($reserva['horarioini'])) ?> até <?= date("d/m H:i", strtotime($reserva['horariofin'])) ?></td>
                                    <td><?= $status_badge ?></td>
                                    <td class="text-end pe-3">
                                        <button class="btn btn-outline-info btn-sm" data-bs-toggle="collapse" data-bs-target="#detalhes<?= $reserva['id_reserva'] ?>"><i class="bi bi-eye-fill"></i> Detalhes</button> <?php if ($reserva['finalizado'] == 0): ?>
                                            <a href="agenda.php?menuop=finalizaragendamento&idreserva=<?= $reserva["id_reserva"] ?>" class="btn btn-outline-success btn-sm btn-finalizar" title="Finalizar Agendamento"><i class="bi bi-check-circle"></i></a>
                                        <?php endif; ?>
                                        <a href="agenda.php?menuop=editaragendamento&idreserva=<?= $reserva["id_reserva"] ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil-square"></i></a>
                                        <a href="agenda.php?menuop=excluiragendamento&idreserva=<?= $reserva["id_reserva"] ?>" class="btn btn-outline-danger btn-sm btn-excluir" title="Excluir"><i class="bi bi-trash3"></i></a>
                                    </td>
                                </tr>
                                
                                <tr class="collapse-row">
                                    <td colspan="5" class="p-0 border-0"> <div id="detalhes<?= $reserva['id_reserva'] ?>" class="collapse">
                                            <div class="collapse-content">
                                                <div class="row">
                                                    <div class="col-md-5 mb-3 mb-md-0">
                                                        <h5><i class="bi bi-door-open"></i> Detalhes do Quarto</h5>
                                                        <img src="<?= $caminho_foto_quarto ?>" alt="Foto do Quarto" class="details-img img-thumbnail mb-2">
                                                        <p class="mb-1"><strong>Preço da Diária:</strong> R$ <?= number_format($reserva['preco_diaria'], 2, ',', '.') ?></p>
                                                        <p class="mb-1"><strong>Capacidade:</strong> <i class="bi bi-person-fill"></i> <?= $reserva['capacidade_adultos'] ?> <?php if($reserva['capacidade_criancas'] > 0) echo "+ <i class='bi bi-person' style='font-size:0.8em;'></i> " . $reserva['capacidade_criancas']; ?></p>
                                                        <p class="mb-0"><strong>Comodidades:</strong> 
                                                            <?php 
                                                            $comodidades_txt = '';
                                                            if($reserva['tem_wifi']) $comodidades_txt .= '<i class="bi bi-wifi" title="Wi-Fi"></i> '; 
                                                            if($reserva['tem_ar_condicionado']) $comodidades_txt .= '<i class="bi bi-snow" title="Ar Condicionado"></i> '; 
                                                            if($reserva['tem_tv']) $comodidades_txt .= '<i class="bi bi-tv" title="Televisão"></i> '; 
                                                            echo trim($comodidades_txt) ?: 'Nenhuma';
                                                            ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-7 border-start ps-md-4">
                                                        <h5><i class="bi bi-person-circle"></i> Detalhes do Cliente</h5>
                                                        <p class="mb-1"><strong>Nome:</strong> <?= htmlspecialchars($reserva['nome']) ?></p>
                                                        <p class="mb-1"><strong>CPF/CNPJ:</strong> <?= formatarCpfCnpj($reserva['cpfcnpj']) ?></p>
                                                        <p class="mb-1"><strong>Telefone:</strong> <?= formatarTelefone($reserva['telefone']) ?></p>
                                                        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($reserva['email'] ?? 'Não informado') ?></p>
                                                        <p class="mb-1"><strong>Nascimento:</strong> <?= date("d/m/Y", strtotime($reserva['nasc'])) ?></p>
                                                        
                                                        <p class="mb-1 mt-3"><strong>Endereço:</strong></p>
                                                        <p class="mb-0"><?= $endereco_cliente ?></p> <hr>
                                                        <h5><i class="bi bi-calendar-check"></i> Detalhes da Reserva</h5>
                                                        <p class="mb-1"><strong>ID Reserva:</strong> <?= $reserva['id_reserva'] ?></p>
                                                        <p class="mb-1"><strong>Período:</strong> <?= date("d/m/Y H:i", strtotime($reserva['horarioini'])) ?> a <?= date("d/m/Y H:i", strtotime($reserva['horariofin'])) ?></p>
                                                        <p class="mb-1"><strong>Valor Total:</strong> R$ <?= number_format($reserva['valor'], 2, ',', '.') ?></p>
                                                        <p class="mb-1"><strong>Pessoas:</strong> <?= $reserva['quant_pessoas'] ?></p>
                                                        <?php if (!empty($reserva['obs'])): ?>
                                                        <p class="mb-0"><strong>Observação:</strong> <?= nl2br(htmlspecialchars($reserva['obs'])) ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            $mensagem = $is_post_request ? "Nenhum agendamento encontrado para os filtros." : "Nenhum agendamento ativo encontrado para hoje.";
                            echo "<tr><td colspan='5' class='text-center p-4'>{$mensagem}</td></tr>"; // Colspan 5
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php if (isset($totalpagina) && $totalpagina > 1): ?>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <span class="text-muted">Total de Registros: <?= $numtotal ?></span>
            <nav><ul class="pagination mb-0">
                <li class="page-item <?= ($pagina == 1) ? 'disabled' : '' ?>"><a class="page-link" href="?menuop=agendamento&pagina=1">Primeira</a></li>
                <?php
                for ($i = 1; $i <= $totalpagina; $i++) {
                    if ($i >= ($pagina - 2) && $i <= ($pagina + 2)) {
                        echo "<li class='page-item " . ($i == $pagina ? 'active' : '') . "'><a class='page-link' href='?menuop=agendamento&pagina=$i'>$i</a></li>";
                    }
                }
                ?>
                <li class="page-item <?= ($pagina == $totalpagina) ? 'disabled' : '' ?>"><a class="page-link" href="?menuop=agendamento&pagina=<?= $totalpagina ?>">Última</a></li>
            </ul></nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Lógica dos botões Excluir e Finalizar (Sem alterações) ---
    const deleteButtons = document.querySelectorAll('.btn-excluir');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const deleteUrl = this.href;
            Swal.fire({
                title: 'Você tem certeza?', text: "Esta ação não poderá ser revertida!", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, excluir!', cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) { window.location.href = deleteUrl; }
            });
        });
    });

    const finalizeButtons = document.querySelectorAll('.btn-finalizar');
    finalizeButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const finalizeUrl = this.href;
            Swal.fire({
                title: 'Finalizar Agendamento?',
                text: "Esta ação marcará o agendamento como concluído e liberará o quarto.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, finalizar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = finalizeUrl;
                }
            });
        });
    });

    // --- Lógica para preencher datas se não for POST (Sem alterações) ---
    <?php if (!$is_post_request): ?>
        const hojeFormatado = new Date().toISOString().slice(0, 10);
        const dataIniInput = document.querySelector('[name="dataini"]');
        const dataFinInput = document.querySelector('[name="datafin"]');
        if (dataIniInput && !dataIniInput.value) { dataIniInput.value = hojeFormatado; }
        if (dataFinInput && !dataFinInput.value) { dataFinInput.value = hojeFormatado; }
    <?php endif; ?>
});

// --- Lógica do Pop-up de Mensagem (Sem alterações) ---
<?php
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    ?>
    Swal.fire({
        icon: '<?= $message['type'] ?>', title: '<?= $message['text'] ?>',
        showConfirmButton: false, timer: 2500
    });
    <?php
    unset($_SESSION['message']);
}
?>
</script>

</body>
</html>