<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    die("Acesso negado.");
}
include("conexao.php");

try {
    $user_id = $_SESSION['user_id'];
    $pasta_uploads = "../Imagens/Usuarios/";

    // 1. Validação do arquivo enviado
    if (!isset($_FILES['nova_foto']) || $_FILES['nova_foto']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Nenhum arquivo enviado ou erro no upload.");
    }
    
    $foto = $_FILES['nova_foto'];
    
    // 2. Validação de tamanho (ex: 2MB)
    if ($foto['size'] > 2 * 1024 * 1024) {
        throw new Exception("O arquivo é muito grande. O tamanho máximo é 2MB.");
    }

    // 3. Validação de tipo (MIME type)
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    $tipo_arquivo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $foto['tmp_name']);
    if (!in_array($tipo_arquivo, $tipos_permitidos)) {
        throw new Exception("Tipo de arquivo não permitido. Apenas JPG, PNG e GIF são aceitos.");
    }

    // 4. Cria um nome de arquivo único para evitar conflitos
    $extensao = pathinfo($foto['name'], PATHINFO_EXTENSION);
    $novo_nome_arquivo = uniqid() . '_' . time() . '.' . $extensao;
    $caminho_completo = $pasta_uploads . $novo_nome_arquivo;

    // 5. Busca o nome da foto antiga para poder excluí-la depois
    $sql_antiga = "SELECT foto_perfil FROM usuarios WHERE id_usuario = ?";
    $stmt_antiga = mysqli_prepare($conexao, $sql_antiga);
    mysqli_stmt_bind_param($stmt_antiga, 'i', $user_id);
    mysqli_stmt_execute($stmt_antiga);
    $result_antiga = mysqli_stmt_get_result($stmt_antiga);
    $foto_antiga = mysqli_fetch_assoc($result_antiga)['foto_perfil'] ?? null;

    // 6. Tenta mover o arquivo para a pasta de uploads
    if (move_uploaded_file($foto['tmp_name'], $caminho_completo)) {
        
        // 7. Atualiza o nome do arquivo no banco de dados
        $sql_update = "UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?";
        $stmt_update = mysqli_prepare($conexao, $sql_update);
        mysqli_stmt_bind_param($stmt_update, 'si', $novo_nome_arquivo, $user_id);
        
        if (mysqli_stmt_execute($stmt_update)) {
            // 8. Se a atualização deu certo, exclui a foto antiga (se houver)
            if ($foto_antiga && file_exists($pasta_uploads . $foto_antiga)) {
                unlink($pasta_uploads . $foto_antiga);
            }

            // ATUALIZA A SESSÃO COM O NOME DA NOVA FOTO
            $_SESSION['user_photo'] = $novo_nome_arquivo;
            
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Foto de perfil atualizada com sucesso!'];
        } else {
            throw new Exception("Erro ao salvar a referência da foto no banco de dados.");
        }
    } else {
        throw new Exception("Erro ao mover o arquivo para o servidor.");
    }

} catch (Exception $e) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Erro: ' . $e->getMessage()
    ];
}

header('Location: agenda.php?menuop=perfil');
exit();
?>