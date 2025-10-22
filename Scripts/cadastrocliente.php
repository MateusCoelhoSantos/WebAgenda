<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Protege a página (opcional, mas recomendado)
if (!isset($_SESSION['user_id'])) { header('Location: index.php?menuop=login'); exit(); }

include_once("conexao.php"); 
include_once("funcoes.php"); // Inclui o arquivo com as funções de validação PHP

$errors = []; // Array para armazenar os erros de validação

// --- LÓGICA DE PROCESSAMENTO DO FORMULÁRIO (QUANDO ENVIADO VIA POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Inicia a transação
    mysqli_begin_transaction($conexao);
    
    try {
        // --- 1. Captura os dados do POST ---
        $clinome    = $_POST["clinome"] ?? '';
        $clicpfcnpj = $_POST["clicpfcnpj"] ?? '';
        $clirgie    = $_POST["clirgie"] ?? '';
        $clinasc    = $_POST["clinasc"] ?? '';
        $cliemail   = $_POST["cliemail"] ?? '';
        $clitel     = $_POST["clitel"] ?? '';
        $clitipo    = $_POST["clitipo"] ?? '0';
        $cligenero  = $_POST["cligenero"] ?? 'N';
        $cep         = $_POST['cep'] ?? '';
        $logradouro  = $_POST['logradouro'] ?? '';
        $numero      = $_POST['numero'] ?? '';
        $complemento = $_POST['complemento'] ?? '';
        $bairro      = $_POST['bairro'] ?? '';
        $cidade      = $_POST['cidade'] ?? '';
        $uf          = $_POST['uf'] ?? '';

        // --- 2. Validação campo a campo ---
        if (empty($clinome)) { $errors['clinome'] = "Nome é obrigatório."; }
        
        $cpfCnpjLimpo = preg_replace('/\D/', '', $clicpfcnpj);
        if (empty($cpfCnpjLimpo)) { 
            $errors['clicpfcnpj'] = "CPF/CNPJ é obrigatório."; 
        } else {
            $isCpf = strlen($cpfCnpjLimpo) == 11;
            $isCnpj = strlen($cpfCnpjLimpo) == 14;
            if ($isCpf && !validarCpf($cpfCnpjLimpo)) { $errors['clicpfcnpj'] = "O CPF informado é inválido."; }
            if ($isCnpj && !validarCnpj($cpfCnpjLimpo)) { $errors['clicpfcnpj'] = "O CNPJ informado é inválido."; }
            if (!$isCpf && !$isCnpj) { $errors['clicpfcnpj'] = "CPF (11 dígitos) ou CNPJ (14 dígitos) inválido."; }
        }

        // --- 3. Verifica se há erros de validação ---
        if (!empty($errors)) {
            // Se houver erros, lança uma exceção para parar o processo
            throw new Exception("Dados inválidos. Por favor, corrija os campos indicados.");
        }

        // --- 4. Se não há erros, continua para o Banco de Dados ---
        
        // Limpa dados para salvar
        $telLimpo = preg_replace('/\D/', '', $clitel);
        $cepLimpo = preg_replace('/\D/', '', $cep);
        
        // Insere na tabela 'pessoas'
        $sql_pessoa = "INSERT INTO pessoas (nome, cpfcnpj, rgie, nasc, email, telefone, f_j, genero, tipopessoa, excluido) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, 0)";
        $stmt_pessoa = mysqli_prepare($conexao, $sql_pessoa);
        mysqli_stmt_bind_param($stmt_pessoa, 'ssssssis', $clinome, $cpfCnpjLimpo, $clirgie, $clinasc, $cliemail, $telLimpo, $clitipo, $cligenero);

        if (!mysqli_stmt_execute($stmt_pessoa)) {
            if (mysqli_errno($conexao) == 1062) { throw new Exception("Este CPF/CNPJ ou E-mail já está cadastrado."); } 
            else { throw new Exception("Não foi possível cadastrar a pessoa."); }
        }
        
        $id_nova_pessoa = mysqli_insert_id($conexao);
        if ($id_nova_pessoa <= 0) { throw new Exception("Falha ao obter o ID da pessoa cadastrada."); }

        // Insere na tabela 'endereco'
        $sql_endereco = "INSERT INTO endereco (id_pessoa, rua, numero, bairro, cidade, uf, cep, complemento) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_endereco = mysqli_prepare($conexao, $sql_endereco);
        mysqli_stmt_bind_param($stmt_endereco, 'isssssss', $id_nova_pessoa, $logradouro, $numero, $bairro, $cidade, $uf, $cepLimpo, $complemento);
        
        if (!mysqli_stmt_execute($stmt_endereco)) {
            throw new Exception("Não foi possível cadastrar o endereço.");
        }

        // Se tudo deu certo, confirma a transação
        mysqli_commit($conexao);
        
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Cliente cadastrado com sucesso!'];
        header('Location: agenda.php?menuop=clientes'); // Redireciona SÓ em caso de sucesso
        exit();

    } catch (Exception $e) {
        // Se deu qualquer erro, desfaz TUDO
        mysqli_rollback($conexao);
        
        // Se o erro NÃO foi de validação (foi de BD), o colocamos no array $errors
        if (empty($errors)) {
            $errors['geral'] = 'Erro: ' . $e->getMessage();
        }
        // Se foi um erro de validação, o script apenas continua e exibe o formulário com os erros
    }
}
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
                <div class="card-header"><h3 class="text-center mb-0">Cadastro de Novo Cliente</h3></div>
                <div class="card-body p-4">
                    
                    <?php if (isset($errors['geral'])): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($errors['geral']) ?></div>
                    <?php endif; ?>

                    <form action="agenda.php?menuop=cadastrocliente" method="post" id="formCadastroCliente">
                        
                        <h5 class="mb-3 text-secondary">Dados Pessoais</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="clinome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control <?= isset($errors['clinome']) ? 'is-invalid' : '' ?>" id="clinome" name="clinome" value="<?= htmlspecialchars($_POST['clinome'] ?? '') ?>" required>
                                <?php if (isset($errors['clinome'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['clinome']) ?></div><?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="clicpfcnpj" class="form-label">CPF/CNPJ</label>
                                <input type="text" class="form-control <?= isset($errors['clicpfcnpj']) ? 'is-invalid' : '' ?>" id="clicpfcnpj" name="clicpfcnpj" value="<?= htmlspecialchars($_POST['clicpfcnpj'] ?? '') ?>" required maxlength="18">
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['clicpfcnpj'] ?? 'CPF ou CNPJ inválido.') ?></div>
                            </div>
                            <div class="col-md-6 mb-3"><label for="clirgie" class="form-label">RG/IE</label><input type="text" class="form-control" id="clirgie" name="clirgie" value="<?= htmlspecialchars($_POST['clirgie'] ?? '') ?>"></div>
                            <div class="col-md-6 mb-3"><label for="cliemail" class="form-label">E-mail</label><input type="email" class="form-control" id="cliemail" name="cliemail" placeholder="exemplo@email.com" value="<?= htmlspecialchars($_POST['cliemail'] ?? '') ?>"></div>
                            <div class="col-md-6 mb-3"><label for="clitel" class="form-label">Telefone</label><input type="text" class="form-control" id="clitel" name="clitel" placeholder="(99) 9 9999-9999" maxlength="15" value="<?= htmlspecialchars($_POST['clitel'] ?? '') ?>"></div>
                            <div class="col-md-6 mb-3"><label for="clinasc" class="form-label">Data de Nascimento</label><input type="date" class="form-control" id="clinasc" name="clinasc" value="<?= htmlspecialchars($_POST['clinasc'] ?? '') ?>"></div>
                            <div class="col-md-6 mb-3"><label class="form-label d-block">Tipo Pessoa</label>
                                <div class="form-check form-check-inline"><input class="form-check-input" type="radio" id="pessoafisica" name="clitipo" value="0" <?php if(($_POST['clitipo'] ?? '0') == '0') echo 'checked'; ?> required><label class="form-check-label" for="pessoafisica">Física</label></div>
                                <div class="form-check form-check-inline"><input class="form-check-input" type="radio" id="pessoajuridica" name="clitipo" value="1" <?php if(($_POST['clitipo'] ?? '') == '1') echo 'checked'; ?> required><label class="form-check-label" for="pessoajuridica">Jurídica</label></div>
                            </div>
                            <div class="col-md-12 mb-3"><label class="form-label d-block">Gênero</label>
                                <div class="form-check form-check-inline"><input class="form-check-input" type="radio" id="masculino" name="cligenero" value="M" <?php if(($_POST['cligenero'] ?? 'N') == 'M') echo 'checked'; ?> required><label class="form-check-label" for="masculino">Masculino</label></div>
                                <div class="form-check form-check-inline"><input class="form-check-input" type="radio" id="feminino" name="cligenero" value="F" <?php if(($_POST['cligenero'] ?? '') == 'F') echo 'checked'; ?> required><label class="form-check-label" for="feminino">Feminino</label></div>
                                <div class="form-check form-check-inline"><input class="form-check-input" type="radio" id="naoidentificado" name="cligenero" value="N" <?php if(($_POST['cligenero'] ?? 'N') == 'N') echo 'checked'; ?> required><label class="form-check-label" for="naoidentificado">Não Informado</label></div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3 text-secondary">Endereço</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3"><label for="cep" class="form-label">CEP</label><input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000" maxlength="9" value="<?= htmlspecialchars($_POST['cep'] ?? '') ?>"></div>
                            <div class="col-md-8 mb-3"><label for="logradouro" class="form-label">Logradouro</label><input type="text" class="form-control" id="logradouro" name="logradouro" value="<?= htmlspecialchars($_POST['logradouro'] ?? '') ?>"></div>
                        </div>
                        <div class="row">
                             <div class="col-md-4 mb-3"><label for="numero" class="form-label">Número</label><input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>"></div>
                             <div class="col-md-8 mb-3"><label for="complemento" class="form-label">Complemento</label><input type="text" class="form-control" id="complemento" name="complemento" value="<?= htmlspecialchars($_POST['complemento'] ?? '') ?>"></div>
                        </div>
                         <div class="row">
                            <div class="col-md-5 mb-3"><label for="bairro" class="form-label">Bairro</label><input type="text" class="form-control" id="bairro" name="bairro" value="<?= htmlspecialchars($_POST['bairro'] ?? '') ?>"></div>
                             <div class="col-md-5 mb-3"><label for="cidade" class="form-label">Cidade</label><input type="text" class="form-control" id="cidade" name="cidade" value="<?= htmlspecialchars($_POST['cidade'] ?? '') ?>"></div>
                             <div class="col-md-2 mb-3"><label for="uf" class="form-label">UF</label><input type="text" class="form-control" id="uf" name="uf" maxlength="2" value="<?= htmlspecialchars($_POST['uf'] ?? '') ?>"></div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

// Função para aplicar máscara de telefone
function mascaraTelefone(valor) {
    valor = valor.replace(/\D/g, ""); // Remove tudo não numérico
    // Aplica a máscara (XX) XXXXX-XXXX ou (XX) XXXX-XXXX
    if (valor.length > 10) { 
        valor = valor.replace(/^(\d{2})(\d{5})(\d{4}).*/, "($1) $2-$3");
    } else if (valor.length > 2) {
        valor = valor.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, "($1) $2-$3");
    } else if (valor.length > 0) {
        valor = valor.replace(/^(\d{0,2})/, "($1");
    }
    return valor.substring(0, 15); // Limita o tamanho
}

// Função para aplicar máscara de CPF/CNPJ
function mascaraCpfCnpj(valor) {
    valor = valor.replace(/\D/g, ''); // Remove tudo que não for número
    if (valor.length <= 11) { // CPF
        valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
        valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
        valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    } else { // CNPJ
        valor = valor.replace(/^(\d{2})(\d)/, '$1.$2');
        valor = valor.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        valor = valor.replace(/\.(\d{3})(\d)/, '.$1/$2');
        valor = valor.replace(/(\d{4})(\d)/, '$1-$2');
    }
    return valor.substring(0, 18); // Limita o tamanho 18 (14.555.555/5555-55)
}

// Função para aplicar máscara de CEP
function mascaraCEP(valor) {
    valor = valor.replace(/\D/g, ""); // Remove tudo que não for dígito
    valor = valor.replace(/^(\d{5})(\d)/, "$1-$2"); // Coloca hífen após o quinto dígito
    return valor.substring(0, 9); // Limita o tamanho 9 (99999-999)
}

// --- LÓGICA PRINCIPAL ---
document.addEventListener("DOMContentLoaded", () => {
    const inputCpfCnpj = document.getElementById('clicpfcnpj');
    const inputTelefone = document.getElementById('clitel');
    const inputCEP = document.getElementById('cep');
    const inputLogradouro = document.getElementById('logradouro');
    const inputNumero = document.getElementById('numero');
    const inputComplemento = document.getElementById('complemento');
    const inputBairro = document.getElementById('bairro');
    const inputCidade = document.getElementById('cidade');
    const inputUF = document.getElementById('uf');

    // Máscara e validação CPF/CNPJ
    inputCpfCnpj.addEventListener('input', () => {
        inputCpfCnpj.value = mascaraCpfCnpj(inputCpfCnpj.value);
        
        const valorLimpo = inputCpfCnpj.value.replace(/\D/g, '');
        let valido = false;
        if (valorLimpo.length === 11) {
            valido = validarCPF_JS(valorLimpo);
        } else if (valorLimpo.length === 14) {
            valido = validarCNPJ_JS(valorLimpo);
        }

        if (valorLimpo.length >= 11) { // Só valida quando completo
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

    // Máscara Telefone
    inputTelefone.addEventListener('input', () => {
        inputTelefone.value = mascaraTelefone(inputTelefone.value);
    });
    
    // Máscara e busca de CEP
    inputCEP.addEventListener('input', () => { 
        inputCEP.value = mascaraCEP(inputCEP.value); 
    });
    inputCEP.addEventListener('blur', () => { // Ao sair do campo CEP
        const cep = inputCEP.value.replace(/\D/g, ''); // Pega só os números

        if (cep.length === 8) { // Se tiver 8 dígitos
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        inputLogradouro.value = data.logradouro;
                        inputBairro.value = data.bairro;
                        inputCidade.value = data.localidade;
                        inputUF.value = data.uf;
                        inputNumero.focus(); // Move o foco para o campo "Número"
                    } else {
                        inputLogradouro.value = '';
                        inputBairro.value = '';
                        inputCidade.value = '';
                        inputUF.value = '';
                        alert("CEP não encontrado.");
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar CEP:', error);
                    alert("Erro ao buscar CEP. Verifique sua conexão.");
                });
        }
    });
});
</script>

</body>
</html>