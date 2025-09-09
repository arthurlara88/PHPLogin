<?php

//Alterar TUDO isso para o servidor
// Essas configurações são apenas para teste localhost, mude na OMQ
$host = 'localhost'; // Trocar pelo IP do server
$db   = 'alterarSenha'; // Colocar o db dos participantes OMQ
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

#excessao
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Falha na conexão com o banco: ' . $e->getMessage());
}
?>
