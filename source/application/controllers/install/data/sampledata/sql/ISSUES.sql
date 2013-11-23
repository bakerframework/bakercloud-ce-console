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
-- Table structure for table `ISSUES`
--

CREATE TABLE IF NOT EXISTS `ISSUES` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `APP_ID` varchar(255) NOT NULL,
  `NAME` varchar(100) DEFAULT NULL,
  `PRICING` enum('paid','free') NOT NULL DEFAULT 'paid',
  `PRODUCT_ID` varchar(255) DEFAULT NULL,
  `TITLE` varchar(100) DEFAULT NULL,
  `INFO` varchar(500) DEFAULT NULL,
  `DATE` datetime DEFAULT NULL,
  `AVAILABILITY` enum('pending','published') NOT NULL DEFAULT 'pending',
  `COVER` varchar(1024) DEFAULT NULL,
  `URL` varchar(1024) DEFAULT NULL,
  `ITUNES_SUMMARY` varchar(1024) DEFAULT NULL,
  `ITUNES_COVERART_URL` varchar(1024) DEFAULT NULL,
  `ITUNES_PUBLISHED` varchar(26) DEFAULT NULL,
  `ITUNES_UPDATED` varchar(26) DEFAULT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `ISSUES`
--

INSERT INTO `ISSUES` (`ID`, `APP_ID`, `NAME`, `PRICING`, `PRODUCT_ID`, `TITLE`, `INFO`, `DATE`, `AVAILABILITY`, `COVER`, `URL`, `ITUNES_SUMMARY`, `ITUNES_COVERART_URL`, `ITUNES_PUBLISHED`, `ITUNES_UPDATED`) VALUES
(8, 'com.nin9creative.baker', 'BakerMagazine-January', 'paid', 'com.nin9creative.baker.issues.january2013', 'January 2013', 'Apple''s Man of the Year Issue.', '2013-01-01 00:00:00', 'published', 'http://www.newsstandcontent.com/publication/com.nin9creative.baker/BookCoverTemplate@2x.png', 'http://www.newsstandcontent.com/publication/com.nin9creative.baker/samplemagazine.hpub', 'In this issue we interview Steve Jobs on all things Apple.', 'http://www.newsstandcontent.com/publication/com.nin9creative.magrocket/itunes/iTunesAtomFeedCoverArt.png', '2012-11-01T00:00:00-07:00', '2012-11-01T00:00:00-07:00'),
(13, 'com.nin9creative.baker', 'BakerMagazine-February', 'free', '', 'February 2013', 'The top 10 developers of the decade.', '2013-02-01 00:00:00', 'published', 'http://www.newsstandcontent.com/publication/com.nin9creative.baker/BookCoverTemplate@2x.png', 'http://www.newsstandcontent.com/publication/com.nin9creative.baker/samplemagazine.hpub', 'Bond.  James Bond.', 'http://www.newsstandcontent.com/publication/com.nin9creative.magrocket/itunes/iTunesAtomFeedCoverArt.png', '2012-10-01T00:00:00-07:00', '2012-10-01T00:00:00-07:00'),
(14, 'com.nin9creative.baker', 'BakerMagazine-March', 'paid', 'com.nin9creative.magrocket.issues.march2013', 'March 2013', 'All you need to know about Objective-C.', '2013-03-01 00:00:00', 'published', 'http://www.newsstandcontent.com/publication/com.nin9creative.baker/BookCoverTemplate@2x.png', 'http://www.newsstandcontent.com/publication/com.nin9creative.baker/samplemagazine.hpub', 'The latest issue for the ultimate professional photographer.', 'http://www.newsstandcontent.com/publication/com.nin9creative.magrocket/itunes/iTunesAtomFeedCoverArt.png', '2012-12-01T00:00:00-07:00', '2012-12-01T00:00:00-07:00'),
(15, 'com.nin9creative.baker', 'BakerMagazine-April', 'paid', 'com.nin9creative.magrocket.baker.april2013', 'April 2013', 'Interview with the Baker team.', '2013-04-01 00:00:00', 'published', 'http://www.newsstandcontent.com/publication/com.nin9creative.baker/BookCoverTemplate@2x.png', 'http://www.newsstandcontent.com/publication/com.nin9creative.baker/samplemagazine.hpub', 'Brad Pitt loves bluetooth headsets.', 'http://www.newsstandcontent.com/publication/com.nin9creative.magrocket/itunes/iTunesAtomFeedCoverArt.png', '2012-09-01T00:00:00-07:00', '2012-09-01T00:00:00-07:00'),
(16, 'com.nin9creative.baker', 'BakerMagazine-May', 'paid', 'com.nin9creative.baker.issues.may2013', 'May 2013', 'Which iPad?  Mini or Not?', '2013-05-01 00:00:00', 'published', 'http://www.newsstandcontent.com/publication/com.nin9creative.baker/BookCoverTemplate@2x.png', 'http://www.newsstandcontent.com/publication/com.nin9creative.baker/samplemagazine.hpub', 'Jake Gyllenhaal is in a lot of movies.', 'http://www.newsstandcontent.com/publication/com.nin9creative.magrocket/itunes/iTunesAtomFeedCoverArt.png', '2012-08-01T00:00:00-07:00', '2012-08-01T00:00:00-07:00'),
(17, 'com.nin9creative.baker', 'BakerMagazine-June', 'paid', 'com.nin9creative.baker.issues.june2013', 'June 2013', 'Apples or Oranges?', '2013-06-01 00:00:00', 'published', 'http://www.newsstandcontent.com/publication/com.nin9creative.baker/BookCoverTemplate@2x.png', 'http://www.newsstandcontent.com/publication/com.nin9creative.baker/samplemagazine.hpub', 'Eminem has recovered and is back on the charts.', 'http://www.newsstandcontent.com/publication/com.nin9creative.magrocket/itunes/iTunesAtomFeedCoverArt.png', '2012-07-01T00:00:00-07:00', '2012-07-01T00:00:00-07:00'),
(18, 'com.nin9creative.baker', 'BakerMagazine-July', 'paid', 'com.nin9creative.baker.issues.july2013', 'July 2013', 'To iOS or not to iOS.', '2013-07-01 00:00:00', 'published', 'http://www.newsstandcontent.com/publication/com.nin9creative.baker/BookCoverTemplate@2x.png', 'http://www.newsstandcontent.com/publication/com.nin9creative.baker/samplemagazine.hpub', 'Is it Johnny or Jack?', 'http://www.newsstandcontent.com/publication/com.nin9creative.magrocket/itunes/iTunesAtomFeedCoverArt.png', '2012-06-01T00:00:00-07:00', '2012-06-01T00:00:00-07:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
