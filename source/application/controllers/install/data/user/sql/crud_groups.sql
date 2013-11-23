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
-- Table structure for table `crud_groups`
--

CREATE TABLE IF NOT EXISTS `crud_groups` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) DEFAULT NULL,
  `group_description` text,
  `group_manage_flag` tinyint(4) DEFAULT NULL,
  `group_full_controll` tinyint(4) DEFAULT NULL,
  `group_read` tinyint(4) DEFAULT NULL,
  `group_read_write` tinyint(4) DEFAULT NULL,
  `group_read_delete` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `crud_groups`
--

INSERT INTO `crud_groups` (`id`, `group_name`, `group_description`, `group_manage_flag`, `group_full_controll`, `group_read`, `group_read_write`, `group_read_delete`) VALUES
(1, 'Administrators', NULL, 3, 1, 2, 2, 2),
(2, 'Super Users', '', 0, 2, 2, 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
