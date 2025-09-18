<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 
include_once("funcoes.php"); // Inclui o arquivo com as funções de formatação

// --- SEGURANÇA E COMPATIBILIDADE NA BUSCA DE DADOS ---
$dados = null;
try {
    $idcli = $_GET["idcli"] ?? null;
    if ($idcli === null || !is_numeric($idcli)) {
        throw new Exception("ID do cliente não foi fornecido ou é inválido.");
    }

    // A consulta está selecionando todas as colunas necessárias
    $sql = "SELECT id_pessoa, nome, cpfcnpj, rgie, email, telefone, nasc, f_j, genero FROM pessoas WHERE id_pessoa = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idcli);
    mysqli_stmt_execute($stmt);

    // --- CÓDIGO CORRIGIDO AQUI ---
    // "Amarramos" variáveis PHP diretamente às colunas do SELECT
    mysqli_stmt_bind_result($stmt, $id_pessoa, $nome, $cpfcnpj, $rgie, $email, $telefone, $nasc, $f_j, $genero);
    
    // "Puxamos" os dados para dentro dessas variáveis
    if (mysqli_stmt_fetch($stmt)) {
        // Montamos o array de dados manualmente
        $dados = [
            'id_pessoa' => $id_pessoa,
            'nome' => $nome,
            'cpfcnpj' => $cpfcnpj,
            'rgie' => $rgie,
            'email' => $email,
            'telefone' => $telefone,
            'nasc' => $nasc,
            'f_j' => $f_j,
            'genero' => $genero
        ];
    }
    mysqli_stmt_close($stmt);
    // --- FIM DA CORREÇÃO ---

    if (!$dados) {
        throw new Exception("Cliente com o ID {$idcli} não encontrado.");
    }
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="background-color: #f0f2f5;">

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="text-center mb-0">Editar Cliente</h3>
                </div>
                <div class="card-body p-4">
                    <form action="agenda.php?menuop=atualizarcliente" method="post">
                        
                        <input type="hidden" name="cliid" value="<?= htmlspecialchars($dados["id_pessoa"]) ?>">

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="clinome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="clinome" name="clinome" value="<?= htmlspecialchars($dados["nome"]) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="clicpfcnpj" class="form-label">CPF/CNPJ</label>
                                <input type="text" class="form-control" id="clicpfcnpj" name="clicpfcnpj" value="<?= htmlspecialchars(formatarCpfCnpj($dados["cpfcnpj"])) ?>" maxlength="18">
                                <div class="invalid-feedback">CPF ou CNPJ inválido.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="clirgie" class="form-label">RG/IE</label>
                                <input type="text" class="form-control" id="clirgie" name="clirgie" value="<?= htmlspecialchars($dados["rgie"]) ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cliemail" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="cliemail" name="cliemail" value="<?= htmlspecialchars($dados["email"]) ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="clitel" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="clitel" name="clitel" value="<?= htmlspecialchars(formatarTelefone($dados["telefone"])) ?>" maxlength="15">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="clinasc" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control" id="clinasc" name="clinasc" value="<?= htmlspecialchars($dados["nasc"]) ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">Tipo Pessoa</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="pessoafisica" name="clitipo" value="0" <?php if ($dados["f_j"] == 0) echo 'checked'; ?> required>
                                    <label class="form-check-label" for="pessoafisica">Física</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="pessoajuridica" name="clitipo" value="1" <?php if ($dados["f_j"] == 1) echo 'checked'; ?> required>
                                    <label class="form-check-label" for="pessoajuridica">Jurídica</label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label d-block">Gênero</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="masculino" name="cligenero" value="M" <?php if ($dados["genero"] == "M") echo 'checked'; ?> required>
                                    <label class="form-check-label" for="masculino">Masculino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="feminino" name="cligenero" value="F" <?php if ($dados["genero"] == "F") echo 'checked'; ?> required>
                                    <label class="form-check-label" for="feminino">Feminino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="naoidentificado" name="cligenero" value="N" <?php if ($dados["genero"] == "N") echo 'checked'; ?> required>
                                    <label class="form-check-label" for="naoidentificado">Não Informado</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer text-end bg-white px-0 pt-3">
                            <a href="agenda.php?menuop=clientes" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success" name="atualizar">
                                <i class="bi bi-check-circle"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Funções de validação em JavaScript (CPF e CNPJ)
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
    let tamanho = cnpj.length - 2, numeros = cnpj.substring(0, tamanho), digitos = cnpj.substring(tamanho), soma = 0, pos = tamanho - 7;
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

// Funções de máscara
function mascaraCpfCnpj(valor) {
    valor = valor.replace(/\D/g, "");
    if (valor.length > 11) {
        valor = valor.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2}).*/, '$1.$2.$3/$4-$5');
    } else {
        valor = valor.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
    }
    return valor;
}

function mascaraTelefone(valor) {
    valor = valor.replace(/\D/g, "");
    valor = valor.replace(/^(\d{2})(\d)/g, "($1) $2");
    valor = valor.replace(/(\d)(\d{4})$/, "$1-$2");
    return valor;
}

// Lógica principal
document.addEventListener("DOMContentLoaded", () => {
    const inputCpfCnpj = document.getElementById('clicpfcnpj');
    const inputTelefone = document.getElementById('clitel');

    const validarEFormatarCpfCnpj = (input) => {
        const valorLimpo = input.value.replace(/\D/g, '');
        let valido = false;
        if (valorLimpo.length === 11) {
            valido = validarCPF_JS(valorLimpo);
        } else if (valorLimpo.length === 14) {
            valido = validarCNPJ_JS(valorLimpo);
        }

        if (valorLimpo.length > 0) {
            if (valido) {
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');
            } else {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
            }
        } else {
            input.classList.remove('is-valid', 'is-invalid');
        }
    };
    
    // Valida o dado que já veio do banco de dados
    validarEFormatarCpfCnpj(inputCpfCnpj); 
    
    // Adiciona o listener para quando o usuário digitar
    inputCpfCnpj.addEventListener('input', () => {
        inputCpfCnpj.value = mascaraCpfCnpj(inputCpfCnpj.value);
        validarEFormatarCpfCnpj(inputCpfCnpj);
    });

    inputTelefone.addEventListener('input', () => {
        inputTelefone.value = mascaraTelefone(inputTelefone.value);
    });
});
</script>

</body>
</html>