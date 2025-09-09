<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"><title>Área Logada</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="container">
            <h2>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h2>
            <p>
                <a href="logout.php">Sair</a> | 
                <a href="redefinir_senha.php">Redefinir Senha</a>
            </p>
        </div>
    </body>
</html>
