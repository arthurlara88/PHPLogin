<?php

$host = 'localhost';
$dbname = 'a2024953241@teiacoltec.org';
$user = 'a2024953241@teiacoltec.org';
$pass = 'a2024953241';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //    echo " Conexão com o banco de dados realizada com sucesso!";
} catch (PDOException $e) {
    echo " Erro ao conectar com o banco de dados: " . $e->getMessage();
    exit;
}
