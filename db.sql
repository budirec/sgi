-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2016 at 06:37 PM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `budirec`
--

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE IF NOT EXISTS `player` (
  `player_id` char(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `credits` int(10) unsigned NOT NULL,
  `lifetime_wons` int(10) unsigned NOT NULL,
  `lifetime_spins` int(10) unsigned NOT NULL,
  `salt_value` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`player_id`, `name`, `credits`, `lifetime_wons`, `lifetime_spins`, `salt_value`) VALUES
('c842d82a-af1b-11e5-96ec-f0def1b59245', 'Budi', 1000, 0, 0, '9odjf3ywey'),
('c842f0ad-af1b-11e5-96ec-f0def1b59245', 'David', 100, 0, 0, 'k6cew3784y'),
('de3b8174-af1b-11e5-96ec-f0def1b59245', 'Hacker', 50, 0, 0, 'k23jrn43iu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`player_id`), ADD UNIQUE KEY `salt_value` (`salt_value`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
