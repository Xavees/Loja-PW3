<?php

class Produto
{
    private $pdo;

    public function conecta()
    {
        try {
            $dsn = 'mysql:host=localhost;dbname=loja_db;charset=utf8mb4';
            $this->pdo = new PDO($dsn, 'root', '', array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ));
            return true;
        } catch (PDOException $erro) {
            $this->pdo = null;
            return false;
        }
    }

    public function enviarProduto($nome, $descricao, $valor, array $fotos = array())
    {
        if (!$this->pdo) {
            throw new RuntimeException('Não há conexão com o banco de dados.');
        }

        try {
            $this->pdo->beginTransaction();
            $comando = $this->pdo->prepare(
                'INSERT INTO produtos (nome_produto, descricao, valor) VALUES (:nome, :descricao, :valor)'
            );
            $comando->execute(array(':nome' => $nome, ':descricao' => $descricao, ':valor' => $valor));

            $idProduto = $this->pdo->lastInsertId();
            $comandoImagem = $this->pdo->prepare(
                'INSERT INTO imagens (nome_imagem, fk_id_produto) VALUES (:imagem, :produto)'
            );
            foreach ($fotos as $foto) {
                $comandoImagem->execute(array(':imagem' => $foto, ':produto' => $idProduto));
            }

            $this->pdo->commit();
            return true;
        } catch (Throwable $erro) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }

    public function listarProdutos()
    {
        if (!$this->pdo) {
            throw new RuntimeException('Não há conexão com o banco de dados.');
        }

        $sql = "SELECT p.id_produto, p.nome_produto, p.descricao, p.valor,
                       GROUP_CONCAT(i.nome_imagem ORDER BY i.id_imagem SEPARATOR '|') AS imagens
                FROM produtos p
                LEFT JOIN imagens i ON i.fk_id_produto = p.id_produto
                GROUP BY p.id_produto, p.nome_produto, p.descricao, p.valor
                ORDER BY p.id_produto DESC";
        return $this->pdo->query($sql)->fetchAll();
    }
}
