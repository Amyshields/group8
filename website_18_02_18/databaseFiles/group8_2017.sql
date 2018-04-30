-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 20, 2018 at 12:37 PM
-- Server version: 5.6.38
-- PHP Version: 7.2.1

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

CREATE TABLE `Admin` (
  `adminID` int(11) NOT NULL,
  `adminUsername` varchar(25) NOT NULL,
  `adminPassword` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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

CREATE TABLE `Candidate` (
  `candidateID` int(11) NOT NULL,
  `candidateName` varchar(50) NOT NULL,
  `candidateParty` varchar(25) NOT NULL,
  `candidateArea` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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

CREATE TABLE `Election` (
  `electionID` int(11) NOT NULL,
  `electionName` varchar(50) NOT NULL,
  `electionType` varchar(25) NOT NULL,
  `electionArea` varchar(25) NOT NULL,
  `electionDate` date NOT NULL,
  `electionCandidates` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Election`
--

INSERT INTO `Election` (`electionID`, `electionName`, `electionType`, `electionArea`, `electionDate`, `electionCandidates`) VALUES
(1, 'GeneralElection2018', 'FPTP', 'National', '2018-05-16', '1');

-- --------------------------------------------------------

--
-- Table structure for table `GeneralElection2018`
--

CREATE TABLE `GeneralElection2018` (
  `voterNIN` varchar(11) NOT NULL,
  `candidateID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Voter`
--

CREATE TABLE `Voter` (
  `Username` varchar(9) NOT NULL,
  `Password` varchar(16) NOT NULL,
  `Constituency` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Voter`
--

INSERT INTO `Voter` (`Username`, `Password`, `Constituency`) VALUES
('VW485910', 'cat123', 'Cardiff South'),
('NW964012', 'dog123', 'Cardiff North'),
('KJ942380', 'password1', 'Cardiff Central'),
('FK052701', 'Hello1', 'Cardiff West'),
('RF167993', 'outofpws1', 'Cardiff Central'),
('UF083211', 'tiger12', 'Cardiff North'),
('YM867040', 'pw12334', 'Cardiff South'),
('ZW989276', 'crazyboy123', 'Cardiff West'),
('QM786065', 'crazygirl123', 'Cardiff South'),
('XI278189', 'last123', 'Cardiff Central');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`adminID`);

--
-- Indexes for table `Candidate`
--
ALTER TABLE `Candidate`
  ADD PRIMARY KEY (`candidateID`);

--
-- Indexes for table `Election`
--
ALTER TABLE `Election`
  ADD PRIMARY KEY (`electionID`);

--
-- Indexes for table `Voter`
--
ALTER TABLE `Voter`
  ADD PRIMARY KEY (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admin`
--
ALTER TABLE `Admin`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Candidate`
--
ALTER TABLE `Candidate`
  MODIFY `candidateID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `Election`
--
ALTER TABLE `Election`
  MODIFY `electionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
