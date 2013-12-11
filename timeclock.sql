-- phpMyAdmin SQL Dump
-- version 4.0.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 11, 2013 at 12:04 PM
-- Server version: 5.5.31-0+wheezy1
-- PHP Version: 5.4.4-14+deb7u5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(256) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `client_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `client_name` varchar(256) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`client_id`),
  UNIQUE KEY `client_id` (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `employee_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `employee_uid` varchar(64) COLLATE utf8_bin NOT NULL,
  `category_id` int(2) unsigned NOT NULL,
  `employee_firstname` varchar(24) COLLATE utf8_bin NOT NULL,
  `employee_lastname` varchar(24) COLLATE utf8_bin NOT NULL,
  `employee_username` varchar(28) COLLATE utf8_bin NOT NULL,
  `employee_password` varchar(48) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`employee_id`),
  UNIQUE KEY `employee_id` (`employee_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=15 ;

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
  PRIMARY KEY (`employee_punch_id`),
  UNIQUE KEY `employee_punch_id` (`employee_punch_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=85 ;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
  `job_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `job_uid` varchar(256) COLLATE utf8_bin NOT NULL,
  `job_name` varchar(256) COLLATE utf8_bin NOT NULL,
  `client` int(3) unsigned NOT NULL,
  `status` enum('na','wip','c') COLLATE utf8_bin NOT NULL DEFAULT 'na',
  PRIMARY KEY (`job_id`),
  UNIQUE KEY `job_id_2` (`job_id`),
  KEY `client` (`client`),
  KEY `job_id` (`job_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `job_punch`
--

CREATE TABLE IF NOT EXISTS `job_punch` (
  `punch_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(5) unsigned NOT NULL,
  `employee_id` int(3) unsigned NOT NULL,
  `category_id` int(2) unsigned NOT NULL,
  `date` varchar(8) COLLATE utf8_bin NOT NULL,
  `time` int(10) NOT NULL,
  `operation` enum('in','out') COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`punch_id`),
  UNIQUE KEY `punch_id` (`punch_id`),
  KEY `employee_id` (`employee_id`,`category_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `pay_periods`
--

CREATE TABLE IF NOT EXISTS `pay_periods` (
  `pay_period_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `pay_period_monday` int(10) unsigned NOT NULL,
  `pay_period_sunday` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pay_period_id`),
  UNIQUE KEY `pay_period_id` (`pay_period_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `setting_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `setting_value` varchar(128) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `setting_id` (`setting_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=15 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`client`) REFERENCES `clients` (`client_id`);

--
-- Constraints for table `job_punch`
--
ALTER TABLE `job_punch`
  ADD CONSTRAINT `job_punch_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `job_punch_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
