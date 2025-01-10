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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employees` (`EmployeeID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
