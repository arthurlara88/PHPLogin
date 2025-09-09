<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        header("Location: index.php");
        exit;
    } else {
        $erro = "E-mail ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"><title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <!-- Mensagens de erro ou sucesso (prevenção SQLInjection) -->

            <?php if (isset($erro)): ?>
                <p class='erro'><?= htmlspecialchars($erro) ?></p>
            <?php elseif (isset($sucesso)): ?>
                <p class='sucesso'><?= htmlspecialchars($sucesso) ?></p>
            <?php endif; ?>

        <form method="post">
            <label>E-mail:</label>
            <input type="email" name="email" required>

            <label>Senha:</label>
            <input type="password" name="senha" required>

            <input type="submit" value="Entrar">
        </form>

        <nav>
            <a href="cadastro.php">Cadastre-se</a> |
            <a href="esqueci_senha.php">Esqueci minha senha</a>
        </nav>
    </div>
</body>

</html>