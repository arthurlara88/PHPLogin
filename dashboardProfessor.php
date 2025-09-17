<?php
session_start();
// Confere professor
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'professor') {
    header("Location: login.php");
    exit();
}
require_once 'db_connect.php';
$nome_professor = $_SESSION['usuario_nome'];
$id_professor = $_SESSION['usuario_id'];
$mensagem = ''; // feedback ao professor
$alunos = [];   // lista de alunos para exibir depois

// ---------------- Adicionar nova atividade ----------------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == 'atividade') {

    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $id_turma = trim($_POST['id_turma']);

    if (!empty($titulo) && !empty($id_turma)) {

        $sql_insert = "INSERT INTO atividades (titulo, descricao, id_professor, id_turma) VALUES (?, ?, ?, ?)";
        $stmt_insert = $pdo->prepare($sql_insert);

        if ($stmt_insert->execute([$titulo, $descricao, $id_professor, $id_turma])) {
            $mensagem = "Atividade adicionada com sucesso!";
        }
        else {
            $mensagem = "Erro ao adicionar atividade.";
        }
    }
    else {
        $mensagem = "Por favor, preencha todos os campos da atividade.";
    }
}
// ---------------- Registrar novo aluno ----------------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == 'aluno') {

    $nome_aluno = trim($_POST['nome_aluno']);
    $email_aluno = trim($_POST['email_aluno']);
    $senha_aluno = trim($_POST['senha_aluno']);
    $turma_aluno = trim($_POST['turma_aluno']);
    $tipo_aluno = 'aluno';

    if (!empty($nome_aluno) && !empty($email_aluno) && !empty($senha_aluno) && !empty($turma_aluno)) {
        $verifica_email = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $verifica_email->execute([$email_aluno]);
        
        if ($verifica_email->fetch()) {
            $mensagem = "Erro ao cadastrar: e-mail já em uso.";
        }
        else {

            //Criptografar a senha antes de salvar no banco
            $hash = password_hash($senha_aluno, PASSWORD_DEFAULT);
            $sql_insert = "INSERT INTO usuarios (nome, email, senha, tipo, turma) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);

            if ($stmt_insert->execute([$nome_aluno, $email_aluno, $hash, $tipo_aluno, $turma_aluno])) {
                $mensagem = "Aluno " . htmlspecialchars($nome_aluno) . " cadastrado com sucesso!";
            }
            else {
                $mensagem = "Erro ao cadastrar aluno.";
            }
        }
    }
    else {
        $mensagem = "Por favor, preencha todos os campos do aluno.";
    }
}

//Buscar lista de alunos
$sql_alunos = "SELECT nome, email, turma FROM usuarios WHERE tipo = 'aluno' ORDER BY turma, nome ASC";
$stmt_alunos = $pdo->query($sql_alunos);
$alunos = $stmt_alunos->fetchAll(PDO::FETCH_ASSOC);

// Lógica para buscar as atividades do professor logado (se houver essa necessidade) ou todas as atividades
$sql_atividades = "SELECT * FROM atividades ORDER BY data_criacao DESC";
$stmt_atividades = $pdo->query($sql_atividades);
$atividades = $stmt_atividades->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard do Professor</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="icon.png" type="image/png">
</head>
<body>
<div class="container" style="max-width: 800px;">
    <h1>Bem-vindo(a), Professor(a) <?php echo htmlspecialchars($nome_professor); ?>!</h1>
    <p><a href="logout.php">Sair</a></p>
    <hr>
    <?php if (!empty($mensagem)): ?>
        <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>
    <h2>Adicionar Novo Aluno</h2>
    <form action="dashboardProfessor.php" method="POST">

        <input type="hidden" name="form_type" value="aluno">
        <label for="nome_aluno">Nome do Aluno:</label>
        <input type="text" id="nome_aluno" name="nome_aluno" placeholder="Ex: Manoel Gomes" required>

        <label for="email_aluno">Email do Aluno:</label>
        <input type="email" id="email_aluno" name="email_aluno" placeholder="Ex: manoel@yahoo.com" required>

        <label for="senha_aluno">Senha do Aluno:</label>
        <input type="password" id="senha_aluno" name="senha_aluno" required>
        
        <label for="turma_aluno">Turma do aluno:</label>
        <input type="text" id="turma_aluno" name="turma_aluno" placeholder="Ex: 203" required>

        <input type="submit" value="Cadastrar Aluno">
    </form>
    <hr>
    <h2>Adicionar Nova Atividade</h2>
    <form action="dashboardProfessor.php" method="POST">

        <input type="hidden" name="form_type" value="atividade">
        <label for="titulo">Título da Atividade:</label>

        <input type="text" id="titulo" name="titulo" required>
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" placeholder="Ex: Atividade de PDO"></textarea>

        <label for="id_turma">Turma:</label>
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
                <th>Ações</th> </tr>
        </thead>
        <tbody>
            <?php if (!empty($alunos)): ?>
                <?php foreach ($alunos as $aluno): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                        <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                        <td><?php echo htmlspecialchars($aluno['turma']); ?></td>
                        <td>
                            <a href="editar_aluno.php?id=<?php echo $aluno['id']; ?>">Editar</a> |
                            <a href="excluir.php?id=<?php echo $aluno['id']; ?>&tipo=aluno">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">Nenhum aluno cadastrado.</td></tr> <?php endif; ?>
        </tbody>
    </table>

    <h2>Atividades Cadastradas</h2>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Turma</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($atividades)): ?>
                <?php foreach ($atividades as $atividade): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($atividade['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($atividade['id_turma']); ?></td>
                        <td>
                            <a href="editar_atividade.php?id=<?php echo $atividade['id']; ?>">Editar</a> |
                            <a href="excluir.php?id=<?php echo $atividade['id']; ?>&tipo=atividade">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">Nenhuma atividade cadastrada.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>