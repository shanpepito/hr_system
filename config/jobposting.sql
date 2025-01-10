-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2025 at 05:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hr_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `jobposting`
--

CREATE TABLE `jobposting` (
  `JobID` int(11) NOT NULL,
  `PositionID` int(11) NOT NULL,
  `DepartmentID` int(11) NOT NULL,
  `JobDesc` text NOT NULL,
  `JobQual` text NOT NULL,
  `JobStatus` enum('open','closed') NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobposting`
--

INSERT INTO `jobposting` (`JobID`, `PositionID`, `DepartmentID`, `JobDesc`, `JobQual`, `JobStatus`) VALUES
(9, 13, 3, 'kjsdjals', 'sdkasdlk', 'open');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobposting`
--
ALTER TABLE `jobposting`
  ADD PRIMARY KEY (`JobID`),
  ADD KEY `fk_PositionID` (`PositionID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobposting`
--
ALTER TABLE `jobposting`
  MODIFY `JobID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jobposting`
--
ALTER TABLE `jobposting`
  ADD CONSTRAINT `fk_PositionID` FOREIGN KEY (`PositionID`) REFERENCES `position` (`PositionID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
