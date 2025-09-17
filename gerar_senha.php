<!-- Scrip de ia para gerar hash para o admin-->

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerador de Hash de Senha</title>
</head>
<body>
    <h1>Gerador de Hash de Senha</h1>
    <p>Digite a senha que você deseja usar para o administrador e clique em Gerar.</p>
    <form method="POST">
        <label>Senha:</label>
        <input type="password" name="senha_limpa" required>
        <button type="submit">Gerar Hash</button>
    </form>
    <hr>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['senha_limpa'])) {
        $senha_limpa = $_POST['senha_limpa'];
        $hash_segura = password_hash($senha_limpa, PASSWORD_DEFAULT);
        echo "<h2>Hash Gerado:</h2>";
        echo "<p>Copie e cole este valor no seu comando SQL para a senha do admin.</p>";
        echo "<code>" . htmlspecialchars($hash_segura) . "</code>";
    }
    ?>
</body>
</html>