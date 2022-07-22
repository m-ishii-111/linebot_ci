CREATE TABLE IF NOT EXISTS `message_msts` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `type`          VARCHAR(255) NOT NULL,
    `seq`           VARCHAR(255) NOT NULL,
    `message`       VARCHAR(255) NOT NULL,
    `user_id`       INT NOT NULL,
    `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE( `type`, `seq` )
);
