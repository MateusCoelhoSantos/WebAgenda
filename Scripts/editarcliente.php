<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Protege a página contra acesso não logado
if (!isset($_SESSION['user_id'])) { header('Location: index.php?menuop=login'); exit(); }

include_once("conexao.php"); 
include_once("funcoes.php"); // Inclui o arquivo com as funções de validação PHP

$errors = []; // Array para armazenar os erros de validação
$idcli = $_GET["idcli"] ?? null; // Pega o ID da URL

// --- LÓGICA DE ATUALIZAÇÃO (QUANDO O FORMULÁRIO É ENVIADO VIA POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Inicia a transação
    mysqli_begin_transaction($conexao);
    
    try {
        // --- 1. Captura os dados do POST ---
        $idcli      = $_POST["cliid"] ?? ''; // Pega o ID do formulário
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
        
        // Query 1: ATUALIZA 'pessoas'
        $sql_pessoa = "UPDATE pessoas SET nome = ?, cpfcnpj = ?, rgie = ?, nasc = ?, email = ?, telefone = ?, f_j = ?, genero = ? WHERE id_pessoa = ?";
        $stmt_pessoa = mysqli_prepare($conexao, $sql_pessoa);
        mysqli_stmt_bind_param($stmt_pessoa, 'ssssssisi', $clinome, $cpfCnpjLimpo, $clirgie, $clinasc, $cliemail, $telLimpo, $clitipo, $cligenero, $idcli);
        
        if (!mysqli_stmt_execute($stmt_pessoa)) {
            if (mysqli_errno($conexao) == 1062) { throw new Exception("Este CPF/CNPJ ou E-mail já está em uso por outro cliente."); }
            throw new Exception("Erro ao atualizar dados pessoais: " . mysqli_stmt_error($stmt_pessoa));
        }
        mysqli_stmt_close($stmt_pessoa);

        // Query 2: VERIFICA SE ENDEREÇO JÁ EXISTE
        $sql_check = "SELECT id_endereco FROM endereco WHERE id_pessoa = ?";
        $stmt_check = mysqli_prepare($conexao, $sql_check);
        mysqli_stmt_bind_param($stmt_check, 'i', $idcli);
        mysqli_stmt_execute($stmt_check);
        $endereco_existente = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_check));
        mysqli_stmt_close($stmt_check);
        
        // Query 3: ATUALIZA (UPDATE) OU INSERE (INSERT) O ENDEREÇO
        if ($endereco_existente) {
            $sql_endereco = "UPDATE endereco SET rua = ?, numero = ?, bairro = ?, cidade = ?, uf = ?, cep = ?, complemento = ? WHERE id_pessoa = ?";
            $stmt_endereco = mysqli_prepare($conexao, $sql_endereco);
            mysqli_stmt_bind_param($stmt_endereco, 'sssssssi', $logradouro, $numero, $bairro, $cidade, $uf, $cepLimpo, $complemento, $idcli);
        } else {
            $sql_endereco = "INSERT INTO endereco (id_pessoa, rua, numero, bairro, cidade, uf, cep, complemento) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_endereco = mysqli_prepare($conexao, $sql_endereco);
            mysqli_stmt_bind_param($stmt_endereco, 'isssssss', $idcli, $logradouro, $numero, $bairro, $cidade, $uf, $cepLimpo, $complemento);
        }
        
        if (!mysqli_stmt_execute($stmt_endereco)) { throw new Exception("Erro ao salvar o endereço: " . mysqli_stmt_error($stmt_endereco)); }
        mysqli_stmt_close($stmt_endereco);

        // Se tudo deu certo, confirma a transação
        mysqli_commit($conexao);
        
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Cliente e endereço atualizados com sucesso!'];
        header('Location: agenda.php?menuop=clientes'); // Redireciona SÓ em caso de sucesso
        exit();

    } catch (Exception $e) {
        // Se deu qualquer erro, desfaz TUDO
        mysqli_rollback($conexao);
        
        if (empty($errors)) { // Se o $errors está vazio, o erro foi do banco (ex: duplicado)
            $errors['geral'] = 'Erro: ' . $e->getMessage();
        }
        // Se o $errors não está vazio, o erro foi de validação e o script continuará para o HTML
    }
}

// --- LÓGICA PARA CARREGAR OS DADOS NA PRIMEIRA VISITA (GET) ---
// Esta parte executa se a página NÃO for um POST ou se for um POST que falhou na validação
try {
    if ($idcli === null || !is_numeric($idcli)) { throw new Exception("ID do cliente não foi fornecido ou é inválido."); }

    // Busca dados da pessoa e do endereço
    $sql = "SELECT p.*, e.rua, e.numero, e.bairro, e.cidade, e.uf, e.cep, e.complemento 
            FROM pessoas p 
            LEFT JOIN endereco e ON p.id_pessoa = e.id_pessoa 
            WHERE p.id_pessoa = ?";
            
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idcli);
    mysqli_stmt_execute($stmt);
    $dados = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

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
                <div class="card-header"><h3 class="text-center mb-0">Editar Cliente</h3></div>
                <div class="card-body p-4">

                    <?php if (isset($errors['geral'])): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($errors['geral']) ?></div>
                    <?php elseif (!empty($errors)): ?>
                        <div class="alert alert-danger"><p class="mb-0">Por favor, corrija os campos indicados abaixo.</p></div>
                    <?php endif; ?>

                    <form action="agenda.php?menuop=editarcliente&idcli=<?= (int)$idcli ?>" method="post" id="formEditarCliente">
                        
                        <input type="hidden" name="cliid" value="<?= htmlspecialchars($dados["id_pessoa"]) ?>">

                        <h5 class="mb-3 text-secondary">Dados Pessoais</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="clinome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control <?= isset($errors['clinome']) ? 'is-invalid' : '' ?>" id="clinome" name="clinome" value="<?= htmlspecialchars($_POST['clinome'] ?? $dados["nome"]) ?>" required>
                                <?php if (isset($errors['clinome'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['clinome']) ?></div><?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="clicpfcnpj" class="form-label">CPF/CNPJ</label>
                                <input type="text" class="form-control <?= isset($errors['clicpfcnpj']) ? 'is-invalid' : '' ?>" id="clicpfcnpj" name="clicpfcnpj" value="<?= htmlspecialchars($_POST['clicpfcnpj'] ?? formatarCpfCnpj($dados["cpfcnpj"])) ?>" required maxlength="18">
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['clicpfcnpj'] ?? 'CPF ou CNPJ inválido.') ?></div>
                            </div>
                            <div class="col-md-6 mb-3"><label for="clirgie" class="form-label">RG/IE</label><input type="text" class="form-control" id="clirgie" name="clirgie" value="<?= htmlspecialchars($_POST['clirgie'] ?? $dados["rgie"]) ?>"></div>
                            <div class="col-md-6 mb-3"><label for="cliemail" class="form-label">E-mail</label><input type="email" class="form-control" id="cliemail" name="cliemail" value="<?= htmlspecialchars($_POST['cliemail'] ?? $dados["email"]) ?>"></div>
                            <div class="col-md-6 mb-3"><label for="clitel" class="form-label">Telefone</label><input type="text" class="form-control" id="clitel" name="clitel" value="<?= htmlspecialchars($_POST['clitel'] ?? formatarTelefone($dados["telefone"])) ?>" maxlength="15"></div>
                            <div class="col-md-6 mb-3"><label for="clinasc" class="form-label">Data de Nascimento</label><input type="date" class="form-control" id="clinasc" name="clinasc" value="<?= htmlspecialchars($_POST['clinasc'] ?? $dados["nasc"]) ?>"></div>
                            <div class="col-md-6 mb-3"><label class="form-label d-block">Tipo Pessoa</label>
                                <div class="form-check form-check-inline"><input class="form-check-input" type="radio" id="pessoafisica" name="clitipo" value="0" <?php if(($_POST['clitipo'] ?? $dados["f_j"]) == 0) echo 'checked'; ?> required><label class="form-check-label" for="pessoafisica">Física</label></div>
                                <div class="form-check form-check-inline"><input class="form-check-input" type="radio" id="pessoajuridica" name="clitipo" value="1" <?php if(($_POST['clitipo'] ?? $dados["f_j"]) == 1) echo 'checked'; ?> required><label class="form-check-label" for="pessoajuridica">Jurídica</label></div>
                            </div>
                            <div class="col-md-12 mb-3"><label class="form-label d-block">Gênero</label>
                                <div class="form-check form-check-inline"><input class="form-check-input" type="radio" id="masculino" name="cligenero" value="M" <?php if(($_POST['cligenero'] ?? $dados["genero"]) == "M") echo 'checked'; ?> required><label class="form-check-label" for="masculino">Masculino</label></div>
                                <div class="form-check form-check-inline"><input class="form-check-input" type="radio" id="feminino" name="cligenero" value="F" <?php if(($_POST['cligenero'] ?? $dados["genero"]) == "F") echo 'checked'; ?> required><label class="form-check-label" for="feminino">Feminino</label></div>
                                <div class="form-check form-check-inline"><input class="form-check-input" type="radio" id="naoidentificado" name="cligenero" value="N" <?php if(($_POST['cligenero'] ?? $dados["genero"]) == "N") echo 'checked'; ?> required><label class="form-check-label" for="naoidentificado">Não Informado</label></div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3 text-secondary">Endereço</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3"><label for="cep" class="form-label">CEP</label><input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000" maxlength="9" value="<?= htmlspecialchars($_POST['cep'] ?? $dados['cep'] ?? '') ?>"></div>
                            <div class="col-md-8 mb-3"><label for="logradouro" class="form-label">Logradouro</label><input type="text" class="form-control" id="logradouro" name="logradouro" value="<?= htmlspecialchars($_POST['logradouro'] ?? $dados['rua'] ?? '') ?>"></div>
                        </div>
                        <div class="row">
                             <div class="col-md-4 mb-3"><label for="numero" class="form-label">Número</label><input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($_POST['numero'] ?? $dados['numero'] ?? '') ?>"></div>
                             <div class="col-md-8 mb-3"><label for="complemento" class="form-label">Complemento</label><input type="text" class="form-control" id="complemento" name="complemento" value="<?= htmlspecialchars($_POST['complemento'] ?? $dados['complemento'] ?? '') ?>"></div>
                        </div>
                         <div class="row">
                            <div class="col-md-5 mb-3"><label for="bairro" class="form-label">Bairro</label><input type="text" class="form-control" id="bairro" name="bairro" value="<?= htmlspecialchars($_POST['bairro'] ?? $dados['bairro'] ?? '') ?>"></div>
                             <div class="col-md-5 mb-3"><label for="cidade" class="form-label">Cidade</label><input type="text" class="form-control" id="cidade" name="cidade" value="<?= htmlspecialchars($_POST['cidade'] ?? $dados['cidade'] ?? '') ?>"></div>
                             <div class="col-md-2 mb-3"><label for="uf" class="form-label">UF</label><input type="text" class="form-control" id="uf" name="uf" maxlength="2" value="<?= htmlspecialchars($_POST['uf'] ?? $dados['uf'] ?? '') ?>"></div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// --- FUNÇÕES DE VALIDAÇÃO E MÁSCARA ---
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

function mascaraTelefone(valor) {
    valor = valor.replace(/\D/g, "");
    if (valor.length > 10) { 
        valor = valor.replace(/^(\d{2})(\d{5})(\d{4}).*/, "($1) $2-$3");
    } else if (valor.length > 2) {
        valor = valor.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, "($1) $2-$3");
    } else if (valor.length > 0) {
        valor = valor.replace(/^(\d{0,2})/, "($1");
    }
    return valor.substring(0, 15);
}

function mascaraCpfCnpj(valor) {
    valor = valor.replace(/\D/g, '');
    if (valor.length <= 11) { 
        valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
        valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
        valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    } else { 
        valor = valor.replace(/^(\d{2})(\d)/, '$1.$2');
        valor = valor.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        valor = valor.replace(/\.(\d{3})(\d)/, '.$1/$2');
        valor = valor.replace(/(\d{4})(\d)/, '$1-$2');
    }
    return valor.substring(0, 18);
}

function mascaraCEP(valor) {
    valor = valor.replace(/\D/g, "");
    valor = valor.replace(/^(\d{5})(\d)/, "$1-$2");
    return valor.substring(0, 9);
}

// --- LÓGICA PRINCIPAL ---
document.addEventListener("DOMContentLoaded", () => {
    const inputCpfCnpj = document.getElementById('clicpfcnpj');
    const inputTelefone = document.getElementById('clitel');
    const inputCEP = document.getElementById('cep');
    const inputLogradouro = document.getElementById('logradouro');
    const inputNumero = document.getElementById('numero');
    const inputBairro = document.getElementById('bairro');
    const inputCidade = document.getElementById('cidade');
    const inputUF = document.getElementById('uf');
    
    const validarEFormatarCpfCnpj = (input) => {
        const valorLimpo = input.value.replace(/\D/g, '');
        let valido = false;
        if (valorLimpo.length === 11) {
            valido = validarCPF_JS(valorLimpo);
        } else if (valorLimpo.length === 14) {
            valido = validarCNPJ_JS(valorLimpo);
        }

        if (valorLimpo.length >= 11) {
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

    // Valida o CPF/CNPJ que já veio do banco
    validarEFormatarCpfCnpj(inputCpfCnpj); 
    
    // Listeners para máscaras e validação
    inputCpfCnpj.addEventListener('input', () => {
        inputCpfCnpj.value = mascaraCpfCnpj(inputCpfCnpj.value);
        validarEFormatarCpfCnpj(inputCpfCnpj);
    });
    inputTelefone.addEventListener('input', () => { inputTelefone.value = mascaraTelefone(inputTelefone.value); });
    inputCEP.addEventListener('input', () => { inputCEP.value = mascaraCEP(inputCEP.value); });

    // Listener para busca de CEP
    inputCEP.addEventListener('blur', () => {
        const cep = inputCEP.value.replace(/\D/g, '');
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        inputLogradouro.value = data.logradouro;
                        inputBairro.value = data.bairro;
                        inputCidade.value = data.localidade;
                        inputUF.value = data.uf;
                        inputNumero.focus();
                    } else {
                        // Limpa os campos se o CEP for inválido, mas não mostra alerta
                        inputLogradouro.value = '';
                        inputBairro.value = '';
                        inputCidade.value = '';
                        inputUF.value = '';
                    }
                })
                .catch(error => { console.error('Erro:', error); });
        }
    });
});
</script>

</body>
</html>