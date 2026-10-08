-- ============================================================
-- Lost and Found Management System
-- Database Schema
-- ============================================================
-- Database: lostfound_db
-- Charset:  utf8mb4 (full Unicode support including emojis)
--
-- This file documents the structure of the database.
-- It can be imported into a fresh phpMyAdmin to recreate
-- all tables from scratch.
-- ============================================================

CREATE DATABASE IF NOT EXISTS `lostfound_db`
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE `lostfound_db`;

-- ============================================================
-- Table: users
-- Stores registered users of the system.
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
    `id`         INT(11)      NOT NULL AUTO_INCREMENT,
    `full_name`  VARCHAR(255) NOT NULL,
    `email`      VARCHAR(255) NOT NULL,
    `password`   VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- Table: items
-- Stores items reported as lost or found by users.
-- item_type: 'lost' or 'found'
-- status:    'open'      -> still active
--            'claimed'   -> someone claimed it (pending verification)
--            'resolved'  -> returned to owner / closed
-- ============================================================
CREATE TABLE IF NOT EXISTS `items` (
    `id`          INT(11)      NOT NULL AUTO_INCREMENT,
    `user_id`     INT(11)      NOT NULL,
    `item_type`   ENUM('lost','found') NOT NULL,
    `item_name`   VARCHAR(255) NOT NULL,
    `description` TEXT         DEFAULT NULL,
    `image`       VARCHAR(255) DEFAULT NULL,
    `location`    VARCHAR(255) DEFAULT NULL,
    `status`      ENUM('open','claimed','resolved') DEFAULT 'open',
    `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- Table: claims
-- Stores claims submitted by users for items they believe
-- belong to them. status is checked by admin/owner.
-- ============================================================
CREATE TABLE IF NOT EXISTS `claims` (
    `id`            INT(11)  NOT NULL AUTO_INCREMENT,
    `item_id`       INT(11)  NOT NULL,
    `user_id`       INT(11)  NOT NULL,
    `claim_message` TEXT     DEFAULT NULL,
    `status`        ENUM('pending','approved','rejected') DEFAULT 'pending',
    `created_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `item_id` (`item_id`),
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- End of schema
-- ============================================================