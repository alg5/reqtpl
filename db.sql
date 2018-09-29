-- phpMyAdmin SQL Dump
-- version 3.3.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Фев 22 2011 г., 03:02
-- Версия сервера: 5.0.77
-- Версия PHP: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `reqtpl`
--

-- --------------------------------------------------------

--
-- Структура таблицы `phpbb_reqtpl_fields`
--

CREATE TABLE IF NOT EXISTS `phpbb_reqtpl_fields` (
  `tpl_id` mediumint(8) unsigned NOT NULL,
  `field_id` mediumint(8) unsigned NOT NULL auto_increment,
  `field_order` mediumint(8) NOT NULL default '0',
  `field_name` varchar(60) character set utf8 NOT NULL,
  `field_comment` varchar(500) character set utf8 NOT NULL,
  `field_type` mediumint(8) unsigned NOT NULL,
  `field_important` tinyint(1) NOT NULL default '0',
  `field_size` mediumint(8) unsigned NOT NULL default '0',
  `field_match` varchar(200) collate utf8_bin NOT NULL,
  `field_default` varchar(500) collate utf8_bin NOT NULL,
  `field_pattern` varchar(1000) character set utf8 NOT NULL default '%s',
  UNIQUE KEY `field_id` (`field_id`),
  KEY `tpl_id` (`tpl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Структура таблицы `phpbb_reqtpl_options`
--

CREATE TABLE IF NOT EXISTS `phpbb_reqtpl_options` (
  `field_id` mediumint(8) unsigned NOT NULL,
  `option_id` mediumint(8) unsigned NOT NULL auto_increment,
  `option_order` mediumint(8) NOT NULL default '0',
  `option_text` varchar(100) character set utf8 NOT NULL,
  PRIMARY KEY  (`option_id`),
  KEY `field_id` (`field_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

--
-- Структура таблицы `phpbb_reqtpl_templates`
--

CREATE TABLE IF NOT EXISTS `phpbb_reqtpl_templates` (
  `tpl_id` mediumint(8) unsigned NOT NULL auto_increment,
  `tpl_forum_id` mediumint(8) unsigned NOT NULL,
  `tpl_show` bit(1) NOT NULL default b'1',
  `tpl_name` varchar(60) character set utf8 NOT NULL,
  `tpl_comment` varchar(500) character set utf8 NOT NULL,
  PRIMARY KEY  (`tpl_id`),
  UNIQUE KEY `tpl_forum_id` (`tpl_forum_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;
