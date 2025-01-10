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
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `EmployeeID` int(11) UNSIGNED NOT NULL,
  `FirstName` varchar(30) NOT NULL,
  `LastName` varchar(30) NOT NULL,
  `Gender` enum('Male','Female','Other') NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `DOB` date NOT NULL,
  `Address` text NOT NULL,
  `DepartmentID` int(11) NOT NULL,
  `PositionID` int(11) NOT NULL,
  `Hire_Date` date NOT NULL,
  `Salary` decimal(10,2) DEFAULT NULL,
  `Attendance_Percentage` float DEFAULT NULL,
  `Performance_Rating` varchar(255) DEFAULT NULL,
  `Created_At` timestamp NULL DEFAULT current_timestamp(),
  `Updated_At` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ProfileImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`EmployeeID`, `FirstName`, `LastName`, `Gender`, `Email`, `Phone`, `DOB`, `Address`, `DepartmentID`, `PositionID`, `Hire_Date`, `Salary`, `Attendance_Percentage`, `Performance_Rating`, `Created_At`, `Updated_At`, `ProfileImage`) VALUES
(7, 'Cristine', 'Lafable', 'Male', 'asda@gmail.com', '09636540503', '2025-01-05', 'Purok Waterlily Yati', 5, 15, '2025-01-05', 20000.00, 20, 'good', '2025-01-05 02:55:30', '2025-01-06 23:49:01', NULL),
(11, 'Cristine', 'Lafable', 'Female', 'lafablecristine@gmail.com', '09636540509', '2025-01-07', 'Purok Waterlily Yati', 5, 15, '2025-01-07', NULL, NULL, NULL, '2025-01-06 19:22:24', '2025-01-06 23:49:28', NULL),
(13, 'Ariadne', 'Arsolon', 'Female', 'ser@gmail.com', '09636540509', '2025-01-07', 'Purok Waterlily Yati', 5, 16, '2025-01-07', NULL, 13.6364, NULL, '2025-01-06 19:40:08', '2025-01-07 13:09:40', NULL),
(14, 'Shantal', 'Pepito', 'Female', 'shan@gmail.com', '09636540509', '2025-01-07', 'Purok Waterlily Yati', 4, 14, '2025-01-07', NULL, NULL, NULL, '2025-01-06 23:50:18', '2025-01-06 23:50:18', NULL),
(15, 'Jayne', 'Arias', 'Female', 'jayne@gmail.com', '09636540509', '2025-01-07', 'sdasd', 4, 14, '2025-01-07', NULL, NULL, NULL, '2025-01-07 07:42:29', '2025-01-07 07:42:29', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`EmployeeID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `Fk_Department` (`DepartmentID`),
  ADD KEY `fk_Position` (`PositionID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `EmployeeID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `Fk_Department` FOREIGN KEY (`DepartmentID`) REFERENCES `department` (`DepartmentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Position` FOREIGN KEY (`PositionID`) REFERENCES `position` (`PositionID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
