-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2025 at 07:57 PM
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
(4, 13, 2, 2, 2, 3, 'Good', 'sdasdas', '2025-01-07 10:23:33'),
(5, 54, 2, 2, 3, 3, 'Very Good', 'iyfgv', '2025-01-09 06:04:11'),
(6, 16, 3, 3, 1, 2, 'Excellent', 'ihiugyv', '2025-01-09 06:04:39'),
(8, 13, 4, 1, 1, 1, 'Good', 'bcvbc', '2025-01-09 20:13:15'),
(10, 54, 4, 5, 3, 5, 'Excellent', 'lkjkghfv', '2025-01-10 05:11:17');

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
(28, 13, '2025-01-11', '06:18:03', '08:19:05', 'Present'),
(29, 14, '2025-01-09', '05:43:13', '05:44:31', 'Present'),
(30, 16, '2025-01-09', '19:16:28', '20:14:21', 'Present'),
(31, 54, '2025-01-09', '19:16:44', '20:05:39', 'Present'),
(33, 59, '2025-01-09', '21:28:44', '21:28:48', 'Present'),
(34, 54, '2025-01-10', '18:46:29', NULL, 'Present'),
(35, 14, '2025-01-10', '19:02:23', NULL, 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `candidateeval`
--

CREATE TABLE `candidateeval` (
  `EvaluationID` int(11) NOT NULL,
  `RecruitmentID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Gender` enum('Male','Female','Other') NOT NULL,
  `DOB` date NOT NULL DEFAULT current_timestamp(),
  `CandidateEmail` varchar(255) NOT NULL,
  `CandidateNum` varchar(20) NOT NULL,
  `Address` text NOT NULL,
  `DepartmentID` int(11) NOT NULL,
  `PositionID` int(11) NOT NULL,
  `ApplicationDate` datetime NOT NULL,
  `ScheduleID` int(11) NOT NULL,
  `EvaluatorName` varchar(100) NOT NULL,
  `EvaluationDate` datetime NOT NULL DEFAULT current_timestamp(),
  `EvalStatus` enum('On Going Interview','Hired','Rejected') NOT NULL DEFAULT 'On Going Interview'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidateeval`
--

INSERT INTO `candidateeval` (`EvaluationID`, `RecruitmentID`, `FirstName`, `LastName`, `Gender`, `DOB`, `CandidateEmail`, `CandidateNum`, `Address`, `DepartmentID`, `PositionID`, `ApplicationDate`, `ScheduleID`, `EvaluatorName`, `EvaluationDate`, `EvalStatus`) VALUES
(15, 24, 'Shantal', 'Pepito', 'Female', '2025-01-08', 'Shantalpepito28@gmail.com', '09913654517', 'BINABAG, ESTACA', 3, 13, '2025-01-08 11:36:55', 9, 'Cristine', '2025-01-09 21:21:25', 'Hired'),
(16, 35, 'Shantal', 'Pepito', 'Female', '2025-01-09', 'Shantalpepito1@gmail.com', '09913654517', 'BINABAG, ESTACA', 7, 18, '2025-01-09 12:26:01', 19, 'Cristine', '2025-01-09 21:26:30', 'Hired'),
(17, 37, 'Shantal', 'Pepito', 'Female', '2025-01-24', 'Shantalpepito3@gmail.com', '09913654517', 'wdbjbd', 7, 18, '2025-01-09 19:07:15', 20, 'Not Provided', '2025-01-10 04:07:31', 'Rejected');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `DepartmentID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`DepartmentID`, `Name`) VALUES
(3, 'IT'),
(4, 'CRM'),
(5, 'HR'),
(6, 'Finance'),
(7, 'Sales');

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
  `PerformanceRating` varchar(255) DEFAULT NULL,
  `Created_At` timestamp NULL DEFAULT current_timestamp(),
  `Updated_At` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ProfileImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`EmployeeID`, `FirstName`, `LastName`, `Gender`, `Email`, `Phone`, `DOB`, `Address`, `DepartmentID`, `PositionID`, `Hire_Date`, `Salary`, `Attendance_Percentage`, `PerformanceRating`, `Created_At`, `Updated_At`, `ProfileImage`) VALUES
(11, 'Cristine', 'Lafable', 'Female', 'lafablecristine@gmail.com', '09636540509', '2025-01-07', 'Purok Waterlily Yati', 5, 15, '2025-01-07', 0.00, NULL, NULL, '2025-01-06 19:22:24', '2025-01-10 17:42:53', NULL),
(13, 'Ariadne', 'Arsolon', 'Female', 'ser@gmail.com', '09636540509', '2025-01-07', 'Purok Waterlily Yati', 5, 16, '2025-01-07', 2590.92, 31.8182, 'Good', '2025-01-06 19:40:08', '2025-01-10 17:45:48', NULL),
(14, 'Shantal', 'Pepito', 'Female', 'shan@gmail.com', '09636540509', '2025-01-07', 'Purok Waterlily Yati', 4, 14, '2025-01-07', 863.64, 9.09091, NULL, '2025-01-06 23:50:18', '2025-01-10 18:02:23', NULL),
(16, 'Ivy', 'Inagong', 'Female', 'ivyinagong@gmail.com', '09987654321', '2000-06-16', 'Danao', 6, 17, '2025-01-07', 863.64, 4.54545, 'Excellent', '2025-01-08 07:51:10', '2025-01-10 17:42:53', NULL),
(54, 'Jayne', 'Arias', 'Female', 'jaynearias@gmail.com', '09348294724', '2025-01-08', 'Canamucan', 3, 13, '2025-01-08', 863.64, 9.09091, 'Excellent', '2025-01-09 05:35:00', '2025-01-10 17:46:29', NULL),
(58, 'Shantal', 'Pepito', 'Female', 'Shantalpepito28@gmail.com', '09913654517', '2025-01-08', 'BINABAG, ESTACA', 3, 13, '2025-01-09', 0.00, NULL, NULL, '2025-01-09 20:21:29', '2025-01-10 17:42:53', NULL),
(59, 'Shantal', 'Pepito', 'Female', 'Shantalpepito1@gmail.com', '09913654517', '2025-01-09', 'BINABAG, ESTACA', 7, 18, '2025-01-09', 863.64, 4.54545, NULL, '2025-01-09 20:26:38', '2025-01-10 17:42:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `interviewschedule`
--

CREATE TABLE `interviewschedule` (
  `ScheduleID` int(11) NOT NULL,
  `RecruitmentID` int(11) NOT NULL,
  `InterviewDate` datetime NOT NULL,
  `Location` varchar(255) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interviewschedule`
--

INSERT INTO `interviewschedule` (`ScheduleID`, `RecruitmentID`, `InterviewDate`, `Location`, `CreatedAt`, `UpdatedAt`) VALUES
(9, 24, '2025-01-13 00:00:00', 'Interview Room 1', '2025-01-08 19:49:17', '2025-01-08 19:49:17'),
(19, 35, '2025-01-13 00:00:00', 'Interview Room 1', '2025-01-09 20:26:15', '2025-01-09 20:26:15'),
(20, 37, '2025-01-13 00:00:00', 'Interview Room 1', '2025-01-10 03:07:25', '2025-01-10 03:07:25');

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
(9, 13, 3, 'kjsdjals', 'sdkasdlk', 'open'),
(10, 18, 7, 'dfhfhd', 'dgfhgf', 'open');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `PayrollID` int(11) NOT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `PositionID` int(11) NOT NULL,
  `AttendancePercentage` float DEFAULT NULL,
  `DeductionAmount` decimal(10,2) DEFAULT 0.00,
  `NetSalary` decimal(10,2) NOT NULL,
  `PayrollDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`PayrollID`, `EmployeeID`, `PositionID`, `AttendancePercentage`, `DeductionAmount`, `NetSalary`, `PayrollDate`) VALUES
(14, 11, 15, NULL, 0.00, 0.00, '2025-01-10'),
(15, 13, 16, 13.6364, 136.36, 2590.92, '2025-01-10'),
(16, 14, 14, 4.54545, 45.45, 863.64, '2025-01-10'),
(17, 16, 17, 4.54545, 45.45, 863.64, '2025-01-10'),
(18, 54, 13, 4.54545, 45.45, 863.64, '2025-01-10'),
(19, 58, 13, NULL, 0.00, 0.00, '2025-01-10'),
(20, 59, 18, 4.54545, 45.45, 863.64, '2025-01-10');

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
(16, 'HR Staff', 20000.00, 5),
(17, 'Finance Manager', 20000.00, 6),
(18, 'Sales Manager', 20000.00, 7);

-- --------------------------------------------------------

--
-- Table structure for table `recruitment`
--

CREATE TABLE `recruitment` (
  `RecruitmentID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Gender` enum('Male','Female','Other') NOT NULL,
  `DOB` date NOT NULL DEFAULT current_timestamp(),
  `CandidateEmail` varchar(255) NOT NULL,
  `CandidateNum` varchar(20) NOT NULL,
  `Address` text NOT NULL,
  `Resume` varchar(255) NOT NULL,
  `DepartmentID` int(11) NOT NULL,
  `PositionID` int(11) NOT NULL,
  `ApplicationDate` datetime NOT NULL DEFAULT current_timestamp(),
  `HiringStatus` enum('Applied','Rejected','Passed') NOT NULL DEFAULT 'Applied'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruitment`
--

INSERT INTO `recruitment` (`RecruitmentID`, `FirstName`, `LastName`, `Gender`, `DOB`, `CandidateEmail`, `CandidateNum`, `Address`, `Resume`, `DepartmentID`, `PositionID`, `ApplicationDate`, `HiringStatus`) VALUES
(24, 'Shantal', 'Pepito', 'Female', '2025-01-08', 'Shantalpepito28@gmail.com', '09913654517', 'BINABAG, ESTACA', 'resume-example.pdf', 3, 13, '2025-01-08 11:36:55', 'Passed'),
(35, 'Shantal', 'Pepito', 'Female', '2025-01-09', 'Shantalpepito1@gmail.com', '09913654517', 'BINABAG, ESTACA', 'resume-example.pdf', 7, 18, '2025-01-09 12:26:01', 'Passed'),
(36, 'Shantal', 'Pepito', 'Other', '2025-01-09', 'Shantalpepito2@gmail.com', '09913654517', 'Danao', 'resume-example.pdf', 3, 13, '2025-01-09 12:27:28', 'Rejected'),
(37, 'Shantal', 'Pepito', 'Female', '2025-01-24', 'Shantalpepito3@gmail.com', '09913654517', 'wdbjbd', 'resume-example.pdf', 7, 18, '2025-01-09 19:07:15', 'Passed');

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
(29, 13, '2025-01-07', '2025-01-07', 'Sick Leave', 'Approved', 'sdasd'),
(30, 13, '2025-01-09', '2025-01-16', 'Vacation', 'Rejected', 'hjgjf'),
(31, 54, '2025-01-11', '2025-01-15', 'Vacation', 'Approved', 'ekfniuef');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) UNSIGNED NOT NULL,
  `EmployeeID` int(11) UNSIGNED NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('Employee','Admin','Manager') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `EmployeeID`, `Username`, `Password`, `Role`) VALUES
(5, 13, 'ariadne', '$2y$10$BxCRlHHZCbiZbd5S.aO4rOST3puJXqpt/627IqsjXBZ7pdrLwknhS', 'Employee'),
(6, 14, 'Shantal', '$2y$10$vqazozR4Gow3uwtUdNvoTesFdZVv2eYBYoUt02UZbEsz6YIHzMim6', 'Manager'),
(8, 11, 'cristine', '$2y$10$l3OEdBfWEp2DK0fpPg3Lw.HJbR51uUck48bU.tJE7s.qvRcty4Uza', 'Admin'),
(9, 54, 'jayne', '$2y$10$HHtTuunYZEH9qspjvSDhI.D6DhJIesySYMFwhMzuFYEaKC3voXOOi', 'Employee'),
(10, 16, 'ivy', '$2y$10$oAR5JgPl7Sw6KD.XWT27u./OLK3GCkEtLbdAz/u3FEnHvbRihJgB2', 'Employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appraisal`
--
ALTER TABLE `appraisal`
  ADD PRIMARY KEY (`AppraisalID`),
  ADD KEY `employeeID` (`EmployeeID`) USING BTREE;

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD KEY `EmployeeID` (`EmployeeID`) USING BTREE;

--
-- Indexes for table `candidateeval`
--
ALTER TABLE `candidateeval`
  ADD PRIMARY KEY (`EvaluationID`),
  ADD KEY `RecruitmentID` (`RecruitmentID`) USING BTREE,
  ADD KEY `DepartmentID` (`DepartmentID`) USING BTREE,
  ADD KEY `PositionID` (`PositionID`),
  ADD KEY `EvaluatorID` (`EvaluatorName`),
  ADD KEY `ScheduleID` (`ScheduleID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`DepartmentID`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`EmployeeID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `PositionID` (`PositionID`) USING BTREE,
  ADD KEY `DepartmentID` (`DepartmentID`) USING BTREE;

--
-- Indexes for table `interviewschedule`
--
ALTER TABLE `interviewschedule`
  ADD PRIMARY KEY (`ScheduleID`),
  ADD UNIQUE KEY `RecruitmentID` (`RecruitmentID`);

--
-- Indexes for table `jobposting`
--
ALTER TABLE `jobposting`
  ADD PRIMARY KEY (`JobID`),
  ADD KEY `PositionID` (`PositionID`) USING BTREE;

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`PayrollID`),
  ADD KEY `EmployeeID` (`EmployeeID`),
  ADD KEY `fk_position` (`PositionID`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`PositionID`),
  ADD KEY `DepartmentID` (`DepartmentID`) USING BTREE;

--
-- Indexes for table `recruitment`
--
ALTER TABLE `recruitment`
  ADD PRIMARY KEY (`RecruitmentID`),
  ADD KEY `DepartmentID` (`DepartmentID`) USING BTREE,
  ADD KEY `PositionID` (`PositionID`) USING BTREE;

--
-- Indexes for table `timeoff`
--
ALTER TABLE `timeoff`
  ADD PRIMARY KEY (`TimeOffID`),
  ADD KEY `EmployeeID` (`EmployeeID`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appraisal`
--
ALTER TABLE `appraisal`
  MODIFY `AppraisalID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `AttendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `candidateeval`
--
ALTER TABLE `candidateeval`
  MODIFY `EvaluationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `DepartmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `EmployeeID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `interviewschedule`
--
ALTER TABLE `interviewschedule`
  MODIFY `ScheduleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `jobposting`
--
ALTER TABLE `jobposting`
  MODIFY `JobID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `PayrollID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `PositionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `recruitment`
--
ALTER TABLE `recruitment`
  MODIFY `RecruitmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `timeoff`
--
ALTER TABLE `timeoff`
  MODIFY `TimeOffID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appraisal`
--
ALTER TABLE `appraisal`
  ADD CONSTRAINT `appraisal_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employees` (`EmployeeID`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employees` (`EmployeeID`) ON DELETE CASCADE;

--
-- Constraints for table `candidateeval`
--
ALTER TABLE `candidateeval`
  ADD CONSTRAINT `candidateeval_ibfk_1` FOREIGN KEY (`DepartmentID`) REFERENCES `department` (`DepartmentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidateeval_ibfk_2` FOREIGN KEY (`RecruitmentID`) REFERENCES `recruitment` (`RecruitmentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidateeval_ibfk_3` FOREIGN KEY (`PositionID`) REFERENCES `position` (`PositionID`),
  ADD CONSTRAINT `candidateeval_ibfk_4` FOREIGN KEY (`ScheduleID`) REFERENCES `interviewschedule` (`ScheduleID`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`DepartmentID`) REFERENCES `department` (`DepartmentID`) ON DELETE CASCADE;

--
-- Constraints for table `interviewschedule`
--
ALTER TABLE `interviewschedule`
  ADD CONSTRAINT `FK_Recruitment` FOREIGN KEY (`RecruitmentID`) REFERENCES `recruitment` (`RecruitmentID`) ON DELETE CASCADE;

--
-- Constraints for table `jobposting`
--
ALTER TABLE `jobposting`
  ADD CONSTRAINT `fk_PositionID` FOREIGN KEY (`PositionID`) REFERENCES `position` (`PositionID`) ON DELETE CASCADE;

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `fk_position` FOREIGN KEY (`PositionID`) REFERENCES `position` (`PositionID`) ON DELETE CASCADE;

--
-- Constraints for table `position`
--
ALTER TABLE `position`
  ADD CONSTRAINT `position_ibfk_1` FOREIGN KEY (`DepartmentID`) REFERENCES `department` (`DepartmentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recruitment`
--
ALTER TABLE `recruitment`
  ADD CONSTRAINT `recruitment_ibfk_1` FOREIGN KEY (`DepartmentID`) REFERENCES `department` (`DepartmentID`) ON DELETE CASCADE;

--
-- Constraints for table `timeoff`
--
ALTER TABLE `timeoff`
  ADD CONSTRAINT `fk_empTimeOff` FOREIGN KEY (`EmployeeID`) REFERENCES `employees` (`EmployeeID`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employees` (`EmployeeID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
