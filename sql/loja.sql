CREATE DATABASE  IF NOT EXISTS loja_db;
USE loja_db;

CREATE TABLE  IF NOT EXISTS produtos (
    id_produto int AUTO_INCREMENT PRIMARY KEY,
    nome_produto varchar(100) NOT NULL,
    descricao text NOT NULL,
    valor decimal(10,2) NOT NULL
);

CREATE TABLE   IF NOT EXISTS imagens (
    id_imagem int AUTO_INCREMENT PRIMARY KEY,
    nome_imagem varchar(100) NOT NULL,
    fk_id_produto int NOT NULL,
    FOREIGN KEY (fk_id_produto) REFERENCES produtos(id_produto)
        ON DELETE CASCADE
);
