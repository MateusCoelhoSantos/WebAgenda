<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 
$idquarto = $_POST['idquarto'] ?? 0;

try {
    if ($idquarto == 0) throw new Exception("ID do quarto inválido.");

    $pasta_uploads = "../Imagens/Quartos/";
    if (isset($_FILES['fotos_quarto'])) {
        foreach ($_FILES['fotos_quarto']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['fotos_quarto']['error'][$key] !== UPLOAD_ERR_OK) continue;

            $foto = ['name' => $_FILES['fotos_quarto']['name'][$key], 'size' => $_FILES['fotos_quarto']['size'][$key], 'tmp_name' => $tmp_name];
            if ($foto['size'] > 3 * 1024 * 1024) throw new Exception("Arquivo muito grande (máx 3MB).");
            
            $tipo_arquivo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $foto['tmp_name']);
            if (!in_array($tipo_arquivo, ['image/jpeg', 'image/png', 'image/gif'])) throw new Exception("Tipo de arquivo inválido.");

            $extensao = pathinfo($foto['name'], PATHINFO_EXTENSION);
            $novo_nome_arquivo = uniqid() . '_' . time() . '.' . $extensao;
            $caminho_completo = $pasta_uploads . $novo_nome_arquivo;

            if (move_uploaded_file($foto['tmp_name'], $caminho_completo)) {
                $sql_imagem = "INSERT INTO quarto_imagens (id_quarto, nome_arquivo) VALUES (?, ?)";
                $stmt_imagem = mysqli_prepare($conexao, $sql_imagem);
                mysqli_stmt_bind_param($stmt_imagem, 'is', $idquarto, $novo_nome_arquivo);
                mysqli_stmt_execute($stmt_imagem);
            }
        }
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Imagens enviadas com sucesso!'];
    } else {
        throw new Exception("Nenhuma imagem foi enviada.");
    }
} catch (Exception $e) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Erro: ' . $e->getMessage()];
}

header('Location: agenda.php?menuop=editarquarto&idquarto=' . $idquarto);
exit();
?>