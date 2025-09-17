<?php 
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("funcoes.php"); // Inclui o arquivo com a função formatarCpfCnpj()
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="background-color: #f0f2f5;">

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="text-center mb-0">Cadastro de Novo Cliente</h3>
                </div>
                <div class="card-body p-4">
                    <form action="agenda.php?menuop=inserecliente" method="post">
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="clinome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="clinome" name="clinome" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="clicpfcnpj" class="form-label">CPF/CNPJ</label>
                                <input type="text" class="form-control" id="clicpfcnpj" name="clicpfcnpj" required maxlength="18">
                                <div class="invalid-feedback">CPF ou CNPJ inválido.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="clirgie" class="form-label">RG/IE</label>
                                <input type="text" class="form-control" id="clirgie" name="clirgie">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cliemail" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="cliemail" name="cliemail" placeholder="exemplo@email.com">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="clitel" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="clitel" name="clitel" placeholder="(99) 9 9999-9999">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="clinasc" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control" id="clinasc" name="clinasc">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">Tipo Pessoa</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="pessoafisica" name="clitipo" value="0" checked required>
                                    <label class="form-check-label" for="pessoafisica">Física</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="pessoajuridica" name="clitipo" value="1" required>
                                    <label class="form-check-label" for="pessoajuridica">Jurídica</label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label d-block">Gênero</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="masculino" name="cligenero" value="M" required>
                                    <label class="form-check-label" for="masculino">Masculino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="feminino" name="cligenero" value="F" required>
                                    <label class="form-check-label" for="feminino">Feminino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="naoidentificado" name="cligenero" value="N" checked required>
                                    <label class="form-check-label" for="naoidentificado">Não Informado</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer text-end bg-white px-0 pt-3">
                            <a href="agenda.php?menuop=clientes" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success" name="incluir">
                                <i class="bi bi-check-circle"></i> Cadastrar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Funções de validação em JavaScript (equivalentes às do PHP)
function validarCPF_JS(cpf) {
    cpf = cpf.replace(/\D/g, '');
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
    let soma = 0, resto;
    for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
    resto = (soma * 10) % 11;
    if ((resto === 10) || (resto === 11)) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) return false;
    soma = 0;
    for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
    resto = (soma * 10) % 11;
    if ((resto === 10) || (resto === 11)) resto = 0;
    if (resto !== parseInt(cpf.substring(10, 11))) return false;
    return true;
}

function validarCNPJ_JS(cnpj) {
    cnpj = cnpj.replace(/\D/g, '');
    if (cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) return false;
    let tamanho = cnpj.length - 2;
    let numeros = cnpj.substring(0, tamanho);
    let digitos = cnpj.substring(tamanho);
    let soma = 0;
    let pos = tamanho - 7;
    for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
    }
    let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0)) return false;
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1)) return false;
    return true;
}

document.addEventListener("DOMContentLoaded", () => {
    const inputCpfCnpj = document.getElementById('clicpfcnpj');

    inputCpfCnpj.addEventListener('input', () => {
        let valor = inputCpfCnpj.value.replace(/\D/g, ''); // Remove tudo que não for número
        
        // Aplica a máscara
        if (valor.length > 11) {
            // CNPJ
            valor = valor.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2}).*/, '$1.$2.$3/$4-$5');
        } else {
            // CPF
            valor = valor.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
        }
        inputCpfCnpj.value = valor;

        // Faz a validação
        const valorLimpo = inputCpfCnpj.value.replace(/\D/g, '');
        let valido = false;
        if (valorLimpo.length === 11) {
            valido = validarCPF_JS(valorLimpo);
        } else if (valorLimpo.length === 14) {
            valido = validarCNPJ_JS(valorLimpo);
        }

        // Aplica o feedback visual
        if (valorLimpo.length > 0) {
            if (valido) {
                inputCpfCnpj.classList.add('is-valid');
                inputCpfCnpj.classList.remove('is-invalid');
            } else {
                inputCpfCnpj.classList.add('is-invalid');
                inputCpfCnpj.classList.remove('is-valid');
            }
        } else {
            inputCpfCnpj.classList.remove('is-valid', 'is-invalid');
        }
    });
});
</script>

</body>
</html>