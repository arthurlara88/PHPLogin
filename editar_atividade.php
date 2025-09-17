<?php
session_start();
// Garante que apenas o professor pode acessar
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'professor') {
    header("Location: login.php");
    exit();
}
require_once 'db_connect.php';
$mensagem = '';
// ---------------- Lógica para buscar a atividade a ser editada ----------------
if (isset($_GET['id'])) {
    $id_atividade = $_GET['id'];
    $sql_atividade = "SELECT titulo, descricao, id_turma FROM atividades WHERE id = ?";
    $stmt_atividade = $pdo->prepare($sql_atividade);
    $stmt_atividade->execute([$id_atividade]);
    $atividade = $stmt_atividade->fetch(PDO::FETCH_ASSOC);
    if (!$atividade) {
        die("Atividade não encontrada.");
    }
} else {
    die("ID da atividade não fornecido.");
}
// ---------------- Lógica para atualizar a atividade ----------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo_novo = trim($_POST['titulo']);
    $descricao_nova = trim($_POST['descricao']);
    $id_turma_nova = trim($_POST['id_turma']);
    if (!empty($titulo_novo) && !empty($id_turma_nova)) {
        $sql_update = "UPDATE atividades SET titulo = ?, descricao = ?, id_turma = ? WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        if ($stmt_update->execute([$titulo_novo, $descricao_nova, $id_turma_nova, $id_atividade])) {
            $mensagem = "Atividade atualizada com sucesso!";
            $atividade['titulo'] = $titulo_novo;
            $atividade['descricao'] = $descricao_nova;
            $atividade['id_turma'] = $id_turma_nova;
        } else {
            $mensagem = "Erro ao atualizar atividade.";
        }
    } else {
        $mensagem = "Por favor, preencha todos os campos obrigatórios.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Atividade</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="icon.png" type="image/png">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h1>Editar Atividade</h1>
        <?php if (!empty($mensagem)): ?>
            <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>
        <form action="editar_atividade.php?id=<?php echo htmlspecialchars($id_atividade); ?>" method="POST">
            <label for="titulo">Título da Atividade:</label>
            <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($atividade['titulo']); ?>" required>
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($atividade['descricao']); ?></textarea>
            <label for="id_turma">Turma:</label>
            <input type="text" id="id_turma" name="id_turma" value="<?php echo htmlspecialchars($atividade['id_turma']); ?>" required>
            <input type="submit" value="Salvar Alterações">
        </form>
        <hr>
        <p><a href="dashboardProfessor.php">Voltar para o Dashboard</a></p>
    </div>
</body>
</html>