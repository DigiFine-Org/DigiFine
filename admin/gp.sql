-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2024 at 08:08 AM
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
-- Database: `gp`
--

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` varchar(20) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `fname`, `lname`, `email`, `phone_no`, `nic`, `password`, `created_at`) VALUES
('b1234567', 'John', 'Doe', 'mrclocktd@gmail.com', '0766743755', '200224903146', '$2y$10$DooxggI8H82zkdYel3kRjOt7uylaneKtQ/qPwmqVemccJrjprJVlq', '2024-09-19 14:59:12');

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE `officers` (
  `id` varchar(20) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `police_station` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_oic` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officers`
--

INSERT INTO `officers` (`id`, `fname`, `lname`, `email`, `phone_no`, `nic`, `police_station`, `password`, `created_at`, `is_oic`) VALUES
('23233', 'John', 'Doe', 'johndoe@example.com', '0766743755', '200224903146', 'PS003', '$2y$10$31Rt2SOxQ1OAHrpXBLEJt.bcTBB3AZKbZTbpJeR6ySr1qQHhrFoUu', '2024-09-18 18:36:28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `police_stations`
--

CREATE TABLE `police_stations` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `police_stations`
--

INSERT INTO `police_stations` (`id`, `name`, `address`, `created_at`) VALUES
('PS001', 'Central Police Station', '123 Main St, Springfield, IL', '2024-09-18 06:29:46'),
('PS002', 'Northside Police Station', '456 Elm St, Springfield, IL', '2024-09-18 06:29:46'),
('PS003', 'Southside Police Station', '789 Oak St, Springfield, IL', '2024-09-18 06:29:46'),
('PS004', 'Eastside Police Station', '101 Pine St, Springfield, IL', '2024-09-18 06:29:46'),
('PS005', 'Westside Police Station', '202 Maple St, Springfield, IL', '2024-09-18 06:29:46');

-- --------------------------------------------------------

--
-- Table structure for table `update_driver_profile_requests`
--

CREATE TABLE `update_driver_profile_requests` (
  `id` varchar(20) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `update_officer_profile_requests`
--

CREATE TABLE `update_officer_profile_requests` (
  `id` varchar(20) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `police_station` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_no` (`phone_no`),
  ADD UNIQUE KEY `nic` (`nic`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_no` (`phone_no`),
  ADD UNIQUE KEY `nic` (`nic`),
  ADD KEY `police_station` (`police_station`);

--
-- Indexes for table `police_stations`
--
ALTER TABLE `police_stations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `update_driver_profile_requests`
--
ALTER TABLE `update_driver_profile_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_no` (`phone_no`),
  ADD UNIQUE KEY `nic` (`nic`);

--
-- Indexes for table `update_officer_profile_requests`
--
ALTER TABLE `update_officer_profile_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_no` (`phone_no`),
  ADD UNIQUE KEY `nic` (`nic`),
  ADD KEY `police_station` (`police_station`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `officers`
--
ALTER TABLE `officers`
  ADD CONSTRAINT `officers_ibfk_1` FOREIGN KEY (`police_station`) REFERENCES `police_stations` (`id`);

--
-- Constraints for table `update_driver_profile_requests`
--
ALTER TABLE `update_driver_profile_requests`
  ADD CONSTRAINT `update_driver_profile_requests_ibfk_1` FOREIGN KEY (`id`) REFERENCES `drivers` (`id`);

--
-- Constraints for table `update_officer_profile_requests`
--
ALTER TABLE `update_officer_profile_requests`
  ADD CONSTRAINT `update_officer_profile_requests_ibfk_1` FOREIGN KEY (`police_station`) REFERENCES `police_stations` (`id`),
  ADD CONSTRAINT `update_officer_profile_requests_ibfk_2` FOREIGN KEY (`id`) REFERENCES `officers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
