<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if ($nome && $email && $senha) {
        $verifica = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $verifica->execute([$email]);

        if ($verifica->fetch()) {
            $erro = "E-mail já cadastrado.";
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $email, $hash]);
            $sucesso = "Cadastro realizado com sucesso.";
        }
    } else {
        $erro = "Preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"><title>Cadastro</title>
        <link rel="stylesheet" href="styles.css">

    </head>
    <body>
        <div class="container">
            <h2>Cadastro</h2>

                <?php if (isset($erro)): ?>
                    <p style='color:red;'><?= htmlspecialchars($erro) ?></p>
                <?php elseif (isset($sucesso)): ?>
                    <p style='color:green;'><?= htmlspecialchars($sucesso) ?></p>
                <?php endif; ?>

            <form method="post">

                <label>Nome:</label><input type="text" name="nome" required><br>
                <label>E-mail:</label><input type="email" name="email" required><br>
                <label>Senha:</label><input type="password" name="senha" required><br>
                <input type="submit" value="Cadastrar">
                
            </form>
            <p><a href="login.php">Já tem conta? Login</a></p>
        </div>
    </body>
</html>