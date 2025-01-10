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
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `PositionID` int(11) NOT NULL,
  `Title` varchar(100) NOT NULL,
  `BaseSalary` decimal(10,2) NOT NULL,
  `DepartmentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`PositionID`, `Title`, `BaseSalary`, `DepartmentID`) VALUES
(13, 'IT Manager', 20000.00, 3),
(14, 'CRM Manager', 20000.00, 4),
(15, 'HR Manager', 20000.00, 5),
(16, 'HR Staff', 20000.00, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`PositionID`),
  ADD KEY `departmentId_fk` (`DepartmentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `PositionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `position`
--
ALTER TABLE `position`
  ADD CONSTRAINT `departmentId_fk` FOREIGN KEY (`DepartmentID`) REFERENCES `department` (`DepartmentID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
