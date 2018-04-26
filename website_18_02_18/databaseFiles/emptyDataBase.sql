-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Host: csmysql.cs.cf.ac.uk
-- Generation Time: Feb 09, 2018 at 01:51 PM
-- Server version: 5.1.73
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `c1672934`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE IF NOT EXISTS `Admin` (
  `adminID` int(11) NOT NULL AUTO_INCREMENT,
  `adminUsername` varchar(25) NOT NULL,
  `adminPassword` varchar(150) NOT NULL,
  PRIMARY KEY (`adminID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Candidate`
--

CREATE TABLE IF NOT EXISTS `Candidate` (
  `candidateID` int(11) NOT NULL AUTO_INCREMENT,
  `candidateName` varchar(50) NOT NULL,
  `candidateParty` varchar(25) NOT NULL,
  `candidateArea` varchar(25) NOT NULL,
  PRIMARY KEY (`candidateID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Election`
--

CREATE TABLE IF NOT EXISTS `Election` (
  `electionID` int(11) NOT NULL AUTO_INCREMENT,
  `electionName` varchar(50) NOT NULL,
  `electionType` varchar(25) NOT NULL,
  `electionArea` varchar(25) NOT NULL,
  `electionDate` date NOT NULL,
  PRIMARY KEY (`electionID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Election0`
--

CREATE TABLE IF NOT EXISTS `Election0` (
  `voterNIN` varchar(11) NOT NULL,
  `candidateID` int(11) NOT NULL,
  PRIMARY KEY (`voterNIN`,`candidateID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Voter`
--

CREATE TABLE IF NOT EXISTS `Voter` (
  `voterNIN` varchar(11) NOT NULL,
  `voterPassword` varchar(150) NOT NULL,
  `voterConstituency` varchar(25) NOT NULL,
  PRIMARY KEY (`voterNIN`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
