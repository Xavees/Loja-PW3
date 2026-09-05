<?php
require __DIR__ . '/classe/Produto.class.php';
$produto = new Produto();
$retorno = $produto->conecta();

if($retorno){
    echo "<script>
    alert('Conectado ao banco!');
    </script>";
}else{
    echo "Banco indisponível. Tente mais tarde!";
}
