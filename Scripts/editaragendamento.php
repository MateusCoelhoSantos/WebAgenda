<?php
// Garante que a sessão está ativa e o usuário logado
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) { header('Location: index.php?menuop=login'); exit(); }

include_once("conexao.php"); 
include_once("funcoes.php"); 

$idreserva = $_GET['idreserva'] ?? 0;

// --- CONSULTA CORRIGIDA: Usa 'q.nome_quarto' e busca todos os detalhes ---
$sql = "SELECT r.*, p.nome, p.cpfcnpj, p.telefone, p.email, 
               q.num_quarto, q.nome_quarto, q.preco_diaria, q.capacidade_adultos, q.capacidade_criancas, q.tem_wifi, q.tem_ar_condicionado, q.tem_tv,
               (SELECT qi.nome_arquivo FROM quarto_imagens qi WHERE qi.id_quarto = q.id_quarto LIMIT 1) as imagem_quarto
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
    .info-box-img { width: 150px; height: 100px; object-fit: cover; border-radius: .375rem; }
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
                            <div class="col-12 col-md-8 position-relative"><label for="descquarto" class="form-label">Quarto</label><input type="text" class="form-control" id="descquarto" name="descquarto" value="<?= htmlspecialchars($reserva['nome_quarto']) ?>" required autocomplete="off"><div id="listaQuartos" class="list-group position-absolute w-100"></div></div>
                        </div>
                        
                        <div id="infoQuarto" class="info-box">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center">
                                    <?php
                                    $caminho_foto = "../Imagens/Quartos/" . ($reserva['imagem_quarto'] ?? 'quarto-sem-foto.png');
                                    if(empty($reserva['imagem_quarto']) || !file_exists($caminho_foto)) {
                                        $caminho_foto = "../Imagens/Quartos/quarto-sem-foto.png";
                                    }
                                    ?>
                                    <img src="<?= $caminho_foto ?>" id="imagem_quarto" class="info-box-img img-thumbnail">
                                </div>
                                <div class="col-md-8 mt-3 mt-md-0">
                                    <p class="mb-1"><strong>Diária:</strong> R$ <span id="preco_diaria"><?= number_format($reserva['preco_diaria'], 2, ',', '.') ?></span></p>
                                    <p class="mb-1"><strong>Capacidade:</strong> 
                                        <span id="capacidade_quarto">
                                            <i class="bi bi-person-fill"></i> <?= $reserva['capacidade_adultos'] ?> 
                                            <?php if($reserva['capacidade_criancas'] > 0) echo "+ <i class='bi bi-person' style='font-size: 0.8em;'></i> " . $reserva['capacidade_criancas']; ?>
                                        </span>
                                    </p>
                                    <p class="mb-0"><strong>Comodidades:</strong> 
                                        <span id="comodidades_quarto">
                                            <?php if($reserva['tem_wifi']) echo '<i class="bi bi-wifi" title="Wi-Fi"></i> '; ?>
                                            <?php if($reserva['tem_ar_condicionado']) echo '<i class="bi bi-snow" title="Ar Condicionado"></i> '; ?>
                                            <?php if($reserva['tem_tv']) echo '<i class="bi bi-tv" title="Televisão"></i> '; ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6"><label for="horarioini" class="form-label">Data/Hora Início</label><input type="datetime-local" class="form-control" name="horarioini" id="horarioini" value="<?= date('Y-m-d\TH:i', strtotime($reserva['horarioini'])) ?>" required></div>
                            <div class="col-md-6"><label for="horariofin" class="form-label">Data/Hora Fim</label><input type="datetime-local" class="form-control" name="horariofin" id="horariofin" value="<?= date('Y-m-d\TH:i', strtotime($reserva['horariofin'])) ?>" required></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6"><label for="valor" class="form-label">Valor Total R$</label><input type="number" step="0.01" class="form-control" id="valor" name="valor" value="<?= htmlspecialchars($reserva['valor']) ?>" readonly></div>
                            <div class="col-md-6"><label for="quant_pessoas" class="form-label">Quantidade de Pessoas</label><input type="number" class="form-control" name="quant_pessoas" value="<?= htmlspecialchars($reserva['quant_pessoas']) ?>"></div>
                        </div>
                        <div class="mb-3"><label for="obs" class="form-label">Observação</label><textarea class="form-control" id="obs" name="obs" rows="3"><?= htmlspecialchars($reserva['obs'] ?? '') ?></textarea></div>

                        <div class="card-footer text-end bg-white px-0 pt-3">
                            <a href="agenda.php?menuop=agendamento" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success" name="incluir">
                                <i class="bi bi-check-circle"></i> Salvar Reserva
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // --- Funções de Formatação ---
    function formatarCpfCnpjJS(numero) {
        const numeroLimpo = String(numero).replace(/\D/g, '');
        if (numeroLimpo.length === 11) {
            return numeroLimpo.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        } else if (numeroLimpo.length === 14) {
            return numeroLimpo.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
        }
        return numero;
    }

    function formatarTelefoneJS(telefone) {
        const numeroLimpo = String(telefone).replace(/\D/g, '');
        if (numeroLimpo.length === 11) {
            return numeroLimpo.replace(/(\d{2})(\d{1})(\d{4})(\d{4})/, '($1) $2 $3-$4');
        } else if (numeroLimpo.length === 10) {
            return numeroLimpo.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        }
        return telefone;
    }

    // --- Seletores dos elementos principais ---
    const inputHorarioIni = document.getElementById('horarioini');
    const inputHorarioFin = document.getElementById('horariofin');
    const numQuartoInput = document.getElementById("numquarto");
    const valorInput = document.getElementById('valor');

    // --- Autocomplete Clientes ---
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

    // --- Autocomplete Quartos ---
    const descQuarto = document.getElementById("descquarto");
    const listaQuartos = document.getElementById("listaQuartos");
    const infoQuarto = document.getElementById("infoQuarto");
    const precoDiariaSpan = document.getElementById("preco_diaria");
    const imagemQuartoEl = document.getElementById("imagem_quarto");
    const capacidadeQuartoEl = document.getElementById("capacidade_quarto");
    const comodidadesQuartoEl = document.getElementById("comodidades_quarto");

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
                        numQuartoInput.dataset.precoDiaria = quarto.preco_diaria;
                        
                        const imgPath = quarto.imagens && quarto.imagens.length > 0 
                            ? `../Imagens/Quartos/${quarto.imagens[0]}`
                            : "../Imagens/Quartos/quarto-sem-foto.png";
                        imagemQuartoEl.src = imgPath;

                        precoDiariaSpan.innerText = parseFloat(quarto.preco_diaria).toFixed(2).replace('.', ',');
                        
                        let capacidadeHTML = `<i class="bi bi-person-fill"></i> ${quarto.capacidade_adultos}`;
                        if (quarto.capacidade_criancas > 0) {
                            capacidadeHTML += ` + <i class="bi bi-person" style="font-size: 0.8em;"></i> ${quarto.capacidade_criancas}`;
                        }
                        capacidadeQuartoEl.innerHTML = capacidadeHTML;

                        let comodidadesHTML = '';
                        if (quarto.tem_wifi) comodidadesHTML += `<i class="bi bi-wifi" title="Wi-Fi"></i> `;
                        if (quarto.tem_ar_condicionado) comodidadesHTML += `<i class="bi bi-snow" title="Ar Condicionado"></i> `;
                        if (quarto.tem_tv) comodidadesHTML += `<i class="bi bi-tv" title="Televisão"></i> `;
                        comodidadesQuartoEl.innerHTML = comodidadesHTML.trim() ? comodidadesHTML : "Nenhuma";
                        
                        infoQuarto.classList.remove("d-none");
                        listaQuartos.innerHTML = "";
                        calcularValorTotal();
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
    
    // Roda o cálculo uma vez no carregamento da página
    calcularValorTotal();
});
</script>

</body>
</html>