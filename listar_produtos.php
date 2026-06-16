<?php
include("conexao.php");

$sql = "SELECT p.*, c.nome AS categoria
        FROM produtos p
        INNER JOIN categorias c
        ON p.categoria_id = c.id";

$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Produtos</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h1>Lista de Produtos</h1>

<a href="cadastrar_produto.php">
    Cadastrar Produto
</a>

<br><br>

<table border="1">

    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Preço</th>
        <th>Estoque</th>
        <th>Raridade</th>
        <th>Categoria</th>
        <th>Ações</th>
    </tr>

    <?php while($produto = mysqli_fetch_assoc($resultado)) { ?>

    <tr>
        <td><?= $produto['id'] ?></td>
        <td><?= $produto['nome'] ?></td>
        <td>G <?= $produto['preco'] ?></td>
        <td><?= $produto['estoque'] ?></td>
        <td><?= $produto['raridade'] ?></td>
        <td><?= $produto['categoria'] ?></td>

        <td>
            <a href="editar_produto.php?id=<?= $produto['id'] ?>">
                Editar
            </a>

            |

            <a href="excluir_produto.php?id=<?= $produto['id'] ?>">
                Excluir
            </a>
        </td>
    </tr>

    <?php } ?>

</table>
<footer>
    <br>
    <a href="index.php">Voltar</a>
</footer>
</body>
</html>