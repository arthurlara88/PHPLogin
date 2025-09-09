<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email_input     = trim($_POST['email'] ?? '');
  $senha_atual     = $_POST['senha_atual']    ?? '';
  $nova_senha      = $_POST['nova_senha']     ?? '';
  $confirma_senha  = $_POST['confirma_senha'] ?? '';

  if ($nova_senha !== $confirma_senha) {
    $erro = "As novas senhas não coincidem.";
  }
  else {
    $usuario_id = $_SESSION['usuario_id'];
    $stmt = $pdo->prepare("SELECT email, senha FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $row = $stmt->fetch();

    if (!$row || strtolower($email_input) !== strtolower($row['email'])) {
      $erro = "O e-mail informado não confere com o cadastro.";
    }
    elseif (!password_verify($senha_atual, $row['senha'])) {
      $erro = "Senha atual incorreta.";
    }
    else {
      $novo_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
      $upd = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
      $upd->execute([$novo_hash, $usuario_id]);
      $sucesso = "Senha atualizada com sucesso!";
    }
  }
}

?>

<!DOCTYPE html>
<html>

  <head>
    <meta charset="UTF-8">
    <title>Alterar Senha</title>
    <link rel="stylesheet" href="styles.css">

  </head>
  <body>
    <div class="container">
      <h2>Alterar Senha</h2>

        <?php if (!empty($erro)): ?>
          <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
        <?php elseif (!empty($sucesso)): ?>
          <p style="color:green;"><?= htmlspecialchars($sucesso) ?></p>
        <?php endif; ?>

      <form method="post">
        <label>E-mail (do seu cadastro):</label>
        <input type="email" name="email" required><br>

        <label>Senha atual:</label>
        <input type="password" name="senha_atual" required><br>

        <label>Nova senha:</label>
        <input type="password" name="nova_senha" required><br>

        <label>Confirme a nova senha:</label>
        <input type="password" name="confirma_senha" required><br>
        <input type="submit" value="Atualizar senha">
        
      </form>
      <p><a href="index.php">Voltar</a></p>
    </div><div>
  </body>
</html>
