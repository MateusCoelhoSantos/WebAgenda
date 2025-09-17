<?php
// Garante que a sessão está ativa e que o usuário está logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?menuop=login');
    exit();
}
include_once("conexao.php");
include_once("funcoes.php"); // Inclui o arquivo com a função formatarCpfCnpj()

// Busca os dados atuais do usuário logado para preencher o formulário
$user_id = $_SESSION['user_id'];
$dados_usuario = null; // A variável é inicializada como nula aqui

// A linha que causava o erro foi REMOVIDA daqui.

try {
    $sql = "SELECT nome, email, telefone, cpf, foto_perfil FROM usuarios WHERE id_usuario = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);

    // Método robusto para buscar os dados
    mysqli_stmt_bind_result($stmt, $nome, $email, $telefone, $cpf, $foto_perfil);
    
    if (mysqli_stmt_fetch($stmt)) {
        // Monta o array de dados corretamente
        $dados_usuario = [
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'cpf' => $cpf,
            'foto_perfil' => $foto_perfil
        ];
    }
    // Fecha o statement após o uso
    mysqli_stmt_close($stmt);

    if (!$dados_usuario) {
        throw new Exception("Não foi possível carregar os dados do usuário.");
    }
} catch (Exception $e) {
    $_SESSION['message'] = ['type' => 'error', 'text' => $e->getMessage()];
    header('Location: agenda.php?menuop=home');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - WebAgenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .profile-pic { width: 150px; height: 150px; object-fit: cover; border-radius: 50%; }
    </style>
</head>
<body style="background-color: #f0f2f5;">

<div class="container my-4">
    <header class="mb-4"><h2 class="text-center text-secondary">Meu Perfil</h2></header>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <?php
                        $caminho_foto = "../Imagens/Usuarios/" . ($dados_usuario['foto_perfil'] ?? 'default-avatar.png');
                        if (empty($dados_usuario['foto_perfil']) || !file_exists($caminho_foto)) {
                            $caminho_foto = "../Imagens/Usuarios/default-avatar.png"; // Caminho para uma imagem padrão
                        }
                    ?>
                    <img src="<?= $caminho_foto ?>" alt="Foto de Perfil" class="profile-pic img-thumbnail mb-3">
                    <h4><?= htmlspecialchars($dados_usuario['nome']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($dados_usuario['email']) ?></p>
                    <hr>
                    <form action="atualizar_foto.php" method="post" enctype="multipart/form-data" class="mb-2">
                        <label for="nova_foto" class="form-label">Alterar foto de perfil:</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="nova_foto" id="nova_foto" accept="image/png, image/jpeg, image/gif" required>
                            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-upload"></i> Enviar</button>
                        </div>
                        <div class="form-text">Envie arquivos .jpg, .png ou .gif de até 2MB.</div>
                    </form>

                    <?php if (!empty($dados_usuario['foto_perfil'])): ?>
                        <a href="remover_foto.php" class="btn btn-sm btn-outline-danger btn-remover-foto">
                            <i class="bi bi-trash3"></i> Remover Foto Atual
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-person-fill"></i> Dados Pessoais</h5></div>
                <div class="card-body p-4">
                    <form action="atualizar_perfil.php" method="post">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($dados_usuario['nome']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($dados_usuario['email']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars(formatarTelefone($dados_usuario['telefone'] ?? '')) ?>">
                            </div>
                             <div class="col-md-6 mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control" id="cpf" name="cpf" value="<?= htmlspecialchars(formatarCpfCnpj($dados_usuario['cpf'] ?? '')) ?>" readonly>
                                <div class="form-text">Seu CPF não pode ser alterado.</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-key-fill"></i> Alterar Senha</h5></div>
                <div class="card-body p-4">
                    <form action="atualizar_senha_perfil.php" method="post">
                        <div class="mb-3">
                            <label for="senha_atual" class="form-label">Senha Atual</label>
                            <input type="password" class="form-control" id="senha_atual" name="senha_atual" required>
                        </div>
                        <div class="mb-3">
                            <label for="nova_senha" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirma_senha" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Alterar Senha</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

    <script>
        // Seleciona o botão de remover foto
        const removeButton = document.querySelector('.btn-remover-foto');

        // Adiciona o listener de clique, se o botão existir na página
        if (removeButton) {
            removeButton.addEventListener('click', function (event) {
                // Previne a ação padrão do link
                event.preventDefault(); 
                
                const removeUrl = this.href; // Guarda o link de remoção

                Swal.fire({
                    title: 'Remover foto?',
                    text: "Seu perfil voltará a exibir a imagem padrão.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sim, remover!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Se o usuário confirmar, redireciona para o script de remoção
                        window.location.href = removeUrl;
                    }
                });
            });
        }
    </script>

</body>
</html>