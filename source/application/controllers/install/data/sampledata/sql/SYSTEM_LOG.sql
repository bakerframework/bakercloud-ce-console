-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 23, 2013 at 05:27 PM
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
-- Table structure for table `SYSTEM_LOG`
--

CREATE TABLE IF NOT EXISTS `SYSTEM_LOG` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TYPE` varchar(25) DEFAULT NULL,
  `MESSAGE` text,
  `TIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1321 ;

--
-- Dumping data for table `SYSTEM_LOG`
--

INSERT INTO `SYSTEM_LOG` (`ID`, `TYPE`, `MESSAGE`, `TIMESTAMP`) VALUES
(1319, 'Info', 'Time since last validating receipt for APP ID: com.nin9creative.baker USER ID: D5DBF605-995F-4D66-8393-45D1017D12B9 = 0 hours 21 minutes', '2013-04-26 02:38:37'),
(1318, 'Info', 'Checking subscription for APP ID: com.nin9creative.baker USER ID: D5DBF605-995F-4D66-8393-45D1017D12B9', '2013-04-26 02:38:37'),
(1316, 'Info', 'Retrieving Issues for APP ID: com.nin9creative.baker USER ID: D5DBF605-995F-4D66-8393-45D1017D12B9', '2013-04-26 02:38:37'),
(1317, 'Info', 'Checking purchases for APP ID: com.nin9creative.baker USER ID: D5DBF605-995F-4D66-8393-45D1017D12B9', '2013-04-26 02:38:37');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
