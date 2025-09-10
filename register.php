<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>
<body>

<h1>Cadastro de novo usuário</h1>

    <form method="POST">
        <label>Digite seu nome:</label>
        <input type="text" name="nome" required><br><br>

        <label>Digite seu email:</label>
        <input type="email" name="email" required><br><br>

        <label>Digite sua senha:</label>
        <input type="password" name="senha" required><br><br>

        <hr>

        <input type="submit" value="Cadastrar">
    </form>
    
    <p><a href="login.php">Já tem conta? Login</a></p>

</body>
</html>

<?php

require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim(string: $_POST["nome"]);
    $email = trim(string: $_POST["email"]);
    $senha = trim(string: $_POST["senha"]);

    if ($nome && $email && $senha) {
        $verifica = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $verifica->execute($email);

        //busca linha
        if ($verifica->fetch()) {
            echo "Erro ao cadastrar";
        }
        else {
            //criptografar senha
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $email, $senha]);

            echo "Cadastro realizado.";
        }
    }
    else {
        echo "Preencha os campos.";
    }
}


?>
