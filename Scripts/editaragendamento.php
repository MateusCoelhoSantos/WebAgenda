<?php
// Garante que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// Protege a página contra acesso não logado
if (!isset($_SESSION['user_id'])) { header('Location: index.php?menuop=login'); exit(); }

include_once("conexao.php"); 
include_once("funcoes.php"); 

$idreserva = $_GET['idreserva'] ?? 0;

// Busca os dados da reserva de forma segura, agora incluindo o preço da diária do quarto
$sql = "SELECT r.*, p.nome, p.cpfcnpj, p.telefone, p.email, 
               q.num_quarto, q.descricao, q.preco_diaria  -- ADICIONADO: q.preco_diaria
        FROM reservas r
        INNER JOIN pessoas p ON r.id_pessoa = p.id_pessoa
        INNER JOIN quartos q ON r.id_quarto = q.id_quarto
        WHERE r.id_reserva = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $idreserva);
$stmt->execute();
$reserva = $stmt->get_result()->fetch_assoc();

if (!$reserva) {
    die("Reserva não encontrada!");
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Reserva</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .list-group { max-height: 200px; overflow-y: auto; z-index: 1000; }
    .info-box { background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; margin-top: 10px; margin-bottom: 1.5rem; border-radius: 8px; }
</style>
</head>
<body style="background-color: #f0f2f5;">

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header"><h3 class="text-center mb-0">Editar Reserva</h3></div>
                <div class="card-body p-4">
                    <form action="agenda.php?menuop=atualizaragendamento&idreserva=<?= (int)$idreserva ?>" method="post">
                        <div class="row mb-3">
                            <div class="col-12 col-md-4"><label for="codcliente" class="form-label">Código Cliente</label><input type="text" class="form-control" id="codcliente" name="codcliente" value="<?= htmlspecialchars($reserva['id_pessoa']) ?>" readonly></div>
                            <div class="col-12 col-md-8 position-relative"><label for="nomecliente" class="form-label">Cliente</label><input type="text" class="form-control" id="nomecliente" name="nomecliente" value="<?= htmlspecialchars($reserva['nome']) ?>" required autocomplete="off"><div id="listaClientes" class="list-group position-absolute w-100"></div></div>
                        </div>
                        <div id="infoCliente" class="info-box"><p class="mb-1"><strong>CPF/CNPJ:</strong> <span id="cpfcnpj"><?= htmlspecialchars(formatarCpfCnpj($reserva['cpfcnpj'])) ?></span></p><p class="mb-1"><strong>Telefone:</strong> <span id="telefone"><?= htmlspecialchars(formatarTelefone($reserva['telefone'])) ?></span></p><p class="mb-0"><strong>Email:</strong> <span id="email"><?= htmlspecialchars($reserva['email']) ?></span></p></div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-4"><label for="numquarto" class="form-label">ID Quarto</label>
                                <input type="text" class="form-control" id="numquarto" name="numquarto" value="<?= htmlspecialchars($reserva['id_quarto']) ?>" data-preco-diaria="<?= htmlspecialchars($reserva['preco_diaria']) ?>" readonly>
                            </div>
                            <div class="col-12 col-md-8 position-relative"><label for="descquarto" class="form-label">Quarto</label><input type="text" class="form-control" id="descquarto" name="descquarto" value="<?= htmlspecialchars($reserva['descricao']) ?>" required autocomplete="off"><div id="listaQuartos" class="list-group position-absolute w-100"></div></div>
                        </div>
                        <div id="infoQuarto" class="info-box"><p class="mb-0"><strong>Preço da Diária:</strong> R$ <span id="preco_diaria"><?= number_format($reserva['preco_diaria'], 2, ',', '.') ?></span></p></div>

                        <div class="row mb-3">
                            <div class="col-md-6"><label for="horarioini" class="form-label">Data/Hora Início</label><input type="datetime-local" class="form-control" name="horarioini" id="horarioini" value="<?= date('Y-m-d\TH:i', strtotime($reserva['horarioini'])) ?>" required></div>
                            <div class="col-md-6"><label for="horariofin" class="form-label">Data/Hora Fim</label><input type="datetime-local" class="form-control" name="horariofin" id="horariofin" value="<?= date('Y-m-d\TH:i', strtotime($reserva['horariofin'])) ?>" required></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6"><label for="valor" class="form-label">Valor Total R$</label>
                                <input type="number" step="0.01" class="form-control" id="valor" name="valor" value="<?= htmlspecialchars($reserva['valor']) ?>" readonly>
                            </div>
                            <div class="col-md-6"><label for="quant_pessoas" class="form-label">Quantidade de Pessoas</label><input type="number" class="form-control" name="quant_pessoas" value="<?= htmlspecialchars($reserva['quant_pessoas']) ?>"></div>
                        </div>
                        
                        <div class="mb-3"><label for="obs" class="form-label">Observação</label><textarea class="form-control" id="obs" name="obs" rows="3"><?= htmlspecialchars($reserva['obs'] ?? '') ?></textarea></div>

                        <div class="card-footer text-end bg-white"><a href="agenda.php?menuop=agendamento" class="btn btn-secondary">Cancelar</a><button type="submit" class="btn btn-primary" name="alterar">Salvar Alterações</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // --- Funções de Formatação ---
    function formatarCpfCnpjJS(numero) { /* ... sua função ... */ }
    function formatarTelefoneJS(telefone) { /* ... sua função ... */ }

    // --- Seletores dos elementos principais ---
    const inputHorarioIni = document.getElementById('horarioini');
    const inputHorarioFin = document.getElementById('horariofin');
    const numQuartoInput = document.getElementById("numquarto");
    const valorInput = document.getElementById('valor');

    // --- Autocomplete Clientes (ALTERADO para usar as funções de formatação) ---
    const nomeCliente = document.getElementById("nomecliente");
    const listaClientes = document.getElementById("listaClientes");
    nomeCliente.addEventListener("keyup", () => {
        const termo = nomeCliente.value;
        if (termo.length < 2) { listaClientes.innerHTML = ""; return; }
        fetch("buscaCliente.php?termo=" + termo)
            .then(res => res.json())
            .then(data => {
                listaClientes.innerHTML = "";
                data.forEach(cliente => {
                    const item = document.createElement("a");
                    item.classList.add("list-group-item", "list-group-item-action");
                    item.textContent = `${cliente.nome} (${cliente.cpfcnpj})`;
                    item.style.cursor = "pointer";
                    item.addEventListener("click", (e) => {
                        e.preventDefault();
                        nomeCliente.value = cliente.nome;
                        document.getElementById("codcliente").value = cliente.id_pessoa;
                        document.getElementById("cpfcnpj").innerText = formatarCpfCnpjJS(cliente.cpfcnpj);
                        document.getElementById("telefone").innerText = formatarTelefoneJS(cliente.telefone);
                        document.getElementById("email").innerText = cliente.email;
                        listaClientes.innerHTML = "";
                    });
                    listaClientes.appendChild(item);
                });
            });
    });

    // --- Autocomplete Quartos (ALTERADO para atualizar o preço) ---
    const descQuarto = document.getElementById("descquarto");
    const listaQuartos = document.getElementById("listaQuartos");
    const infoQuarto = document.getElementById("infoQuarto");
    const precoDiariaSpan = document.getElementById("preco_diaria");
    descQuarto.addEventListener("keyup", () => {
        const termo = descQuarto.value;
        if (termo.length < 1) { listaQuartos.innerHTML = ""; return; }
        fetch("buscaQuarto.php?termo=" + termo)
            .then(res => res.json())
            .then(data => {
                listaQuartos.innerHTML = "";
                data.forEach(quarto => {
                    const item = document.createElement("a");
                    item.classList.add("list-group-item", "list-group-item-action");
                    item.textContent = `${quarto.num_quarto} - ${quarto.nome_quarto}`;
                    item.style.cursor = "pointer";
                    item.addEventListener("click", (e) => {
                        e.preventDefault();
                        descQuarto.value = quarto.nome_quarto;
                        numQuartoInput.value = quarto.id_quarto;
                        numQuartoInput.dataset.precoDiaria = quarto.preco_diaria; // Atualiza o preço
                        precoDiariaSpan.innerText = parseFloat(quarto.preco_diaria).toFixed(2).replace('.', ',');
                        infoQuarto.classList.remove("d-none");
                        listaQuartos.innerHTML = "";
                        calcularValorTotal(); // Recalcula o total
                    });
                    listaQuartos.appendChild(item);
                });
            });
    });

    // --- LÓGICA DE CÁLCULO DE VALOR TOTAL ---
    function calcularValorTotal() {
        const dataInicio = new Date(inputHorarioIni.value);
        const dataFim = new Date(inputHorarioFin.value);
        const precoDiaria = parseFloat(numQuartoInput.dataset.precoDiaria);

        if (!isNaN(dataInicio) && !isNaN(dataFim) && !isNaN(precoDiaria) && dataFim > dataInicio) {
            const diffMilissegundos = dataFim - dataInicio;
            const diffDias = diffMilissegundos / (1000 * 60 * 60 * 24);
            const numeroDeNoites = Math.ceil(diffDias);
            const valorTotal = numeroDeNoites * precoDiaria;
            valorInput.value = valorTotal.toFixed(2);
        } else {
            valorInput.value = "0.00";
        }
    }

    // Adiciona os gatilhos para recalcular
    inputHorarioIni.addEventListener('change', calcularValorTotal);
    inputHorarioFin.addEventListener('change', calcularValorTotal);
    
    // Roda o cálculo uma vez no carregamento da página para garantir que o valor inicial esteja correto
    calcularValorTotal();
});
</script>

</body>
</html>