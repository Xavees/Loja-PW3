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
        <a href="produtos.php" class="sombra">Ver Todos Os Produtos</a>
        <form action="" method="post" enctype="multipart/form-data">
            <h1>Envio de Imagens</h1>
            
            <label for="nome">Nome do Produto</label>
            <input type="text" name="nome" id="nome" class="sombra">
            
            <label for="desc">Descrição</label>
            <textarea name="desc" id="desc" class="sombra"></textarea>
            
            <label for="valor">Valor</label>
            <input type="text" name="valor" id="valor" class="sombra">

            <input type="file" name="foto[]" multiple class="sombra meuInput">
            <input type="submit" id="botao">
        </form>
    </section>    
</body>
</html> 

<?php
if(isset($_POST['nome'])){
    $nome        = addslashes($_POST['nome']);
    $valor       = addslashes($_POST['valor']);
    $descricao   = addslashes($_POST['desc']);

    // Cria o vetor para guardar o nome das fotos se o usuário enviar
    $fotos = array();

    // Checa se foi enviada alguma foto
    if(isset($_FILES['foto'])){
        for($i = 0; $i < count($_FILES['foto']['name']); $i++){
            $tipo = "";

            if($_FILES['foto']['type'][$i] == 'image/jpeg'){
                $tipo = ".jpg";
            } else if($_FILES['foto']['type'][$i] == 'image/png'){
                $tipo = ".png";
            } else {
                $tipo = "outro";
            }

            if($tipo == "outro"){
                echo "<script>alert('Só é possível enviar arquivos JPG e PNG')</script>";
            } else {
                $nome_arquivo = pathinfo($_FILES['foto']['name'][$i], PATHINFO_FILENAME) . rand(1, 999) . $tipo;
                // Corrigido para usar tmp_name
                move_uploaded_file($_FILES['foto']['tmp_name'][$i], 'imagens/' . $nome_arquivo);
                
                // Corrigido para usar o nome correto da variável ($fotos)
                array_push($fotos, $nome_arquivo);
            }
        }

        if(!empty($nome) && !empty($valor) && !empty($descricao)){
            require 'classe/Produto.class.php';
            $produto = new Produto();
            $retorno = $produto->conecta();

            if($retorno){
                $produto->enviarProduto($nome, $descricao, $valor, $fotos);
                echo "<script>alert('Produto enviado com sucesso!')</script>"; // Corrigida a tag script
            } else {
                echo "<script>alert('Banco indisponivel. Tente mais tarde!')</script>"; // Adicionado ponto e vírgula
            }
        }
    }
}
?>