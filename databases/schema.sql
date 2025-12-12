-- database/schema.sql
CREATE DATABASE IF NOT EXISTS `moja_strona`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `moja_strona`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(190) NOT NULL UNIQUE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dodaj nowe kolumny (ignoruj błędy jeśli już istnieją)
ALTER TABLE `users` ADD COLUMN `password_hash` VARCHAR(255) NULL;
ALTER TABLE `users` ADD COLUMN `department` VARCHAR(100) NULL;
ALTER TABLE `users` ADD COLUMN `position` VARCHAR(100) NULL;
ALTER TABLE `users` ADD COLUMN `hire_date` DATE NULL;
ALTER TABLE `users` ADD COLUMN `salary` DECIMAL(10,2) NULL;
ALTER TABLE `users` ADD COLUMN `is_active` BOOLEAN NOT NULL DEFAULT TRUE;

CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `email` VARCHAR(190) NOT NULL UNIQUE,
  `role` ENUM('super_admin', 'admin', 'manager') NOT NULL DEFAULT 'admin',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `projects` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `description` TEXT,
  `status` ENUM('planning', 'active', 'completed', 'cancelled') NOT NULL DEFAULT 'planning',
  `budget` DECIMAL(10,2) NULL,
  `start_date` DATE NULL,
  `end_date` DATE NULL,
  `created_by` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`created_by`) REFERENCES `admins`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `project_members` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `role` VARCHAR(50) NOT NULL DEFAULT 'member',
  `joined_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_project_user` (`project_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `role` ENUM('user', 'premium', 'vip', 'banned') NOT NULL DEFAULT 'user',
  `permissions` JSON NULL,
  `assigned_by` INT UNSIGNED NOT NULL,
  `assigned_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`assigned_by`) REFERENCES `admins`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `work_time` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `date` DATE NOT NULL,
  `start_time` TIME NULL,
  `end_time` TIME NULL,
  `break_duration` INT DEFAULT 0 COMMENT 'Break duration in minutes',
  `total_hours` DECIMAL(4,2) NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_user_date` (`user_id`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `session_token` VARCHAR(255) NOT NULL UNIQUE,
  `login_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logout_time` TIMESTAMP NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`name`, `email`, `password_hash`, `department`, `position`, `hire_date`, `salary`, `is_active`)
VALUES
('Jan Kowalski', 'jan.kowalski@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'IT', 'Programista', '2023-01-15', 8000.00, 1),
('Anna Nowak', 'anna.nowak@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'HR', 'Specjalista HR', '2023-02-01', 6000.00, 1),
('Piotr Wiśniewski', 'piotr.wisniewski@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Marketing', 'Marketing Manager', '2023-03-10', 7000.00, 1),
('Maria Kowalczyk', 'maria.kowalczyk@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Finanse', 'Księgowa', '2023-01-20', 5500.00, 1)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `password_hash` = VALUES(`password_hash`),
  `department` = VALUES(`department`),
  `position` = VALUES(`position`),
  `hire_date` = VALUES(`hire_date`),
  `salary` = VALUES(`salary`),
  `is_active` = VALUES(`is_active`);

INSERT INTO `admins` (`username`, `password_hash`, `email`, `role`)
VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'super_admin')
ON DUPLICATE KEY UPDATE
  `username` = VALUES(`username`);

INSERT INTO `projects` (`name`, `description`, `status`, `budget`, `start_date`, `end_date`, `created_by`)
VALUES
('Strona internetowa', 'Nowoczesna strona firmowa z systemem CMS', 'active', 15000.00, '2024-01-15', '2024-06-30', 1),
('Aplikacja mobilna', 'Aplikacja do zarządzania zadaniami', 'planning', 25000.00, '2024-03-01', '2024-12-31', 1),
('System CRM', 'Kompleksowy system zarządzania klientami', 'completed', 50000.00, '2023-06-01', '2023-12-15', 1)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`);

INSERT INTO `user_roles` (`user_id`, `role`, `assigned_by`)
VALUES
(1, 'premium', 1),
(2, 'user', 1)
ON DUPLICATE KEY UPDATE
  `role` = VALUES(`role`);

-- Przykładowe dane czasu pracy
INSERT INTO `work_time` (`user_id`, `date`, `start_time`, `end_time`, `break_duration`, `total_hours`, `notes`)
VALUES
(1, '2024-01-15', '09:00:00', '17:00:00', 60, 7.0, 'Normalny dzień pracy'),
(1, '2024-01-16', '08:30:00', '16:30:00', 30, 7.5, 'Wcześniejsze rozpoczęcie'),
(2, '2024-01-15', '09:15:00', '17:15:00', 45, 7.25, 'Spotkanie z klientem'),
(2, '2024-01-16', '09:00:00', '18:00:00', 60, 8.0, 'Dłuższy dzień pracy'),
(3, '2024-01-15', '10:00:00', '18:00:00', 60, 7.0, 'Praca zdalna'),
(4, '2024-01-15', '08:00:00', '16:00:00', 30, 7.5, 'Wczesne rozpoczęcie')
ON DUPLICATE KEY UPDATE
  `start_time` = VALUES(`start_time`),
  `end_time` = VALUES(`end_time`),
  `break_duration` = VALUES(`break_duration`),
  `total_hours` = VALUES(`total_hours`),
  `notes` = VALUES(`notes`);