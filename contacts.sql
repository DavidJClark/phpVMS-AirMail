-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 29, 2013 at 07:18 AM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phpvms`
--

-- --------------------------------------------------------

--
-- Table structure for table `phpvms_contacts`
--

CREATE TABLE IF NOT EXISTS `phpvms_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pilot` int(11) NOT NULL,
  `contact` int(11) NOT NULL,
  `blocked` enum('Y','N') NOT NULL DEFAULT 'N',
  `relation` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pilot` (`pilot`),
  KEY `contact` (`contact`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `phpvms_contacts`
--

--
-- Constraints for dumped tables
--

--
-- Constraints for table `phpvms_contacts`
--
ALTER TABLE `phpvms_contacts`
  ADD CONSTRAINT `phpvms_contacts_ibfk_1` FOREIGN KEY (`pilot`) REFERENCES `phpvms_pilots` (`pilotid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `phpvms_contacts_ibfk_2` FOREIGN KEY (`contact`) REFERENCES `phpvms_pilots` (`pilotid`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
