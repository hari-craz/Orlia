-- phpMyAdmin SQL Dump
-- Generation Time: Feb 07, 2026 at 02:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `orlia`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_key` varchar(255) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_category` varchar(100) DEFAULT 'Technical',
  `event_type` varchar(50) NOT NULL,
  `day` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `event_venue` varchar(255) DEFAULT '',
  `event_time` varchar(255) DEFAULT '',
  `event_description` text DEFAULT NULL,
  `event_rules` text DEFAULT NULL,
  `event_topics` text DEFAULT NULL,
  `event_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_key` (`event_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` VALUES
('30', 'TamilSpeech', 'Tamil Speech', 'Technical', 'Solo', 'day1', '1', 'CH-4', '1.30 PM - 3.30 PM', 'Speech', '[]', '[]', 'assets/images/events/TamilSpeech.png'),
('31', 'FreeFire', 'Free Fire', 'Technical', 'Group', 'day1', '1', 'RK Classrooms', '10 - 12 AM', 'aa', 'AA', '', 'assets/images/events/FreeFire.jpg'),
('32', 'Aniil', 'Aniil', 'Technical', 'Solo', 'day1', '1', 'AA', 'AA', 'AA', '[\"AA\"]', '[]', 'uploads/events/Aniil.png');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_pass` varchar(50) NOT NULL,
  `event_key` varchar(50) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `feedback_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` int(11) DEFAULT 5,
  `suggestions` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_pass` (`event_pass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` VALUES
('1', 'ORA02WS0001', 'Photography', 'Photography', 'Nice to vote', '2026-02-03 20:19:33', '5', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `groupevents`
--

CREATE TABLE `groupevents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_pass` varchar(50) DEFAULT NULL,
  `teamname` varchar(255) NOT NULL,
  `teamleadname` varchar(255) NOT NULL,
  `tregno` varchar(255) NOT NULL,
  `temail` varchar(255) NOT NULL,
  `events` varchar(255) NOT NULL,
  `song` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'Group',
  `tmembername` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`tmembername`)),
  `year` varchar(255) NOT NULL,
  `phoneno` varchar(255) NOT NULL,
  `dept` varchar(255) NOT NULL,
  `day` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groupevents`
--

INSERT INTO `groupevents` VALUES
('22', 'ORA01VG0001', 'LekaaAI', 'Lekaasreenithi', '927625BAD087', 'itsmejayanthan@gmail.com', 'Lyricalhunt', '', 'Group', '[{\"name\":\"Jayanthan S\",\"roll\":\"927622BAL016\",\"phone\":\"8825756388\",\"dept\":\"AIML\",\"year\":\"IV year\"}]', 'I year', '8825756388', 'AIDS', 'day1'),
('23', 'ORA01GG0001', 'LekaaAIa', 'Jayanthan S', '927622BAL016', 'itsmejayanthan@gmail.com', 'Trailertime', '', 'Group', '[{\"name\":\"Jayaraj V\",\"roll\":\"927622BAL017\",\"phone\":\"934521078\",\"dept\":\"AIML\",\"year\":\"IV year\"}]', 'IV year', '8825756388', 'AIML', 'day1'),
('24', 'ORA01GG0002', 'wcd', 'hhh', '927622BEE074', 'ewdc@gmail.com', 'Trailertime', '', 'Group', '[{\"name\":\"kkk\",\"roll\":\"927622BEE555\",\"phone\":\"1234567898\",\"dept\":\"EEE\",\"year\":\"IV year\"}]', 'IV year', '7894561231', 'EEE', 'day1'),
('25', 'ORA01GG0003', 'Jei', 'Jayanthan', '927622BAL016', 'itsmejayanthan@gmail.com', 'Trailertime', '', 'Group', '[{\"name\":\"Harish\",\"roll\":\"927622BAL014\",\"phone\":\"7418520963\",\"dept\":\"AIML\",\"year\":\"IV year\"}]', 'IV year', '8825756388', 'AIML', 'day1');

-- --------------------------------------------------------

--
-- Table structure for table `photography`
--

CREATE TABLE `photography` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regno` varchar(25) NOT NULL,
  `name` varchar(50) NOT NULL,
  `dept` varchar(50) NOT NULL,
  `year` varchar(50) DEFAULT NULL,
  `vote` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `regno` (`regno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photography`
--

INSERT INTO `photography` VALUES
('9', '927625BAD087', 'LEKAASREENITHI', 'AIDS', 'I year', '8');

-- --------------------------------------------------------

--
-- Table structure for table `soloevents`
--

CREATE TABLE `soloevents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_pass` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `regno` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `phoneno` varchar(255) NOT NULL,
  `dept` varchar(255) NOT NULL,
  `day` varchar(255) NOT NULL,
  `events` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `soloevents`
--

INSERT INTO `soloevents` VALUES
('1', 'ORA02WS0001', 'Jayanthan S', '927622BAL016', 'IV year', '8825756388', 'AIML', 'day2', 'Photography', 'itsmejayanthan@gmail.com', '', 'uploads/photos/e3d32bd2d7a7ed6ac56e7a419f24c5ba.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES
('1', 'admin', '123456@', '0'),
('2', 'admin123', '123456@', '0'),
('3', 'superadmin', '12345', '2');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
