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
-- Table structure for table `crud_users`
--

CREATE TABLE IF NOT EXISTS `crud_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_id` bigint(20) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `user_first_name` varchar(255) DEFAULT NULL,
  `user_las_name` varchar(255) DEFAULT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_website` varchar(255) DEFAULT NULL,
  `user_aim` varchar(255) DEFAULT NULL,
  `user_yahoo` varchar(255) DEFAULT NULL,
  `user_skype` varchar(255) DEFAULT NULL,
  `user_info` text,
  `user_manage_flag` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `crud_users`
--

INSERT INTO `crud_users` (`id`, `group_id`, `user_name`, `user_password`, `user_first_name`, `user_las_name`, `user_image`, `user_email`, `user_website`, `user_aim`, `user_yahoo`, `user_skype`, `user_info`, `user_manage_flag`) VALUES
(1, 1, 'BakerAdmin', '7e34475ed9a94dc242234ea57def4ea63546e1a9', 'Baker', 'Administrator', '', 'admin@bakerframework.com', 'http://www.bakerframework.com', '', '', '', '<p>This is the Baker Cloud Console (CE) Administrator account.&nbsp; Do not tamper with this account.&nbsp; All users of the Baker Cloud Console (CE) should log in with the <strong>suser</strong> or <strong>user</strong> accounts.&nbsp; Using this account may harm or delete senstive data or corrupt the Baker Cloud Console (CE) installation.</p>\n', 0),
(2, 2, 'user', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Baker', 'User', '1354699676-image2.png', 'user@bakerframework.com', 'http://www.bakerframework.com', '', '', '', '', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
