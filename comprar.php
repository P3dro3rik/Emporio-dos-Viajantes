<?php
include("conexao.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$resultado = mysqli_query($conn, "SELECT * FROM produtos WHERE id = $id");
$produto = mysqli_fetch_assoc($resultado);

if (!$produto || $produto['estoque'] <= 0) {
    header("Location: index.php");
    exit();
}

$erro = "";
$sucesso = false;
$codigo_pedido = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $classe = $_POST['classe'];
    $quantidade = (int)$_POST['quantidade'];

    if (empty($nome)) {
        $erro = "O nome do aventureiro é obrigatório para a retirada!";
    } elseif ($quantidade > $produto['estoque'] || $quantidade <= 0) {
        $erro = "Quantidade indisponível no estoque!";
    } else {
        // Gera o código único do pedido para apresentação na retirada
        $codigo_pedido = "RET-" . strtoupper(bin2hex(random_bytes(4)));
        
        // Atualiza e decrementa automaticamente o estoque físico
        mysqli_query($conn, "UPDATE produtos SET estoque = estoque - $quantidade WHERE id = $id");

        $sucesso = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Retirada</title>
    <script>
        function calcularTotalDinamico(quantidade) {
            const precoUnitario = <?= $produto['preco'] ?>;
            const total = precoUnitario * quantidade;
            document.getElementById('total_exibicao').innerText = total.toFixed(2).replace('.', ',');
        }
    </script>
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

    h1, h3, h4 {
        color: #c5a059;
    }

    a {
        color: #c5a059;
        text-decoration: none;
    }

    /* Alinha o formulário e o resumo lado a lado */
    .checkout-container {
        display: flex;
        flex-wrap: wrap;
        gap: 50px;
        margin-top: 30px;
    }

    /* Formatação dos campos do Formulário */
    form div {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #a3854b;
    }

    input, select {
        background-color: #2a221d;
        color: #e6dfd3;
        border: 1px solid #5a4a3f;
        padding: 10px;
        width: 100%;
        max-width: 300px;
        font-family: inherit;
    }

    /* Botão de confirmação */
    button[type="submit"] {
        background-color: #8c2d19;
        color: white;
        border: none;
        padding: 12px 20px;
        font-weight: bold;
        font-family: inherit;
        cursor: pointer;
        width: 100%;
        max-width: 320px;
    }

    /* Caixa de Resumo do Item */
    .resumo-box {
        background-color: #241c18;
        border: 1px dashed #c5a059;
        padding: 25px;
        min-width: 280px;
        height: fit-content;
    }

    /* Mensagens de Sucesso ou Erro */
    .sucesso-box {
        background-color: #1c2e1a;
        border: 1px solid #3e633a;
        padding: 20px;
    }
</style>


<body>

    <h1>Confirmar Ordem de Retirada</h1>

    <?php if ($sucesso) { ?>
        <div>
            <h2>Reserva Confirmada com Sucesso!</h2>
            <p>Apresente este código no balcão do empório para retirar seu item:</p>
            <p><strong>Código de Retirada: <?= $codigo_pedido ?></strong></p>
            <a href="index.php">[ Voltar ao Catálogo ]</a>
        </div>
    <?php } else { ?>
        <a href="index.php"><- Voltar ao Catálogo</a>
        
        <?php if (!empty($erro)) echo "<p style='color:red;'>$erro</p>"; ?>

        <div>
            <form method="POST">
                <div>
                    <label>Nome do Aventureiro (Obrigatório):</label>
                    <input type="text" name="nome" required>
                </div>
                <br>
                <div>
                    <label>Classe do Personagem:</label>
                    <select name="classe">
                        <option value="Guerreiro">Guerreiro</option>
                        <option value="Mago">Mago</option>
                        <option value="Arqueiro">Arqueiro</option>
                        <option value="Ladino">Ladino</option>
                        <option value="Clérigo">Clérigo</option>
                    </select>
                </div>
                <br>
                <div>
                    <label>Quantidade para Retirada:</label>
                    <input type="number" name="quantidade" value="1" min="1" max="<?= $produto['estoque'] ?>" oninput="calcularTotalDinamico(this.value)" required>
                </div>
                <br>
                <button type="submit">Reservar para Retirada</button>
            </form>

            <hr>

            <div>
                <h3>Artefato Selecionado</h3>
                <p><strong>Produto:</strong> <?= htmlspecialchars($produto['nome']) ?></p>
                <p><strong>Preço Unitário:</strong> R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                <p><strong>Estoque Disponível na Loja:</strong> <?= $produto['estoque'] ?> unidades</p>
                <h4>Total a Pagar na Retirada: R$ <span id="total_exibicao"><?= number_format($produto['preco'], 2, ',', '.') ?></span></h4>
            </div>
        </div>
    <?php } ?>

</body>
</html>