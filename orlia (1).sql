-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2026 at 06:09 AM
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
  `id` int(11) NOT NULL,
  `event_key` varchar(255) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_category` varchar(50) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `day` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `event_venue` varchar(255) DEFAULT '',
  `event_time` varchar(255) DEFAULT '',
  `event_description` text DEFAULT NULL,
  `event_rules` text DEFAULT NULL,
  `event_topics` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_key`, `event_name`, `event_category`, `event_type`, `day`, `status`, `event_venue`, `event_time`, `event_description`, `event_rules`, `event_topics`) VALUES
(1, 'Tamilspeech', 'Tamil Speech', 'Non-Technical', 'Solo', 'day1', 1, 'CH-1', '9.30 AM - 11.00 AM', 'Solo Performance', '• போட்டி இரண்டு சுற்றுகள்.\n• முதல் சுற்று: 3 நிமிடம் பேச்சு, சிறந்தவர்கள் தேர்வு.\n• இரண்டாம் சுற்று: 5 நிமிடம் தயார், 3 நிமிடம் பேச்சு, வெற்றி.', '• இந்தியா : பல மாநிலங்களின் கூட்டமைப்பு\n• மொழிப்போர் : அன்றும், இன்றும்.\n• அரசியல் பிழைத்தோர்க்கு ஆறம் கூற்றாகும்'),
(2, 'Englishspeech', 'English Speech', 'Non-Technical', 'Solo', 'day1', 1, 'CH-2', '9.30 AM - 11.00 AM', 'Solo Performance', '• Duration: 3-4 minutes.\n• Round 1: Speech on an given topic.\n• Round 2: On-the-spot topic.', '• Poverty and income inequality: Can we ever bridge the gap?\n• Social media: Connecting people or creating distance?\n• Education: A right or a privilege?'),
(3, 'Singing', 'Singing', 'Non-Technical', 'Solo', 'day1', 1, 'CH-3', '9.30 AM - 11.00 AM', 'Solo Performance', '• Sing with or without instruments.\n• Karaoke allowed; submit in advance if needed.\n• Judging: Vocal quality, stage presence, expression, song difficulty, and audience engagement.', NULL),
(4, 'Memecreation', 'Meme Creation', 'Non-Technical', 'Solo', 'day1', 1, 'CH-3', '2.00 PM - 3.30 PM', 'Solo Performance', '• Submit memes, reels, or both based on given themes.\n• Content must be appropriate (no hate speech, violence, or offensive material).\n• Reel duration: 20-45 seconds.', NULL),
(5, 'Solodance', 'Solo Dance', 'Non-Technical', 'Solo', 'day1', 1, 'CH-5', '10.00 AM - 12.00 PM', 'Solo Performance', '• Solo competition (individual participants only).\n• Random songs played based on slot selection; dance accordingly.\n• Judging: Spontaneity, expression, and adaptability.', NULL),
(6, 'Divideconquer', 'Divide Conquer', 'Technical', 'Group', 'day1', 1, 'CH-2', '2.00 PM - 3.30 PM', 'Group Performance', '• Rounds with group-based activities.\n• Maximum 4-5 Members.\n• Strategic task division required to complete challenges.', NULL),
(7, 'Trailertime', 'Trailer Time', 'Non-Technical', 'Group', 'day1', 1, 'CH-4', '10.30 AM - 12.00 PM', 'Group Performance', '• Trailer duration: 1 to 3 minutes (audio & video provided on the spot).\n• Maximum 1-2 Members.\n• Original creation only plagiarism not allowed.', NULL),
(8, 'Groupdance', 'Group Dance', 'Non-Technical', 'Group', 'day1', 1, 'Ground', '4.00 PM', 'Group Performance', '• Duration: 5 minutes (Tracks must be verified with Fine Arts Coordinator by 29.03.2025).\n• Maximum 6-15 Members.\n• Any dance form allowed (Classical, Folk, Western, Fusion, etc.).', NULL),
(9, 'Shortflim', 'Short Film', 'Non-Technical', 'Solo', 'day2', 1, 'CH-3', '11.00 AM - 12.30 PM', 'Solo Performance', '• Original content only; plagiarism or prior publication leads to disqualification.\n• Duration: Maximum 12 minutes (including credits).\n• No offensive, religious, or political content; violations will result in disqualification.', NULL),
(10, 'Bestmanager', 'Best Manager', 'Non-Technical', 'Solo', 'day2', 1, 'CH-4', '11.00 AM - 12.30 PM', 'Solo Performance', '• Round-1:Quiz.\n• Round-2: Group Discussion.\n• Round-3: Scenario based talk.', NULL),
(11, 'Instrumentalplaying', 'Instrumental Playing', 'Non-Technical', 'Solo', 'day2', 1, 'CH-5', '9 AM - 10 AM', 'Solo Performance', '• Any instrument allowed (guitar, piano, violin, flute, drums, etc.). Bring your own instruments and accessories.\n• Performance duration: 3 to 5 minutes.\n• Singing is allowed along with instrument playing.', NULL),
(12, 'Rjvj', 'RJ/VJ Hunt', 'Non-Technical', 'Solo', 'day2', 1, 'CH-4', '2.00 PM - 3.30 PM', 'Solo Performance', '• On-the-spot topic.\n• 3 to 5 minutes to present.\n• Judged on originality, engagement, and innovation.', NULL),
(13, 'Artfromwaste', 'Art From Waste', 'Non-Technical', 'Group', 'day2', 1, 'Vishweshwaraya', '11 AM - 1:30 PM', 'Group Performance', '• Duration: 60 minutes\n• Maximum 2 Members\n• Use only recyclable/waste materials (e.g., newspapers, plastic bottles, fabric scraps, cardboard). Pre-made parts are not allowed. Participants must bring their own materials.', NULL),
(14, 'Twindance', 'Twin Dance', 'Non-Technical', 'Group', 'day2', 1, 'CH-5', '2 PM - 4 PM', 'Group Performance', '• Duration: Up to 4 minutes.\n• Maximum 2 Members\n• Only verified tracks allowed (must be approved by Fine Arts staff coordinator before 29.03.2025). Proper dress code required.', NULL),
(15, 'Vegetablefruitart', 'Vegetable Fruit Art', 'Non-Technical', 'Group', 'day2', 1, 'Vishweshwaraya', '2 PM - 3.30 PM', 'Group Performance', '• Duration: 60 minutes\n• Maximum 1-2 Members\n• Use only vegetables & fruits; pre-made parts are not allowed. Participants must bring their own materials.', NULL),
(16, 'Mime', 'Mime', 'Non-Technical', 'Both', 'day2', 1, 'CH-3', '2 PM - 4 PM', 'Solo Performance', '• Duration: 7 to 12 minutes\n• Background music/soundtracks allowed\n• Avoid sensitive topics like politics, religion, or violence', NULL),
(17, 'Drawing', 'Drawing', 'Non-Technical', 'Solo', 'day1', 1, 'Drawing Hall', '10.30 AM - 11.30 AM', 'Solo Performance', '• Theme-based drawing, Duration: 60 minutes.\n• Use A3 or A4 sheets.\n• Any medium allowed (pencils, paints, markers, charcoal).', '• The Beauty of the Four Seasons.\n• Dreamland with a pet.\n• Social issue & awareness.'),
(18, 'Mehandi', 'Mehandi', 'Non-Technical', 'Solo', 'day1', 1, 'Vivekanandha Hall', '1.30 PM - 3.00 PM', 'Solo Performance', '• Individual event.\n• Participants are encouraged to bring their own requirements.\n• Time duration: 60 Minutes.', NULL),
(19, 'Firelesscooking', 'Fireless Cooking', 'Non-Technical', 'Group', 'day1', 1, 'Vishweshwaraya Hall', '11.00 AM - 12.30 PM', 'Group Performance', '• The participants must bring the required materials.\n• Maximum 2 Members.\n• Appliances that require heat, like toasters or grills, are not allowed.', NULL),
(20, 'Dumpcharades', 'Dump Charades', 'Non-Technical', 'Group', 'day1', 1, 'Vivekanandha', '11.00 AM - 1.00 PM', 'Group Performance', '• This event consists of 3 rounds.\n• Maximum 2-3 Members.\n• It is fun event. Based on concentration and team coordination.', NULL),
(21, 'Iplauction', 'IPL Auction', 'Non-Technical', 'Group', 'day1', 1, 'CH-5', '2.00 PM - 4.00 PM', 'Group Performance', '• Virtual money provided as team budget for bidding and buying players.\n• Maximum 3 Members.\n• Team structure: Batsmen, 4 Bowlers, 1 All-rounder, 1 Legend (any category), 1 Wicketkeeper.', NULL),
(22, 'Lyricalhunt', 'Lyrical Hunt', 'Non-Technical', 'Group', 'day1', 1, 'CH-4', '1.30 PM - 3.30 PM', 'Group Performance', '• Identify the song, complete the lyrics, or respond with matching lyrics.\n• Maximum 2-3 Members.\n• Time-limited rounds for each challenge.', NULL),
(23, 'Photography', 'Photography', 'Non-Technical', 'Solo', 'day2', 1, 'APJ&RK Entrance', '9 AM - 3.30 PM', 'Solo Performance', '• Solo participation; only one image per participant.\n• Submit the image via the provided link before 31.03.2025.\n• Theme: Favorite place in MKCE.', ''),
(24, 'Rangoli', 'Rangoli', 'Non-Technical', 'Group', 'day2', 1, 'Ground', '9.00 AM - 10.30 AM', 'Group Performance', '• Duration: 60 minutes\n• Maximum 3-4 Members.\n• Bring own materials. artwork must match the theme (Orlia2k26 Cultural Fest).', NULL),
(25, 'Sherlockholmes', 'Sherlock Holmes', 'Non-Technical', 'Group', 'day2', 1, 'CH-2', '11 AM - 1:30 PM', 'Group Performance', '• Multiple rounds testing deduction and investigative skills\n• Maximum 2-3 Members\n• Solve encrypted messages and detective-themed puzzles', NULL),
(26, 'Freefire', 'Free Fire', 'Non-Technical', 'Group', 'day2', 1, 'Vivekanandha', '2.00 PM - 4.00 PM', 'Group Performance', '• Only mobile players, No pc players allowed.\n• Maximum 4 Members.\n• Gun skin off and Character skill allowed.', NULL),
(27, 'Treasurehunt', 'Treasure Hunt', 'Non-Technical', 'Group', 'day2', 1, 'Vivekanandha', '10.00 AM - 12.00 PM', 'Group Performance', '• Clues must be solved in order; no skipping or tampering.\n• Maximum 3-4 Members\n• Time-bound challenge; penalties for delays or rule violations.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `event_pass` varchar(50) NOT NULL,
  `event_key` varchar(50) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `feedback_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` int(11) DEFAULT 5,
  `suggestions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `event_pass`, `event_key`, `event_name`, `feedback_text`, `created_at`, `rating`, `suggestions`) VALUES
(1, 'ORA02WS0001', 'Photography', 'Photography', 'Nice to vote', '2026-02-03 14:49:33', 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `groupevents`
--

CREATE TABLE `groupevents` (
  `id` int(11) NOT NULL,
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
  `day` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groupevents`
--

INSERT INTO `groupevents` (`id`, `event_pass`, `teamname`, `teamleadname`, `tregno`, `temail`, `events`, `song`, `type`, `tmembername`, `year`, `phoneno`, `dept`, `day`) VALUES
(22, 'ORA01VG0001', 'LekaaAI', 'Lekaasreenithi', '927625BAD087', 'itsmejayanthan@gmail.com', 'Lyricalhunt', '', 'Group', '[{\"name\":\"Jayanthan S\",\"roll\":\"927622BAL016\",\"phone\":\"8825756388\",\"dept\":\"AIML\",\"year\":\"IV year\"}]', 'I year', '8825756388', 'AIDS', 'day1'),
(23, 'ORA01GG0001', 'LekaaAIa', 'Jayanthan S', '927622BAL016', 'itsmejayanthan@gmail.com', 'Trailertime', '', 'Group', '[{\"name\":\"Jayaraj V\",\"roll\":\"927622BAL017\",\"phone\":\"934521078\",\"dept\":\"AIML\",\"year\":\"IV year\"}]', 'IV year', '8825756388', 'AIML', 'day1');

-- --------------------------------------------------------

--
-- Table structure for table `photography`
--

CREATE TABLE `photography` (
  `id` int(11) NOT NULL,
  `regno` varchar(25) NOT NULL,
  `name` varchar(50) NOT NULL,
  `dept` varchar(50) NOT NULL,
  `year` varchar(50) DEFAULT NULL,
  `vote` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photography`
--

INSERT INTO `photography` (`id`, `regno`, `name`, `dept`, `year`, `vote`) VALUES
(9, '927625BAD087', 'LEKAASREENITHI', 'AIDS', 'I year', 8);

-- --------------------------------------------------------

--
-- Table structure for table `soloevents`
--

CREATE TABLE `soloevents` (
  `id` int(11) NOT NULL,
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
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `soloevents`
--

INSERT INTO `soloevents` (`id`, `event_pass`, `name`, `regno`, `year`, `phoneno`, `dept`, `day`, `events`, `mail`, `video`, `photo`) VALUES
(1, 'ORA02WS0001', 'Jayanthan S', '927622BAL016', 'IV year', '8825756388', 'AIML', 'day2', 'Photography', 'itsmejayanthan@gmail.com', '', 'uploads/photos/e3d32bd2d7a7ed6ac56e7a419f24c5ba.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userid` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userid`, `password`, `role`) VALUES
(1, 'admin', '123456@', '0'),
(2, 'admin123', '123456@', '0'),
(3, 'Iplauction', '123', '1'),
(4, 'Groupdance', '123', '1'),
(5, 'Divideconquer', '123', '1'),
(6, 'Firelesscooking', '123', '1'),
(7, 'Trailertime', '123', '1'),
(8, 'Lyricalhunt', '123', '1'),
(9, 'Dumpcharades', '123', '1'),
(10, 'Rangoli', '123', '1'),
(11, 'Sherlockholmes', '123', '1'),
(12, 'Freefire', '123', '1'),
(13, 'Treasurehunt', '123', '1'),
(14, 'Artfromwaste', '123', '1'),
(15, 'Twindance', '123', '1'),
(16, 'Mime', '123', '1'),
(17, 'Tamilspeech', '123', '1'),
(18, 'Englishspeech', '123', '1'),
(19, 'Singing', '123', '1'),
(20, 'Drawing', '123', '1'),
(21, 'Mehandi', '123', '1'),
(22, 'Memecreation', '123', '1'),
(23, 'Solodance', '123', '1'),
(24, 'Photography', '123', '1'),
(25, 'Bestmanager', '123', '1'),
(26, 'Instrumentalplaying', '123', '1'),
(27, 'Rjvj', '123', '1'),
(28, 'Shortflim', '123', '1'),
(29, 'superadmin', '12345', '2'),
(30, 'Vegetablefruitart', '123', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_key` (`event_key`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_pass` (`event_pass`);

--
-- Indexes for table `groupevents`
--
ALTER TABLE `groupevents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photography`
--
ALTER TABLE `photography`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `regno` (`regno`);

--
-- Indexes for table `soloevents`
--
ALTER TABLE `soloevents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `groupevents`
--
ALTER TABLE `groupevents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `photography`
--
ALTER TABLE `photography`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `soloevents`
--
ALTER TABLE `soloevents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
