-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Host: csmysql.cs.cf.ac.uk
-- Generation Time: Apr 26, 2018 at 12:35 PM
-- Server version: 5.1.73
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `group8_2017`
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `Admin`
--

INSERT INTO `Admin` (`adminID`, `adminUsername`, `adminPassword`) VALUES
(1, 'adminNumber1', 'adminpassword123'),
(2, 'adminNumber2', 'securepassword123');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `Candidate`
--

INSERT INTO `Candidate` (`candidateID`, `candidateName`, `candidateParty`, `candidateArea`) VALUES
(1, 'FInlay Clayton', 'Labour', 'Cardiff North'),
(2, 'Keira Archer', 'Conservative ', 'Cardiff North'),
(3, 'Harvey Miller', 'Plaid Cymru', 'Cardiff North'),
(4, 'Aimee Peacock', 'Green', 'Cardiff North'),
(5, 'Hayden Dyer', 'Labour', 'Cardiff Central'),
(6, 'Jacob Lee', 'Conservative', 'Cardiff Central'),
(7, 'Reece Davey', 'Plaid Cymru', 'Cardiff Central'),
(8, 'Jacob Charlton', 'Green', 'Cardiff Central'),
(9, 'Andrew Curtis', 'Labour', 'Cardiff West'),
(10, 'Tyler Kemp', 'Conservative', 'Cardiff West'),
(11, 'Molly Brooks', 'Plaid Cymru', 'Cardiff West'),
(12, 'Ewan Patterson', 'Green', 'Cardiff West'),
(13, 'Sofia Weston', 'Labour', 'Cardiff South'),
(14, 'Sam Howarth', 'Conservative', 'Cardiff South'),
(15, 'Henry Bentley', 'Plaid Cymru', 'Cardiff South');

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
  `electionDisplayName` char(50) NOT NULL,
  `electionCandidates` varchar(255) NOT NULL,
  PRIMARY KEY (`electionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `Election`
--

INSERT INTO `Election` (`electionID`, `electionName`, `electionType`, `electionArea`, `electionDate`, `electionDisplayName`, `electionCandidates`) VALUES
(1, 'GeneralElection2018', 'FPTP', 'National', '2018-05-16', 'General Election 2018', '');

-- --------------------------------------------------------

--
-- Table structure for table `GeneralElection2018`
--

CREATE TABLE IF NOT EXISTS `GeneralElection2018` (
  `voterNIN` varchar(11) NOT NULL,
  `candidateID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `GeneralElection2018`
--

INSERT INTO `GeneralElection2018` (`voterNIN`, `candidateID`) VALUES
('NW964012', 0),
('VW485910', 13),
('FK052701', 9),
('ZW989276', 11),
('YM867040', 15);

-- --------------------------------------------------------

--
-- Table structure for table `Voter`
--

CREATE TABLE IF NOT EXISTS `Voter` (
  `Username` varchar(9) NOT NULL,
  `Password` varchar(16) NOT NULL,
  `Constituency` varchar(25) NOT NULL,
  `hasVoted` tinyint(1) NOT NULL,
  PRIMARY KEY (`Username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Voter`
--

INSERT INTO `Voter` (`Username`, `Password`, `Constituency`, `hasVoted`) VALUES
('VW485910', 'cat123', 'Cardiff South', 1),
('NW964012', 'dog123', 'Cardiff North', 1),
('KJ942380', 'password1', 'Cardiff Central', 0),
('FK052701', 'Hello1', 'Cardiff West', 1),
('RF167993', 'outofpws1', 'Cardiff Central', 0),
('UF083211', 'tiger12', 'Cardiff North', 0),
('YM867040', 'pw12334', 'Cardiff South', 1),
('ZW989276', 'crazyboy123', 'Cardiff West', 1),
('QM786065', 'crazygirl123', 'Cardiff South', 0),
('XI278189', 'last123', 'Cardiff Central', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
