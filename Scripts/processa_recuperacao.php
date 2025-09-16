<?php
// A forma correta e segura de garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once("conexao.php"); 
require '../vendor/autoload.php'; // Verifique se este caminho está correto!

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Adicione estas linhas no topo para garantir que todos os erros PHP apareçam
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = $_POST['email'] ?? '';

        // O resto da sua lógica de busca no banco e geração de token...
        $sql = "SELECT id_usuario FROM usuarios WHERE email = ? AND excluido <> 1";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $token = bin2hex(random_bytes(32));
            $expira_em = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $sql_update = "UPDATE usuarios SET reset_token = ?, token_expira_em = ? WHERE email = ?";
            $stmt_update = mysqli_prepare($conexao, $sql_update);
            mysqli_stmt_bind_param($stmt_update, 'sss', $token, $expira_em, $email);
            mysqli_stmt_execute($stmt_update);

            $mail = new PHPMailer(true);

            // Configuração do SMTP (seus dados)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mateuscoelho1327@gmail.com';
            $mail->Password   = 'hjiltxdenncrepsm';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('mateuscoelho1327@gmail.com', 'WebAgenda');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha - WebAgenda';
            $link = "http://webagendagithub.test/WebAgenda/Scripts/redefinir_senha.php?token=" . $token; // Lembre-se de ajustar este link
            $mail->Body = "Link de recuperação: <a href='{$link}'>Redefinir Senha</a>";
            
            echo "Tentando enviar e-mail...<br>"; // Mensagem de teste
            $mail->send();
            echo "E-mail enviado com sucesso (segundo o PHPMailer)!";
        } else {
            echo "E-mail não encontrado no banco de dados.";
        }

    } catch (Exception $e) {
        echo "<h1>Ocorreu um erro!</h1>";
        echo "<strong>Erro do PHPMailer:</strong> " . $mail->ErrorInfo;
        echo "<br><strong>Exceção geral:</strong> " . $e->getMessage();
    }
    
    // --- PASSO 2: DESATIVAR O REDIRECIONAMENTO ---
     header('Location: index.php?menuop=login');
     exit();
}
?>