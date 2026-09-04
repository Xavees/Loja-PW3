CREATE DATABASE  IF NOT EXISTS loja_db;
USE loja_db;

CREATE TABLE  IF NOT EXISTS produtos (
    id_produto int AUTO_INCREMENT PRIMARY KEY,
    nome_produto varchar(100),
    descricao text,
    valor double
);

CREATE TABLE   IF NOT EXISTS imagens (
    id_imagem int AUTO_INCREMENT PRIMARY KEY,
    nome_imagem varchar(100),
    fk_id_produto int,
    FOREIGN KEY(fk_id_produto)REFERENCES produtos(id_produto)
)
