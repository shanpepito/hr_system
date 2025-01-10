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
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `AttendanceID` int(11) NOT NULL,
  `EmployeeID` int(11) UNSIGNED NOT NULL,
  `Date` date NOT NULL,
  `ClockIn` time DEFAULT NULL,
  `ClockOut` time DEFAULT NULL,
  `Status` enum('Present','Absent','On Leave') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`AttendanceID`, `EmployeeID`, `Date`, `ClockIn`, `ClockOut`, `Status`) VALUES
(17, 13, '2025-01-06', '21:15:34', '21:15:49', 'Present'),
(19, 13, '2025-01-08', '06:18:01', '00:20:25', 'Present'),
(24, 13, '2025-01-07', '14:09:40', '14:09:46', 'Present'),
(25, 13, '2025-01-08', '06:18:03', '08:19:05', 'Present'),
(26, 13, '2025-01-09', '06:18:03', '08:19:05', 'Present'),
(27, 13, '2025-01-10', '06:18:03', '08:19:05', 'Present'),
(28, 13, '2025-01-11', '06:18:03', '08:19:05', 'Present');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD KEY `fk_empAttId` (`EmployeeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `AttendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `fk_empAttId` FOREIGN KEY (`EmployeeID`) REFERENCES `users` (`EmployeeID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
