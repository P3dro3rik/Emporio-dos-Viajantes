<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("conexao.php");


include("conexao.php");

$id = $_GET["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];
    $preco = $_POST["preco"];
    $estoque = $_POST["estoque"];
    $raridade = $_POST["raridade"];
    $imagem = $_POST["imagem"];
    $categoria_id = $_POST["categoria_id"];

    $sql = "UPDATE produtos SET
            nome='$nome',
            descricao='$descricao',
            preco='$preco',
            estoque='$estoque',
            raridade='$raridade',
            imagem='$imagem',
            categoria_id='$categoria_id'
            WHERE id='$id'";

    mysqli_query($conn, $sql);

    header("Location: listar_produtos.php");
    exit();
}

$sql = "SELECT * FROM produtos WHERE id = $id";
$resultado = mysqli_query($conn, $sql);
$produto = mysqli_fetch_assoc($resultado);

$categorias = mysqli_query($conn, "SELECT * FROM categorias");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h1>Editar Produto</h1>

<form method="POST">

    <input type="text" name="nome"
           value="<?= $produto['nome'] ?>" required>
    <br><br>

    <textarea name="descricao"><?= $produto['descricao'] ?></textarea>
    <br><br>

    <input type="number" step="0.01" name="preco"
           value="<?= $produto['preco'] ?>" required>
    <br><br>

    <input type="number" name="estoque"
           value="<?= $produto['estoque'] ?>" required>
    <br><br>

    <select name="raridade">

        <option <?= ($produto['raridade']=="Comum") ? "selected" : "" ?>>
            Comum
        </option>

        <option <?= ($produto['raridade']=="Raro") ? "selected" : "" ?>>
            Raro
        </option>

        <option <?= ($produto['raridade']=="Épico") ? "selected" : "" ?>>
            Épico
        </option>

        <option <?= ($produto['raridade']=="Lendário") ? "selected" : "" ?>>
            Lendário
        </option>

    </select>

    <br><br>

    <input type="text" name="imagem"
           value="<?= $produto['imagem'] ?>">
    <br><br>

    <select name="categoria_id">

        <?php while($categoria = mysqli_fetch_assoc($categorias)) { ?>

            <option
                value="<?= $categoria['id'] ?>"
                <?= ($categoria['id'] == $produto['categoria_id']) ? "selected" : "" ?>>

                <?= $categoria['nome'] ?>

            </option>

        <?php } ?>

    </select>

    <br><br>

    <button type="submit">
        Atualizar Produto
    </button>

</form>

</body>
</html>