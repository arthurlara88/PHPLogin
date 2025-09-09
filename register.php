<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>
<body>
    <form method="POST">
        <label>Digite seu nome:</label>
        <input type="text" name="nome" required>

        <label>Digite seu email:</label>
        <input type="novo_email" name="email" required>

        <label>Digite sua senha:</label>
        <input type="password" name="senha" required>

        <input type="submit" value="Cadastrar">
    </form>
    
    <p><a href="login.php">Já tem conta? Login</a></p>

</body>
</html>