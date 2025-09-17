<?php

$server = "newton.coltec.ufmg.br";
$username = "a2024953241@teiacoltec.org"; // usuario
$password = "a2024953241";
$dbname = "a2024953241@teiacoltec.org"; //nome do seu banco de dados

//Tenta estabelecer conexao com o phpmyadmin
//Senao ele so encerra o processo
try {
    $pdo = new PDO("mysql:host=$server;dbname=$dbname;charset=utf8", $username, $password);

    //modo do db
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}