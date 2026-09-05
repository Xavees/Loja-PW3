<?php
require_once __DIR__ . '/classe/Produto.class.php';

function escapar($texto)
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

$produto = new Produto();
$produtos = array();
$erro = '';

if (!$produto->conecta()) {
    $erro = 'Banco indisponível. Confira a configuração e tente novamente.';
} else {
    try {
        $produtos = $produto->listarProdutos();
    } catch (Throwable $excecao) {
        $erro = 'Não foi possível carregar os produtos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <title>Produtos cadastrados</title>
</head>
<body>
    <main class="catalogo">
        <header class="cabecalho">
            <h1>Produtos cadastrados</h1>
            <a href="index.php" class="link-produtos">Cadastrar produto</a>
        </header>
        <?php if ($erro !== ''): ?>
            <p class="mensagem erro"><?= escapar($erro) ?></p>
        <?php elseif (empty($produtos)): ?>
            <p class="vazio">Nenhum produto foi cadastrado ainda.</p>
        <?php else: ?>
            <section class="grade-produtos">
                <?php foreach ($produtos as $item): ?>
                    <?php $imagens = $item['imagens'] ? explode('|', $item['imagens']) : array(); ?>
                    <article class="produto">
                        <?php if (!empty($imagens)): ?>
                            <img src="imagens/<?= rawurlencode($imagens[0]) ?>" alt="<?= escapar($item['nome_produto']) ?>">
                        <?php else: ?>
                            <div class="sem-imagem">Sem imagem</div>
                        <?php endif; ?>
                        <div class="produto-conteudo">
                            <h2><?= escapar($item['nome_produto']) ?></h2>
                            <p><?= nl2br(escapar($item['descricao'])) ?></p>
                            <strong>R$ <?= number_format((float) $item['valor'], 2, ',', '.') ?></strong>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
