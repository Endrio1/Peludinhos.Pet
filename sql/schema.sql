-- Schema para o projeto Peludinhos da UFOPA
-- Cria tabelas: admins, cats, adoption_requests
-- IMPORTANTE: ajuste o nome do banco de dados antes de importar

CREATE DATABASE IF NOT EXISTS `peludinhos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `peludinhos`;

-- Administradores
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Gatos disponíveis para adoção
DROP TABLE IF EXISTS `cats`;
CREATE TABLE `cats` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `age` VARCHAR(50) DEFAULT NULL,
  `breed` VARCHAR(100) DEFAULT NULL,
  `sex` ENUM('M','F') DEFAULT 'M',
  `neutered` TINYINT(1) DEFAULT 0,
  `vaccinated` TINYINT(1) DEFAULT 0,
  `description` TEXT DEFAULT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('available','adopted') DEFAULT 'available',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pedidos de adoção
DROP TABLE IF EXISTS `adoption_requests`;
CREATE TABLE `adoption_requests` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cat_id` INT UNSIGNED NOT NULL,
  `applicant_name` VARCHAR(150) NOT NULL,
  `applicant_email` VARCHAR(150) NOT NULL,
  `applicant_phone` VARCHAR(50) DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `status` ENUM('pending','approved','rejected') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`cat_id`),
  CONSTRAINT `fk_request_cat` FOREIGN KEY (`cat_id`) REFERENCES `cats`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
