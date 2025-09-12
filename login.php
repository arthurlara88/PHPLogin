?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>

        <?php if (!empty($erro)): ?>
            <p class="erro"><?php echo htmlspecialchars($erro); ?></p>
        <?php endif; ?>
        <?php if (isset($sucesso)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($sucesso); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            
            <input type="submit" value="Entrar">
        </form>
        <p><a href="register.php">Não tem uma conta? Cadastre-se</a></p>
    </div>
</body>
</html>
<?php

session_start(); //lembrar informações do login dos usuarios, cookie

require_once ("db_connect.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = trim($_POST["email"]); //trim remove espaços em branco apos o email, formatação
    $senha = $_POST["senha"];

    //consulta sql via PDO
    //? corresponde ao email, evita SQLInjection
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email ?");
    $stmt->execute([$email]); //executa a consulta

    //consulta primeira linha do resultado SQL e retorna false se nao encontrar
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario["senha"])) {
        $_SESSION["usuario_id"] = $usuario["id"]; //confere id
        $_SESSION["usuario_nome"] = $usuario["nome"]; //confere nome
        $_SESSION["usuario_email"] = $usuario["email"]; //confere email

        header("Location: index.php"); //redireciona para o index
    }
    else {
        $erro = "Email ou senha incorretos";
    }

    //fecha interação PDO
    //$stmt->close();
    //$pdo->close();
    }
?>
