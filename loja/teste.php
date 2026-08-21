<?php
require 'classe\Produto.class.php';
$produto = new Produto();
$retorno = $produto->conecta();

if($retorno){
    echo "<script>
    alert('Conectado ao banco!')
    </script>";
}else{
    echo "Bancoindisponível. Tente mais tarde!";
}
echo "</h1>";
