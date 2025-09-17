<?php
session_start();
// Garante que apenas o professor pode acessar
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'professor') {
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php';
$mensagem = '';

// ---------------- Lógica para buscar o aluno a ser editado ----------------
if (isset($_GET['id'])) {
    $id_aluno = $_GET['id'];
    $sql_aluno = "SELECT nome, email, turma FROM usuarios WHERE id = ? AND tipo = 'aluno'";
    $stmt_aluno = $pdo->prepare($sql_aluno);
    $stmt_aluno->execute([$id_aluno]);
    $aluno = $stmt_aluno->fetch(PDO::FETCH_ASSOC);
    if (!$aluno) {
        die("Aluno não encontrado.");
    }
} else {
    die("ID do aluno não fornecido.");
}
// ---------------- Lógica para atualizar o aluno ----------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_novo = trim($_POST['nome']);
    $email_novo = trim($_POST['email']);
    $turma_nova = trim($_POST['turma']);
    // Nao permite editar a senha
    if (!empty($nome_novo) && !empty($email_novo) && !empty($turma_nova)) {
        $sql_update = "UPDATE usuarios SET nome = ?, email = ?, turma = ? WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        if ($stmt_update->execute([$nome_novo, $email_novo, $turma_nova, $id_aluno])) {
            $mensagem = "Aluno atualizado com sucesso!";
            // Atualiza os dados do objeto aluno para que o formulário exiba as novas infos
            $aluno['nome'] = $nome_novo;
            $aluno['email'] = $email_novo;
            $aluno['turma'] = $turma_nova;
        } else {
            $mensagem = "Erro ao atualizar aluno.";
        }
    } else {
        $mensagem = "Por favor, preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Aluno</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="icon.png" type="image/png">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h1>Editar Aluno</h1>
        <?php if (!empty($mensagem)): ?>
            <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>
        <form action="editar_aluno.php?id=<?php echo htmlspecialchars($id_aluno); ?>" method="POST">
            <label for="nome">Nome do Aluno:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($aluno['nome']); ?>" required>
            <label for="email">Email do Aluno:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($aluno['email']); ?>" required>
            <label for="turma">Turma do Aluno:</label>
            <input type="text" id="turma" name="turma" value="<?php echo htmlspecialchars($aluno['turma']); ?>" required>
            <input type="submit" value="Salvar Alterações">
        </form>
        <hr>
        <p><a href="dashboardProfessor.php">Voltar para o Dashboard</a></p>
    </div>
</body>
</html>