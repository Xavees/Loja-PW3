<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <title>Formulário de Cadastro de Produtos</title>
</head>
<body>
    <section>
        <a href = "produtos.php" class = "sombra">Ver Todos Os Produtos</a>
        <form action="" method = "post" enctype = "multipart/form-data">
            <h1>Envio de Imagens</h1>
            
            <label for="nome">Nome do Produto</label>
            <input type="text" name = "nome" class = "sombra" >
            
            <label for="desc">Descrição</label>
            <textarea name="desc" class="sombra"></textarea>
            
            <label for="valor">Valor</label>
            <input type = "text"
            name = "valor" class = "sombra">

            <input type="file" name = "foto[]" multiple class = "sombra meuInput">
            <input type="submit" id="botao">
        </form>
    </section>    

</body>
</html> 