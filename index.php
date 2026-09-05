<?php
require_once __DIR__ . '/classe/Produto.class.php';

$mensagem = '';
$tipoMensagem = 'erro';
$nome = '';
$descricao = '';
$valor = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim(isset($_POST['nome']) ? $_POST['nome'] : '');
    $descricao = trim(isset($_POST['desc']) ? $_POST['desc'] : '');
    $valorInformado = trim(isset($_POST['valor']) ? $_POST['valor'] : '');
    $valor = $valorInformado;
    $valorNormalizado = str_replace(',', '.', $valorInformado);

    if ($nome === '' || $descricao === '' || $valorInformado === '') {
        $mensagem = 'Preencha nome, descrição e valor.';
    } elseif (!is_numeric($valorNormalizado) || (float) $valorNormalizado < 0) {
        $mensagem = 'Informe um valor válido.';
    } else {
        $fotos = array();
        $arquivosSalvos = array();
        $erroUpload = '';

        if (isset($_FILES['foto']) && is_array($_FILES['foto']['name'])) {
            $pastaImagens = __DIR__ . '/imagens';
            if (!is_dir($pastaImagens) && !mkdir($pastaImagens, 0775, true)) {
                $erroUpload = 'Não foi possível criar a pasta de imagens.';
            }

            $quantidade = count($_FILES['foto']['name']);
            for ($i = 0; $i < $quantidade && $erroUpload === ''; $i++) {
                $erro = $_FILES['foto']['error'][$i];
                if ($erro === UPLOAD_ERR_NO_FILE) {
                    continue;
                }
                if ($erro !== UPLOAD_ERR_OK) {
                    $erroUpload = 'Uma das imagens não pôde ser enviada.';
                    break;
                }

                if ($_FILES['foto']['size'][$i] > 5 * 1024 * 1024) {
                    $erroUpload = 'Cada imagem deve ter no máximo 5 MB.';
                    break;
                }

                $temporario = $_FILES['foto']['tmp_name'][$i];
                $mime = function_exists('mime_content_type') ? mime_content_type($temporario) : '';
                $extensoes = array('image/jpeg' => 'jpg', 'image/png' => 'png');
                if (!isset($extensoes[$mime])) {
                    $erroUpload = 'Envie somente imagens JPG ou PNG.';
                    break;
                }

                try {
                    $identificador = bin2hex(random_bytes(12));
                } catch (Exception $erro) {
                    $identificador = uniqid('', true);
                }
                $nomeArquivo = $identificador . '.' . $extensoes[$mime];
                $destino = $pastaImagens . DIRECTORY_SEPARATOR . $nomeArquivo;
                if (!move_uploaded_file($temporario, $destino)) {
                    $erroUpload = 'Não foi possível salvar uma das imagens.';
                    break;
                }

                $fotos[] = $nomeArquivo;
                $arquivosSalvos[] = $destino;
            }
        }

        if ($erroUpload !== '') {
            foreach ($arquivosSalvos as $arquivo) {
                if (is_file($arquivo)) {
                    unlink($arquivo);
                }
            }
            $mensagem = $erroUpload;
        } else {
            $produto = new Produto();
            if (!$produto->conecta()) {
                $mensagem = 'Banco indisponível. Confira a configuração e tente novamente.';
            } elseif ($produto->enviarProduto($nome, $descricao, (float) $valorNormalizado, $fotos)) {
                $mensagem = 'Produto cadastrado com sucesso!';
                $tipoMensagem = 'sucesso';
                $nome = $descricao = $valor = '';
            } else {
                $mensagem = 'Não foi possível cadastrar o produto.';
            }

            if ($tipoMensagem !== 'sucesso') {
                foreach ($arquivosSalvos as $arquivo) {
                    if (is_file($arquivo)) {
                        unlink($arquivo);
                    }
                }
            }
        }
    }
}

function escapar($texto)
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <title>Cadastro de produtos</title>
</head>
<body>
    <main class="painel">
        <a href="produtos.php" class="link-produtos">Ver todos os produtos</a>
        <form method="post" enctype="multipart/form-data">
            <h1>Cadastrar produto</h1>
            <?php if ($mensagem !== ''): ?>
                <p class="mensagem <?= $tipoMensagem ?>"><?= escapar($mensagem) ?></p>
            <?php endif; ?>
            <label for="nome">Nome do produto</label>
            <input type="text" name="nome" id="nome" maxlength="100" required value="<?= escapar($nome) ?>">
            <label for="desc">Descrição</label>
            <textarea name="desc" id="desc" required><?= escapar($descricao) ?></textarea>
            <label for="valor">Valor</label>
            <input type="number" name="valor" id="valor" min="0" step="0.01" required value="<?= escapar($valor) ?>">
            <label for="foto">Fotos (JPG ou PNG)</label>
            <input type="file" name="foto[]" id="foto" multiple accept="image/jpeg,image/png" class="arquivo">
            <button type="submit">Cadastrar</button>
        </form>
    </main>
</body>
</html>
