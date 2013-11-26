-- phpMyAdmin SQL Dump
-- version 3.4.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 26, 2013 at 11:08 AM
-- Server version: 5.1.72
-- PHP Version: 5.3.3-7+squeeze17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `timeclock`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `employee_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `employee_uid` varchar(64) COLLATE utf8_bin NOT NULL,
  `employee_firstname` varchar(24) COLLATE utf8_bin NOT NULL,
  `employee_lastname` varchar(24) COLLATE utf8_bin NOT NULL,
  `employee_username` varchar(28) COLLATE utf8_bin NOT NULL,
  `employee_password` varchar(48) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_punch`
--

CREATE TABLE IF NOT EXISTS `employee_punch` (
  `employee_punch_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `pay_period_id` int(2) unsigned NOT NULL,
  `employee_id` int(3) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `date` varchar(8) COLLATE utf8_bin NOT NULL,
  `operation` varchar(3) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`employee_punch_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=84 ;

-- --------------------------------------------------------

--
-- Table structure for table `pay_periods`
--

CREATE TABLE IF NOT EXISTS `pay_periods` (
  `pay_period_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `pay_period_monday` int(10) unsigned NOT NULL,
  `pay_period_sunday` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pay_period_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `setting_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `setting_value` varchar(128) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
