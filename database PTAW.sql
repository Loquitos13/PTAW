CREATE DATABASE IF NOT EXISTS ptaw;
USE ptaw;


CREATE TABLE Admins (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nome_admin VARCHAR(255) NOT NULL,
    email_admin VARCHAR(255) NOT NULL,
    pass_admin VARCHAR(255) NOT NULL,
    contacto_admin VARCHAR(255) NOT NULL,
    funcao_admin VARCHAR(255) NOT NULL
);


CREATE TABLE Clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome_cliente VARCHAR(255) NOT NULL,
    email_cliente VARCHAR(255) NOT NULL,
    pass_cliente VARCHAR(255) NOT NULL,
    contacto_cliente VARCHAR(255) NOT NULL,
    morada_cliente VARCHAR(255) NOT NULL,
    nif_cliente VARCHAR(255) NOT NULL,
    id_gift INT,
    id_favoritos INT,
    id_boletim INT
);


CREATE TABLE Categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    titulo_categoria VARCHAR(255) NOT NULL,
    descricao_categoria TEXT NOT NULL,
    id_tipo_dimensao INT NOT NULL
);


CREATE TABLE Produtos (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT NOT NULL,
    titulo_produto VARCHAR(255) NOT NULL,
    descricao_produto TEXT NOT NULL,
    imagem_principal VARCHAR(255) NOT NULL,
    preco_produto DECIMAL(10, 2) NOT NULL,
    stock_produto INT NOT NULL,
    keywords_produto VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_categoria) REFERENCES Categorias(id_categoria)
);


CREATE TABLE ImagemProdutos (
    id_imagem INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT NOT NULL,
    imagem_produto VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_produto) REFERENCES Produtos(id_produto)
);


CREATE TABLE Dimensoes (
    id_tipo_dimensao INT AUTO_INCREMENT PRIMARY KEY,
    nome_tipo VARCHAR(255) NOT NULL
);


CREATE TABLE Cores (
    id_cor INT AUTO_INCREMENT PRIMARY KEY,
    nome_cor VARCHAR(255) NOT NULL,
    hex_cor VARCHAR(7) NOT NULL
);


CREATE TABLE Carrinhos (
    id_carrinho INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente)
);


CREATE TABLE CarrinhoItens (
    id_carrinho_item INT AUTO_INCREMENT PRIMARY KEY,
    id_carrinho INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_carrinho) REFERENCES Carrinhos(id_carrinho),
    FOREIGN KEY (id_produto) REFERENCES Produtos(id_produto)
);


CREATE TABLE Encomendas (
    id_encomenda INT AUTO_INCREMENT PRIMARY KEY,
    id_carrinho INT NOT NULL,
    preco_total_encomenda DECIMAL(10, 2) NOT NULL,
    status VARCHAR(255) NOT NULL,
    data_criacao_encomenda DATETIME NOT NULL,
    data_rececao_encomenda DATETIME,
    FOREIGN KEY (id_carrinho) REFERENCES Carrinhos(id_carrinho)
);

CREATE TABLE Pagamento (
    id_pagamento INT AUTO_INCREMENT PRIMARY KEY,
    id_encomenda INT NOT NULL,
    valor_pago DECIMAL(10, 2) NOT NULL,
    data_pagamento DATETIME NOT NULL,
    FOREIGN KEY (id_encomenda) REFERENCES Encomendas(id_encomenda)
);


CREATE TABLE MetodoPagamento (
    id_metodo_pagamento INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_cartao INT,
    id_mbway INT,
    id_paypal INT,
    FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente)
);


CREATE TABLE PagamentoCartao (
    id_cartao INT AUTO_INCREMENT PRIMARY KEY,
    numero_cartao VARCHAR(16) NOT NULL,
    validade_cartao VARCHAR(7) NOT NULL,
    cvv_cartao VARCHAR(4) NOT NULL,
    nome_cartao VARCHAR(255) NOT NULL
);


CREATE TABLE PagamentoMbway (
    id_mbway INT AUTO_INCREMENT PRIMARY KEY,
    telemovel_mbway VARCHAR(15) NOT NULL
);


CREATE TABLE PagamentoPaypal (
    id_paypal INT AUTO_INCREMENT PRIMARY KEY,
    email_paypal VARCHAR(255) NOT NULL
);

CREATE TABLE Personalizacoes (
    id_personalizacao INT AUTO_INCREMENT PRIMARY KEY,
    id_carrinho_item INT NOT NULL,
    imagem_personalizada VARCHAR(255),
    mensagem_personalizada VARCHAR(255),
    FOREIGN KEY (id_carrinho_item) REFERENCES CarrinhoItens(id_carrinho_item)
);


CREATE TABLE Reviews (
    id_review INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_produto INT NOT NULL,
    comentario TEXT NOT NULL,
    classificacao INT NOT NULL,
    data_review DATETIME NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente),
    FOREIGN KEY (id_produto) REFERENCES Produtos(id_produto)
);