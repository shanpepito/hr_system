-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2025 at 09:29 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `candidateeval`
--

CREATE TABLE `candidateeval` (
  `EvaluationID` int(11) NOT NULL,
  `RecruitmentID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
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

INSERT INTO `candidateeval` (`EvaluationID`, `RecruitmentID`, `FirstName`, `LastName`, `CandidateEmail`, `CandidateNum`, `Address`, `DepartmentID`, `PositionID`, `ApplicationDate`, `ScheduleID`, `EvaluatorName`, `EvaluationDate`, `EvalStatus`) VALUES
(1, 21, 'Shantal', 'Pepito', 'Shantalpepito28@gmail.com', '09913654517', 'BINABAG, ESTACA', 2, 26, '2025-01-06 20:59:13', 6, 'Cristine', '2025-01-08 04:02:51', 'Hired'),
(2, 21, 'Shantal', 'Pepito', 'Shantalpepito28@gmail.com', '09913654517', 'BINABAG, ESTACA', 2, 26, '2025-01-06 20:59:13', 6, 'Cristine', '2025-01-08 04:02:56', 'Hired'),
(3, 22, 'Shantal', 'Pepito', 'Shantalpepito28@gmail.com', '09913654517', 'Estaca', 6, 25, '2025-01-06 22:18:05', 7, 'Cristine', '2025-01-08 06:44:25', 'Hired');

-- --------------------------------------------------------

--
-- Table structure for table `contract`
--

CREATE TABLE `contract` (
  `ContractID` int(11) NOT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `Terms` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(6, 'Finance');

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
(15, 'Jayne', 'Arias', 'Female', 'jayne@gmail.com', '09636540509', '2025-01-07', 'sdasd', 4, 14, '2025-01-07', NULL, NULL, NULL, '2025-01-07 07:42:29', '2025-01-07 07:42:29', NULL),
(16, 'Ivy', 'Inagong', 'Female', 'ivyinagong@gmail.com', '09987654321', '2000-06-16', 'Danao', 6, 17, '2025-01-07', NULL, NULL, NULL, '2025-01-08 07:51:10', '2025-01-08 07:51:30', NULL);

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
(4, 19, '2025-01-13 00:00:00', 'Interview Room 1', '2025-01-06 23:05:36', '2025-01-06 23:05:36'),
(5, 20, '2025-01-13 00:00:00', 'Interview Room 1', '2025-01-07 04:32:59', '2025-01-07 04:32:59'),
(6, 21, '2025-01-13 00:00:00', 'Interview Room 1', '2025-01-07 06:18:25', '2025-01-07 06:18:25'),
(7, 22, '2025-01-13 00:00:00', 'Interview Room 1', '2025-01-07 22:41:12', '2025-01-07 22:41:12');

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

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `PayrollID` int(11) NOT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `BaseSalary` decimal(10,2) NOT NULL,
  `Bonuses` decimal(10,2) DEFAULT 0.00,
  `Deductions` decimal(10,2) DEFAULT 0.00,
  `NetSalary` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(17, 'Finance Manager', 20000.00, 6);

-- --------------------------------------------------------

--
-- Table structure for table `recruitment`
--

CREATE TABLE `recruitment` (
  `RecruitmentID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
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

INSERT INTO `recruitment` (`RecruitmentID`, `FirstName`, `LastName`, `CandidateEmail`, `CandidateNum`, `Address`, `Resume`, `DepartmentID`, `PositionID`, `ApplicationDate`, `HiringStatus`) VALUES
(14, 'CRISTINE', 'LAFABLE', 'shantal3@gmail.com', '09987654321', 'Compostela', 'resume-example.pdf', 6, 25, '2025-01-05 20:24:44', 'Rejected'),
(15, 'Shantal', 'Pepito', 'Shantalpepito28@gmail.com', '09913654517', 'BINABAG, ESTACA', 'resume-example.pdf', 2, 26, '2025-01-05 20:25:02', 'Rejected'),
(19, 'Shantal', 'Pepito', 'Shantalpepito28@gmail.com', '09913654517', 'BINABAG, ESTACA', 'resume-example.pdf', 2, 26, '2025-01-06 15:05:12', ''),
(20, 'Shantal', 'Pepito', 'Shantalpepito28@gmail.com', '09913654517', 'BINABAG, ESTACA', 'resume-example.pdf', 2, 26, '2025-01-06 15:05:26', 'Rejected'),
(21, 'Shantal', 'Pepito', 'Shantalpepito28@gmail.com', '09913654517', 'BINABAG, ESTACA', 'resume-example.pdf', 2, 26, '2025-01-06 20:59:13', 'Passed'),
(22, 'Shantal', 'Pepito', 'Shantalpepito28@gmail.com', '09913654517', 'Estaca', 'resume-example.pdf', 6, 25, '2025-01-06 22:18:05', 'Passed');

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
(4, 7, 'cristine', '$2y$10$DC09YFXpVkNBSIxUqOkZXenBaIzZue0cOxlKd27kZ9MW48TXLHU5C', 'Admin'),
(5, 13, 'ariadne', '$2y$10$BxCRlHHZCbiZbd5S.aO4rOST3puJXqpt/627IqsjXBZ7pdrLwknhS', 'Employee'),
(6, 14, 'Shantal', '$2y$10$vqazozR4Gow3uwtUdNvoTesFdZVv2eYBYoUt02UZbEsz6YIHzMim6', 'Manager'),
(7, 15, 'jayne', '$2y$10$7wzUdSEGlIv.1mGkk6GnBODHOQrx8KQ66k8YaikZHe8.kzCZf5Ik.', 'Employee');

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
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD KEY `fk_empAttId` (`EmployeeID`);

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
-- Indexes for table `contract`
--
ALTER TABLE `contract`
  ADD PRIMARY KEY (`ContractID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

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
  ADD KEY `Fk_Department` (`DepartmentID`),
  ADD KEY `fk_Position` (`PositionID`);

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
  ADD KEY `fk_PositionID` (`PositionID`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`PayrollID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`PositionID`),
  ADD KEY `departmentId_fk` (`DepartmentID`);

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
  ADD KEY `fk_empTimeOff` (`EmployeeID`);

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
  MODIFY `AppraisalID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `AttendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `candidateeval`
--
ALTER TABLE `candidateeval`
  MODIFY `EvaluationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contract`
--
ALTER TABLE `contract`
  MODIFY `ContractID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `DepartmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `EmployeeID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `interviewschedule`
--
ALTER TABLE `interviewschedule`
  MODIFY `ScheduleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobposting`
--
ALTER TABLE `jobposting`
  MODIFY `JobID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `PayrollID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `PositionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `recruitment`
--
ALTER TABLE `recruitment`
  MODIFY `RecruitmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `timeoff`
--
ALTER TABLE `timeoff`
  MODIFY `TimeOffID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contract`
--
ALTER TABLE `contract`
  ADD CONSTRAINT `contract_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`) ON DELETE CASCADE;

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
