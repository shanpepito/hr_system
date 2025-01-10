-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2025 at 05:14 PM
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
-- Table structure for table `timeoff`
--

CREATE TABLE `timeoff` (
  `TimeOffID` int(11) NOT NULL,
  `EmployeeID` int(11) UNSIGNED DEFAULT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `Type` enum('Sick Leave','Vacation','Maternity Leave','Other') NOT NULL,
  `ApprovalStatus` enum('Pending','Approved','Rejected') NOT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timeoff`
--

INSERT INTO `timeoff` (`TimeOffID`, `EmployeeID`, `StartDate`, `EndDate`, `Type`, `ApprovalStatus`, `Description`) VALUES
(27, 13, '2025-01-07', '2025-01-07', 'Sick Leave', 'Approved', 'SDASD'),
(28, 15, '2025-01-07', '0000-00-00', 'Sick Leave', 'Approved', 'sdasd'),
(29, 13, '2025-01-07', '2025-01-07', 'Sick Leave', 'Approved', 'sdasd');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `timeoff`
--
ALTER TABLE `timeoff`
  ADD PRIMARY KEY (`TimeOffID`),
  ADD KEY `fk_empTimeOff` (`EmployeeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timeoff`
--
ALTER TABLE `timeoff`
  MODIFY `TimeOffID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `timeoff`
--
ALTER TABLE `timeoff`
  ADD CONSTRAINT `fk_empTimeOff` FOREIGN KEY (`EmployeeID`) REFERENCES `employees` (`EmployeeID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
