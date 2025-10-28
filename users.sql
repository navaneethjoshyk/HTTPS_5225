-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed an admin user (password: admin123)
INSERT INTO users (name, email, password, image)
VALUES ('Admin', 'admin@example.com', '$2y$10$E5jF2GQ2X7pMcaJ2q9Lhpe4B1c0xP1W7Jt2LRv1m0kB2UZQkD9R5m', NULL)
ON DUPLICATE KEY UPDATE email=email;