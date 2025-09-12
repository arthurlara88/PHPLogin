<?php

$server = "localhost";
$username = "root"; // ou "admin", dependendo da sua configuração
$password = "";
$dbname = "atividade"; // Nome do seu banco de dados

try {
    $pdo = new PDO("mysql:host=$server;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}