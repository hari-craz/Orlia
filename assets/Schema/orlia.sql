-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2026 at 07:11 AM
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
(1, NULL, 'Leks.AI', 'Jayanthan', '927622BAL', 'itsmejayanthan@gmail.com', 'Firelesscooking', NULL, 'Group', '[{\"name\":\"Lekaa\",\"roll\":\"8524\"}]', 'IV year', '8825756388', 'AIML', 'day1'),
(2, NULL, 'Leks.AI', 'Jayanthan Senthilkumar', '927625BCS056', 'kf@gmail.com', 'Firelesscooking', NULL, 'Group', '[{\"name\":\"Lekaa\",\"roll\":\"da\",\"phone\":\"ad\"}]', 'I year', '08825756388', 'CSE', 'day1'),
(3, NULL, 'Leks.AI', 'JAYANTHAN S', '927624BAM556', 'jah@gmail.com', 'Firelesscooking', NULL, 'Group', '[{\"name\":\"Lekaa\",\"roll\":\"8524\",\"phone\":\"08825756388\",\"dept\":\"CSE\",\"year\":\"I year\"}]', 'II year', '08825756388', 'AIML', 'day1'),
(4, NULL, 'BHjl', 'bklj', '927624BAD583', 'jah@gmail.com', 'Firelesscooking', NULL, 'Group', '[{\"name\":\"zdvf\",\"roll\":\"927625BAM856\",\"phone\":\"865\",\"dept\":\"AIML\",\"year\":\"I year\"}]', 'II year', '9865', 'AIDS', 'day1'),
(5, NULL, 'd5ruty', 'ftugkbhjnk', '927625BAM120', 'uftygbhjn@gmail.com', 'Groupdance', NULL, 'Group', '[{\"name\":\"ryfvgibhj\",\"roll\":\"927625BAD222\",\"phone\":\"cfuvgbhj\",\"dept\":\"AIDS\",\"year\":\"I year\"},{\"name\":\"hcfjhb jn\",\"roll\":\"927624BAM521\",\"phone\":\"ctyfvgjbh\",\"dept\":\"AIML\",\"year\":\"II year\"},{\"name\":\"rdfyvgbhj\",\"roll\":\"927624BAM865\",\"phone\":\"xrdcfgvhb \",\"dept\":\"AIML\",\"year\":\"II year\"},{\"name\":\"yfgvbjhn \",\"roll\":\"927625BAM85\",\"phone\":\"rryvgj\",\"dept\":\"AIML\",\"year\":\"I year\"},{\"name\":\"yvgkbj \",\"roll\":\"927625BAM49865\",\"phone\":\"xeytdgh \",\"dept\":\"AIML\",\"year\":\"I year\"}]', 'I year', 'tcfvgyjbnk', 'AIML', 'day1'),
(6, NULL, 'wcd', 'vrf', '927624BAD852', 'ubkhl@gmail.com', 'Groupdance', 'uploads/songs/a72e3c6dc2afe0e9cd00f703182fda9e.mp3', 'Group', '[{\"name\":\"ceadz\",\"roll\":\"927625BAM\",\"phone\":\"vear\",\"dept\":\"AIML\",\"year\":\"I year\"},{\"name\":\"eravge\",\"roll\":\"927625BCS\",\"phone\":\"aevgaet\",\"dept\":\"CSE\",\"year\":\"I year\"},{\"name\":\"aervaerg\",\"roll\":\"927625BAD\",\"phone\":\"aergvaer\",\"dept\":\"AIDS\",\"year\":\"I year\"},{\"name\":\"rgawgv\",\"roll\":\"927624BCS\",\"phone\":\"ergvaer\",\"dept\":\"CSE\",\"year\":\"II year\"},{\"name\":\"regvaer\",\"roll\":\"927624BAD\",\"phone\":\"eraw\",\"dept\":\"AIDS\",\"year\":\"II year\"}]', 'II year', '87451296325', 'AIDS', 'day1'),
(7, NULL, 'xrdchfg ', 'xrdcfgj', '927625BAD', 'dxcfgvjh@gmail.com', 'Trailertime', '', 'Group', '[{\"name\":\"tcyvfjgk\",\"roll\":\"927625BAD555\",\"phone\":\"5468790\",\"dept\":\"AIDS\",\"year\":\"I year\"}]', 'I year', '576878', 'AIDS', 'day1'),
(8, NULL, '6rfcj', 'cfjg', '927625BCS564', 'cjg@gmail.com', 'Firelesscooking', '', 'Group', '[{\"name\":\"xcthfjgh\",\"roll\":\"927625BAD546\",\"phone\":\"tcfgvykbhh\",\"dept\":\"AIDS\",\"year\":\"I year\"}]', 'I year', '45', 'CSE', 'day1'),
(9, NULL, 'wserdctf', 'ctfjgh', '927625BCS', 'kf@gmail.com', 'Dumpcharades', '', 'Group', '[{\"name\":\"xerctfvgh\",\"roll\":\"927625BEV654\",\"phone\":\"trfcgh\",\"dept\":\"VLSI\",\"year\":\"I year\"}]', 'I year', '3456789', 'CSE', 'day1'),
(10, NULL, 'etcufyvg', 'g vhbjk', '927625BAD', 'rcfyghj@gmial.cok', 'Iplauction', '', 'Group', '[{\"name\":\"rxdcfv\",\"roll\":\"927625BAD\",\"phone\":\"4567890\",\"dept\":\"AIDS\",\"year\":\"I year\"},{\"name\":\"45678\",\"roll\":\"927625BAD\",\"phone\":\"567890-=987654\",\"dept\":\"AIDS\",\"year\":\"I year\"}]', 'I year', 'fituvkgj', 'AIDS', 'day1'),
(11, NULL, 'fvy gh', 'vgf knm', '927625BAD', 'nk@gmailc.om', 'Lyricalhunt', '', 'Group', '[{\"name\":\"v frjn\",\"roll\":\"927625BAD\",\"phone\":\"w3456\",\"dept\":\"AIDS\",\"year\":\"I year\"}]', 'I year', 'jm', 'AIDS', 'day1'),
(12, NULL, '74weef', 'bhjk', '927625BAD', 'gsvfer@gmslc.com', 'Divideconquer', '', 'Group', '[{\"name\":\"edcwas\",\"roll\":\"927625BAD\",\"phone\":\"8745\",\"dept\":\"AIDS\",\"year\":\"I year\"},{\"name\":\"874521\",\"roll\":\"927625BAD\",\"phone\":\"986541\",\"dept\":\"AIDS\",\"year\":\"I year\"},{\"name\":\"98561\",\"roll\":\"927625BAD\",\"phone\":\"9876451\",\"dept\":\"AIDS\",\"year\":\"I year\"}]', 'I year', '987542', 'AIDS', 'day1'),
(13, NULL, 'janah', 'cdaerf', '927625BAD', 'ewdc@gmail.com', 'Groupdance', 'uploads/songs/a3e61dd67e6296745dd8dca7212b6d48.mp3', 'Group', '[{\"name\":\"98756\",\"roll\":\"927625BAD\",\"phone\":\"895632\",\"dept\":\"AIDS\",\"year\":\"I year\"},{\"name\":\"87954\",\"roll\":\"927625BAD\",\"phone\":\"8956\",\"dept\":\"AIDS\",\"year\":\"I year\"},{\"name\":\"89564\",\"roll\":\"927625BAD\",\"phone\":\"895\",\"dept\":\"AIDS\",\"year\":\"I year\"},{\"name\":\"94856\",\"roll\":\"927625BAD\",\"phone\":\"8451\",\"dept\":\"AIDS\",\"year\":\"I year\"},{\"name\":\"9815\",\"roll\":\"927625BAD\",\"phone\":\"7984512\",\"dept\":\"AIDS\",\"year\":\"I year\"}]', 'I year', '987546', 'AIDS', 'day1'),
(14, NULL, 'edcwf', '7', '927625BAD', 'ewdc@gmail.com', 'Rangoli', '', 'Group', '[{\"name\":\"edccs\",\"roll\":\"927625BEC\",\"phone\":\"wesdc\",\"dept\":\"ECE\",\"year\":\"I year\"},{\"name\":\"874521\",\"roll\":\"927625BAD\",\"phone\":\"45\",\"dept\":\"AIDS\",\"year\":\"I year\"}]', 'I year', '84', 'AIDS', 'day2'),
(15, NULL, 'wcddw', 'Jayan', '927625BCS', 'kf@gmail.com', 'Treasurehunt', '', 'Group', '[{\"name\":\"Lekaa\",\"roll\":\"927625BAD\",\"phone\":\"c\",\"dept\":\"AIDS\",\"year\":\"I year\"},{\"name\":\"cd\",\"roll\":\"927624BCS\",\"phone\":\"cd\",\"dept\":\"CSE\",\"year\":\"II year\"}]', 'I year', '+91 88257-56388', 'CSE', 'day2'),
(16, NULL, 'Leks.AIb', 'Jayanthan', '927624BAD', 'kvubh@gmail.com', 'Artfromwaste', '', 'Group', '[{\"name\":\"f s\",\"roll\":\"927625BAM856\",\"phone\":\"08825756388\",\"dept\":\"AIML\",\"year\":\"I year\"}]', 'II year', '65934626', 'AIDS', 'day2'),
(17, NULL, 'Leks.AIss', 'Jayanthan', '927624BCS', 'ddsc@gmail.com', 'Sherlockholmes', '', 'Group', '[{\"name\":\"xyctjgh\",\"roll\":\"927625BAD\",\"phone\":\"846532\",\"dept\":\"AIDS\",\"year\":\"I year\"}]', 'II year', '9856421', 'CSE', 'day2'),
(18, NULL, 'Leks.AIsss', 'qedcwf', '927625BAM', 'ewdc@gmail.com', 'Freefire', '', 'Group', '[{\"name\":\"swedcwfc\",\"roll\":\"927625BAM\",\"phone\":\"wqd\",\"dept\":\"AIML\",\"year\":\"I year\"},{\"name\":\"qwdqew\",\"roll\":\"927625BAM\",\"phone\":\"2343567\",\"dept\":\"AIML\",\"year\":\"I year\"},{\"name\":\"2134\",\"roll\":\"927625BCS\",\"phone\":\"2134r\",\"dept\":\"CSE\",\"year\":\"I year\"}]', 'I year', 'we234', 'AIML', 'day2'),
(19, NULL, 'xrdchfg s', 'Jayanthan', '927625BAM', 'kvubh@gmail.com', 'Vegetablefruitart', '', 'Group', '[{\"name\":\"213e\",\"roll\":\"927625BAM\",\"phone\":\"08825756388\",\"dept\":\"AIML\",\"year\":\"I year\"}]', 'I year', '65934626', 'AIML', 'day2'),
(20, NULL, 'BHjldddd', 'bklj', '927624BAM556', 'kvubh@gmail.com', 'Twindance', '', 'Group', '[{\"name\":\"Lekaa\",\"roll\":\"927625BAMsss\",\"phone\":\"08825756388\",\"dept\":\"AIML\",\"year\":\"I year\"}]', 'II year', '+91 8825756388', 'AIML', 'day2'),
(21, 'ORA02NG0002', 'LekaaAI', 'Lekaasreenithi', '927625BAD087', 'lekaasreenithi@gmail.com', 'Twindance', 'uploads/songs/1eb85e7c68c1522b0fed4d0d4b0aefb7.mp3', 'Group', '[{\"name\":\"Jayanthan S\",\"roll\":\"927622BAL016\",\"phone\":\"8825756388\",\"dept\":\"AIML\",\"year\":\"IV year\"}]', 'I year', '8825756388', 'AIDS', 'day2');

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
(6, '927625BAD087', 'LEKAASREENITHI', 'AIDS', 'I year', 1),
(7, '927622BAL017', 'JAYARAJ V', 'AIML', 'IV year', 5);

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

INSERT INTO `soloevents` (`id`, `event_pass`, `name`, `regno`, `year`, `phoneno`, `dept`, `day`, `events`, `mail`, `video`) VALUES
(1, NULL, 'Jayan', '852741', 'IV year', '65934626', 'AIML', '', '', 'jah@gmail.com', NULL),
(2, NULL, 'Jayan', '852741', 'IV year', '65934626', 'AIML', '', '', 'jah@gmail.com', NULL),
(3, NULL, 'Jayan', '852741', 'IV year', '65934626', 'AIML', '', '', 'jah@gmail.com', NULL),
(4, NULL, 'Jayan', '852741', 'IV year', '8825756388', 'AIML', 'day1', 'Singing', 'jah@gmail.com', NULL),
(5, NULL, 'Jayanthan', '927622BAL016', 'IV year', '8825756388', 'AIML', 'day1', 'Singing', 'itsmejayanthan@gmail.com', NULL),
(6, NULL, 'Jayanthan', '927622BAL016', 'IV year', '8825756388', 'AIML', 'day1', 'Tamilspeech', 'itsmejayanthan@gmail.com', NULL),
(7, NULL, 'bklj', '927622BAL125', 'IV year', '67890234', 'AIML', 'day1', 'Tamilspeech', 'kvubh@gmail.com', NULL),
(8, NULL, 'Jayanthan', '927622BAL102', 'IV year', '7864531251', 'AIML', 'day1', 'Englishspeech', 'vgbjh@gmail.com', NULL),
(9, NULL, 'tcfvjh b', '927625BAD087', 'I year', '12345678', 'AIDS', 'day1', 'Singing', 'gbkjnk@gmail.com', NULL),
(10, NULL, 'Jayanthan', '927625BEC275', 'I year', '34567890', 'ECE', 'day1', 'Solodance', 'tcyfvg@gmail.com', NULL),
(11, NULL, '123456789', '927625BAD', 'I year', '546789', 'AIDS', 'day1', 'Drawing', 'xdchgv@gail.com', NULL),
(12, NULL, 'Jayanthan', '927625BAD', 'I year', 'tcrf6578', 'AIDS', 'day1', 'Mehandi', 'itsmejayanthan@gmail.com', NULL),
(13, NULL, '8465', '927625BAD', 'I year', '8956', 'AIDS', 'day1', 'Memecreation', 'fyvgbh@gmailc.om', NULL),
(14, NULL, 'Jayanthan', '927624BAM', 'II year', '98', 'AIML', 'day2', 'Photography', '7845@gmail.com', NULL),
(15, NULL, 'Jayanthan', '927625BAD', 'I year', '98756', 'AIDS', 'day2', 'Instrumentalplaying', '8456@gmain.com', NULL),
(16, NULL, 'Jayanthan', '927624BCS', 'II year', '+91 8825756388', 'CSE', 'day2', 'Shortflim', 'itsmejayanthan@gmail.com', NULL),
(17, NULL, 'bklj', '927625BAM', 'I year', '8825756388', 'AIML', 'day2', '', 'jah@gmail.com', NULL),
(18, NULL, 'bklj', '927625BAM', 'I year', '8825756388', 'AIML', 'day2', '', 'jah@gmail.com', NULL),
(19, NULL, 'bklj', '927625BAM', 'I year', '8825756388', 'AIML', 'day2', '', 'jah@gmail.com', NULL),
(20, NULL, 'bklj', '927625BAM', 'I year', '8825756388', 'AIML', 'day2', '', 'jah@gmail.com', NULL),
(21, NULL, 'bklj', '927625BAM', 'I year', '8825756388', 'AIML', 'day2', '', 'jah@gmail.com', NULL),
(22, NULL, 'bklj', '927625BAM', 'I year', '8825756388', 'AIML', 'day2', '', 'jah@gmail.com', NULL),
(23, NULL, 'bklj', '927625BAM', 'I year', '8825756388', 'AIML', 'day2', '', 'jah@gmail.com', NULL),
(24, NULL, 'bklj', '927625BAM', 'I year', '8825756388', 'AIML', 'day2', '', 'jah@gmail.com', NULL),
(25, NULL, 'bklj', '927625BAM', 'I year', '8825756388', 'AIML', 'day2', '', 'jah@gmail.com', NULL),
(26, NULL, 'Jayanthan', '927625BAM', 'I year', '8825756388', 'AIML', 'day2', 'Mime', 'jah@gmail.com', NULL),
(27, NULL, 'bklj', '927625BAM', 'I year', '8825756388', 'AIML', 'day2', 'Bestmanager', 'jah@gmail.com', NULL),
(28, NULL, 'Jayanthan', '927625BCS', 'I year', '8825756388', 'CSE', 'day2', 'Rjvj', 'jah@gmail.com', NULL),
(29, NULL, 'Jayanthan', '927624BAD550', 'II year', '7894561230', 'AIDS', 'day2', 'Shortflim', 'nj@gmail.com', 'uploads/videos/147b3cf4957259abed5d33f1ad26aa9b.mp4'),
(30, NULL, 'Jayanthan', '927622BAL016', 'IV year', '8825756388', 'AIML', 'day1', 'Singing', 'itsmejayanthan@gmail.com', ''),
(31, 'ORA01CS0005', 'Jayanthan', '927624BAD003', 'II year', '784521960', 'AIDS', 'day1', 'Singing', 'jah@gmail.com', ''),
(32, 'ORA01CS0006', 'Jayanthan', '927625BAMsss', 'I year', '8825756388', 'AIML', 'day1', 'Singing', 'jah@gmail.com', ''),
(33, 'ORA01CS0007', 'Jayanthan', '927625BSCss1', 'I year', '8825756388', 'CYBER', 'day1', 'Singing', 'jah@gmail.com', ''),
(34, 'ORA01CS0008', 'Lekaasreenithi', '927625BAD087', 'I year', '7418520963', 'AIDS', 'day1', 'Singing', 'lekaasreenithi@gmail.com', ''),
(35, 'ORA01CS0009', 'Jayanthan', '927625BAMsss', 'I year', '+91 8825756388', 'AIML', 'day1', 'Singing', 'jah@gmail.com', ''),
(36, 'ORA01CS0010', 'Lekaasreenithi', '927625BAD087', 'I year', '8825756388', 'AIDS', 'day1', 'Singing', 'lekaasreenithi@gmail.com', ''),
(37, 'ORA01DS0002', 'Jayanthan S', '927622BAL016', 'IV year', '8825756388', 'AIML', 'day1', 'Memecreation', 'itsmejayanthan@gmail.com', '');

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
-- AUTO_INCREMENT for table `groupevents`
--
ALTER TABLE `groupevents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `photography`
--
ALTER TABLE `photography`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `soloevents`
--
ALTER TABLE `soloevents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_pass` varchar(50) NOT NULL,
  `event_key` varchar(255) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `feedback_text` text DEFAULT NULL,
  `rating` int(11) DEFAULT 5,
  `suggestions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
