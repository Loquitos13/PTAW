CREATE DATABASE IF NOT EXISTS `PTAW-2025-GR4`;
USE `PTAW-2025-GR4`;

INSERT INTO Admins(id_admin, nome_admin, email_admin, pass_admin, contacto_admin, funcao_admin, data_criacao_admin)
VALUES (1, "Geral", "geral@printandgo.pt", "senha123", "912345678", "teste", '2025-05-18');


CREATE TABLE Admins (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nome_admin VARCHAR(255) NOT NULL,
    email_admin VARCHAR(255) NOT NULL UNIQUE,
    pass_admin VARCHAR(255) NOT NULL,
    contacto_admin VARCHAR(255),
    funcao_admin VARCHAR(255) NOT NULL,
    data_criacao_admin DATE NOT NULL
);

CREATE TABLE Clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome_cliente VARCHAR(255) NOT NULL,
    email_cliente VARCHAR(255) NOT NULL UNIQUE,
    pass_cliente VARCHAR(255) NOT NULL,
    contacto_cliente VARCHAR(15),
    morada_cliente VARCHAR(255),
    cidade_cliente VARCHAR(255),
    state_cliente VARCHAR(255),
    cod_postal_cliente VARCHAR(255),
    pais_cliente VARCHAR(255),
    nif_cliente VARCHAR(9),
    --ip_cliente VARCHAR(45) NOT NULL UNIQUE,
    ip_cliente VARCHAR(45) UNIQUE,
    imagem_cliente VARCHAR(255),
    id_gift INT,
    id_favoritos INT,
    id_boletim INT,
    id_google VARCHAR(255) UNIQUE,
    id_facebook VARCHAR(255) UNIQUE,
    data_criacao_cliente DATETIME NOT NULL
);

CREATE TABLE Cores (
    id_cor INT AUTO_INCREMENT PRIMARY KEY,
    nome_cor VARCHAR(255) NOT NULL,
    hex_cor VARCHAR(7) NOT NULL
);

CREATE TABLE Categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    titulo_categoria VARCHAR(255) NOT NULL,
    descricao_categoria TEXT NOT NULL,
    status_categoria BOOLEAN DEFAULT 0
);

CREATE TABLE Produtos (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT NOT NULL,
    data_criacao_produto DATETIME NOT NULL,
    titulo_produto VARCHAR(255) NOT NULL,
    modelo3d_produto VARCHAR(255),
    descricao_produto TEXT NOT NULL,
    imagem_principal VARCHAR(255) NOT NULL,
    preco_produto DECIMAL(10, 2) NOT NULL,
    stock_produto INT NOT NULL,
    keywords_produto VARCHAR(255) NOT NULL,
    status_produto BOOLEAN DEFAULT 0,
    FOREIGN KEY (id_categoria)
        REFERENCES Categorias(id_categoria)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE TABLE Dimensoes (
    id_dimensao INT AUTO_INCREMENT PRIMARY KEY,
    dimensao_tipo VARCHAR(255) NOT NULL,
    tamanho VARCHAR(255) NOT NULL,
    id_produto INT NOT NULL,
    CONSTRAINT fk_produto
        FOREIGN KEY (id_produto)
        REFERENCES Produtos(id_produto)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE TABLE ImagemProdutos (
    id_imagem_extra INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT NOT NULL,
    imagem_extra VARCHAR(255) NOT NULL,
    imagem_extra_2 VARCHAR(255),
    imagem_extra_3 VARCHAR(255),
    FOREIGN KEY (id_produto)
        REFERENCES Produtos(id_produto)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE ProdutosVariantes (
    id_produto_variante INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT NOT NULL,
    id_cor INT NOT NULL,
    promocao DECIMAL(5, 2) DEFAULT 0,
    FOREIGN KEY (id_produto)
        REFERENCES Produtos(id_produto)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_cor)
        REFERENCES Cores(id_cor)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE TABLE Carrinhos (
    id_carrinho INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    ip_cliente VARCHAR(45),
    FOREIGN KEY (id_cliente)
        REFERENCES Clientes(id_cliente)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE CarrinhoItens (
    id_carrinho_item INT AUTO_INCREMENT PRIMARY KEY,
    id_carrinho INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    tamanho VARCHAR(255) NOT NULL,
    cor VARCHAR(50) NOT NULL,
    personalizado BOOLEAN DEFAULT 0 NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_carrinho)
        REFERENCES Carrinhos(id_carrinho)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_produto)
        REFERENCES Produtos(id_produto)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE TABLE Encomendas (
    id_encomenda INT AUTO_INCREMENT PRIMARY KEY,
    id_carrinho INT NOT NULL,
    preco_total_encomenda DECIMAL(10, 2) NOT NULL,
    fatura VARCHAR(255) NOT NULL,
    status_encomenda VARCHAR(50) NOT NULL,
    data_criacao_encomenda DATETIME NOT NULL,
    data_rececao_encomenda DATETIME,
    FOREIGN KEY (id_carrinho)
        REFERENCES Carrinhos(id_carrinho)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE TABLE EncomendaItens (
    id_encomenda_item INT AUTO_INCREMENT PRIMARY KEY,
    id_encomenda INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    nome_cor VARCHAR(50) NOT NULL,
    tamanho VARCHAR(255) NOT NULL,
    id_personalizacao INT,
    FOREIGN KEY (id_encomenda) REFERENCES Encomendas(id_encomenda) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES Produtos(id_produto) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_personalizacao) REFERENCES Personalizacao(id_personalizacao) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE PagamentoCartao (
    id_cartao INT AUTO_INCREMENT PRIMARY KEY,
    numero_cartao VARCHAR(255) NOT NULL,
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

CREATE TABLE MetodoPagamento (
    id_metodo_pagamento INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_cartao INT,
    id_mbway INT,
    id_paypal INT,
    FOREIGN KEY (id_cliente)
        REFERENCES Clientes(id_cliente)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_cartao)
        REFERENCES PagamentoCartao(id_cartao)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_mbway)
        References PagamentoMbway(id_mbway)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_paypal)
        References PagamentoPaypal(id_paypal)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Pagamento (
    id_pagamento INT AUTO_INCREMENT PRIMARY KEY,
    id_encomenda INT NOT NULL,
    id_metodo_pagamento INT NOT NULL,
    valor_pago DECIMAL(10, 2) NOT NULL,
    data_pagamento DATETIME NOT NULL,
    FOREIGN KEY (id_encomenda)
        REFERENCES Encomendas(id_encomenda)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY (id_metodo_pagamento)
        REFERENCES MetodoPagamento(id_metodo_pagamento)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE TABLE Personalizacao (
    id_personalizacao INT AUTO_INCREMENT PRIMARY KEY,
    id_carrinho_item INT NOT NULL,
    imagem_escolhida VARCHAR(255) NOT NULL,
    modelo3d_personalizado VARCHAR(255) NOT NULL,
    mensagem_personalizada VARCHAR(255),
    preco_personalizacao DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_carrinho_item)
        REFERENCES CarrinhoItens(id_carrinho_item)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Reviews (
    id_review INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    nome_cliente VARCHAR(200) NOT NULL,
    id_produto INT NOT NULL,
    comentario TEXT NOT NULL,
    classificacao INT NOT NULL,
    data_review DATETIME NOT NULL,
    recommend BOOLEAN DEFAULT 0,
    FOREIGN KEY (id_cliente)
        REFERENCES Clientes(id_cliente)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_produto)
        REFERENCES Produtos(id_produto)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    avatar VARCHAR(255),
    role ENUM('admin', 'user', 'manager') DEFAULT 'user',
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS user_notification_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    email_notifications BOOLEAN DEFAULT true,
    sms_notifications BOOLEAN DEFAULT false,
    push_notifications BOOLEAN DEFAULT true,
    security_alerts BOOLEAN DEFAULT true,
    marketing_emails BOOLEAN DEFAULT false,
    weekly_reports BOOLEAN DEFAULT true,
    system_updates BOOLEAN DEFAULT true,
    team_invitations BOOLEAN DEFAULT true,
    billing_alerts BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS user_security_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    login_alerts BOOLEAN DEFAULT true,
    session_timeout INT DEFAULT 3600,
    password_change_required BOOLEAN DEFAULT false,
    password_expires_at DATE NULL,
    login_attempts_limit INT DEFAULT 5,
    ip_restriction BOOLEAN DEFAULT false,
    allowed_ips TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
-- Create Teams table
CREATE TABLE Teams (
    id_team INT PRIMARY KEY AUTO_INCREMENT,
    nome_team VARCHAR(100) NOT NULL,
    descricao_team TEXT,
    data_criacao_team DATETIME DEFAULT CURRENT_TIMESTAMP,
    status_team ENUM('active', 'inactive') DEFAULT 'active',
    created_by_admin INT,
    FOREIGN KEY (created_by_admin) REFERENCES Admins(id_admin)
);

-- Create TeamMembers table
CREATE TABLE TeamMembers (
    id_team_member INT PRIMARY KEY AUTO_INCREMENT,
    id_team INT NOT NULL,
    id_cliente INT NOT NULL,
    role_member ENUM('member', 'admin') DEFAULT 'member',
    data_adicao DATETIME DEFAULT CURRENT_TIMESTAMP,
    status_member ENUM('active', 'inactive') DEFAULT 'active',
    FOREIGN KEY (id_team) REFERENCES Teams(id_team) ON DELETE CASCADE,
    FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente) ON DELETE CASCADE,
    UNIQUE KEY unique_team_member (id_team, id_cliente)
);

CREATE TABLE IF NOT EXISTS billing_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_type ENUM('free', 'basic', 'premium', 'enterprise') DEFAULT 'free',
    billing_cycle ENUM('monthly', 'yearly') DEFAULT 'monthly',
    price DECIMAL(10,2) DEFAULT 0.00,
    currency VARCHAR(3) DEFAULT 'USD',
    payment_method ENUM('credit_card', 'paypal', 'bank_transfer') NULL,
    card_last_four VARCHAR(4) NULL,
    card_brand VARCHAR(20) NULL,
    next_billing_date DATE NULL,
    status ENUM('active', 'cancelled', 'past_due', 'trialing') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS billing_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'EUR',
    description VARCHAR(255),
    transaction_id VARCHAR(100),
    payment_method VARCHAR(50),
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS user_activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT IGNORE INTO user_notification_settings (user_id)
SELECT id FROM users WHERE id NOT IN (SELECT user_id FROM user_notification_settings);

INSERT IGNORE INTO user_security_settings (user_id)
SELECT id FROM users WHERE id NOT IN (SELECT user_id FROM user_security_settings);

INSERT IGNORE INTO billing_info (user_id)
SELECT id FROM users WHERE id NOT IN (SELECT user_id FROM billing_info);