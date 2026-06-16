<?php
include("conexao.php");

$id = $_GET["id"];

$sql = "DELETE FROM produtos WHERE id = $id";

mysqli_query($conn, $sql);

header("Location: listar_produtos.php");
exit();