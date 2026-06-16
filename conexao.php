<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "emporio_viajantes";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Erro na conexão: " . mysqli_connect_error());
}
?>