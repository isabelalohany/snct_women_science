-- Estrutura inicial do portal Mulheres na Ciência.
-- O nome do banco deve corresponder a DB_NAME no arquivo .env.

CREATE DATABASE IF NOT EXISTS mulheres_na_ciencia
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE mulheres_na_ciencia;

CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('usuario', 'administrador') NOT NULL DEFAULT 'usuario',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    alterado_por_usuario_id INT UNSIGNED NULL,

    CONSTRAINT uq_usuarios_email UNIQUE (email),
    INDEX idx_usuarios_alterado_por (alterado_por_usuario_id),
    CONSTRAINT fk_usuarios_alterado_por
        FOREIGN KEY (alterado_por_usuario_id)
        REFERENCES usuarios (id_usuario)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cientistas (
    id_cientista INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(180) NOT NULL,
    nacionalidade VARCHAR(100) NULL,
    data_nascimento DATE NULL,
    area_atuacao VARCHAR(255) NOT NULL,
    biografia TEXT NOT NULL,
    contribuicoes TEXT NOT NULL,
    informacoes_adicionais TEXT NULL,
    imagem_path VARCHAR(255) NULL,
    fontes_referencias TEXT NOT NULL,
    status ENUM('pendente', 'publicada', 'rejeitada', 'oculta') NOT NULL DEFAULT 'pendente',
    usuario_indicador_id INT UNSIGNED NULL,
    analisado_por_usuario_id INT UNSIGNED NULL,
    analisado_em DATETIME NULL,
    publicado_em DATETIME NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    alterado_por_usuario_id INT UNSIGNED NULL,

    INDEX idx_cientistas_status_nome (status, nome_completo),
    INDEX idx_cientistas_status_publicado (status, publicado_em),
    INDEX idx_cientistas_atualizado_em (atualizado_em),
    INDEX idx_cientistas_usuario_indicador (usuario_indicador_id),
    INDEX idx_cientistas_analisado_por (analisado_por_usuario_id),
    INDEX idx_cientistas_alterado_por (alterado_por_usuario_id),
    CONSTRAINT fk_cientistas_usuario_indicador
        FOREIGN KEY (usuario_indicador_id)
        REFERENCES usuarios (id_usuario)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_cientistas_analisado_por
        FOREIGN KEY (analisado_por_usuario_id)
        REFERENCES usuarios (id_usuario)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    CONSTRAINT fk_cientistas_alterado_por
        FOREIGN KEY (alterado_por_usuario_id)
        REFERENCES usuarios (id_usuario)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
