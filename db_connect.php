<?php

$servername = "localhost"; // local onde o db esta
$username = "root"; //nome do usuario
$password = ""; 
$dbname = "atividade"; //nome do db

$conn = new mysqli($servername, $username, $password, $dbname);

//se nao ocorrer a conexao, nada do sistema ira ocorrer
if($conn ->connect_error){
    die("". $conn ->connect_error); //o die encerra o script
}

