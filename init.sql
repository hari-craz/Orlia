-- Orlia Database Initialization Script
-- This file is automatically executed when the MariaDB container starts

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `orlia`;
USE `orlia`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET NAMES utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `events`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `regno` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `phoneno` varchar(255) NOT NULL,
  `dept` varchar(255) NOT NULL,
  `day` varchar(255) NOT NULL,
  `events` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  KEY `idx_day` (`day`),
  KEY `idx_events` (`events`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `groupevents`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `groupevents`;
CREATE TABLE IF NOT EXISTS `groupevents` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `teamname` varchar(255) NOT NULL,
  `teamleadname` varchar(255) NOT NULL,
  `tregno` varchar(255) NOT NULL,
  `temail` varchar(255) NOT NULL,
  `events` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'Group',
  `tmembername` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `year` varchar(255) NOT NULL,
  `phoneno` varchar(255) NOT NULL,
  `dept` varchar(255) NOT NULL,
  `day` varchar(255) NOT NULL,
  KEY `idx_day` (`day`),
  KEY `idx_events` (`events`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `login`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `login`;
CREATE TABLE IF NOT EXISTS `login` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userid` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Insert default login credentials
-- --------------------------------------------------------

INSERT INTO `login` (`userid`, `password`, `role`) VALUES
('admin', '123456@', '0'),
('admin123', '123456@', '0'),
('Iplauction', '123', '1'),
('Groupdance', '123', '1'),
('Divideconquer', '123', '1'),
('Firelesscooking', '123', '1'),
('Trailertime', '123', '1'),
('Lyricalhunt', '123', '1'),
('Dumpcharades', '123', '1'),
('Rangoli', '123', '1'),
('Sherlockholmes', '123', '1'),
('Freefire', '123', '1'),
('Treasurehunt', '123', '1'),
('Artfromwaste', '123', '1'),
('Twindance', '123', '1'),
('Mime', '123', '1'),
('Tamilspeech', '123', '1'),
('Englishspeech', '123', '1'),
('Singing', '123', '1'),
('Drawing', '123', '1'),
('Mehandi', '123', '1'),
('Memecreation', '123', '1'),
('Solodance', '123', '1'),
('Photography', '123', '1'),
('Bestmanager', '123', '1'),
('Instrumentalplaying', '123', '1'),
('Rjvj', '123', '1'),
('Shortflim', '123', '1'),
('superadmin', '12345.#', '2'),
('Vegetablefruitart', '123', '1');
