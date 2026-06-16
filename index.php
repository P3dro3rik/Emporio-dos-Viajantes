<?php
include("conexao.php"); 

$pesquisa = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$categoria_filtro = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;

$sql = "SELECT p.*, c.nome AS categoria_nome FROM produtos p 
        INNER JOIN categorias c ON p.categoria_id = c.id WHERE 1=1";

if (!empty($pesquisa)) {
    $sql .= " AND p.nome LIKE '%" . mysqli_real_escape_string($conn, $pesquisa) . "%'";
}
if ($categoria_filtro > 0) {
    $sql .= " AND p.categoria_id = $categoria_filtro";
}

$resultado_produtos = mysqli_query($conn, $sql);
$categorias = mysqli_query($conn, "SELECT * FROM categorias");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Empório dos Viajantes</title>
</head>


<style>
    body {
    font-family: 'Georgia', serif;
    background-image: linear-gradient(rgba(26, 21, 18, 0.85), rgba(26, 21, 18, 0.85)), url('fundoparede.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    color: #e6dfd3;
    margin: 0;
    padding: 40px;
    }
.logo-container {
    text-align: center;
    margin-bottom: 20px;
}

.logo {
    max-width: 250px; 
    height: auto;
}
header {
    border-bottom: 1px solid #c5a059;
    padding-bottom: 20px;
    margin-bottom: 30px;
    text-align: center;
}
    header {
        border-bottom: 1px solid #cac13f;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    h1 {
        color: black;
        margin: 0 0 15px 0;
    }
  
    input, select, button {
        background-color: #2a221d;
        color: #e6dfd3;
        border: 1px solid #5a3861;
        padding: 8px 12px;
        font-family: inherit;
    }
    button {
        cursor: pointer;
        background-color: #c5a059;
        color: #1a1512;
        font-weight: bold;
        border: none;
    }
    
    main {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
    }

    main > div {
        background-color: #2d2219;
        border: 1px solid #88873e;
        padding: 20px;
        width: 260px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    }

    main h3 {
        color: #c5a059;
        margin-top: 0;
    }

    main a {
        display: block;
        text-align: center;
        background-color: #535c24;
        color: white;
        text-decoration: none;
        padding: 10px;
        font-weight: bold;
        margin-top: 15px;
    }

    footer a {
        color: #a3854b;
        text-decoration: none;
        font-size: 0.9em;
    }
</style>

<body>

    <header>
    <div class="logo-container">
        <img src="Logo.png" alt="Empório dos Viajantes" class="logo">
    </div>
    
    <form method="GET" action="index.php">
            <input type="text" name="busca" placeholder="Pesquisar..." value="<?= htmlspecialchars($pesquisa) ?>">
            
            <select name="categoria">
                <option value="0">Todas as Categorias</option>
                <?php while($cat = mysqli_fetch_assoc($categorias)) { ?>
                    <option value="<?= $cat['id'] ?>" <?= $categoria_filtro == $cat['id'] ? 'selected' : '' ?>><?= $cat['nome'] ?></option>
                <?php } ?>
            </select>
            <button type="submit">Buscar</button>
        </form>
    </header>

    <main>
        <?php if (mysqli_num_rows($resultado_produtos) > 0) { 
            while($produto = mysqli_fetch_assoc($resultado_produtos)) { ?>
                <div>
                    <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                    <p><?= htmlspecialchars($produto['descricao']) ?></p>
                    <p>Raridade: <?= htmlspecialchars($produto['raridade']) ?></p>
                    <p>Preço: R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                    
                    <?php if ($produto['estoque'] > 0) { ?>
                        <p>Estoque: <?= $produto['estoque'] ?> un.</p>
                        <a href="comprar.php?id=<?= $produto['id'] ?>">Requisitar para Retirada</a>
                    <?php } else { ?>
                        <p>Esgotado</p>
                        <button disabled>Sem Estoque</button>
                    <?php } ?>
                </div>
                <hr>
            <?php } 
        } else { ?>
            <p>Nenhum produto encontrado.</p>
        <?php } ?>
    </main>

    <footer>
        <br>
        <a href="listar_produtos.php">Painel do Administrador</a>
    </footer>

</body>
</html>