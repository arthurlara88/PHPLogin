<?php

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'db_connect.php';

// Obtém as informações do aluno logado
$aluno_id = $_SESSION['id_aluno'];

$sql_aluno = "SELECT nome, turma FROM alunos WHERE id = ?";

$stmt_aluno = $conn->prepare($sql_aluno);
$stmt_aluno->bind_param("i", $aluno_id);
$stmt_aluno->execute();
$result_aluno = $stmt_aluno->get_result();
$aluno = $result_aluno->fetch_assoc();

if (!$aluno) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$turma_aluno = $aluno['turma'];

//Busca as atividades da turma do aluno
$sql_atividades = "SELECT titulo, descricao, data_entrega FROM atividades WHERE id_turma = ?";
$stmt_atividades = $conn->prepare($sql_atividades);
$stmt_atividades->bind_param("s", $turma_aluno); // O 's' aqui é porque sua coluna 'turma' é uma string
$stmt_atividades->execute();
$result_atividades = $stmt_atividades->get_result();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal do Aluno</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo, <?php echo $aluno['nome']; ?>!</h1>
        <p>Sua turma: <strong><?php echo $turma_aluno; ?></strong></p>

        <hr>

        <h2>Suas Atividades</h2>

        <?php if ($result_atividades->num_rows > 0): ?>
            <div class="atividades-list">
                <?php while ($atividade = $result_atividades->fetch_assoc()): ?>
                    <div class="atividade-card">
                        <h3><?php echo $atividade['titulo']; ?></h3>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Nenhuma atividade encontrada.</p>
        <?php endif; ?>

        <a href="logout.php">Sair</a>
    </div>
</body>
</html>
