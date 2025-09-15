<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("conexao.php");

$idreserva = $_GET['idreserva'] ?? 0;

// Busca os dados da reserva de forma segura
$sql = "SELECT r.*, p.nome, p.cpfcnpj, p.telefone, p.email, q.num_quarto, q.descricao
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* Estilos necessários para o autocomplete e a caixa de info */
    .list-group { max-height: 200px; overflow-y: auto; z-index: 1000; }
    .info-box { background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; margin-top: 10px; margin-bottom: 1.5rem; border-radius: 8px; }
</style>
</head>
<body style="background-color: #f0f2f5;">

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="text-center mb-0">Editar Reserva</h3>
                </div>
                <div class="card-body p-4">
                    <form action="agenda.php?menuop=atualizaragendamento&idreserva=<?= (int)$idreserva ?>" method="post">

                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="codcliente" class="form-label">Código Cliente</label>
                                <input type="text" class="form-control" id="codcliente" name="codcliente" value="<?= htmlspecialchars($reserva['id_pessoa']) ?>" readonly>
                            </div>
                            <div class="col-12 col-md-8 position-relative">
                                <label for="nomecliente" class="form-label">Cliente</label>
                                <input type="text" class="form-control" id="nomecliente" name="nomecliente" value="<?= htmlspecialchars($reserva['nome']) ?>" required autocomplete="off">
                                <div id="listaClientes" class="list-group position-absolute w-100"></div>
                            </div>
                        </div>

                        <div id="infoCliente" class="info-box">
                            <p class="mb-1"><strong>CPF/CNPJ:</strong> <span id="cpfcnpj"><?= htmlspecialchars($reserva['cpfcnpj']) ?></span></p>
                            <p class="mb-1"><strong>Telefone:</strong> <span id="telefone"><?= htmlspecialchars($reserva['telefone']) ?></span></p>
                            <p class="mb-0"><strong>Email:</strong> <span id="email"><?= htmlspecialchars($reserva['email']) ?></span></p>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="numquarto" class="form-label">ID Quarto</label>
                                <input type="text" class="form-control" id="numquarto" name="numquarto" value="<?= htmlspecialchars($reserva['id_quarto']) ?>" readonly>
                            </div>
                            <div class="col-12 col-md-8 position-relative">
                                <label for="descquarto" class="form-label">Quarto</label>
                                <input type="text" class="form-control" id="descquarto" name="descquarto" value="<?= htmlspecialchars($reserva['descricao']) ?>" required autocomplete="off">
                                <div id="listaQuartos" class="list-group position-absolute w-100"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="horarioini" class="form-label">Data/Hora Início</label>
                                <input type="datetime-local" class="form-control" name="horarioini" value="<?= date('Y-m-d\TH:i', strtotime($reserva['horarioini'])) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="horariofin" class="form-label">Data/Hora Fim</label>
                                <input type="datetime-local" class="form-control" name="horariofin" value="<?= date('Y-m-d\TH:i', strtotime($reserva['horariofin'])) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="valor" class="form-label">Valor R$</label>
                                <input type="number" step="0.01" class="form-control" name="valor" value="<?= htmlspecialchars($reserva['valor']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="quant_pessoas" class="form-label">Quantidade de Pessoas</label>
                                <input type="number" class="form-control" name="quant_pessoas" value="<?= htmlspecialchars($reserva['quant_pessoas']) ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="obs" class="form-label">Observação</label>
                            <textarea class="form-control" id="obs" name="obs" rows="3"><?= htmlspecialchars($reserva['obs'] ?? '') ?></textarea>
                        </div>

                        <div class="text-end mt-4">
                            <a href="agenda.php?menuop=agendamento" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary" name="alterar">Salvar Alterações</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // === BUG CORRIGIDO: O SCRIPT QUE DEFINIA A DATA ATUAL FOI REMOVIDO DAQUI ===
    // Em uma tela de edição, devemos sempre mostrar os dados salvos, não a data/hora atual.

    // === Autocomplete Clientes (Funcionalidade Mantida) ===
    const nomeCliente = document.getElementById("nomecliente");
    const listaClientes = document.getElementById("listaClientes");
    const infoCliente = document.getElementById("infoCliente");

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
                    item.href = "#";
                    item.onclick = (e) => {
                        e.preventDefault();
                        nomeCliente.value = cliente.nome;
                        document.getElementById("codcliente").value = cliente.id_pessoa;
                        document.getElementById("cpfcnpj").innerText = cliente.cpfcnpj;
                        document.getElementById("telefone").innerText = cliente.telefone;
                        document.getElementById("email").innerText = cliente.email;
                        infoCliente.classList.remove("d-none");
                        listaClientes.innerHTML = "";
                    };
                    listaClientes.appendChild(item);
                });
            });
    });

    // === Autocomplete Quartos (Funcionalidade Mantida) ===
    const descQuarto = document.getElementById("descquarto");
    const listaQuartos = document.getElementById("listaQuartos");

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
                    item.textContent = `${quarto.num_quarto} - ${quarto.descricao}`;
                    item.href = "#";
                    item.onclick = (e) => {
                        e.preventDefault();
                        descQuarto.value = quarto.descricao;
                        document.getElementById("numquarto").value = quarto.id_quarto;
                        listaQuartos.innerHTML = "";
                    };
                    listaQuartos.appendChild(item);
                });
            });
    });
});
</script>

</body>
</html>