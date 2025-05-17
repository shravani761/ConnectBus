-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2025 at 04:05 PM
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
-- Database: `registrationdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `busdetails`
--

CREATE TABLE `busdetails` (
  `id` int(11) NOT NULL,
  `Source` varchar(50) NOT NULL,
  `Destination` varchar(50) NOT NULL,
  `Bustype` varchar(50) NOT NULL,
  `Capacity` int(60) NOT NULL,
  `DriverID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `busdetails`
--

INSERT INTO `busdetails` (`id`, `Source`, `Destination`, `Bustype`, `Capacity`, `DriverID`) VALUES
(8, 'bangalore', 'chennai', 'Metro', 10, 5),
(9, 'hyderabad', 'razole', 'Airbus', 12, 6),
(10, 'mumbai', 'kashmir', 'Airbus', 50, 6),
(11, 'chennai', 'kerala', 'metro', 80, 10),
(12, 'kochi', 'bihar', 'Airbus', 50, 6),
(13, 'Rajasthan', 'Mangalore', 'Luxury', 60, 17),
(14, 'hyderabad', 'miryalaguda', 'superluxury', 60, 18);

-- --------------------------------------------------------

--
-- Table structure for table `driverlogin`
--

CREATE TABLE `driverlogin` (
  `Username` varchar(50) NOT NULL,
  `Password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driverlogin`
--

INSERT INTO `driverlogin` (`Username`, `Password`) VALUES
('srivani', '8745872q3');

-- --------------------------------------------------------

--
-- Table structure for table `driverreg`
--

CREATE TABLE `driverreg` (
  `DriverID` int(11) NOT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Phone__no` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driverreg`
--

INSERT INTO `driverreg` (`DriverID`, `Username`, `Email`, `Password`, `Phone__no`) VALUES
(5, 'Satyasai', 'sairazole1417@gmail.com', 'Admin@123', 7731066049),
(6, 'tester', 'test@gmail.com', 'Admin@123', 9704504153),
(7, 'spurthy', 'spu@gmail.com', '685674', 8564723190),
(8, 'kalpana', 'kal@gmail.com', '685674', 654987321),
(9, 'kavya', 'kav@gmail.com', '987654', 987654321),
(10, 'varsha', 'var@gmail.com', '800861', 986543210),
(11, 'varsha', 'spoorthy@gmail.com', '987654', 987654321),
(14, 'see', 'see@gmail.com', '987546', 8564723190),
(16, 'sam', 'sam@gmail.com', '123456', 4532678965),
(17, 'Arjun', 'Arju@gmail.com', '8179766', 9517538429),
(18, 'harika', 'har@gmail.com', 'abcd1234', 6578943212);

-- --------------------------------------------------------

--
-- Table structure for table `userlogin`
--

CREATE TABLE `userlogin` (
  `Username` varchar(50) NOT NULL,
  `Password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userlogin`
--

INSERT INTO `userlogin` (`Username`, `Password`) VALUES
('rakshithabairi', '70327823'),
('shravani', '9849385'),
('sam', '12348'),
('sam', '123');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Phone__no` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Username`, `Email`, `Password`, `Phone__no`) VALUES
(1, 'rakshitha', 'bairirakshitha@gmail.com', '1234568', 986543210),
(2, 'sam', 'sam@gmail.com', '12348', 4532678965),
(4, 'john', 'john@gmail.com', '98363', 42),
(5, 'Satyasai', 'sairazole1417@gmail.com', 'Admin@123', 7731066049),
(6, 'spoorthy', 'spoorthy@gmail.com', '800861', 987654321),
(7, 'sam', 'sam@gmail.com', '951753624', 4532678965),
(8, 'yashu', 'ya@gmail.com', '654987', 9876547896),
(9, 'siri', 'sir@gmail.com', '987654', 987654321),
(10, 'siri', 'sir@gmail.com', '80086', 987654321),
(12, 'sri', 'bairirakshitha@gmail.com', '80086', 986543210),
(13, 'sris', 'sris@gmal', '987654', 8564723190),
(14, 'sris', 'bairirakshitha@gmail.com', '654987', 986543210),
(15, 'sris', 'bairirakshitha@gmail.com', '987654', 986543210),
(16, 'sree', 'sree@gmail', '12386019', 80086131),
(18, 'sony', 'son@gmail.com', '9347685', 9876543215),
(19, 'akshitha', 'aks@gmail.com', '1234567', 987654321);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `busdetails`
--
ALTER TABLE `busdetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_driver` (`DriverID`);

--
-- Indexes for table `driverreg`
--
ALTER TABLE `driverreg`
  ADD PRIMARY KEY (`DriverID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `busdetails`
--
ALTER TABLE `busdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `driverreg`
--
ALTER TABLE `driverreg`
  MODIFY `DriverID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `busdetails`
--
ALTER TABLE `busdetails`
  ADD CONSTRAINT `fk_driver` FOREIGN KEY (`DriverID`) REFERENCES `driverreg` (`DriverID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
