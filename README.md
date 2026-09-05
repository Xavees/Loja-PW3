# Loja

Projeto simples de cadastro e listagem de produtos em PHP com MySQL.

## Como executar

1. Inicie o Apache e o MySQL (por exemplo, pelo XAMPP).
2. Importe o arquivo `sql/loja.sql` no phpMyAdmin.
3. Coloque a pasta `loja` dentro de `htdocs` e acesse `http://localhost/loja/`.

Por padrão, a conexão usa MySQL em `localhost`, banco `loja_db`, usuário `root` e senha vazia. Se o seu ambiente for diferente, altere esses dados em `classe/Produto.class.php`.
