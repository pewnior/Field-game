-- --------------------------------------------------------
-- Field Game — schemat bazy danych
-- --------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- --------------------------------------------------------
-- Tabela `administrators`
-- --------------------------------------------------------

CREATE TABLE `administrators` (
  `id`       int(11)      NOT NULL AUTO_INCREMENT,
  `name`     varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Haslo zahaszowane BCrypt',
  `game_id`  int(11)      NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------
-- Tabela `players`
-- --------------------------------------------------------

CREATE TABLE `players` (
  `id`       int(11)      NOT NULL AUTO_INCREMENT,
  `name`     varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `points`   int(11)      NOT NULL DEFAULT 0,
  `game_id`  int(11)      NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------
-- Tabela `checkpoints`
-- --------------------------------------------------------

CREATE TABLE `checkpoints` (
  `id`       int(11)      NOT NULL AUTO_INCREMENT,
  `name`     varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Unikalny token w kodzie QR',
  `points`   int(11)      NOT NULL DEFAULT 2,
  `game_id`  int(11)      NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------
-- Tabela `logs`
-- --------------------------------------------------------

CREATE TABLE `logs` (
  `id`            int(11)  NOT NULL AUTO_INCREMENT,
  `player_id`     int(11)  NOT NULL,
  `checkpoint_id` int(11)  NOT NULL,
  `timestamp`     datetime NOT NULL,
  `game_id`       int(11)  NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------
-- Domyslny administrator
-- Login:  admin
-- Haslo:  admin  (zahaszowane BCrypt)
-- ZMIEN HASLO po pierwszym zalogowaniu!
-- --------------------------------------------------------

INSERT INTO `administrators` (`name`, `password`, `game_id`) VALUES
('admin', '$2y$12$8rfKtmRb/UcPOBti3UiTYOm5x8jdkK1fIDzyKMOAnyXWWVu41BgmO', 1);

COMMIT;
