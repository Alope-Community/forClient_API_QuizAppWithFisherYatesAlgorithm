-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 18, 2025 at 02:46 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `username`, `password`, `role`) VALUES
(1, 'Ilham Hafidz', 'xxhamz_', 'password', 'admin'),
(2, 'Ira Irwanti', 'irwwt', 'password', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int NOT NULL,
  `value` varchar(150) NOT NULL,
  `question_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `value`, `question_id`) VALUES
(1, 'house', 1),
(2, 'car', 1),
(3, 'tree', 1),
(4, 'door', 1),
(5, 'Spanish', 2),
(6, 'Portuguese', 2),
(7, 'French', 2),
(8, 'English', 2),
(9, 'childs', 3),
(10, 'children', 3),
(11, 'childes', 3),
(12, 'child', 3),
(13, 'goed', 4),
(14, 'gone', 4),
(15, 'went', 4),
(16, 'go', 4),
(17, 'Greek', 5),
(18, 'Russian', 5),
(19, 'Arabic', 5),
(20, 'Latin', 5),
(21, 'noun', 6),
(22, 'verb', 6),
(23, 'adjective', 6),
(24, 'adverb', 6),
(25, 'to start a fight', 7),
(26, 'to finish work', 7),
(27, 'to start a conversation', 7),
(28, 'to make ice', 7),
(29, 'new', 8),
(30, 'modern', 8),
(31, 'old', 8),
(32, 'ancient', 8),
(33, 'German', 9),
(34, 'English', 9),
(35, 'French', 9),
(36, 'Italian', 9),
(37, 'goodest', 10),
(38, 'best', 10),
(39, 'better', 10),
(40, 'good', 10),
(41, 'please', 11),
(42, 'thank you', 11),
(43, 'sorry', 11),
(44, 'welcome', 11),
(45, 'Mandarin Chinese', 12),
(46, 'English', 12),
(47, 'Spanish', 12),
(48, 'Hindi', 12),
(49, 'sad', 13),
(50, 'joyful', 13),
(51, 'angry', 13),
(52, 'tired', 13),
(53, 'noun', 14),
(54, 'adjective', 14),
(55, 'verb', 14),
(56, 'adverb', 14),
(57, 'origin of words', 15),
(58, 'grammar rules', 15),
(59, 'sentence structure', 15),
(60, 'word meanings', 15),
(61, 'Germanic', 16),
(62, 'Romance', 16),
(63, 'Slavic', 16),
(64, 'Celtic', 16),
(65, 'eat', 17),
(66, 'eats', 17),
(67, 'ate', 17),
(68, 'eating', 17),
(69, 'their = possessive, there = place, theyre = they are', 18),
(70, 'their = there, there = theyre, theyre = their', 18),
(71, 'their = theyre, there = their, theyre = place', 18),
(72, 'their = place, there = possessive, theyre = they are', 18),
(73, 'mouse', 19),
(74, 'mice', 19),
(75, 'mouses', 19),
(76, 'mousees', 19),
(77, 'Arabic', 20),
(78, 'English', 20),
(79, 'French', 20),
(80, 'Spanish', 20),
(81, 'Japanese', 21),
(82, 'Chinese', 21),
(83, 'Korean', 21),
(84, 'Vietnamese', 21),
(85, 'sounds of speech', 22),
(86, 'sentence structure', 22),
(87, 'word meanings', 22),
(88, 'grammar rules', 22),
(89, 'words that sound the same but have different meanings', 23),
(90, 'words with same spelling', 23),
(91, 'words that rhyme', 23),
(92, 'words with same meaning', 23),
(93, 'wrote', 24),
(94, 'written', 24),
(95, 'write', 24),
(96, 'writing', 24),
(97, 'hello', 25),
(98, 'goodbye', 25),
(99, 'please', 25),
(100, 'thank you', 25),
(101, 'badder', 26),
(102, 'worse', 26),
(103, 'worst', 26),
(104, 'bad', 26),
(105, 'connect words or clauses', 27),
(106, 'describe nouns', 27),
(107, 'express actions', 27),
(108, 'state of being', 27),
(109, 'Spain', 28),
(110, 'France', 28),
(111, 'Portugal', 28),
(112, 'Spanish', 28),
(113, 'words that imitate sounds', 29),
(114, 'words that are very long', 29),
(115, 'words from another language', 29),
(116, 'words with multiple meanings', 29),
(117, 'goose', 30),
(118, 'geese', 30),
(119, 'gooses', 30),
(120, 'geeses', 30),
(121, 'Arabic', 31),
(122, 'Hebrew', 31),
(123, 'Persian', 31),
(124, 'Urdu', 31),
(125, 'loanword', 32),
(126, 'synonym', 32),
(127, 'antonym', 32),
(128, 'homonym', 32),
(129, 'large', 33),
(130, 'big', 33),
(131, 'small', 33),
(132, 'tiny', 33),
(133, 'Japanese', 34),
(134, 'Chinese', 34),
(135, 'Korean', 34),
(136, 'Thai', 34),
(137, 'sentence structure', 35),
(138, 'word meaning', 35),
(139, 'sound of speech', 35),
(140, 'paragraph organization', 35),
(141, 'analyses', 36),
(142, 'analysis', 36),
(143, 'analyzes', 36),
(144, 'analysises', 36),
(145, 'hello', 37),
(146, 'goodbye', 37),
(147, 'please', 37),
(148, 'thank you', 37),
(149, 'a word that describes a noun', 38),
(150, 'a word that describes a verb', 38),
(151, 'a word that describes an adverb', 38),
(152, 'a word that describes an adjective', 38),
(153, 'Arabic', 39),
(154, 'Hebrew', 39),
(155, 'Latin', 39),
(156, 'Greek', 39),
(157, 'a group of letters placed before a word', 40),
(158, 'a group of letters placed after a word', 40),
(159, 'a group of words', 40),
(160, 'a sentence', 40),
(161, 'speaking two languages', 41),
(162, 'speaking one language', 41),
(163, 'speaking three languages', 41),
(164, 'speaking many languages', 41),
(165, 'Portuguese', 42),
(166, 'Spanish', 42),
(167, 'English', 42),
(168, 'French', 42),
(169, 'run', 43),
(170, 'ran', 43),
(171, 'running', 43),
(172, 'runs', 43),
(173, 'English and French', 44),
(174, 'English only', 44),
(175, 'French only', 44),
(176, 'Spanish', 44),
(177, 'Hindi', 45),
(178, 'Bengali', 45),
(179, 'Tamil', 45),
(180, 'Urdu', 45),
(181, 'a group of letters placed at the end of a word', 46),
(182, 'a group of letters placed before a word', 46),
(183, 'a word', 46),
(184, 'a sentence', 46),
(185, 'Swahili', 47),
(186, 'Zulu', 47),
(187, 'Xhosa', 47),
(188, 'Afrikaans', 47),
(189, 'analyses', 48),
(190, 'analysis', 48),
(191, 'analyze', 48),
(192, 'analysisize', 48),
(193, 'Spanish', 49),
(194, 'Portuguese', 49),
(195, 'English', 49),
(196, 'French', 49),
(197, 'structure of words', 50),
(198, 'sentence structure', 50),
(199, 'meaning of words', 50),
(200, 'sound of words', 50);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int NOT NULL,
  `question` varchar(150) NOT NULL,
  `image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `difficulty` enum('easy','medium','hard') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `image`, `difficulty`, `answer`) VALUES
(1, 'What is the English word for \"rumah\"?', NULL, 'easy', 'house'),
(2, 'Which language is primarily spoken in Brazil?', NULL, 'easy', 'Portuguese'),
(3, 'What is the plural form of \"child\"?', NULL, 'medium', 'children'),
(4, 'What is the past tense of \"go\"?', NULL, 'easy', 'went'),
(5, 'Which language uses Cyrillic script?', NULL, 'medium', 'Russian'),
(6, 'What part of speech is the word \"quickly\"?', NULL, 'medium', 'adverb'),
(7, 'What is the meaning of the idiom \"break the ice\"?', NULL, 'medium', 'to start a conversation'),
(8, 'What is the antonym of \"ancient\"?', NULL, 'easy', 'modern'),
(9, 'Which language is the origin of the word \"kindergarten\"?', NULL, 'hard', 'German'),
(10, 'What is the superlative form of \"good\"?', NULL, 'medium', 'best'),
(11, 'Translate \"merci\" from French to English.', NULL, 'easy', 'thank you'),
(12, 'Which language has the most native speakers worldwide?', NULL, 'medium', 'Mandarin Chinese'),
(13, 'What is a synonym for \"happy\"?', NULL, 'easy', 'joyful'),
(14, 'What is the grammatical term for a word that describes a noun?', NULL, 'easy', 'adjective'),
(15, 'What does \"etymology\" study?', NULL, 'hard', 'origin of words'),
(16, 'Which language family does Spanish belong to?', NULL, 'medium', 'Romance'),
(17, 'What is the infinitive form of \"ate\"?', NULL, 'easy', 'eat'),
(18, 'What is the difference between \"their\", \"there\", and \"they\'re\"?', NULL, 'hard', 'their = possessive, there = place, they\'re = they are'),
(19, 'What is the plural of \"mouse\"?', NULL, 'medium', 'mice'),
(20, 'What is the main language spoken in Egypt?', NULL, 'easy', 'Arabic'),
(21, 'Which language uses \"Kanji\" characters?', NULL, 'medium', 'Japanese'),
(22, 'What does \"phonetics\" study?', NULL, 'hard', 'sounds of speech'),
(23, 'What is a \"homophone\"?', NULL, 'medium', 'words that sound the same but have different meanings'),
(24, 'What is the past participle of \"write\"?', NULL, 'medium', 'written'),
(25, 'Translate \"ciao\" from Italian.', NULL, 'easy', 'hello'),
(26, 'What is the comparative form of \"bad\"?', NULL, 'medium', 'worse'),
(27, 'What is a \"conjunction\" used for?', NULL, 'easy', 'to connect words or clauses'),
(28, 'Which language is official in both Spain and parts of Africa?', NULL, 'medium', 'Spanish'),
(29, 'What is the meaning of \"onomatopoeia\"?', NULL, 'hard', 'words that imitate sounds'),
(30, 'What is the singular form of \"geese\"?', NULL, 'medium', 'goose'),
(31, 'Which language is written right-to-left?', NULL, 'medium', 'Arabic'),
(32, 'What is the term for a word borrowed from another language?', NULL, 'hard', 'loanword'),
(33, 'What is the synonym of \"big\"?', NULL, 'easy', 'large'),
(34, 'What language does the word \"karaoke\" come from?', NULL, 'medium', 'Japanese'),
(35, 'What does \"syntax\" refer to?', NULL, 'hard', 'sentence structure'),
(36, 'What is the plural of \"analysis\"?', NULL, 'hard', 'analyses'),
(37, 'Translate \"bonjour\" from French.', NULL, 'easy', 'hello'),
(38, 'What is an adjective?', NULL, 'easy', 'a word that describes a noun'),
(39, 'Which language is used in the Quran?', NULL, 'medium', 'Arabic'),
(40, 'What is a \"prefix\"?', NULL, 'medium', 'a group of letters placed before a word'),
(41, 'What does \"bilingual\" mean?', NULL, 'easy', 'speaking two languages'),
(42, 'What language is official in Brazil?', NULL, 'easy', 'Portuguese'),
(43, 'What is the past tense of \"run\"?', NULL, 'easy', 'ran'),
(44, 'What is the main language spoken in Canada?', NULL, 'medium', 'English and French'),
(45, 'Which language uses the Devanagari script?', NULL, 'hard', 'Hindi'),
(46, 'What is a \"suffix\"?', NULL, 'medium', 'a group of letters placed at the end of a word'),
(47, 'What language does the word \"safari\" come from?', NULL, 'medium', 'Swahili'),
(48, 'What is the plural of \"analysis\"?', NULL, 'hard', 'analyses'),
(49, 'What language is spoken in Argentina?', NULL, 'easy', 'Spanish'),
(50, 'What does \"morphology\" study?', NULL, 'hard', 'structure of words');

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int NOT NULL,
  `account_id` int NOT NULL,
  `score` int NOT NULL,
  `difficulty` enum('easy','medium','hard') NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`id`, `account_id`, `score`, `difficulty`, `created_at`) VALUES
(1, 2, 100, 'easy', '2025-05-18 14:27:54'),
(2, 1, 40, 'hard', '2025-05-18 14:28:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_options_question` (`question_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_score` (`account_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `fk_options_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `fk_scores_account` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
