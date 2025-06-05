

-- Tabela: users
CREATE TABLE IF NOT EXISTS `users` (
   `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
   `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL, -- Para armazenar a senha com hash
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `remember_token` VARCHAR(100) NULL DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: task_categories
CREATE TABLE IF NOT EXISTS `task_categories` (
     `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     `user_id` INT UNSIGNED NOT NULL,
     `name` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY `user_category_name_unique` (`user_id`, `name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: tasks
CREATE TABLE IF NOT EXISTS `tasks` (
   `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
   `user_id` INT UNSIGNED NOT NULL,
   `category_id` INT UNSIGNED NULL DEFAULT NULL,
   `title` VARCHAR(255) NOT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `status` ENUM('pending', 'in_progress', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    `priority` TINYINT UNSIGNED NOT NULL DEFAULT 2, -- Ex: 1=Baixa, 2=Média, 3=Alta
    `due_date` DATETIME NULL DEFAULT NULL,
    `completed_at` DATETIME NULL DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`category_id`) REFERENCES `task_categories`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: user_sessions
CREATE TABLE IF NOT EXISTS `user_sessions` (
   `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
   `user_id` INT UNSIGNED NOT NULL,
   `token_hash` VARCHAR(255) NOT NULL UNIQUE,
    `type` ENUM('api', 'remember_me', 'web_persistent') NOT NULL DEFAULT 'api',
    `device_info` VARCHAR(255) NULL DEFAULT NULL,
    `ip_address` VARCHAR(45) NULL DEFAULT NULL,
    `last_used_at` TIMESTAMP NULL DEFAULT NULL,
    `expires_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_user_sessions_expires_at` (`expires_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

