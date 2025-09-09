<?php
require_once 'conexao.php';

$token = $_GET['token'] ?? '';
$mostrar_form = false;

if ($token){
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND expira_em > NOW()");
    $stmt->execute([$token]);
    $row = $stmt->fetch();

    if ($row) {
        $email = $row['email'];
        $mostrar_form = true;
    } else {
        $erro = "Token inválido ou expirado.";
    }
}
else {
    $erro = "Token não fornecido.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova = $_POST['nova_senha'] ?? '';
    $confirma = $_POST['confirma_senha'] ?? '';

    if ($nova !== $confirma) {
        $erro = "As senhas não coincidem.";
        $mostrar_form = true;
    } else {
        $hash = password_hash($nova, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE usuarios SET senha = ? WHERE email = ?")
            ->execute([$hash, $email]);
        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
        $sucesso = "Senha redefinida com sucesso.";
        $mostrar_form = false;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"><title>Redefinir Senha</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="container">

            <h2>Redefinir Senha</h2>

                <?php if (isset($erro)): ?>
                    <p style='color:red;'><?= htmlspecialchars($erro) ?></p>
                <?php elseif (isset($sucesso)): ?>
                    <p style='color:green;'><?= htmlspecialchars($sucesso) ?></p>
                <?php endif; ?>

            <?php if ($mostrar_form): ?>
            <form method="post">
                <label>Nova Senha:</label><input type="password" name="nova_senha" required><br>
                <label>Confirmar Senha:</label><input type="password" name="confirma_senha" required><br>
                <input type="submit" value="Redefinir">
            </form>
            <?php endif; ?>
            <p><a href="login.php">Login</a></p>

        </div>
    </body>
</html>