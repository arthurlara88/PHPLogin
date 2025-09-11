<?php
session_start();

// 1. VERIFICAÇÃO DE SEGURANÇA: Checa se o usuário está logado e se é um professor
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'professor') {
    // Se não for, nega o acesso
    die("Acesso negado.");
}

// 2. VERIFICAÇÃO DE MÉTODO: Checa se o formulário foi enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. INCLUSÃO E COLETA DE DADOS
    require_once 'conexao.php';
    
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $id_professor = $_SESSION['usuario_id']; // Pega o ID do professor que está logado

    // 4. PREPARAÇÃO DA CONSULTA SQL (USANDO PREPARED STATEMENTS PARA SEGURANÇA)
    // O uso de '?' previne injeção de SQL
    $sql = "INSERT INTO atividades (titulo, descricao, id_professor) VALUES (?, ?, ?)";
    
    // Prepara a consulta
    $stmt = $conexao->prepare($sql);
    
    // Verifica se a preparação foi bem-sucedida
    if ($stmt === false) {
        die("Erro ao preparar a consulta: " . $conexao->error);
    }
    
    // 'ssi' significa que estamos enviando duas strings (s) e um inteiro (i)
    $stmt->bind_param("ssi", $titulo, $descricao, $id_professor);
    
    // 5. EXECUÇÃO E REDIRECIONAMENTO
    if ($stmt->execute()) {
        // Se deu tudo certo, redireciona de volta para o dashboard
        header("Location: dashboard_professor.php");
        exit();
    } else {
        // Se deu erro
        echo "Erro ao salvar a atividade: " . $stmt->error;
    }
    
    // Fecha o statement e a conexão
    $stmt->close();
    $conexao->close();

} else {
    // Se tentarem acessar o arquivo diretamente, redireciona
    header("Location: dashboard_professor.php");
    exit();
}
?>