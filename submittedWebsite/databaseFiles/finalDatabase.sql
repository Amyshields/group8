-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Host: csmysql.cs.cf.ac.uk
-- Generation Time: May 10, 2018 at 11:32 AM
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
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `adminID` int(11) NOT NULL AUTO_INCREMENT,
  `adminUsername` varchar(25) NOT NULL,
  `adminPassword` varchar(150) NOT NULL,
  PRIMARY KEY (`adminID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminID`, `adminUsername`, `adminPassword`) VALUES
(1, 'noah', '$2y$10$ob2KDppBpTO9YHdCDzJnXOXhQKlxJ54ndLskGuHaga4IMaK2z9Shq'),
(5, 'amy', '$2y$10$UfPnJIUYRfugXXtYCJ8Ml.D//6xB/F/hTfS7TOLJmo7VvrbPIqBcO'),
(4, 'cyrus', '$2y$10$tIMLAuaCc490kuXZOlC6ZOQtBRdtx6pucH/o35YellvDQ3Y7r7Fda'),
(6, 'ben', '$2y$10$5WnvUOzhzERHjDkQ/7xVPuX88nzU8JvVGr11O.91KcJsEFuJddBES'),
(7, 'max', '$2y$10$ODdABd1Mqtlsh1Gh5dlR9eyUfjYqtnZoXWXJNkYxrzKGiad1OPr6K'),
(8, 'austen', '$2y$10$e6Ep64mtB5jwBNZJMIuMT.11TBmek0AQlT7y5vxqlzu9Z6bkokf3S'),
(9, 'dervla', '$2y$10$ciTdlynHSXbZCipyYiCGyu4l2nPIJbgY0PF0UA4Ahdd6OIdDGZpUi'),
(10, 'callum', '$2y$10$Rx9VLT.o6XfqamnffJmnXOg5AkBHh7iiNGA05/F/Bivh/A1NXnPjy'),
(11, 'ged', '$2y$10$lCiBvjwTYiEGE62BAfbngeHpp4UtYTksU9J/mMjnSW/rjSW77hYmy'),
(12, 'ermal', '$2y$10$Wwqm8KLSPeJMDyCw7D906.hwrMWwUDg098xiTLYgS2TcLJ1TPj/qe'),
(13, 'ElectionAdmin2018', '$2y$10$cTOpF/O3OGpyHdhsFbmxiOConi.0aNmhvjvoeEcZeXPcl8rRrtcWa');

-- --------------------------------------------------------

--
-- Table structure for table `adminPrivateKeys`
--

CREATE TABLE IF NOT EXISTS `adminPrivateKeys` (
  `adminID` int(10) NOT NULL,
  `adminUsername` varchar(20) NOT NULL,
  `privateKey` varchar(2000) NOT NULL,
  UNIQUE KEY `adminID` (`adminID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE IF NOT EXISTS `candidate` (
  `candidateID` int(11) NOT NULL AUTO_INCREMENT,
  `candidateName` varchar(50) NOT NULL,
  `candidateParty` varchar(25) NOT NULL,
  `candidateArea` varchar(25) NOT NULL,
  PRIMARY KEY (`candidateID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`candidateID`, `candidateName`, `candidateParty`, `candidateArea`) VALUES
(1, 'Finlay Clayton', 'Labour', 'Cardiff North'),
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
(15, 'Henry Bentley', 'Plaid Cymru', 'Cardiff South'),
(18, 'Andy Jones', 'Labour', 'Cardiff West'),
(25, 'John Smith', 'Labour', 'Reading West');

-- --------------------------------------------------------

--
-- Table structure for table `election`
--

CREATE TABLE IF NOT EXISTS `election` (
  `electionID` int(11) NOT NULL AUTO_INCREMENT,
  `electionName` varchar(50) NOT NULL,
  `electionType` varchar(25) NOT NULL,
  `electionArea` varchar(25) NOT NULL,
  `electionDate` date NOT NULL,
  `electionDisplayName` char(50) NOT NULL,
  `electionCandidates` varchar(255) NOT NULL,
  `isEncrypted` tinyint(1) NOT NULL,
  PRIMARY KEY (`electionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

-- --------------------------------------------------------

--
-- Table structure for table `voter`
--

CREATE TABLE IF NOT EXISTS `voter` (
  `Username` varchar(9) NOT NULL,
  `Password` varchar(150) NOT NULL,
  `Constituency` varchar(25) NOT NULL,
  PRIMARY KEY (`Username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voter`
--

INSERT INTO `voter` (`Username`, `Password`, `Constituency`) VALUES
('VW485910', '$2y$10$KRNoSYuVYqjTf9BsCkSFFO1vNXNTFbNWJ.lnw1vqS9YUZ1/grUt9K', 'Cardiff South'),
('NW964012', '$2y$10$x6EVqJTbSHbuTIYbIUbGX.762MDK0yZfBJ8jZng.A2kMwsLhws4Si', 'Cardiff North'),
('KJ942380', '$2y$10$lRmRFj1j3Qr0gvCHsSIP7uqnALGr9YYqhvRwTuW25giGfd/Zq7x1W', 'Cardiff Central'),
('FK052701', '$2y$10$JKW/jKpfx1.bHWuGy8cgjOdwtAoaMPHlszaIiax98Mk0XiEGrpZ86', 'Cardiff West'),
('RF167993', '$2y$10$wbN6wmDO5sqNa4fYT/86wO.Fs7lKYTJHXRqDDqOUnxAltysDev/Q.', 'Cardiff Central'),
('UF083211', '$2y$10$BLvRLsZSsb.ex/L7RMEzEe8hZkm8.eVZYlMWvbrPLtCakCQRzAFk6', 'Cardiff North'),
('YM867040', '$2y$10$mp6F2NeAHWC8dwLIZ6v1AeNnIOnWr8jevQSNJ7tsukhTaNRxqTZiO', 'Cardiff South'),
('ZW989276', '$2y$10$hbitcPg8QkU9W8CxyBbna.eHhq5lEhbIzvsmwvCRb0O2FCT.xo1gC', 'Cardiff West'),
('QM786065', '$2y$10$XFtYjn11Vc6CsSjzlEV7tuvi41VNG4WuvnsfDN2mH0Zh4NZ5mG0iy', 'Cardiff South'),
('XI278189', '$2y$10$yie7z97spAgoAHg.t.mM.OIyTrPvWzNyuydRP0klZnvX73BxT88Ki', 'Cardiff Central'),
('CD123456', '$2y$10$SfoW/9AjkP19rgf9YDbhHOCgJy12MdFNN1BARcJEM5xGelckmNALS', 'Cardiff Central');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
