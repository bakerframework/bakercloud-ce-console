-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 23, 2013 at 05:26 PM
-- Server version: 5.5.32-cll
-- PHP Version: 5.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bakerc_cloudce`
--

-- --------------------------------------------------------

--
-- Table structure for table `APNS_TOKENS`
--

CREATE TABLE IF NOT EXISTS `APNS_TOKENS` (
  `APP_ID` varchar(255) NOT NULL DEFAULT '',
  `USER_ID` varchar(255) NOT NULL DEFAULT '',
  `APNS_TOKEN` varchar(64) NOT NULL DEFAULT '',
  `STATUS` enum('active','uninstalled') NOT NULL DEFAULT 'active',
  `MODIFIED` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`APP_ID`(25),`USER_ID`(25))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `APNS_TOKENS`
--

INSERT INTO `APNS_TOKENS` (`APP_ID`, `USER_ID`, `APNS_TOKEN`, `STATUS`, `MODIFIED`) VALUES
('com.nin9creative.baker', 'D5DBF605-995F-4D66-8393-45D1017D12B9', '4afcff1849f07e24d1193f33116aa39a6f5ee61fb13e9d6880eab69127ce417c', 'active', '2013-11-23 22:10:39');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
