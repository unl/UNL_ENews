-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2010 at 11:13 AM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


--
-- Database: `enews`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob NOT NULL,
  `type` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `size` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
CREATE TABLE `newsletters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `newsroom_id` int(10) unsigned NOT NULL,
  `release_date` datetime DEFAULT NULL,
  `subject` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `intro` mediumtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `newsroom_id` (`newsroom_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_stories`
--

DROP TABLE IF EXISTS `newsletter_stories`;
CREATE TABLE `newsletter_stories` (
  `newsletter_id` int(10) unsigned NOT NULL,
  `story_id` int(10) unsigned NOT NULL,
  `sort_order` int(10) unsigned DEFAULT NULL,
  `intro` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`newsletter_id`,`story_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsrooms`
--

DROP TABLE IF EXISTS `newsrooms`;
CREATE TABLE `newsrooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `shortname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shortname` (`shortname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsroom_stories`
--

DROP TABLE IF EXISTS `newsroom_stories`;
CREATE TABLE `newsroom_stories` (
  `newsroom_id` int(10) unsigned NOT NULL,
  `story_id` int(10) unsigned NOT NULL,
  `uid_created` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `status` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `source` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`newsroom_id`,`story_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stories`
--

DROP TABLE IF EXISTS `stories`;
CREATE TABLE `stories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `request_publish_start` datetime DEFAULT NULL,
  `request_publish_end` datetime DEFAULT NULL,
  `sponsor` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uid_created` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_submitted` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `request_publish_end` (`request_publish_end`),
  KEY `request_publish_start` (`request_publish_start`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `story_files`
--

DROP TABLE IF EXISTS `story_files`;
CREATE TABLE `story_files` (
  `story_id` int(10) unsigned NOT NULL,
  `file_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`story_id`,`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `uid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `newsroom_id` int(10) unsigned NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_has_permission`
--

DROP TABLE IF EXISTS `user_has_permission`;
CREATE TABLE `user_has_permission` (
  `user_uid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `newsroom_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_uid`,`newsroom_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
