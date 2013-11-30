-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 30, 2013 at 01:39 AM
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
-- Table structure for table `crud_permissions`
--

CREATE TABLE IF NOT EXISTS `crud_permissions` (
  `group_id` bigint(20) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `permission_type` tinyint(4) NOT NULL,
  PRIMARY KEY (`group_id`,`table_name`,`permission_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `crud_permissions`
--

INSERT INTO `crud_permissions` (`group_id`, `table_name`, `permission_type`) VALUES
(1, 'APNS_TOKENS', 1),
(1, 'APNS_TOKENS', 2),
(1, 'APNS_TOKENS', 3),
(1, 'APNS_TOKENS', 4),
(1, 'APNS_TOKENS', 5),
(1, 'crud_groups', 1),
(1, 'crud_groups', 2),
(1, 'crud_groups', 3),
(1, 'crud_groups', 4),
(1, 'crud_users', 1),
(1, 'crud_users', 2),
(1, 'crud_users', 3),
(1, 'crud_users', 4),
(1, 'ISSUES', 1),
(1, 'ISSUES', 2),
(1, 'ISSUES', 3),
(1, 'ISSUES', 4),
(1, 'ISSUES', 5),
(1, 'PUBLICATION', 1),
(1, 'PUBLICATION', 2),
(1, 'PUBLICATION', 3),
(1, 'PUBLICATION', 4),
(1, 'PUBLICATION', 5),
(1, 'PURCHASES', 1),
(1, 'PURCHASES', 2),
(1, 'PURCHASES', 3),
(1, 'PURCHASES', 4),
(1, 'PURCHASES', 5),
(1, 'RECEIPTS', 1),
(1, 'RECEIPTS', 2),
(1, 'RECEIPTS', 3),
(1, 'RECEIPTS', 4),
(1, 'RECEIPTS', 5),
(1, 'SUBSCRIPTIONS', 1),
(1, 'SUBSCRIPTIONS', 2),
(1, 'SUBSCRIPTIONS', 3),
(1, 'SUBSCRIPTIONS', 4),
(1, 'SUBSCRIPTIONS', 5),
(1, 'SYSTEM_LOG', 1),
(1, 'SYSTEM_LOG', 2),
(1, 'SYSTEM_LOG', 3),
(1, 'SYSTEM_LOG', 4),
(1, 'SYSTEM_LOG', 5),
(2, 'APNS_TOKENS', 1),
(2, 'APNS_TOKENS', 2),
(2, 'APNS_TOKENS', 3),
(2, 'APNS_TOKENS', 4),
(2, 'ISSUES', 1),
(2, 'ISSUES', 2),
(2, 'ISSUES', 3),
(2, 'ISSUES', 4),
(2, 'PUBLICATION', 1),
(2, 'PUBLICATION', 2),
(2, 'PUBLICATION', 3),
(2, 'PUBLICATION', 4),
(2, 'PURCHASES', 1),
(2, 'PURCHASES', 2),
(2, 'PURCHASES', 3),
(2, 'PURCHASES', 4),
(2, 'RECEIPTS', 1),
(2, 'RECEIPTS', 2),
(2, 'RECEIPTS', 3),
(2, 'RECEIPTS', 4),
(2, 'SUBSCRIPTIONS', 1),
(2, 'SUBSCRIPTIONS', 2),
(2, 'SUBSCRIPTIONS', 3),
(2, 'SUBSCRIPTIONS', 4),
(2, 'SYSTEM_LOG', 3),
(2, 'SYSTEM_LOG', 4);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
