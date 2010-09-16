-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 12, 2010 at 04:34 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

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
  `data` longblob NOT NULL,
  `type` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `use_for` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `newsroom_id` int(10) unsigned NOT NULL,
  `release_date` datetime DEFAULT NULL,
  `subject` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `intro` mediumtext COLLATE utf8_unicode_ci,
  `distributed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `newsroom_id` (`newsroom_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_stories`
--

CREATE TABLE IF NOT EXISTS `newsletter_stories` (
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

CREATE TABLE IF NOT EXISTS `newsrooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `shortname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_lists` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `allow_submissions` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `shortname` (`shortname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `source` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
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
  `full_article` longtext COLLATE utf8_unicode_ci NULL,
  `request_publish_start` datetime DEFAULT NULL,
  `request_publish_end` datetime DEFAULT NULL,
  `sponsor` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uid_created` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `uid_modified` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_submitted` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_publish_end` (`request_publish_end`),
  KEY `request_publish_start` (`request_publish_start`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `newsroom_id` int(10) unsigned NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`)
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
