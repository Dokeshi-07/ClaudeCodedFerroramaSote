-- Ferrorama: cadeia usuário -> trem -> rota -> sensor
-- Uso: mysql -u root -p < database/schema.sql

CREATE DATABASE IF NOT EXISTS ferrorama
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ferrorama;

CREATE TABLE IF NOT EXISTS usuarios (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario     VARCHAR(100) NOT NULL UNIQUE,
    email_usuario    VARCHAR(150) NOT NULL UNIQUE,
    telefone_usuario VARCHAR(15)  NOT NULL,
    senha_usuario    VARCHAR(255) NOT NULL,            -- hash (password_hash)
    administrador    TINYINT(1)   NOT NULL DEFAULT 0   -- 1 = admin, 0 = comum
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS trens (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nome_trem   VARCHAR(100) NOT NULL,
    tipo_trem   VARCHAR(100) NOT NULL,
    carga       VARCHAR(100) NOT NULL,
    id_usuario  INT          NOT NULL,                 -- usuário responsável
    CONSTRAINT fk_trens_usuario FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS rotas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nome_rota   VARCHAR(100) NOT NULL UNIQUE,
    estado_rota CHAR(2)      NOT NULL,
    local_rota  VARCHAR(150) NOT NULL,
    id_trem     INT          NOT NULL,
    CONSTRAINT fk_rotas_trem FOREIGN KEY (id_trem)
        REFERENCES trens(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sensores (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    tipo_sensor     VARCHAR(50)   NOT NULL,
    dados_sensor    TEXT          NOT NULL,
    valor_capturado DECIMAL(10,2) NULL,                -- opcional
    limite_disparo  DECIMAL(10,2) NULL,                -- opcional
    id_rota         INT           NOT NULL,
    CONSTRAINT fk_sensores_rota FOREIGN KEY (id_rota)
        REFERENCES rotas(id) ON DELETE RESTRICT
) ENGINE=InnoDB;
