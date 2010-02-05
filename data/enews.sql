-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 05, 2010 at 11:59 AM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `enews`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob NOT NULL,
  `type` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `size` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE IF NOT EXISTS `newsletter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `newsroom_id` int(10) unsigned NOT NULL,
  `release_date` datetime NOT NULL,
  `subject` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `intro` mediumtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `newsroom_id` (`newsroom_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_stories`
--

CREATE TABLE IF NOT EXISTS `newsletter_stories` (
  `newsletter_id` int(10) unsigned NOT NULL,
  `story_id` int(10) unsigned NOT NULL,
  `order` int(10) unsigned DEFAULT NULL,
  `intro` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`newsletter_id`,`story_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsrooms`
--

CREATE TABLE IF NOT EXISTS `newsrooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `shortname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shortname` (`shortname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `newsroom_stories`
--

CREATE TABLE IF NOT EXISTS `newsroom_stories` (
  `newsroom_id` int(10) unsigned NOT NULL,
  `story_id` int(10) unsigned NOT NULL,
  `uid_created` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `status` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`newsroom_id`,`story_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stories`
--

CREATE TABLE IF NOT EXISTS `stories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `event_date` datetime DEFAULT NULL,
  `sponsor` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uid_created` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_submitted` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`,`event_date`,`uid_created`,`date_submitted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `story_files`
--

CREATE TABLE IF NOT EXISTS `story_files` (
  `story_id` int(10) unsigned NOT NULL,
  `file_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`story_id`,`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_has_permission`
--

CREATE TABLE IF NOT EXISTS `user_has_permission` (
  `user_uid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `newsroom_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_uid`,`newsroom_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
