<?php
require_once "db_connect.php"; // Correto, usando a nova conexão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);
    $tipo = $_POST["tipo"];
    $turma = ($tipo == 'aluno') ? trim($_POST["turma"]) : null;

    if ($nome && $email && $senha && $tipo) {
        $verifica = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $verifica->execute([$email]);

        if ($verifica->fetch()) {
            echo "Erro ao cadastrar: este e-mail já está em uso.";
        } else {
            // AQUI ESTÁ A CORREÇÃO DE SEGURANÇA: Criptografar a senha!
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo, turma) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $hash, $tipo, $turma]);

            echo "Cadastro realizado com sucesso!";
        }
    } else {
        echo "Preencha todos os campos obrigatórios.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro de novo usuário</h1>
        <form method="POST">
            <label>Digite seu nome:</label>
            <input type="text" name="nome" required>

            <label>Digite seu email:</label>
            <input type="email" name="email" required>

            <label>Digite sua senha:</label>
            <input type="password" name="senha" required>

            <label>Tipo de usuário:</label>
            <select name="tipo" id="tipo" required>
                <option value="aluno">Aluno</option>
                <option value="professor">Professor</option>
            </select>

            <label for="turma_input">Turma (apenas para alunos):</label>
            <input type="text" name="turma" id="turma_input">

            <hr>

            <input type="submit" value="Cadastrar">
        </form>

        <p><a href="login.php">Já tem conta? Login</a></p>
    </div>
</body>
</html>