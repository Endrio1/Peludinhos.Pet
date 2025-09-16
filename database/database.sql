-- Criação do Banco de Dados (Opcional, caso ainda não tenha criado)
CREATE DATABASE peludinhos_ufopa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Seleciona o banco de dados para usar
USE peludinhos_ufopa;

-- Tabela para os administradores do sistema
CREATE TABLE `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `senha_hash` VARCHAR(255) NOT NULL,
  `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela para armazenar os dados dos gatos
CREATE TABLE `gatos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
  `idade` VARCHAR(50) NOT NULL,
  `sexo` ENUM('Macho', 'Fêmea') NOT NULL,
  `descricao` TEXT NOT NULL,
  `foto_url` VARCHAR(255) NOT NULL,
  `status` ENUM('Disponível', 'Processando Adoção', 'Adotado') NOT NULL DEFAULT 'Disponível',
  `data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela para gerenciar os pedidos de adoção
CREATE TABLE `pedidos_adocao` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `gato_id` INT NOT NULL,
  `nome_interessado` VARCHAR(150) NOT NULL,
  `email_interessado` VARCHAR(150) NOT NULL,
  `telefone_interessado` VARCHAR(20) NOT NULL,
  `mensagem` TEXT,
  `status` ENUM('Pendente', 'Aprovado', 'Rejeitado') NOT NULL DEFAULT 'Pendente',
  `data_pedido` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`gato_id`) REFERENCES `gatos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- (Opcional) Criar um usuário administrador inicial para testes
-- A senha 'admin123' será criptografada pelo script PHP de cadastro ou um script auxiliar.
-- Exemplo de como inserir manualmente uma senha já criptografada:
-- INSERT INTO `admins` (`nome`, `email`, `senha_hash`) VALUES ('Admin Principal', 'admin@ufopa.edu.br', '$2y$10$exemploDeHashPreGeradoAqui...');