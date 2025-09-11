<?php
session_start();

// Verifica se o usuário está logado E se é um professor
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'professor') {
    header("Location: index.html");
    exit();
}

// Inclui o arquivo de conexão
require_once 'conexao.php';

// Pega o nome do professor da sessão
$nome_professor = $_SESSION['usuario_nome'];

// --- LÓGICA PARA BUSCAR OS ALUNOS ---
$sql_alunos = "SELECT id, nome, email FROM usuarios WHERE tipo = 'aluno' ORDER BY nome ASC";
$resultado_alunos = $conexao->query($sql_alunos);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard do Professor</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        h1, h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-container { background-color: #f9f9f9; padding: 15px; border: 1px solid #ddd; border-radius: 5px; margin-top: 30px; }
        .form-container input, .form-container textarea { width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box; }
        .form-container button { padding: 10px 15px; background-color: #28a745; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Bem-vindo, Professor <?php echo htmlspecialchars($nome_professor); ?>!</h1>
    <p>Esta é a sua área administrativa. <a href="logout.php">Sair</a></p>
    
    <hr>

    <h2>Alunos Cadastrados</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Verifica se a consulta retornou algum aluno
            if ($resultado_alunos->num_rows > 0) {
                // Loop para exibir cada aluno na tabela
                while($aluno = $resultado_alunos->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($aluno['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($aluno['email']) . "</td>";
                    echo "</tr>";
                }
            } else {
                // Se não houver alunos
                echo "<tr><td colspan='2'>Nenhum aluno cadastrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <hr>

    <div class="form-container">
        <h2>Adicionar Nova Atividade</h2>
        <form action="adicionar_atividade.php" method="POST">
            <label for="titulo">Título da Atividade:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="4"></textarea>

            <button type="submit">Salvar Atividade</button>
        </form>
    </div>
    
</body>
</html>


