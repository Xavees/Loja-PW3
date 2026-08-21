CREATE DATABASE loja_db IF NOT EXISTS;
USE loja_db;

CREATE TABLE produtos IF NOT EXISTS(
    id_produto int AUTO_INCREMENT PRIMARY KEY,
    nome_produto varchar(100),
    descricao text,
    valor double
);

CREATE TABLE imagens IF NOT EXISTS(
    id_imagem int AUTO_INCREMENT PRIMARY KEY,
    nome_imagem varchar(100),
    fk_id_produto int,
    FOREIGN KEY(fk_id_produto)REFERENCES produtos(id_produto)
)
