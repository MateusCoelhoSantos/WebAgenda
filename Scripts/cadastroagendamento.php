<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 
include_once("funcoes.php"); // Inclui o arquivo com a função formatarCpfCnpj()
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
                    <h3 class="text-center mb-0">Cadastrar Nova Reserva</h3>
                </div>
                <div class="card-body p-4">
                    <form action="agenda.php?menuop=insereagendamento" method="post">

                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="codcliente" class="form-label">Código Cliente</label>
                                <input type="text" class="form-control" id="codcliente" name="codcliente" readonly>
                            </div>
                            <div class="col-12 col-md-8 position-relative">
                                <label for="nomecliente" class="form-label">Cliente</label>
                                <input type="text" class="form-control" id="nomecliente" name="nomecliente" required autocomplete="off" placeholder="Digite para pesquisar...">
                                <div id="listaClientes" class="list-group position-absolute w-100"></div>
                            </div>
                        </div>

                        <div id="infoCliente" class="info-box d-none">
                            <p class="mb-1"><strong>CPF/CNPJ:</strong> <span id="cpfcnpj"></span></p>
                            <p class="mb-1"><strong>Telefone:</strong> <span id="telefone"></span></p>
                            <p class="mb-0"><strong>Email:</strong> <span id="email"></span></p>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-4">
                                <label for="numquarto" class="form-label">ID Quarto</label>
                                <input type="text" class="form-control" id="numquarto" name="numquarto" readonly>
                            </div>
                            <div class="col-12 col-md-8 position-relative">
                                <label for="descquarto" class="form-label">Quarto</label>
                                <input type="text" class="form-control" id="descquarto" name="descquarto" required autocomplete="off" placeholder="Digite para pesquisar...">
                                <div id="listaQuartos" class="list-group position-absolute w-100"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="horarioini" class="form-label">Data/Hora Início</label>
                                <input type="datetime-local" class="form-control" name="horarioini" id="horarioini" required>
                            </div>
                            <div class="col-md-6">
                                <label for="horariofin" class="form-label">Data/Hora Fim</label>
                                <input type="datetime-local" class="form-control" name="horariofin" id="horariofin" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="valor" class="form-label">Valor R$</label>
                                <input type="number" step="0.01" class="form-control" name="valor" value="0">
                            </div>
                            <div class="col-md-6">
                                <label for="quant_pessoas" class="form-label">Quantidade de Pessoas</label>
                                <input type="number" class="form-control" name="quant_pessoas" value="1">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="obs" class="form-label">Observação</label>
                            <textarea class="form-control" id="obs" name="obs" rows="3" placeholder="Digite aqui alguma observação sobre a reserva..."></textarea>
                        </div>

                        <div class="card-footer text-end bg-white">
                            <a href="agenda.php?menuop=agendamento" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success" name="incluir">Incluir Reserva</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function formatarCpfCnpjJS(numero) {
    const numeroLimpo = String(numero).replace(/\D/g, '');
    if (numeroLimpo.length === 11) {
        return numeroLimpo.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    } else if (numeroLimpo.length === 14) {
        return numeroLimpo.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    } else {
        return numero;
    }
}

function formatarTelefoneJS(telefone) {
    const numeroLimpo = String(telefone).replace(/\D/g, '');
    if (numeroLimpo.length === 11) {
        return numeroLimpo.replace(/(\d{2})(\d{1})(\d{4})(\d{4})/, '($1) $2 $3-$4');
    } else if (numeroLimpo.length === 10) {
        return numeroLimpo.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
    } else {
        return telefone;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    // --- LÓGICA PARA DEFINIR A DATA E HORA LOCAL DO USUÁRIO ---
    const inputHorarioIni = document.getElementById('horarioini');
    const inputHorarioFin = document.getElementById('horariofin');
    const agora = new Date();
    const ano = agora.getFullYear();
    const mes = String(agora.getMonth() + 1).padStart(2, '0');
    const dia = String(agora.getDate()).padStart(2, '0');
    const hora = String(agora.getHours()).padStart(2, '0');
    const minuto = String(agora.getMinutes()).padStart(2, '0');
    const dataHoraFormatada = `${ano}-${mes}-${dia}T${hora}:${minuto}`;
    inputHorarioIni.value = dataHoraFormatada;
    inputHorarioFin.value = dataHoraFormatada;

    // --- Autocomplete Clientes ---
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
                        // --- LINHAS ALTERADAS AQUI ---
                        document.getElementById("cpfcnpj").innerText = formatarCpfCnpjJS(cliente.cpfcnpj);
                        document.getElementById("telefone").innerText = formatarTelefoneJS(cliente.telefone);
                        // --- FIM DAS LINHAS ALTERADAS ---
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
                    item.textContent = `${quarto.num_quarto} - ${quarto.descricao}`;

                    item.addEventListener("click", () => {
                        descQuarto.value = quarto.descricao;
                        document.getElementById("numquarto").value = quarto.id_quarto;
                        listaQuartos.innerHTML = "";
                    });
                    listaQuartos.appendChild(item);
                });
            });
    });
});
</script>

</body>
</html>