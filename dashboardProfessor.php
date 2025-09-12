<?php
session_start();

// Garante que apenas o professor pode acessar esta página
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'professor') {
    header("Location: login.php");
    exit();
}

require_once 'db_connect.php';

$nome_professor = $_SESSION['usuario_nome'];
$id_professor = $_SESSION['usuario_id'];
$mensagem_atividade = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['titulo'])) {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $id_turma = trim($_POST['id_turma']);

    if (!empty($titulo) && !empty($id_turma)) {
        $sql_insert = "INSERT INTO atividades (titulo, descricao, id_professor, id_turma) VALUES (?, ?, ?, ?)";
        $stmt_insert = $pdo->prepare($sql_insert);
        if ($stmt_insert->execute([$titulo, $descricao, $id_professor, $id_turma])) {
            $mensagem_atividade = "Atividade adicionada com sucesso!";
        } else {
            $mensagem_atividade = "Erro ao adicionar atividade.";
        }
    } else {
        $mensagem_atividade = "Por favor, preencha todos os campos da atividade.";
    }
}

// Busca todos os alunos
$sql_alunos = "SELECT id, nome, email, turma FROM usuarios WHERE tipo = 'aluno' ORDER BY turma, nome ASC";
$stmt_alunos = $pdo->query($sql_alunos);
$alunos = $stmt_alunos->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard do Professor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 800px;">
        <h1>Bem-vindo, Professor <?php echo htmlspecialchars($nome_professor); ?>!</h1>
        <p>Esta é a sua área administrativa. <a href="logout.php">Sair</a></p>

        <hr>

        <h2>Adicionar Nova Atividade</h2>
        <?php if (!empty($mensagem_atividade)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($mensagem_atividade); ?></p>
        <?php endif; ?>
        <form action="dashboardProfessor.php" method="POST">
            <label for="titulo">Título da Atividade:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao"></textarea>

            <label for="id_turma">Para a Turma:</label>
            <input type="text" id="id_turma" name="id_turma" placeholder="Ex: 3A" required>

            <input type="submit" value="Salvar Atividade">
        </form>

        <hr>

        <h2>Alunos Cadastrados</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Turma</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($alunos)): ?>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['turma']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">Nenhum aluno cadastrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>