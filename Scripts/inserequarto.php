<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php");

// Inicia uma transação. Ou tudo funciona, ou nada é salvo.
mysqli_begin_transaction($conexao);

try {
    // 1. Captura e valida os dados do formulário
    if (empty($_POST["numquarto"]) || empty($_POST["nome_quarto"])) {
        throw new Exception("Número e Nome do quarto são obrigatórios.");
    }

    $numquarto = $_POST["numquarto"];
    $nome_quarto = $_POST["nome_quarto"];
    $descricao = $_POST["descricao"];
    $capacidade_adultos = $_POST["capacidade_adultos"];
    $capacidade_criancas = $_POST["capacidade_criancas"];
    $preco_diaria = $_POST["preco_diaria"];
    $comodidades = $_POST['comodidades'] ?? [];
    $tem_wifi = isset($comodidades['wifi']) ? 1 : 0;
    $tem_ar = isset($comodidades['ar']) ? 1 : 0;
    $tem_tv = isset($comodidades['tv']) ? 1 : 0;

    // 2. Insere os dados principais na tabela 'quartos'
    $sql_quarto = "INSERT INTO quartos 
        (num_quarto, nome_quarto, descricao, capacidade_adultos, capacidade_criancas, preco_diaria, tem_wifi, tem_ar_condicionado, tem_tv, status, excluido) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0)";
    
    $stmt_quarto = mysqli_prepare($conexao, $sql_quarto);
    mysqli_stmt_bind_param($stmt_quarto, 'sssiidiii', $numquarto, $nome_quarto, $descricao, $capacidade_adultos, $capacidade_criancas, $preco_diaria, $tem_wifi, $tem_ar, $tem_tv);
    
    if (!mysqli_stmt_execute($stmt_quarto)) {
        throw new Exception("Erro ao salvar os dados do quarto.");
    }
    
    // Pega o ID do quarto que acabamos de criar
    $id_novo_quarto = mysqli_insert_id($conexao);

    // 3. Processa o upload das imagens
    $pasta_uploads = "../Imagens/Quartos/"; // Confirme se este caminho está correto
    if (isset($_FILES['fotos_quarto']) && count($_FILES['fotos_quarto']['name']) <= 3) {
        
        foreach ($_FILES['fotos_quarto']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['fotos_quarto']['error'][$key] !== UPLOAD_ERR_OK) {
                continue; // Pula arquivos com erro
            }

            // Validações de segurança para cada arquivo
            $nome_arquivo = $_FILES['fotos_quarto']['name'][$key];
            $tamanho_arquivo = $_FILES['fotos_quarto']['size'][$key];
            $tipo_arquivo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $tmp_name);
            $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];

            if ($tamanho_arquivo > 3 * 1024 * 1024) continue; // Pula arquivos > 3MB
            if (!in_array($tipo_arquivo, $tipos_permitidos)) continue; // Pula arquivos de tipo não permitido
            
            // Cria um nome de arquivo único e seguro
            $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
            $novo_nome_arquivo = uniqid() . '_' . time() . '.' . $extensao;
            $caminho_completo = $pasta_uploads . $novo_nome_arquivo;

            // Move o arquivo para a pasta
            if (move_uploaded_file($tmp_name, $caminho_completo)) {
                // Insere a referência da imagem no banco de dados
                $sql_imagem = "INSERT INTO quarto_imagens (id_quarto, nome_arquivo) VALUES (?, ?)";
                $stmt_imagem = mysqli_prepare($conexao, $sql_imagem);
                mysqli_stmt_bind_param($stmt_imagem, 'is', $id_novo_quarto, $novo_nome_arquivo);
                if (!mysqli_stmt_execute($stmt_imagem)) {
                    throw new Exception("Erro ao salvar uma das imagens.");
                }
            }
        }
    }

    // Se tudo deu certo até aqui, confirma a transação
    mysqli_commit($conexao);

    $_SESSION['message'] = ['type' => 'success', 'text' => 'Quarto cadastrado com sucesso!'];

} catch (Exception $e) {
    // Se algo deu errado, desfaz todas as operações
    mysqli_rollback($conexao);
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Erro: ' . $e->getMessage()];
}

header('Location: agenda.php?menuop=quartos');
exit();
?>