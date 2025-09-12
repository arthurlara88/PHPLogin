<?php

$server = "localhost"; // local onde o db esta
$username = "root"; //nome do usuario
$password = ""; 

try {
    //sem especificar um banco de dados
    $pdo = new PDO("mysql:host=$server", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dbname = "atividade";
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $pdo->exec($sql);

    $pdo = null;
    $pdo = new PDO("mysql:host=$server;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql_users = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT(11) PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL
    )";
    $pdo->exec($sql_users);

} catch(PDOException $e) {
    die("Erro: " . $e->getMessage());
}

$pdo = null;