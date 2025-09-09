<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'conexao.php';
require_once 'config.php';

require_once 'PHPMailer-master\src\LoggerInterface.php';

require_once 'Exception.php';
require_once 'PHPMailer.php';
require_once 'SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $token = bin2hex(random_bytes(16));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
        $pdo->prepare("INSERT INTO password_resets (email, token, expira_em) VALUES (?, ?, ?)")
            ->execute([$email, $token, $expira]);

        //ALtere esse dominio para o da OMQ
        // conserte o link se necessario para o server
        $link = "http://localhost/alterarSenha/redefinir_senha.php?token=$token";

        //Enviar e-mail com PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Mude as configurações do servidor SMTP para o PHPMail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; //SMTP do Gmail
            $mail->SMTPAuth   = true;
            
            //Altere o email e a senha de aplicativo no config.php
            $mail->Username = getenv('EMAIL_USER'); 
            $mail->Password = getenv('EMAIL_PASS');

            $mail->SMTPSecure = 'tls'; 
            $mail->Port       = 587;

            // Se o servidor tiver problemas com a verificação SSL/TLS
            // Um pouco arriscado, mas funcional
            $mail->SMTPOptions = array(
               'ssl' => array(
                 'verify_peer' => false,
                 'verify_peer_name' => false,
                 'allow_self_signed' => true
               )
             );


            // Mude esses endereços de email para o da OMQ 
            $mail->setFrom('arthurspider743@gmail.com', 'OMQ');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Redefinir sua senha';
            $mail->Body    = "
                <h3>Redefinição de Senha - Olimpíada Mineira de Química</h3>
                <p>Olá, clique no link abaixo para redefinir sua senha:</p>
                <p><a href='$link'>$link</a></p>
                <p>Se não solicitou isso, ignore este e-mail.</p>
            ";
            
            //caso não ler HTML
            $mail->AltBody = "Olá, acesse este link para redefinir sua senha: $link";

            $mail->send();
            $sucesso = "E-mail enviado com sucesso. Verifique sua caixa de entrada.";
        }
        catch (Exception $e){
            $erro = "Erro ao enviar e-mail: {$mail->ErrorInfo}";
        }
    }
    else {
        $erro = "E-mail não encontrado, por favor tente novamente ou comunique os autores.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Esqueci minha senha</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Esqueci minha senha</h2>

    <!-- sanitizar -->
    <?php if (isset($erro)): ?>
        <p class='erro'><?= htmlspecialchars($erro) ?></p>
    <?php elseif (isset($sucesso)): ?>
        <p class='sucesso'><?= htmlspecialchars($sucesso) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>E-mail:</label>
        <input type="email" name="email" required>
        <input type="submit" value="Enviar link de redefinição">
    </form>

    <p><a href="login.php">Voltar ao Login</a></p>
</div>
</body>
</html>