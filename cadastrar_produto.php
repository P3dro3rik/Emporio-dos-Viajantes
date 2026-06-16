<?php
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];
    $preco = $_POST["preco"];
    $estoque = $_POST["estoque"];
    $raridade = $_POST["raridade"];
    $imagem = $_POST["imagem"];
    $categoria_id = $_POST["categoria_id"];

    $sql = "INSERT INTO produtos
            (nome, descricao, preco, estoque, raridade, imagem, categoria_id)
            VALUES
            ('$nome', '$descricao', '$preco', '$estoque', '$raridade', '$imagem', '$categoria_id')";

    mysqli_query($conn, $sql);

    header("Location: listar_produtos.php");
    exit();
}

$categorias = mysqli_query($conn, "SELECT * FROM categorias");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h1>Cadastrar Produto</h1>

<form method="POST">

    <label>Nome:</label>
    <br>
    <input type="text" name="nome" required>
    <br><br>

    <label>Descrição:</label>
    <br>
    <textarea name="descricao"></textarea>
    <br><br>

    <label>Preço:</label>
    <br>
    <input type="number" step="0.01" name="preco" required>
    <br><br>

    <label>Estoque:</label>
    <br>
    <input type="number" name="estoque" required>
    <br><br>

    <label>Raridade:</label>
    <br>
    <select name="raridade">
        <option>Comum</option>
        <option>Raro</option>
        <option>Épico</option>
        <option>Lendário</option>
    </select>
    <br><br>

    <label>Imagem:</label>
    <br>
    <input type="text" name="imagem">
    <br><br>

    <label>Categoria:</label>
    <br>
    <select name="categoria_id">

        <?php while($categoria = mysqli_fetch_assoc($categorias)) { ?>

            <option value="<?= $categoria['id'] ?>">
                <?= $categoria['nome'] ?>
            </option>

        <?php } ?>

    </select>

    <br><br>

    <button type="submit">
        Salvar Produto
    </button>

</form>
<footer>
    <br>
    <a href="listar_produtos.php">Voltar para Lista de Produtos</a>
</footer>
</body>
</html>