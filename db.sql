-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2019 at 09:49 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `commentform`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(6) UNSIGNED NOT NULL,
  `parent_id` int(6) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `comment` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `parent_id`, `email`, `name`, `comment`, `date`) VALUES
(1, NULL, 'andrius.inciura@gmail.com', 'Andrius InÄiura', 'Vienas, du, trys.', '2019-08-20 14:15:38'),
(2, NULL, 'pardenis.vavardenis@gmail.com', 'Pardenis Vavardenis', 'Testing, testing, everything seems to be in order.', '2019-08-20 14:57:10'),
(3, 1, 'tomas.tomaitis@gmail.com', 'Tomas Tomaitis', 'Hello.', '2019-08-20 23:48:29'),
(4, 2, 'juozas.juozaitis@gmail.com', 'Juozas Juozaitis', 'Labas.', '2019-08-20 23:49:34'),
(5, 2, 'domas.domaitis@gmail.com', 'Domas Domaitis', 'One, two, three.', '2019-08-20 23:50:52'),
(6, NULL, 'tadas.tadaitis@gmail.com', 'Tadas Tadaitis', 'My name is Tadas.', '2019-08-20 23:53:43'),
(7, 1, 'gytis.gytaitis@gmail.com', 'Gytis Gytaitis', 'Time to sleep.', '2019-08-20 23:56:23'),
(8, 2, 'ignas.ignaitis@gmail.com', 'Ignas Ignaitis', 'Ananasas.', '2019-08-21 11:54:09'),
(9, 6, 'rytis.rytaitis@gmail.com', 'Rytis Rytaitis', 'Mano vardas Rytis.', '2019-08-21 11:55:18'),
(10, NULL, 'dovydas.dovydaitis@gmail.com', 'Dovydas Dovydaitis', 'First!', '2019-08-21 11:56:08'),
(11, 6, 'petras.petraitis@gmail.com', 'Petras Petraitis', 'Mano vardas Petras Petraitis.', '2019-08-21 12:14:21'),
(12, 6, 'lukas.lukaitis@gmail.com', 'Lukas Lukaitis', 'My name is Lukas!', '2019-08-21 16:03:00'),
(13, 10, 'aldas.aldaitis@gmail.com', 'Aldas Aldaitis', '    * White space allowed.', '2019-08-22 07:45:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
