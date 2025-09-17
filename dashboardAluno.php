<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'aluno') {
    header("Location: login.php");
    exit;
}
require_once 'db_connect.php';

$mensagem = ''; // Mensagem para feedback do usuário

// ---------------- Lógica para Alteração de Senha ----------------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == 'alterar_senha') {
    $current_password = $_POST['senha_atual'];
    $new_password = $_POST['nova_senha'];
    $confirm_password = $_POST['confirmar_senha'];

    if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
        if ($new_password !== $confirm_password) {
            $mensagem = "Erro: As novas senhas não correspondem.";
        } else {
            // Busca a senha atual do aluno no banco de dados
            $sql = "SELECT senha FROM usuarios WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$_SESSION['usuario_id']]);
            $user = $stmt->fetch();

            if ($user && password_verify($current_password, $user['senha'])) {
                // Senha atual está correta, atualiza a senha no banco
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql_update = "UPDATE usuarios SET senha = ? WHERE id = ?";
                $stmt_update = $pdo->prepare($sql_update);
                if ($stmt_update->execute([$hashed_new_password, $_SESSION['usuario_id']])) {
                    $mensagem = "Senha alterada com sucesso!";
                } else {
                    $mensagem = "Erro ao alterar a senha.";
                }
            } else {
                $mensagem = "Erro: Senha atual incorreta.";
            }
        }
    } else {
        $mensagem = "Por favor, preencha todos os campos.";
    }
}

// ---------------- Lógica para Exibir Atividades ----------------
$aluno_id = $_SESSION['usuario_id'];
$turma_aluno = $_SESSION['usuario_turma'];

$sql_atividades = "SELECT titulo, descricao, data_criacao FROM atividades WHERE id_turma = ?";
$stmt_atividades = $pdo->prepare($sql_atividades);
$stmt_atividades->execute([$turma_aluno]);
$atividades = $stmt_atividades->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal do Aluno</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="icon.png" type="image/png">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h1>
        <p>Sua turma: <strong><?php echo htmlspecialchars($turma_aluno); ?></strong></p>

        <hr>

        <?php if (!empty($mensagem)): ?>
            <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>

        <h2>Alterar Senha</h2>
        <form action="dashboardAluno.php" method="POST">
            <input type="hidden" name="form_type" value="alterar_senha">
            <label for="senha_atual">Senha Atual:</label>
            <input type="password" id="senha_atual" name="senha_atual" required>
            
            <label for="nova_senha">Nova Senha:</label>
            <input type="password" id="nova_senha" name="nova_senha" required>

            <label for="confirmar_senha">Confirmar Nova Senha:</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required>

            <input type="submit" value="Alterar Senha">
        </form>

        <hr>

        <h2>Suas Atividades</h2>

        <?php if (!empty($atividades)): ?>
            <div class="atividades-list">
                <?php foreach ($atividades as $atividade): ?>
                    <div class="atividade-card">
                        <h3><?php echo htmlspecialchars($atividade['titulo']); ?></h3>
                        <p><strong>Data:</strong> <?php echo htmlspecialchars($atividade['data_criacao']); ?></p>
                        <p><?php echo htmlspecialchars($atividade['descricao']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Nenhuma atividade encontrada.</p>
        <?php endif; ?>

        <a href="logout.php">Sair</a>
    </div>
</body>
</html>