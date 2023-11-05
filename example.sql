-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2023 at 08:11 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `konyvtar`
--

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Agatha Christie', NULL, NULL),
(2, 'Charles Osborne', NULL, NULL),
(3, 'Randall Munroe', '2023-11-04 10:14:46', '2023-11-04 10:14:46');
--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `isbn`, `thumbnail`, `publish_year`, `created_at`, `updated_at`) VALUES
(1, '9780002261982', 'https://upload.wikimedia.org/wikipedia/en/0/0e/Spiders_Web_First_Edition_Cover_2000.jpg', 2000, '2023-11-04 11:44:28', '2023-11-05 18:49:54'),
(4, '9783328106906', 'https://m.media-amazon.com/images/I/51oz8oVYo8L.jpg', 2014, '2023-11-05 16:48:47', '2023-11-05 18:49:39');
--
-- Dumping data for table `author_book`
--

INSERT INTO `author_book` (`book_id`, `author_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(1, 2, NULL, NULL),
(4, 3, NULL, NULL);

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `short_name`, `long_name`, `created_at`, `updated_at`) VALUES
(1, 'EN', 'English', NULL, NULL),
(2, 'HU', 'Magyar', NULL, NULL);

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `created_at`, `updated_at`) VALUES
(11, '2023-11-05 16:55:14', '2023-11-05 16:55:14'),
(12, '2023-11-05 16:56:12', '2023-11-05 16:56:12'),
(14, '2023-11-05 17:20:55', '2023-11-05 17:20:55'),
(15, '2023-11-05 17:24:50', '2023-11-05 17:24:50'),
(27, '2023-11-05 17:52:28', '2023-11-05 17:52:28');

--
-- Dumping data for table `book_tag`
--

INSERT INTO `book_tag` (`book_id`, `tag_id`, `created_at`, `updated_at`) VALUES
(1, 14, NULL, NULL),
(1, 15, NULL, NULL),
(4, 11, NULL, NULL),
(4, 12, NULL, NULL);


--
-- Dumping data for table `tag_translations`
--

INSERT INTO `tag_translations` (`id`, `tag_id`, `language_id`, `name`, `created_at`, `updated_at`) VALUES
(16, 11, 1, 'Scientific', '2023-11-05 16:55:14', '2023-11-05 16:55:14'),
(17, 11, 2, 'Tudományos', '2023-11-05 16:55:14', '2023-11-05 16:55:14'),
(18, 12, 1, 'Comedy', '2023-11-05 16:56:12', '2023-11-05 16:56:12'),
(19, 12, 2, 'Humor', '2023-11-05 16:56:12', '2023-11-05 16:56:12'),
(22, 14, 1, 'Novel', '2023-11-05 17:20:55', '2023-11-05 17:20:55'),
(23, 14, 2, 'Novella', '2023-11-05 17:20:55', '2023-11-05 17:20:55'),
(24, 15, 1, 'Crime', '2023-11-05 17:24:50', '2023-11-05 17:24:50'),
(25, 15, 2, 'Krimi', '2023-11-05 17:24:50', '2023-11-05 17:24:50'),
(45, 27, 1, 'Mystery', '2023-11-05 17:52:28', '2023-11-05 17:52:28'),
(46, 27, 2, 'Rejtély', '2023-11-05 17:52:28', '2023-11-05 17:52:28');

--
-- Dumping data for table `translations`
--

INSERT INTO `translations` (`id`, `book_id`, `language_id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(8, 4, 1, 'What If?', 'Serious Scientific Answers to Absurd Hypothetical Questions', '2023-11-05 17:02:28', '2023-11-05 17:02:28'),
(9, 1, 1, 'Spider\'s Web', 'A witty and comedic mystery that follows Clarissa, who accidentally discovers a dead body in her drawing room. As she tries to dispose of the body, a web of lies and misunderstandings unfolds, leading to unexpected twists and turns in true Agatha Christie fashion. The play is known for its humor and clever plot twists.', '2023-11-05 17:25:03', '2023-11-05 17:25:03'),
(10, 1, 2, 'Pókháló', 'Egy esős tavaszi estén Clarissa éppen megszabadul vidéki kúriájukban a szállóvendégektől, akik vacsorázni mennek a golfklubba, mikor férje nagy hírrel tér haza: a köd miatt „Mr. Jones” repülője nem Londonban száll le, hanem a házuktól néhány mérföldnyire lévő kis repülőtéren. A miniszterelnök is hozzájuk tart egy kis titkos megbeszélésre. Ám amikor Hailsham-Brown elindul „Mr. Jones” elé, Clarissa egy hullát talál a nappaliban!', '2023-11-05 17:25:50', '2023-11-05 17:25:50'),
(12, 4, 2, 'Mi lenne ha?', '\"Néha az is felemelő érzést jelent, ha nem döntjük romba a világot\"', '2023-11-05 17:53:54', '2023-11-05 17:53:54');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
