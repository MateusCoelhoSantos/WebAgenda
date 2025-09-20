<?php
// Garante que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// Protege a página (opcional, mas recomendado)
// if (!isset($_SESSION['user_id'])) { header('Location: index.php?menuop=login'); exit(); }

include_once("conexao.php"); 
include_once("funcoes.php");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro de Reserva</title>
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
                <div class="card-header"><h3 class="text-center mb-0">Cadastrar Nova Reserva</h3></div>
                <div class="card-body p-4">
                    <form action="agenda.php?menuop=insereagendamento" method="post">
                        <div class="row mb-3">
                            <div class="col-12 col-md-4"><label for="codcliente" class="form-label">Código Cliente</label><input type="text" class="form-control" id="codcliente" name="codcliente" readonly></div>
                            <div class="col-12 col-md-8 position-relative"><label for="nomecliente" class="form-label">Cliente</label><input type="text" class="form-control" id="nomecliente" name="nomecliente" required autocomplete="off" placeholder="Digite para pesquisar..."><div id="listaClientes" class="list-group position-absolute w-100"></div></div>
                        </div>
                        <div id="infoCliente" class="info-box d-none"><p class="mb-1"><strong>CPF/CNPJ:</strong> <span id="cpfcnpj"></span></p><p class="mb-1"><strong>Telefone:</strong> <span id="telefone"></span></p><p class="mb-0"><strong>Email:</strong> <span id="email"></span></p></div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-4"><label for="numquarto" class="form-label">ID Quarto</label><input type="text" class="form-control" id="numquarto" name="numquarto" readonly></div>
                            <div class="col-12 col-md-8 position-relative"><label for="descquarto" class="form-label">Quarto</label><input type="text" class="form-control" id="descquarto" name="descquarto" required autocomplete="off" placeholder="Digite para pesquisar..."><div id="listaQuartos" class="list-group position-absolute w-100"></div></div>
                        </div>
                        <div id="infoQuarto" class="info-box d-none"><p class="mb-0"><strong>Preço da Diária:</strong> R$ <span id="preco_diaria"></span></p></div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6"><label for="horarioini" class="form-label">Data/Hora Início</label><input type="datetime-local" class="form-control" name="horarioini" id="horarioini" required></div>
                            <div class="col-md-6"><label for="horariofin" class="form-label">Data/Hora Fim</label><input type="datetime-local" class="form-control" name="horariofin" id="horariofin" required></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6"><label for="valor" class="form-label">Valor Total R$</label><input type="number" step="0.01" class="form-control" id="valor" name="valor" value="0.00" readonly></div>
                            <div class="col-md-6"><label for="quant_pessoas" class="form-label">Quantidade de Pessoas</label><input type="number" class="form-control" name="quant_pessoas" value="1"></div>
                        </div>
                        
                        <div class="mb-3"><label for="obs" class="form-label">Observação</label><textarea class="form-control" id="obs" name="obs" rows="3" placeholder="Digite aqui alguma observação sobre a reserva..."></textarea></div>

                        <div class="card-footer text-end bg-white"><a href="agenda.php?menuop=agendamento" class="btn btn-secondary">Cancelar</a><button type="submit" class="btn btn-success" name="incluir">Incluir Reserva</button></div>
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

    // --- LÓGICA PARA DEFINIR A DATA E HORA LOCAL DO USUÁRIO ---
    const inputHorarioIni = document.getElementById('horarioini');
    const inputHorarioFin = document.getElementById('horariofin');
    
    const agora = new Date();
    agora.setMinutes(agora.getMinutes() - agora.getTimezoneOffset());
    inputHorarioIni.value = agora.toISOString().slice(0, 16);

    const amanha = new Date(agora);
    amanha.setDate(agora.getDate() + 1);
    inputHorarioFin.value = amanha.toISOString().slice(0, 16);

    // --- Autocomplete Clientes (CÓDIGO RESTAURADO) ---
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
                    item.style.cursor = "pointer";
                    item.textContent = `${cliente.nome} (${cliente.cpfcnpj})`;
                    
                    item.addEventListener("click", () => {
                        nomeCliente.value = cliente.nome;
                        document.getElementById("codcliente").value = cliente.id_pessoa;
                        document.getElementById("cpfcnpj").innerText = formatarCpfCnpjJS(cliente.cpfcnpj);
                        document.getElementById("telefone").innerText = formatarTelefoneJS(cliente.telefone);
                        document.getElementById("email").innerText = cliente.email;
                        infoCliente.classList.remove("d-none");
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
    const numQuartoInput = document.getElementById("numquarto");

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
                    item.style.cursor = "pointer";
                    item.textContent = `${quarto.num_quarto} - ${quarto.nome_quarto}`;
                    item.addEventListener("click", () => {
                        descQuarto.value = quarto.nome_quarto;
                        numQuartoInput.value = quarto.id_quarto;
                        numQuartoInput.dataset.precoDiaria = quarto.preco_diaria;
                        precoDiariaSpan.innerText = parseFloat(quarto.preco_diaria).toFixed(2).replace('.', ',');
                        infoQuarto.classList.remove("d-none");
                        listaQuartos.innerHTML = "";
                        calcularValorTotal();
                    });
                    listaQuartos.appendChild(item);
                });
            });
    });

    // --- LÓGICA DE CÁLCULO DE VALOR TOTAL ---
    const valorInput = document.getElementById('valor');

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

    inputHorarioIni.addEventListener('change', calcularValorTotal);
    inputHorarioFin.addEventListener('change', calcularValorTotal);
});
</script>

</body>
</html>