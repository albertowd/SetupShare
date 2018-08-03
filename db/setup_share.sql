-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 19, 2018 at 08:58 PM
-- Server version: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `setup_share`
--

-- --------------------------------------------------------

--
-- Table structure for table `setup`
--

CREATE TABLE `setup` (
  `ac_version` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `car` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `driver` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL,
  `ini` blob NOT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `sp` blob,
  `steam_id` bigint(20) DEFAULT NULL,
  `track` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `version_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `visibility` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `setup`
--
ALTER TABLE `setup`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`car`,`name`,`steam_id`,`track`) USING BTREE,
  ADD KEY `ID_CAR` (`car`),
  ADD KEY `ID_DRIVER` (`driver`),
  ADD KEY `ID_TRACK` (`track`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `setup`
--
ALTER TABLE `setup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
