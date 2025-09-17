<?php
session_start();
// Garante que apenas o professor pode acessar
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'professor') {
    header("Location: login.php");
    exit();
}
require_once 'db_connect.php';
// Verifica se o ID e o tipo de item foram fornecidos
if (isset($_GET['id']) && isset($_GET['tipo'])) {
    $id = $_GET['id'];
    $tipo = $_GET['tipo'];
    try {
        if ($tipo === 'aluno') {
            $sql_delete = "DELETE FROM usuarios WHERE id = ? AND tipo = 'aluno'";
        } elseif ($tipo === 'atividade') {
            $sql_delete = "DELETE FROM atividades WHERE id = ?";
        } else {
            die("Tipo de exclusão inválido.");
        }
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$id]);
        // Redireciona de volta para o dashboard do professor após a exclusão
        header("Location: dashboardProfessor.php?mensagem=" . urlencode("Item excluído com sucesso!"));
        exit();
    } catch (PDOException $e) {
        die("Erro ao excluir o item: " . $e->getMessage());
    }
} else {
    die("ID ou tipo de item não fornecido para exclusão.");
}
?>