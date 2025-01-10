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
-- Table structure for table `appraisal`
--

CREATE TABLE `appraisal` (
  `AppraisalID` int(11) NOT NULL,
  `EmployeeID` int(11) UNSIGNED NOT NULL,
  `QualityOfWork` int(5) DEFAULT NULL,
  `CommunicationSkills` int(5) DEFAULT NULL,
  `TeamWork` int(5) DEFAULT NULL,
  `Punctuality` int(5) DEFAULT NULL,
  `PerformanceRating` varchar(255) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `AppraisalDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appraisal`
--

INSERT INTO `appraisal` (`AppraisalID`, `EmployeeID`, `QualityOfWork`, `CommunicationSkills`, `TeamWork`, `Punctuality`, `PerformanceRating`, `comments`, `AppraisalDate`) VALUES
(3, 15, 3, 3, 3, 3, 'Very Good', 'koko', '2025-01-07 10:09:52'),
(4, 13, 2, 2, 2, 3, 'Good', 'sdasdas', '2025-01-07 10:23:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appraisal`
--
ALTER TABLE `appraisal`
  ADD PRIMARY KEY (`AppraisalID`),
  ADD KEY `employee_ibfk_1` (`EmployeeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appraisal`
--
ALTER TABLE `appraisal`
  MODIFY `AppraisalID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appraisal`
--
ALTER TABLE `appraisal`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employees` (`EmployeeID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
