-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-digifine.alwaysdata.net
-- Generation Time: Apr 22, 2025 at 12:41 PM
-- Server version: 10.11.9-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digifine_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `fname`, `lname`, `email`, `nic`, `password`) VALUES
(1100, 'Imalsha', 'Jathunarachchi', 'imalsha.contact@gmail.com', '200138241314', 'Digifine00');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `published_by` varchar(20) NOT NULL,
  `published_id` varchar(20) NOT NULL,
  `target_role` enum('admin','oic','officer','driver','all') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime DEFAULT NULL,
  `police_station` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `message`, `published_by`, `published_id`, `target_role`, `created_at`, `expires_at`, `police_station`) VALUES
(1, 'test', 'this is a test for all drivers', 'admin', '0', 'driver', '2024-12-10 18:54:34', '2024-12-17 01:24:00', NULL),
(2, 'test for oics', 'this is a test for OICs', 'admin', '0', 'oic', '2024-12-10 18:57:20', '2024-12-27 05:27:00', NULL),
(6, 'test all', 'this is an announcement for all users', 'admin', '0', 'all', '2024-12-10 19:16:23', '2024-12-24 07:46:00', NULL),
(15, 'test for officers', 'this is for officers', 'admin', '1234', 'officer', '2024-12-10 22:20:58', NULL, NULL),
(16, 'for all', 'this is fot all', 'admin', '1234', 'all', '2024-12-10 22:21:13', NULL, NULL),
(17, 'for drivers', 'this announcement is only for drivers', 'admin', '1234', 'driver', '2024-12-17 06:09:17', NULL, NULL),
(18, 'temporarily unavailable for maintenance or updates', 'Temporarily unvailable', 'admin', '1234', 'driver', '2024-12-17 18:01:32', '2024-12-17 23:32:00', NULL),
(19, 'Stolen Vehicle Update', 'NP|ABC-5367', 'admin', '1234', 'oic', '2024-12-17 18:04:13', '2024-12-17 23:35:00', NULL),
(31, 'gjsio', 'gsjgsjopg', 'oic', '23233', 'officer', '2024-12-30 20:54:04', NULL, 515),
(32, 'nioisov', 'sgjsijios jio sjgio', 'oic', '23233', 'officer', '2024-12-30 20:55:19', NULL, 515),
(33, 'Announcement for Officers', 'Site Development', 'oic', '23233', 'officer', '2024-12-31 08:37:56', '2024-12-02 14:07:00', 515),
(34, 'bug fix', 'this is the test after the bug fix', 'oic', '23233', 'officer', '2025-01-20 18:25:13', '2025-01-30 23:55:00', 515),
(35, 'test 1', 'check one', 'oic', '12005', 'officer', '2025-01-20 18:27:42', '2025-01-29 23:57:00', 250);

-- --------------------------------------------------------

--
-- Table structure for table `assigned_duties`
--

CREATE TABLE `assigned_duties` (
  `id` int(11) NOT NULL,
  `police_id` int(11) NOT NULL,
  `duty` text NOT NULL,
  `notes` text DEFAULT NULL,
  `duty_date` date NOT NULL,
  `duty_time_start` time NOT NULL,
  `duty_time_end` time NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `submitted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assigned_duties`
--

INSERT INTO `assigned_duties` (`id`, `police_id`, `duty`, `notes`, `duty_date`, `duty_time_start`, `duty_time_end`, `assigned_by`, `assigned_at`, `submitted`) VALUES
(2, 55555, 'Traffic Duty', 'Near the junction', '2024-09-10', '10:00:00', '11:00:00', 66666, '2024-12-03 17:40:52', 0),
(3, 77777, 'Night Patrol Duty', 'Near the Independence Square', '2024-09-19', '12:00:00', '01:30:00', 66666, '2024-12-03 17:43:24', 1),
(4, 55555, 'Night Patrol Duty', 'Near Jawatta', '0000-00-00', '00:00:00', '00:00:00', 66666, '2024-12-03 17:50:43', 0),
(5, 77777, 'Traffic Duty', 'Near the main traffic lights', '0000-00-00', '00:00:00', '00:00:00', 66666, '2024-12-03 17:55:17', 0),
(8, 55555, 'Traffic Duty', 'Near the crossing', '0000-00-00', '00:00:00', '00:00:00', 66666, '2024-12-08 08:29:30', 0),
(9, 77777, 'Night Patrol', 'Again same place', '0000-00-00', '00:00:00', '00:00:00', 66666, '2024-12-08 08:47:44', 1),
(10, 77777, 'Traffic', 'Near the school', '0000-00-00', '00:00:00', '00:00:00', 66666, '2024-12-08 11:58:34', 1),
(12, 77777, 'Speed Monitoring', 'Do it near the junction', '0000-00-00', '00:00:00', '00:00:00', 66666, '2024-12-08 18:28:19', 1),
(13, 77777, 'Traffic Duty', 'Near the junction', '0000-00-00', '00:00:00', '00:00:00', 66666, '2024-12-15 04:45:19', 1),
(27, 15368, 'Crowd Control', 'Near the embassy', '2025-01-27', '11:30:00', '12:30:00', 23233, '2025-01-25 19:15:38', 1),
(28, 15368, 'Crowd Control', 'Control the crowd near the embassy', '2025-01-27', '11:30:00', '12:30:00', 23233, '2025-01-25 19:24:32', 1),
(29, 15368, 'Traffic Duty', 'Near the school', '2025-01-28', '07:00:00', '08:00:00', 23233, '2025-01-25 19:53:56', 1),
(35, 12004, 'New Duty as Imalsha', '', '2025-04-09', '12:00:00', '13:00:00', 12005, '2025-04-08 08:55:47', 1),
(36, 12004, 'test 01', 'Make sure the fines', '2025-04-10', '00:00:00', '06:00:00', 12005, '2025-04-08 08:58:16', 1),
(42, 15368, 'Crowd Control', 'Near the junction', '2025-04-17', '08:00:00', '10:00:00', 23233, '2025-04-16 06:23:19', 0),
(43, 15368, 'Traffic Duty', 'Replacement', '2025-04-16', '12:16:00', '12:25:00', 23233, '2025-04-16 06:37:06', 0),
(44, 23230, 'Near the Maligawa', 'None', '2025-04-17', '12:00:00', '14:00:00', 23233, '2025-04-16 08:24:59', 1),
(45, 44113, 'Highway Patrol', 'Check Vehicles', '2025-04-10', '17:00:00', '18:15:00', 23233, '2025-04-17 09:20:08', 0),
(46, 12004, 'Duty on School Cross Line', '', '2025-04-17', '18:15:00', '20:15:00', 12005, '2025-04-17 10:45:22', 0),
(47, 15368, 'Traffic Duty', 'N/A', '2025-04-18', '08:00:00', '10:00:00', 23233, '2025-04-17 13:40:44', 0),
(48, 15368, 'Traffic Duty', 'nothing', '2025-04-18', '08:00:00', '10:00:00', 23233, '2025-04-17 13:42:50', 0),
(51, 23230, 'Traffic Duty', 'Nothing', '2025-04-19', '12:42:00', '12:45:00', 23233, '2025-04-19 07:03:01', 1),
(52, 23230, 'Traffic Duty', 'N/A', '2025-04-19', '14:35:00', '14:38:00', 23233, '2025-04-19 08:56:31', 1),
(53, 23230, 'Traffic Duty', 'hhhhhhh', '2025-04-20', '10:55:00', '11:00:00', 23233, '2025-04-20 05:17:25', 1),
(54, 23230, 'Speed Monitoring', 'N/A', '2025-04-20', '12:46:00', '13:30:00', 23233, '2025-04-20 07:07:31', 1),
(55, 23230, 'Traffic Duty', 'bygbyb', '2025-04-20', '13:00:00', '13:02:00', 23233, '2025-04-20 07:21:39', 1),
(56, 23230, 'Traffic Duty', 'Near the junction', '2025-04-22', '12:34:00', '13:00:00', 23233, '2025-04-22 06:56:29', 1),
(59, 23230, 'Traffic Duty', 'Near the park road', '2025-04-22', '20:00:00', '22:00:00', 23233, '2025-04-22 07:22:00', 0),
(60, 23230, 'Speed Monitoring', 'Near Highway', '2025-04-23', '18:00:00', '20:00:00', 23233, '2025-04-22 09:12:41', 0),
(61, 23230, 'Traffic Duty', 'Near the junction', '2025-04-22', '15:50:00', '16:45:00', 23233, '2025-04-22 10:08:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE `codes` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `code` varchar(5) NOT NULL,
  `expire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `codes`
--

INSERT INTO `codes` (`id`, `email`, `code`, `expire`) VALUES
(1, 'imalsha@gmail.com', '58947', 1736403220),
(2, 'imalsha@gmail.com', '93196', 1736403497),
(3, 'imalsha@gmail.com', '25512', 1736404559),
(4, 'imalsha@gmail.com', '15864', 1736404599),
(5, 'imalsha@gmail.com', '13378', 1736404613),
(6, 'imalsha.contact@gmail.com', '11138', 1736406303),
(7, 'imalsha@gmail.com', '66116', 1736420986),
(8, 'imalsha@gmail.com', '54981', 1736421049),
(9, 'siripala@gmail.com', '25647', 1737378193),
(10, 'imalshajathunarachchi@gmail.com', '95645', 1737420575),
(11, 'imalshajathunarachchi@gmail.com', '80033', 1737420719),
(12, 'siripala@gmail.com', '56250', 1737435616),
(13, 'imalshajathunarachchi@gmail.com', '67877', 1737436541),
(14, 'imalshajathunarachchi@gmail.com', '80985', 1737437192),
(15, 'imalshajathunarachchi@gmail.com', '87537', 1737440141),
(16, 'imalshajathunarachchi@gmail.com', '96792', 1737445061),
(17, 'imazja22@gmail.com', '78847', 1744638425),
(18, 'imazja22@gmail.com', '96824', 1744638594),
(19, 'imazja22@gmail.com', '36735', 1744638601),
(20, 'imazja22@gmail.com', '21735', 1744638607),
(21, 'imazja22@gmail.com', '59847', 1744638677),
(22, 'imazja22@gmail.com', '97114', 1744638683),
(23, 'imazja22@gmail.com', '72817', 1744638690),
(24, 'imazja22@gmail.com', '98300', 1744638755),
(25, 'imalsha.contact@gmail.com', '18236', 1744956427),
(26, 'imalsha.contact@gmail.com', '25501', 1744956531),
(27, 'imalsha.contact@gmail.com', '88989', 1744956709),
(28, 'imalsha.contact@gmail.com', '48372', 1744956769),
(29, 'imazjzen@gmail.com', '13568', 1744956935),
(30, 'imazjzen@gmail.com', '27900', 1744956999),
(31, 'cozifink@gmail.com', '90183', 1744957124),
(32, 'imazja22@gmail.com', '21839', 1744957415),
(33, 'cozifink@gmail.com', '67264', 1744957574),
(34, 'cozifink@gmail.com', '61311', 1744957636),
(35, 'cozifink@gmail.com', '12251', 1744957685),
(36, 'imalshajathunarachchi@gmail.com', '13113', 1745028151);

-- --------------------------------------------------------

--
-- Table structure for table `complaint_category`
--

CREATE TABLE `complaint_category` (
  `id` int(11) NOT NULL,
  `complaint` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaint_category`
--

INSERT INTO `complaint_category` (`id`, `complaint`) VALUES
(1, 'Abuse of Women or Children'),
(2, 'Appreciation'),
(3, 'Archeological Issue'),
(4, 'Assault'),
(5, 'Bribery and Corruption'),
(6, 'Complaint against Police'),
(7, 'Criminal Offence'),
(8, 'Cybercrime'),
(9, 'Demonstration / Protest / Strike'),
(10, 'Environmental Issue'),
(11, 'Exchange Fault'),
(12, 'Foreign Employment Issue'),
(13, 'Frauds / Cheating'),
(14, 'House Breaking'),
(15, 'Illegal Mining'),
(16, 'Industrial / Labour Dispute'),
(17, 'Information'),
(18, 'Intellectual Property Dispute'),
(19, 'Miscellaneous'),
(20, 'Mischief / Sabotage'),
(21, 'Murder'),
(22, 'Narcotics / Dangerous Drugs'),
(23, 'National Security'),
(24, 'Natural Disaster'),
(25, 'Offence / Act against Public Health'),
(26, 'Offence against Public Property'),
(27, 'Organized Crime'),
(28, 'Personal Complaint'),
(29, 'Police Clearance'),
(30, 'Property Disputes'),
(31, 'Robbery'),
(32, 'Sexual Offences'),
(33, 'Suggestion'),
(34, 'Terrorism Related'),
(35, 'Theft'),
(36, 'Threat & Intimidation'),
(37, 'Tourist Harassment'),
(38, 'Traffic & Road Safety'),
(39, 'Treasure Hunting'),
(40, 'Vice Related'),
(41, 'Violation of Immigration Laws');

-- --------------------------------------------------------

--
-- Table structure for table `dmt_drivers`
--

CREATE TABLE `dmt_drivers` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `license_id` varchar(10) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `address` varchar(300) NOT NULL,
  `license_issue_date` date NOT NULL,
  `license_expiry_date` date NOT NULL,
  `restrictions` varchar(20) NOT NULL,
  `blood_group` enum('A+','A-','B+','B-','O+','O-','AB+','AB-') NOT NULL,
  `A1_issue_date` date NOT NULL,
  `A1_expiry_date` date NOT NULL,
  `A_issue_date` date NOT NULL,
  `A_expiry_date` date NOT NULL,
  `B1_issue_date` date NOT NULL,
  `B1_expiry_date` date NOT NULL,
  `B_issue_date` date NOT NULL,
  `B_expiry_date` date NOT NULL,
  `C1_issue_date` date NOT NULL,
  `C1_expiry_date` date NOT NULL,
  `C_issue_date` date NOT NULL,
  `C_expiry_date` date NOT NULL,
  `CE_issue_date` date NOT NULL,
  `CE_expiry_date` date NOT NULL,
  `D1_issue_date` date NOT NULL,
  `D1_expiry_date` date NOT NULL,
  `D_issue_date` date NOT NULL,
  `D_expiry_date` date NOT NULL,
  `DE_issue_date` date NOT NULL,
  `DE_expiry_date` date NOT NULL,
  `G1_issue_date` date NOT NULL,
  `G1_expiry_date` date NOT NULL,
  `G_issue_date` date NOT NULL,
  `G_expiry_date` date NOT NULL,
  `J_issue_date` date NOT NULL,
  `J_expiry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dmt_drivers`
--

INSERT INTO `dmt_drivers` (`id`, `fname`, `lname`, `license_id`, `nic`, `birth_date`, `address`, `license_issue_date`, `license_expiry_date`, `restrictions`, `blood_group`, `A1_issue_date`, `A1_expiry_date`, `A_issue_date`, `A_expiry_date`, `B1_issue_date`, `B1_expiry_date`, `B_issue_date`, `B_expiry_date`, `C1_issue_date`, `C1_expiry_date`, `C_issue_date`, `C_expiry_date`, `CE_issue_date`, `CE_expiry_date`, `D1_issue_date`, `D1_expiry_date`, `D_issue_date`, `D_expiry_date`, `DE_issue_date`, `DE_expiry_date`, `G1_issue_date`, `G1_expiry_date`, `G_issue_date`, `G_expiry_date`, `J_issue_date`, `J_expiry_date`) VALUES
(1, 'Imalsha', 'Jathunarachchi', 'b1111111', '200134465734', '2001-12-27', 'No: 24/7A, Winsant Road, Beliatta', '2020-01-15', '2030-01-15', 'No', 'O+', '2020-01-15', '2030-01-15', '2020-01-15', '2030-01-15', '2020-01-15', '2030-01-15', '2020-01-15', '2030-01-15', '2020-01-15', '2030-01-15', '2020-01-15', '2030-01-15', '2020-01-15', '2030-01-15', '2020-01-15', '2030-01-15', '2020-01-15', '2030-01-15', '2020-01-15', '2030-01-15', '2020-01-15', '2030-01-15', '2020-01-15', '2030-01-15', '2020-01-15', '2030-01-15'),
(8, 'Imalsha', 'Akalanka', 'b1234567', '200224903146', '1997-06-06', '678 Sixth Street, Kolonna', '2020-06-06', '2030-06-06', 'None', 'B+', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06'),
(1, 'John', 'Doe', 'b2381520', '199123456V', '1990-01-01', '123 Main Street, Colombo', '2020-01-01', '2030-01-01', 'None', 'O+', '2020-01-01', '2030-01-01', '2020-01-01', '2030-01-01', '2020-01-01', '2030-01-01', '2020-01-01', '2030-01-01', '2020-01-01', '2030-01-01', '2020-01-01', '2030-01-01', '2020-01-01', '2030-01-01', '2020-01-01', '2030-01-01', '2020-01-01', '2030-01-01', '2020-01-01', '2030-01-01', '2020-01-01', '2030-01-01', '2020-01-01', '2030-01-01', '2020-01-01', '2030-01-01'),
(2, 'Jane', 'Smith', 'b2381521', '19987654V', '1992-02-02', '456 Second Street, Galle', '2020-02-02', '2030-02-02', 'None', 'A+', '2020-02-02', '2030-02-02', '2020-02-02', '2030-02-02', '2020-02-02', '2030-02-02', '2020-02-02', '2030-02-02', '2020-02-02', '2030-02-02', '2020-02-02', '2030-02-02', '2020-02-02', '2030-02-02', '2020-02-02', '2030-02-02', '2020-02-02', '2030-02-02', '2020-02-02', '2030-02-02', '2020-02-02', '2030-02-02', '2020-02-02', '2030-02-02', '2020-02-02', '2030-02-02'),
(3, 'Alice', 'Brown', 'b2381522', 'NIC654321V', '1994-03-03', '789 Third Street, Kandy', '2020-03-03', '2030-03-03', 'None', 'B-', '2020-03-03', '2030-03-03', '2020-03-03', '2030-03-03', '2020-03-03', '2030-03-03', '2020-03-03', '2030-03-03', '2020-03-03', '2030-03-03', '2020-03-03', '2030-03-03', '2020-03-03', '2030-03-03', '2020-03-03', '2030-03-03', '2020-03-03', '2030-03-03', '2020-03-03', '2030-03-03', '2020-03-03', '2030-03-03', '2020-03-03', '2030-03-03', '2020-03-03', '2030-03-03'),
(4, 'Bob', 'Johnson', 'b2381523', 'NIC321654V', '1996-04-04', '321 Fourth Street, Jaffna', '2020-04-04', '2030-04-04', 'None', 'AB-', '2020-04-04', '2030-04-04', '2020-04-04', '2030-04-04', '2020-04-04', '2030-04-04', '2020-04-04', '2030-04-04', '2020-04-04', '2030-04-04', '2020-04-04', '2030-04-04', '2020-04-04', '2030-04-04', '2020-04-04', '2030-04-04', '2020-04-04', '2030-04-04', '2020-04-04', '2030-04-04', '2020-04-04', '2030-04-04', '2020-04-04', '2030-04-04', '2020-04-04', '2030-04-04'),
(5, 'Emma', 'Taylor', 'b2381524', 'NIC111111V', '1998-05-05', '567 Fifth Street, Trincomalee', '2020-05-05', '2030-05-05', 'None', 'O-', '2020-05-05', '2030-05-05', '2020-05-05', '2030-05-05', '2020-05-05', '2030-05-05', '2020-05-05', '2030-05-05', '2020-05-05', '2030-05-05', '2020-05-05', '2030-05-05', '2020-05-05', '2030-05-05', '2020-05-05', '2030-05-05', '2020-05-05', '2030-05-05', '2020-05-05', '2030-05-05', '2020-05-05', '2030-05-05', '2020-05-05', '2030-05-05', '2020-05-05', '2030-05-05'),
(6, 'Sophia', 'Williams', 'b2381525', 'NIC222222V', '1997-06-06', '678 Sixth Street, Anuradhapura', '2020-06-06', '2030-06-06', 'None', 'B+', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06'),
(7, 'Kasun', 'Kalhara', 'b2381526', 'NIC222223V', '1997-06-06', '678 Sixth Street, Colombo', '2020-06-06', '2030-06-06', 'None', 'B+', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06', '2020-06-06', '2030-06-06');

-- --------------------------------------------------------

--
-- Table structure for table `dmt_vehicles`
--

CREATE TABLE `dmt_vehicles` (
  `id` int(11) NOT NULL,
  `license_id` varchar(30) NOT NULL,
  `vehicle_number` varchar(50) NOT NULL,
  `vehicle_type` varchar(20) NOT NULL,
  `fuel_type` varchar(15) NOT NULL,
  `license_type` varchar(15) NOT NULL,
  `license_plate_number` varchar(20) NOT NULL,
  `license_issue_date` date NOT NULL,
  `license_expiry_date` date NOT NULL,
  `vehicle_owner_fname` varchar(50) NOT NULL,
  `vehicle_owner_lname` varchar(50) NOT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `no_of_seats` int(11) NOT NULL,
  `is_stolen` tinyint(4) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `stolen_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dmt_vehicles`
--

INSERT INTO `dmt_vehicles` (`id`, `license_id`, `vehicle_number`, `vehicle_type`, `fuel_type`, `license_type`, `license_plate_number`, `license_issue_date`, `license_expiry_date`, `vehicle_owner_fname`, `vehicle_owner_lname`, `nic`, `no_of_seats`, `is_stolen`, `address`, `stolen_date`) VALUES
(1, 'CP000000308607', 'CP|JKL-2345', 'CAR', 'PETROL', 'PRIVATE', 'CP|JKL-2345', '2023-03-25', '2024-03-24', 'Sunil', 'Fernando', '199045678912', 5, 0, 'No. 15, Kandy', NULL),
(2, 'CP000000308612', 'CP|YZA-8901', 'VAN', 'DIESEL', 'COMMERCIAL', 'CP|YZA-8901', '2023-04-14', '2024-04-13', 'Tharindu', 'Dias', '198723456789', 8, 0, 'No. 25, Nuwara Eliya', NULL),
(3, 'EP000000308613', 'EP|BCD-2345', 'CAR', 'PETROL', 'PRIVATE', 'EP|BCD-2345', '2022-06-30', '2023-06-29', 'Kasun', 'Ekanayake', '198834567891', 5, 0, 'No. 30, Ampara', NULL),
(4, 'EP000000308608', 'EP|MNO-6789', 'MOTOR CYCLE', 'PETROL', 'PRIVATE', 'EP|MNO-6789', '2022-11-11', '2023-11-10', 'Chamara', 'Jayasinghe', '198756789012', 2, 0, 'No. 3, Batticaloa', NULL),
(5, 'NP000000308605', 'NP|DEF-5678', 'CAR', 'PETROL', 'PRIVATE', 'NP|DEF-5678', '2023-07-20', '2024-07-19', 'Saman', 'Bandara', '198945632781', 5, 0, 'No. 22, Jaffna', NULL),
(6, 'NP000000308610', 'NP|STU-7890', 'CAR', 'HYBRID', 'PRIVATE', 'NP|STU-7890', '2022-09-05', '2023-09-04', 'Ruwan', 'Wijesinghe', '198965432178', 5, 0, 'No. 12, Anuradhapura', NULL),
(7, 'SP000000308604', 'SP|CBA-1234', 'MOTOR CYCLE', 'PETROL', 'PRIVATE', 'SP|CBA-1234', '2022-05-15', '2023-05-14', 'Nimal', 'Perera', '198435627382', 2, 0, 'No. 10, Matara', NULL),
(8, 'SP000000308609', 'SP|PQR-3456', 'VAN', 'DIESEL', 'COMMERCIAL', 'SP|PQR-3456', '2023-08-18', '2024-08-17', 'Lakmal', 'Ratnayake', '197845612345', 8, 0, 'No. 8, Galle', NULL),
(9, 'WP000000308606', 'WP|GHI-9101', 'VAN', 'DIESEL', 'COMMERCIAL', 'WP|GHI-9101', '2024-01-10', '2025-01-09', 'Kamal', 'Silva', '197823456789', 8, 0, 'No. 5, Colombo', NULL),
(10, 'WP000000308611', 'WP|VWX-4567', 'MOTOR CYCLE', 'PETROL', 'PRIVATE', 'WP|VWX-4567', '2024-02-22', '2025-02-21', 'Asela', 'Karunaratne', '199034567890', 2, 1, 'No. 18, Negombo', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dmt_vehicle_details`
--

CREATE TABLE `dmt_vehicle_details` (
  `absoulute_owner` varchar(90) NOT NULL,
  `engine_no` varchar(50) NOT NULL,
  `class_of_vehicle` varchar(50) NOT NULL,
  `status_when_registered` varchar(50) NOT NULL,
  `make` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `wheel_base` int(11) NOT NULL,
  `type_of_body` varchar(50) NOT NULL,
  `colour` varchar(50) NOT NULL,
  `seating_capacity` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `internal_height` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `provincial_council` varchar(50) NOT NULL,
  `date_of_registration` date NOT NULL,
  `taxes_payable` int(11) NOT NULL,
  `cylinder_capacity` int(11) NOT NULL,
  `taxation_class` varchar(50) NOT NULL,
  `country_of_origin` varchar(50) NOT NULL,
  `manufactures_des` varchar(255) NOT NULL,
  `over_hang` int(11) NOT NULL,
  `year_of_manufacture` year(4) NOT NULL,
  `previous_owners` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dmt_vehicle_details`
--

INSERT INTO `dmt_vehicle_details` (`absoulute_owner`, `engine_no`, `class_of_vehicle`, `status_when_registered`, `make`, `model`, `wheel_base`, `type_of_body`, `colour`, `seating_capacity`, `length`, `width`, `internal_height`, `height`, `provincial_council`, `date_of_registration`, `taxes_payable`, `cylinder_capacity`, `taxation_class`, `country_of_origin`, `manufactures_des`, `over_hang`, `year_of_manufacture`, `previous_owners`) VALUES
('Risafa Imtiyas', '101234', 'SUV', 'New', 'Toyota', 'Fortuner', 2750, 'Closed Body', 'Black', 7, 4800, 1900, 1400, 1800, 'Western Province', '2023-02-10', 20000, 2800, 'Private', 'Japan', 'Toyota Manufacturing', 1200, '2023', 'None'),
('Ilthizam Imtiyas', '102345', 'Sedan', 'Used', 'Honda', 'Accord', 2750, 'Sedan', 'White', 5, 4850, 1820, 1350, 1450, 'Central Province', '2021-08-15', 18000, 2000, 'Private', 'Japan', 'Honda Motors', 1150, '2021', 'Mohammed Zahir'),
('Ulfath Imtiaz', '103456', 'Truck', 'New', 'Isuzu', 'D-Max', 3200, 'Open Body', 'Blue', 2, 5400, 2050, 1500, 2000, 'Southern Province', '2022-05-12', 25000, 3000, 'Commercial', 'Thailand', 'Isuzu Motors', 1700, '2022', 'None'),
('Imalsha Akalanka', '104567', 'Van', 'Used', 'Nissan', 'Caravan', 2900, 'Closed Body', 'Silver', 8, 4950, 1950, 1400, 1850, 'Eastern Province', '2020-03-18', 15000, 2200, 'Private', 'Japan', 'Nissan Corporation', 1300, '2019', 'Kamal Perera'),
('Liseka Mendis', '105678', 'Motorcycle', 'New', 'Yamaha', 'R15', 1400, 'Two-Wheeler', 'Red', 2, 2100, 750, 1100, 1150, 'Northern Province', '2023-07-22', 6000, 155, 'Private', 'India', 'Yamaha Motors', 600, '2023', 'None');

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
  `points` int(11) NOT NULL DEFAULT 20,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `license_suspended` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `fname`, `lname`, `email`, `phone_no`, `nic`, `password`, `points`, `created_at`, `license_suspended`) VALUES
('b1111111', 'Imalsha', 'Jathunarachchi', 'imalshajathunarachchi@gmail.com', '0762381520', '200134465734', '$2y$10$RLkfX49tZpuodUMGosMhPe01O1cgYBeT/9ud.bTSHZCi77o1eQn/W', 10, '2025-01-21 14:15:00', 1),
('b1233333', 'Kasun', 'Gamage', 'kasung@gmail.com', '0778231245', '200136202834', '$2y$10$22PTdcbyzJAQxRmQEucrJ.2aEiQCWR6BnyNeeIPueypjJT0WewiCa', 100, '2025-04-20 17:09:12', 0),
('b1234445', 'Kasun', 'Siriwardhana', 'kasuns@gmail.com', '0762381567', '200536304529', '$2y$10$kX87HNBkCNd4ExowwzrBHOiU7.Sz67xQvJh8GX6UH6AL/EQji/kpu', 20, '2025-04-21 03:40:37', 0),
('b1234567', 'John', 'Does', 'imalsha.contact@gmail.com', '0762381769', '200224903146', '$2y$10$c0jHjKDd3NJ2TJ.Y.HtMS.EX6zCcV3Rx3rmZHLn1O7rN1wyajjPFq', 20, '2024-09-19 14:59:12', 0),
('b1234568', 'Kasun', 'Akarsha', 'imalsha.contact1@gmail.com', '0719843053', '200136202812', '$2y$10$xLDhvR2aYQqQJheqkzi98esv/CcnsIXQkgBZLcfU06g6FWRnkr2BG', 100, '2025-04-16 15:41:00', 0),
('b1655555', 'Hamdhi', 'Hamsa', 'hamdi@gmail.com', '0763467896', '200424675623', '$2y$10$0PvjPOKPMFGE2qFJTwUMv.jyM2hzMOh6psCmmcekxwlmE8zS5Adme', 100, '2025-04-21 03:20:59', 0),
('b2222001', 'Siripala', 'Ariyarathna', 'siripala@gmail.com', '0792378567', '1956345678557', '$2y$10$x8EL/TdrnVikVO1an.gGqetXIaL8boiEtg2okaNS1di.rsZU9S4uG', 100, '2024-11-28 15:01:24', 0),
('b2222002', 'Kasun', 'Senanayka', 'kasunsenanayaka@gmail.com', '0758767457', '199523788967', '$2y$10$FnNJ6Rj9jlTiF0z54AuSCON2FLth.OmzcolksnwPaWF2fTcJxFTdK', 100, '2024-11-28 15:08:37', 0),
('b2222003', 'Thisakya ', 'Hewagamage', 'thisakk@gmail.com', '0764568909', '199345678956', '$2y$10$O6ARu4QGFf8sUY9rta1zGe26zNIKflnEW6GNzbpqO3mM2FKOFXg1u', 100, '2024-11-28 15:11:19', 0),
('b2222004', 'Sanudi', 'Thisera', 'sanudi@gmail.com', '0710979659', '199623537945', '$2y$10$7wsnVm9KJpfK7X9ff/jjV.mS5UBji6qaisxyfnqVVSCJqnc1EbuXO', 100, '2024-11-28 15:17:36', 0),
('b2222005', 'Nadhiya', 'Nashath', 'nadhiyanash@gmail.com', '0792381520', '200336457823', '$2y$10$X371byues2LwTqIrC1Wicemox9GjRsWkxpgsYo/X/0pRCRDtWHqSe', 100, '2024-11-28 16:03:46', 0),
('b2222006', 'Hansaja', 'Kithmal', 'hansajakith@gmail.com', '0743478987', '199323896865', '$2y$10$2OGdE8XTSiHoilB2oxkr2eGyUYGekFOxXFh2lMBWTQSaUv1dgpPUm', 100, '2024-11-28 16:10:15', 0),
('b2222007', 'Gishan', 'Maduranga', 'gishanmadu@gmail.com', '0794567896', '199534985948', '$2y$10$S827l1dOfUMdZ.wvoacmIu0eGZhyq2JEKwwb.F/etuELj2VsX8lpK', 100, '2024-11-28 16:15:41', 0),
('b2222008', 'Lehan', 'Selaka', 'lehanselekar@gmail.com', '0768489780', '200423783589', '$2y$10$C1j.0AwhmHHDgCn7L9TGMeUMPqosUNYHaxmJfN7h89kFNkUZ7Zbp2', 100, '2024-11-28 16:18:55', 0),
('b2222222', 'Imaz', 'Kalhara', 'ikasun@gmail.com', '0768509438', '20023242435', '$2y$10$u.JzajQAUmn/bF4HHYZyHeMliB/5f1Yk8Cj8IO0hLlouWa.NnCXXy', 88, '2024-09-20 06:15:24', 0),
('b2222400', 'Kasun', 'Senadeera', 'kasunsena@gmail.com', '0722378677', '2001567890978', '$2y$10$2bmyU/KYu074Sy8G/1D4c.IS5sKpz8xcQYS9f0drislocheA.698G', 100, '2024-11-29 04:41:41', 0),
('b2381520', 'Pasindu', 'Munasinghe', 'pasindudf@gmail.com', '0753478678', '200435678986', '$2y$10$4AJMVe/3JfWFJ3hZxjMZVO1uh5n3wUX6FYZ8hqAmAHIsEqRRZnVga', 100, '2024-11-29 05:47:34', 0),
('b4444444', 'Himasha ', 'Lokusuriya', 'himasha@workmail.com', '0701367892', '200145900101', '$2y$10$o1ckRhWy8oOFYw.Zb9zIXO8cdiwPgN.x5p2.1h3IFIEmqoXe/C8je', 7, '2025-04-22 04:11:37', 1),
('b4567891', 'L Y', 'Mendis', 'liseka@gmail.com', '0784534456', '123456789bV', '$2y$10$zqM4ogMQoV5xgs4BGJQiPeAXlbJMzOxdql.cALCUW.hBOcZsOB8UC', 5, '2025-04-15 01:59:03', 1),
('b4567892', 'Savindi', 'Mendis', 'savindi@mail.com', '0701367891', '200276900101', '$2y$10$9rjL2Bg6QARLvtW41nEzs.Qycy8gewv/BfX2MJYIhXfRWiZFeo7Be', 20, '2025-04-21 08:22:17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `duty_locations`
--

CREATE TABLE `duty_locations` (
  `id` int(11) NOT NULL,
  `police_station_id` int(11) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `duty_locations`
--

INSERT INTO `duty_locations` (`id`, `police_station_id`, `location_name`, `created_at`, `updated_at`) VALUES
(1, 515, 'Alawathugoda Central College', '2025-01-04 05:32:42', '2025-01-04 05:32:42'),
(7, 515, 'highway', '2025-01-08 04:28:30', '2025-01-08 04:28:30'),
(8, 250, 'Delft Town', '2025-01-08 04:34:31', '2025-01-08 04:34:31'),
(19, 250, 'Galle', '2025-04-17 09:11:52', '2025-04-17 09:11:52');

-- --------------------------------------------------------

--
-- Table structure for table `duty_submissions`
--

CREATE TABLE `duty_submissions` (
  `id` int(11) NOT NULL,
  `police_id` int(11) NOT NULL,
  `assigned_duty_id` int(11) DEFAULT NULL,
  `patrol_location` varchar(255) NOT NULL,
  `patrol_time_started` time NOT NULL,
  `patrol_time_ended` time NOT NULL,
  `patrol_information` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_late_submission` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `duty_submissions`
--

INSERT INTO `duty_submissions` (`id`, `police_id`, `assigned_duty_id`, `patrol_location`, `patrol_time_started`, `patrol_time_ended`, `patrol_information`, `created_at`, `is_late_submission`) VALUES
(22, 12004, 35, 'Jaffna', '12:00:00', '13:00:00', '4 fines', '2025-04-08 08:56:43', 0),
(23, 12004, 36, 'Jaffna', '14:31:00', '14:33:00', 'Fines issued', '2025-04-08 08:59:14', 0),
(24, 23230, 44, 'Kandy', '12:00:00', '14:00:00', 'No fines issued', '2025-04-16 08:35:56', 0),
(25, 23230, 53, 'School Junction', '11:22:00', '11:28:00', 'nn', '2025-04-20 05:46:33', 0),
(26, 23230, 51, 'School Junction', '08:45:00', '10:45:00', 'Duty Completed', '2025-04-20 07:06:18', 1),
(27, 23230, 52, 'Bridge Junction', '13:10:00', '14:08:00', 'nn', '2025-04-20 10:27:49', 1),
(28, 23230, 54, 'Alawathugoda Junction', '15:08:00', '16:08:00', 'jjj', '2025-04-20 10:32:28', 0),
(29, 23230, 55, 'Bridge Junction', '16:00:00', '16:15:00', 'Done', '2025-04-20 10:39:22', 0),
(30, 23230, 56, 'Near the junction', '12:50:00', '13:40:00', 'N/A', '2025-04-22 09:18:47', 0),
(31, 23230, 61, 'School Junction', '15:50:00', '16:50:00', 'hhhhhh', '2025-04-22 10:13:37', 0);

-- --------------------------------------------------------

--
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `id` int(11) NOT NULL,
  `police_id` int(11) NOT NULL,
  `driver_id` varchar(20) NOT NULL,
  `license_plate_number` varchar(20) NOT NULL,
  `issued_date` date DEFAULT NULL,
  `issued_time` time NOT NULL,
  `expire_date` date NOT NULL,
  `offence_type` enum('fine','court') NOT NULL,
  `location` varchar(30) NOT NULL,
  `nature_of_offence` text NOT NULL,
  `offence` varchar(50) DEFAULT NULL,
  `fine_status` enum('pending','overdue','paid') NOT NULL DEFAULT 'pending',
  `is_reported` tinyint(1) NOT NULL DEFAULT 0,
  `evidence` varchar(255) DEFAULT NULL,
  `reported_description` text DEFAULT NULL,
  `is_fair` tinyint(1) DEFAULT 1,
  `oics_action` text DEFAULT NULL,
  `is_discarded` tinyint(1) DEFAULT 0,
  `fine_amount` decimal(10,2) DEFAULT 0.00,
  `police_station` int(11) DEFAULT NULL,
  `is_solved` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `paid_at` datetime DEFAULT NULL,
  `court_release_date` date NOT NULL,
  `confirmation_document` varchar(255) NOT NULL,
  `is_license_cancelled` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fines`
--

INSERT INTO `fines` (`id`, `police_id`, `driver_id`, `license_plate_number`, `issued_date`, `issued_time`, `expire_date`, `offence_type`, `location`, `nature_of_offence`, `offence`, `fine_status`, `is_reported`, `evidence`, `reported_description`, `is_fair`, `oics_action`, `is_discarded`, `fine_amount`, `police_station`, `is_solved`, `created_at`, `paid_at`, `court_release_date`, `confirmation_document`, `is_license_cancelled`) VALUES
(10, 15368, 'b1234567', 'SP|ABH-5364', '2024-11-23', '20:32:09', '0000-00-00', 'fine', '', 'FINES', 'Not Carrying Revenue License', 'paid', 1, 'uploads/evidence/6773b048218b9_frame (1).png', 'aaaaaa', 1, 'Identified as Unfair', 1, 1000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(12, 15365, 'b1234567', 'SP|ABH-4545', '2024-11-24', '00:52:35', '0000-00-00', 'court', '', 'efwevaeva', NULL, 'pending', 0, '', NULL, 1, NULL, 0, 0.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(15, 15368, 'b1234567', 'cad-9089', '2024-11-25', '16:27:42', '0000-00-00', 'fine', '', 'nature', 'Identification Plates', 'pending', 1, '', 'unfair', 1, 'uny', 1, 1000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(16, 15368, 'b1234567', 'cad-9045', '2024-11-25', '16:28:13', '0000-00-00', 'court', '', 'ascascas', NULL, 'overdue', 0, '', 'no such offence was commiitted', 1, NULL, 0, 0.00, NULL, 1, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(17, 15368, 'b1234567', 'cad-9045', '2024-11-25', '17:45:29', '0000-00-00', 'fine', '', 'nnbmn\r\n\r\n', 'Contravening R.L. provisions', 'overdue', 1, '', NULL, 1, 'unfair', 1, 1000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(18, 15368, 'B2381520', 'SP|BBY-1683', '2024-11-29', '11:22:05', '0000-00-00', 'fine', '', 'NO BREAK LIGHTS', 'Signals by Driver', 'pending', 1, '', 'THE FINE IS NOT FAIR', 1, 'THE FINE IS NOT FAIR', 1, 1000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(19, 15368, 'b1234567', 'SP|BBY-1683', '2024-12-10', '23:41:36', '0000-00-00', 'fine', '', 'check issue fine with amount', 'Driving Emergency Service Vehicles &amp; Public Se', 'paid', 1, 'uploads/evidence/676da21634d01_img3.jpeg', 'FINE IS NOT FAIR', 1, NULL, 0, 1000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(20, 15368, 'b1234567', 'SP|BBY-1683', '2024-12-10', '23:49:04', '0000-00-00', 'fine', '', 'hello', 'Driving a vehicle loaded with chemicals/hazardous ', 'paid', 0, '', NULL, 1, NULL, 0, 1000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(200, 44112, 'b1234567', 'DAD-9367', '2025-01-05', '10:13:58', '0000-00-00', 'court', '', 'bribing', NULL, 'pending', 0, '', NULL, 1, NULL, 0, 0.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(206, 15368, 'b1234567', 'SP|BBY-1683', '2025-01-21', '09:10:17', '0000-00-00', 'fine', '', 'mail check', 'Contravening R.L. provisions', 'paid', 0, '', NULL, 1, NULL, 0, 1000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(207, 15368, 'b1234567', 'SP|BBY-1683', '2025-01-21', '09:14:25', '0000-00-00', 'fine', '', 'mail check 2', 'Contravening R.L. provisions', 'paid', 0, '', NULL, 1, NULL, 0, 1000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(208, 15368, 'b1111111', 'sp|CAD-6425', '2025-01-24', '14:38:35', '2025-01-27', 'fine', '', 'this is the description\r\n', 'Disobeying Road Rules', 'paid', 0, '', NULL, 1, NULL, 0, 2000.00, NULL, 0, '2025-04-19 13:24:57', '2025-04-19 14:07:34', '0000-00-00', '', 0),
(209, 15368, 'b1234567', 'CAD-4567', '2025-01-25', '10:25:17', '2025-01-27', 'fine', '', 'Improper lane changed ', 'Disobeying Road Rules', 'paid', 0, '', NULL, 1, NULL, 0, 2000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(210, 15368, 'b2222222', 'sp|CAD-6425', '2025-01-25', '19:03:22', '2025-01-25', 'fine', '', 'this is the description\r\n', 'Not having a license to drive a specific class of ', 'pending', 0, '', NULL, 1, NULL, 0, 1000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(211, 15368, 'b1111111', 'SP|BBY-1683', '2025-01-28', '02:17:52', '2025-02-11', 'fine', '', 'guguugugu', 'Disobeying Road Rules', 'pending', 0, '', NULL, 1, NULL, 0, 2000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(212, 15368, 'b1234567', 'CAD-4567', '2025-02-04', '06:02:51', '0000-00-00', 'fine', '', 'ggusbduhwvc', 'Not carrying Driving License', 'paid', 1, '', 'THE FINE IS NOT FAIR ', 1, NULL, 0, 1000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(213, 12004, 'b1234567', 'KL9012', '2025-02-04', '12:13:19', '2025-02-18', 'fine', '', 'sdgsg', 'Not having an instructor’s license', 'paid', 0, '', NULL, 1, NULL, 0, 2000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(215, 15368, 'b2222001', 'ADD-0101', '2025-02-17', '01:15:37', '2025-02-26', 'fine', '', 'none', 'Signals by Driver', 'pending', 1, '', 'I did not commit this violation', 1, NULL, 0, 7000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(228, 15368, 'b1111111', 'sp|CAD-6425', '2025-02-17', '09:37:59', '0000-00-00', 'fine', 'Nugegoda', 'klmklk', 'Disobeying Road Rules', 'pending', 0, '', NULL, 1, NULL, 0, 2000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(229, 15368, 'b1111111', 'sp|CAD-6425', '2025-02-17', '09:43:26', '0000-00-00', 'fine', 'Nugegoda', 'fjioaionio io oaio', 'Contravening Speed Limits', 'pending', 0, '', NULL, 1, NULL, 0, 3000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(230, 15368, 'b1111111', 'sp|CAD-6425', '2025-02-17', '14:15:39', '2025-03-03', 'fine', 'Nugegoda', 'cnavoniobanon ianio aig oan', '011', 'pending', 0, '', NULL, 1, NULL, 0, 2000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(231, 15368, 'b1234567', 'sp|CAD-6425', '2025-02-17', '16:53:00', '2025-03-03', 'fine', 'highway', 'yuy ygyugyugyugugy  gyu gu', '011', 'paid', 1, 'uploads/evidence/67fcaf822c43a_class.jpg', 'risafa imtiyas', 1, NULL, 0, 2000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(232, 15368, 'b1111111', 'sp|CAD-6425', '2025-02-17', '17:00:01', '2025-03-03', 'fine', 'highway', 'last used', '011', 'pending', 0, '', NULL, 1, NULL, 0, 2000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(233, 15368, 'b1234567', 'sp|CAD-6425', '2025-04-07', '10:47:23', '2025-04-21', 'fine', 'Alawathugoda Central College', 'Not carrying Driving License', '008', 'paid', 1, 'uploads/evidence/67f360e623f36_DSC_0456.jpg', 'frrrrrrrrr', 1, 'Fine is not fair', 1, 1000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(234, 15368, 'b1234567', 'SP|ABH-5555', '2025-04-08', '09:52:45', '2025-04-22', 'fine', 'highway', 'mm', '007', 'paid', 1, 'uploads/evidence/67fcd16900ac8_img2.jpeg', 'THE fine is not fair', 1, 'Investigation done. The fine is fair.', 0, 1000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(235, 15368, 'b1234567', 'SP|ABH-5364', '2025-04-08', '12:54:12', '2025-04-22', 'fine', 'Alawathugoda Central College', 'liscence not have', '007', 'pending', 1, 'uploads/evidence/67fcf49d66233_class.jpg', 'Beemath weema', 1, 'Because of officers misunderstand the fine is not fair', 1, 1000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(236, 15368, 'b1234567', 'SP|ABH-5555', '2025-04-08', '13:19:49', '2025-04-22', 'fine', 'near bus stand', 'chemical loaded vehicle', '006', 'pending', 1, 'uploads/evidence/67fcd0abb7338_img2.jpeg', 'THE FINE IS NOT FAIR', 1, 'The fine is not fair', 1, 1000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(237, 15368, 'b1234567', 'SP|ABH-5555', '2025-04-08', '13:41:54', '2025-04-22', 'court', 'near bus stand', 'court violation', NULL, 'pending', 0, NULL, NULL, 1, NULL, 0, 0.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(238, 15368, 'b1234567', 'SP|ABH-5555', '2025-04-09', '20:48:06', '2025-04-23', 'fine', 'police station', 'high speed', '010', 'paid', 0, NULL, NULL, 1, NULL, 0, 3000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(239, 15368, 'B4567891', 'CAD-4645', '2025-04-15', '07:46:35', '2025-04-29', 'fine', 'Alawathugoda Central College', 'Offender drove a vehicle that is not in the licence.  ', '007', 'pending', 1, 'uploads/evidence/67fdc26062c41_DSC_0456.jpg', 'Officer didn&#039;t check it properly as he was drunk. ', 1, 'Inquiry done. The fine is not fair. ', 1, 1000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(240, 15368, 'B4567891', 'CAD-4647', '2025-04-15', '13:51:23', '2025-04-29', 'fine', 'Alawathugoda Central College', 'Driving Emergency Service Vehicle', '004', 'pending', 1, 'uploads/evidence/67fe17a340a0e_67f35eb95fc8a_img1.jpeg', 'At the moment it was urgent. ', 1, 'After the inquiry, the fine has been concluded as unfair. ', 1, 1000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(241, 15222, 'b1234567', 'SP|BBY-1683', '2025-04-17', '10:13:00', '2025-05-01', 'fine', 'Police Station', 'High Speed', '010', 'paid', 0, NULL, NULL, 1, NULL, 0, 3000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(242, 15222, 'b1234567', 'SP|BBY-1683', '2025-04-17', '10:13:03', '2025-05-01', 'fine', 'Police Station', 'High Speed', '010', 'paid', 0, NULL, NULL, 1, NULL, 0, 3000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(243, 12004, 'b1234567', 'SP|BBY-1683', '2025-04-17', '17:49:57', '2025-05-01', 'fine', 'Delft Town', 'Avoiding Road Rules', '011', 'paid', 0, NULL, NULL, 1, NULL, 0, 2000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(245, 23230, 'B4567891', 'CAD-4645', '2025-04-18', '06:33:13', '2025-05-02', 'fine', 'highway', 'Didn&#039;t care about the traffic lights. ', '011', 'pending', 1, 'uploads/evidence/6801a5178ba7c_img1.jpeg', 'It was an emergency situation. ', 1, 'After the inquiry, fine has been concluded as unfair. ', 1, 2000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(246, 12004, 'b1234567', 'DAD-9367', '2025-04-18', '09:44:33', '2025-05-02', 'court', 'Delft Town', 'Court Offence', NULL, 'pending', 0, NULL, NULL, 1, NULL, 0, 0.00, NULL, 1, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(247, 12004, 'b1234567', 'CP|YZA-8901', '2025-04-18', '09:45:22', '2025-05-02', 'court', 'Delft Town', 'A court offence', NULL, 'pending', 0, NULL, NULL, 1, NULL, 0, 0.00, NULL, 1, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(248, 12004, 'b1234567', 'ADD-0101', '2025-04-18', '09:45:57', '2025-05-02', 'court', 'Delft Town', 'A court offence', NULL, 'pending', 0, NULL, NULL, 1, NULL, 0, 0.00, NULL, 1, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(249, 15368, 'b1234567', 'SP|BBY-1683', '2025-04-18', '10:46:02', '2025-05-02', 'fine', 'Alawathugoda Central College', 'not having liscence', '009', 'paid', 0, NULL, NULL, 1, NULL, 0, 2000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(250, 15368, 'b1111111', 'sp|CAD-6425', '2025-04-18', '14:29:16', '2025-05-02', 'fine', '7', 'thisa  ssbiba  a', '008', 'pending', 0, NULL, NULL, 1, NULL, 0, 1000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(251, 15368, 'b2222222', 'sp|CAD-6425', '2025-04-18', '14:30:46', '2025-05-02', 'fine', 'near bus stand', 'this is the description', '010', 'pending', 0, NULL, NULL, 1, NULL, 0, 3000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(252, 15368, 'b1111111', 'sp|CAD-6425', '2025-04-18', '14:50:35', '2025-05-02', 'fine', '1', 'hgiw sivabu javbnbao', '008', 'pending', 0, NULL, NULL, 1, NULL, 0, 1000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(253, 15368, 'b2222222', 'sp|CAD-6425', '2025-04-18', '14:53:41', '2025-05-02', 'fine', 'near bus stand', 'gthis sibgab ib ', '007', 'pending', 0, NULL, NULL, 1, NULL, 0, 1000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(257, 15368, 'b1111111', 'sp|CAD-6425', '2025-04-18', '16:37:48', '2025-05-02', 'fine', '7', 'ghi vboB nion ioan ionao', '012', 'pending', 0, NULL, NULL, 1, NULL, 0, 1000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(258, 15368, 'b1111111', 'sp|CAD-6425', '2025-04-18', '17:12:55', '2025-05-02', 'fine', '1', 'thiehigab ahin ni ', '010', 'pending', 0, NULL, NULL, 1, NULL, 0, 3000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(259, 15368, 'b1111111', 'sp|CAD-6425', '2025-04-18', '17:15:47', '2025-05-02', 'fine', '7', 'tjoa nvanoa nono on ao ', '009', 'paid', 0, NULL, NULL, 1, NULL, 0, 2000.00, 515, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(260, 15368, 'b1234567', 'cad-9089', '2025-04-18', '23:54:26', '2025-05-02', 'fine', 'Alawathugoda Central College', 'high speed', '010', 'paid', 0, NULL, NULL, 1, NULL, 0, 3000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(261, 15368, 'b1111111', 'cad-9089', '2025-04-19', '07:30:36', '2025-05-03', 'fine', 'highway', 'Driving Special Vehicle without a license', '005', 'paid', 0, NULL, NULL, 1, NULL, 0, 1000.00, 115, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(262, 12004, 'b1234567', 'cad-9089', '2025-04-19', '08:03:45', '2025-05-03', 'fine', 'Delft Town', 'High Speed', '010', 'paid', 0, NULL, NULL, 1, NULL, 0, 3000.00, 115, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(263, 12004, 'b1234567', 'cad-9089', '2025-04-19', '08:27:31', '2025-05-03', 'fine', 'Delft Town', 'Not Carry Driver License', '008', 'paid', 0, NULL, NULL, 1, NULL, 0, 1000.00, NULL, 0, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(264, 12004, 'B4567891', 'CAD-4645', '2025-04-19', '13:23:32', '2025-05-03', 'court', 'Delft Town', 'blah blah ', NULL, 'pending', 0, NULL, NULL, 1, NULL, 0, 0.00, 515, 1, '2025-04-19 13:24:57', NULL, '0000-00-00', '', 0),
(265, 15368, 'b1234567', 'sp|CAD-6425', '2025-04-20', '14:13:54', '2025-05-04', 'fine', 'highway', 'gfgcghcgg g ', '001', 'pending', 1, 'uploads/evidence/6804b4e74a318_676da21634d01_img3.jpeg', 'Officers misunderstand', 1, 'blah', 1, 1000.00, 515, 0, '2025-04-20 10:43:57', NULL, '0000-00-00', '', 0),
(266, 15368, 'b1234567', 'sp|CAD-6425', '2025-04-20', '14:30:05', '2025-05-04', 'fine', 'highway', 'he he', '013', 'pending', 1, 'uploads/evidence/6804b7afc9873_67fdc26062c41_DSC_0456.jpg', 'hehehe', 1, 'Fair', 0, 7000.00, 515, 0, '2025-04-20 11:00:08', NULL, '0000-00-00', '', 0),
(267, 23230, 'b1234567', 'CP|JKL-2345', '2025-04-20', '19:28:30', '2025-05-04', 'fine', 'Alawathugoda Central College', 'Disobeyed Road Rules.', '011', 'pending', 1, 'uploads/evidence/6804fdcceb1dc_6801a5178ba7c_img1.jpeg', 'Officers Misunderstand.', 1, 'The fine is concluded as fair.', 0, 2000.00, 515, 0, '2025-04-20 15:52:05', NULL, '0000-00-00', '', 0),
(268, 23230, 'B4567891', 'CAD-4645', '2025-04-20', '19:38:23', '2025-05-04', 'fine', 'Alawathugoda Central College', 'Speeding', '010', 'pending', 1, 'uploads/evidence/680500767b7e8_67f360e623f36_DSC_0456.jpg', 'It was an emergency. ', 1, 'The fine is fair.', 0, 3000.00, 515, 1, '2025-04-20 16:01:58', NULL, '0000-00-00', '', 0),
(269, 15368, 'b2222222', 'sp|CAD-6425', '2025-04-21', '04:40:26', '2025-05-05', 'fine', '1', 'hehehehe\r\n', '010', 'pending', 0, NULL, NULL, 1, NULL, 0, 3000.00, 515, 0, '2025-04-21 01:10:29', NULL, '0000-00-00', '', 0),
(270, 15368, 'b1234567', 'sp|CAD-6425', '2025-04-21', '10:00:35', '2025-05-05', 'fine', 'Alawathugoda Central College', 'hehe', '008', 'paid', 0, NULL, NULL, 1, NULL, 0, 1000.00, 515, 0, '2025-04-21 06:30:41', '2025-04-21 06:37:26', '0000-00-00', '', 0),
(271, 15368, 'b1234567', 'CP|JKL-2345', '2025-04-21', '10:13:23', '2025-05-05', 'fine', 'Alawathugoda Central College', 'testing the date and time', '013', 'pending', 0, NULL, NULL, 1, NULL, 0, 7000.00, 515, 0, '2025-04-21 06:43:25', NULL, '0000-00-00', '', 0),
(272, 12004, 'b1234567', 'CP|JKL-2345', '2025-04-21', '13:34:31', '2025-05-05', 'court', 'Delft Town', '1', NULL, 'pending', 0, NULL, NULL, 1, NULL, 0, 0.00, 250, 0, '2025-04-21 10:04:34', NULL, '0000-00-00', '', 0),
(273, 12004, 'b1234567', 'CP|JKL-2345', '2025-04-21', '13:35:00', '2025-05-05', 'court', 'Delft Town', '2', NULL, 'pending', 0, NULL, NULL, 1, NULL, 0, 0.00, 250, 0, '2025-04-21 10:05:03', NULL, '0000-00-00', '', 0),
(274, 12004, 'b1234567', 'CP|JKL-2345', '2025-04-21', '13:35:34', '2025-05-05', 'court', 'Delft Town', '3', NULL, 'pending', 0, NULL, NULL, 1, NULL, 0, 0.00, 250, 0, '2025-04-21 10:05:37', NULL, '0000-00-00', '', 0),
(275, 15368, 'b1111111', 'sp|CAD-6425', '2025-04-21', '16:10:50', '2025-05-05', 'fine', 'Alawathugoda Central College', 'Speeding', '010', 'pending', 0, NULL, NULL, 1, NULL, 0, 3000.00, 515, 0, '2025-04-21 12:34:24', NULL, '0000-00-00', '', 0),
(276, 15368, 'b1111111', 'sp|CAD-6425', '2025-04-21', '16:11:48', '2025-05-05', 'fine', 'Alawathugoda Central College', 'Not having the RL', '002', 'pending', 0, NULL, NULL, 1, NULL, 0, 1000.00, 515, 0, '2025-04-21 12:35:23', NULL, '0000-00-00', '', 0),
(277, 15368, 'b1111111', 'sp|CAD-6425', '2025-03-13', '16:13:10', '2025-05-05', 'fine', 'Alawathugoda Central College', 'kkkk', '004', 'pending', 0, NULL, NULL, 1, NULL, 0, 1000.00, 515, 0, '2025-04-21 12:36:43', NULL, '0000-00-00', '', 0),
(278, 15368, 'B4444444', 'CAD-4548', '2025-04-22', '09:52:51', '2025-05-20', 'fine', 'Alawathugoda Central College', 'Disobeyed Road Rules', '011', 'pending', 0, NULL, NULL, 1, NULL, 0, 2000.00, 515, 0, '2025-04-22 06:16:23', NULL, '0000-00-00', '', 0),
(279, 15368, 'B4444444', 'sp|CAD-6425', '2025-04-22', '09:53:29', '2025-05-20', 'fine', 'Alawathugoda Central College', 'Speeding', '010', 'pending', 1, 'uploads/evidence/680731e7e63c2_67fdc26062c41_DSC_0456.jpg', 'Fine is not fair.', 1, 'Fine is fair.', 0, 3000.00, 515, 1, '2025-04-22 06:17:03', NULL, '0000-00-00', '', 0),
(280, 15368, 'B4444444', 'CAD-4548', '2025-04-22', '09:54:21', '2025-05-20', 'fine', 'Alawathugoda Central College', 'DL is not with her', '008', 'pending', 0, NULL, NULL, 1, NULL, 0, 1000.00, 515, 0, '2025-04-22 06:17:55', NULL, '0000-00-00', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `offences`
--

CREATE TABLE `offences` (
  `offence_id` int(11) NOT NULL,
  `offence_number` varchar(10) DEFAULT NULL,
  `description_sinhala` varchar(255) NOT NULL,
  `description_tamil` varchar(255) NOT NULL,
  `description_english` varchar(255) NOT NULL,
  `points_deducted` int(11) DEFAULT NULL,
  `fine_amount` decimal(10,2) DEFAULT NULL,
  `offence` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offences`
--

INSERT INTO `offences` (`offence_id`, `offence_number`, `description_sinhala`, `description_tamil`, `description_english`, `points_deducted`, `fine_amount`, `offence`) VALUES
(1, '001', 'හඳුනාගැනීමේ තහඩු', 'அடையாளங்காணல் தகடுகள்', 'Identification Plates', 1, 1000.00, NULL),
(12, '002', 'අවසර බලපත්‍රය රැගෙන නොයාම', 'அனுமதிப்பத்திரம் கொண்டு செல்லாமை', 'Not Carrying Revenue License', 3, 1000.00, NULL),
(13, '003', 'අවසර බලපත්‍ර නීති උල්ලංඝනය', 'அனுமதிப்பத்திரக் கட்டுப்பாடுகளை மீறல்', 'Contravening R.L. provisions', 4, 1000.00, NULL),
(15, '004', 'බලපත්‍ර නොමැතිව හදිසි සේවා සහ පොදු සේවා වාහන පැදවීම', 'அனுமதிப்பத்திரமின்றி அவசரகால சேவை வாகனங்களையும் பொதுச்சேவை வாகனங்களையும் ஓட்டுதல் ', 'Driving Emergency Service Vehicles &amp; Public Service Vehicles without Driving License', 3, 1000.00, NULL),
(16, '005', 'බලපත්‍රයක් නොමැතිව විශේෂ කාර්ය වාහන පැදවීම', 'அனுமதிப்பத்திரமின்றி விசேட நோக்க வாகனங்களைச் செலுத்தல்', 'Driving Special Purpose Vehicles without a license', 4, 1000.00, NULL),
(17, '006', 'බලපත්‍රයක් නොමැතිව රසායනික ද්‍රව්‍ය, අපද්‍රව්‍ය ආදිය පටවූ වාහනයක් පැදවීම', 'அனுமதிப்பத்திரமின்றி இரசாயனப் பொருட்கள், ஆபத்தான கழிவுகள் ஏற்றப்பட்ட வாகனத்தைச் செலுத்தல்', 'Driving a vehicle loaded with chemicals/hazardous waste without a license', 3, 1000.00, NULL),
(18, '007', 'පන්තියකට අයත් වාහනයක් පැදවීමට සදහා බලපත්‍රයක් නොමැතිව', 'குறிப்பிட்டொரு வகுப்பினவான வாகனமொன்றைச் செலுத்துவதற்கான அனுமதிப்பத்திரமின்மை', 'Not having a license to drive a specific class of vehicles', 4, 1000.00, NULL),
(19, '008', 'රියදුරු බලපත්‍රය රැගෙන නොයාම', 'சாரதி அனுமதிப்பத்திரத்தை கொண்டு செல்லத் தவறுதல்', 'Not carrying Driving License', 5, 1000.00, NULL),
(20, '009', 'උපදේශක බලපත්‍රයක් නොමැතිව පැදවීම', 'பயிற்றுனர் அனுமதிப்பத்திரமின்மை', 'Not having an instructor’s license', 3, 2000.00, NULL),
(21, '010', 'වේගසීමා විධිවිධාන උල්ලංඝනය', 'வேக எல்லை விதிகளை மீறல்', 'Contravening Speed Limits', 4, 3000.00, NULL),
(22, '011', 'මාර්ග නීතිවලට අවනත නොවීම', 'சாலா விதிகளுக்கு இணங்கியமையே', 'Disobeying Road Rules', 4, 2000.00, NULL),
(23, '012', 'මෝටර් රථයේ පාලනය කටයුතු වලට බාධා කිරීම', 'மொட்டார் வாகனக் கட்டுப்பாட்டிற்கு இடையூறான செயல்கள்', 'Activities obstructing control of the motor vehicle', 4, 1000.00, NULL),
(25, '013', 'රියදුරු දිය යුතු සංඥා', 'சாரதி வழங்க வேண்டிய சைகைகள் ', 'Signals by Driver', 4, 7000.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE `officers` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `police_station` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_oic` tinyint(4) DEFAULT 0,
  `is_active` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officers`
--

INSERT INTO `officers` (`id`, `fname`, `lname`, `email`, `phone_no`, `nic`, `police_station`, `password`, `created_at`, `is_oic`, `is_active`) VALUES
(11004, 'Kenneth', 'Thilakarathna', 'imazja22@gmail.com', '0762381521', '200136202816', 11, '$2y$10$/U.ZK/Y.XSuTrkfDBxF2J.bklza4VYJtuvOs7/OEdh19gqwjWwXDm', '2025-04-14 13:40:34', 0, 0),
(11854, 'Imalsha', 'Akalanka', 'imazja@gmail.com', '0762381520', '200136202736', 1, '$2y$10$S050w4qtdHBDntaTQlZ28OCHh3ESLrYLIDT4pqWITbKfrAZCD8lGi', '2025-02-11 09:38:49', 0, 0),
(12003, 'Abdhul', 'Rahman', 'shadmin1@gmail.com', '0720027454', '20023341144', 250, '$2y$10$0he7ISAFr99lNU1lbrUJP.gF6U8Ps8reXjGC9dubU/AdF3WontpXO', '2025-02-12 05:04:03', 0, 0),
(12004, 'Shiva', 'Karthik', 'shivakarthik@gmail.com', '0740982343', '198922338899', 250, '$2y$10$p7mhyFAroFknvdxtn/Her.XYeCu0LyIr/dRkUSE4igNgGw9WHO2ZW', '2025-01-05 07:13:56', 0, 0),
(12005, 'John', 'Vijay', 'johnvijay@gmail.com', '0771108976', '198722001278', 250, '$2y$10$NpfCMPbiMHGqkwB7aSY3Dui.2wbKor7X3Wclv6/KOuh/YeHbiq6SO', '2025-01-05 12:10:45', 1, 0),
(12122, 'Imalsha', 'Akalanka', 'imazja2@gmail.com', '0762381520', '200136202736', 297, '$2y$10$l3vjVqmaT5gqjehLiKGFbOKDheedNMtrpA8kAMetScym1ZolttXwm', '2025-02-11 09:41:30', 0, 0),
(12123, 'Hashir', 'Ahamed', 'zuhairahamed@gmail.com', '0772247898', '20023341145', 523, '$2y$10$DFshdupnEivhPQ7ubq.d1OPGskd2cllWvrzT7KG/5i3oWZ6yjg.zG', '2025-01-05 06:16:29', 1, 0),
(12124, 'Ishan', 'Shanuka', 'ishanshanuka@gmail.com', '0774401289', '200253401299', 246, '$2y$10$Y7YTHQF/XZ7TuFb3crQzcOmXrdOimgPdl.yzzhFXkjopaoNs1dMSO', '2025-01-05 06:17:44', 1, 0),
(12125, 'Gayod', 'Sanjan', 'gayodpathi2@gmail.com', '0784401277', '200112345477', 350, '$2y$10$Rhkx3uiW6R1QGhjthWp2XOzlvYg9JGQsQJE2octq38/kuvXzlfC16', '2025-01-05 06:18:54', 1, 0),
(12126, 'Dulina', 'Hewage', 'dulinahewa12@gmail.com', '0762234532', '200312456544', 297, '$2y$10$.QWvYldy03da3.3ktDaJLeUheIVHCIOKcDUVK4.ff6QiClCOqiD.K', '2025-01-05 06:21:05', 1, 0),
(12346, 'Theshawa', 'Dasun', 'mrclocktd@gmail.com', '0784380893', '200238897812', 450, '$2y$10$Zm/2KwpUc6ESfIa9u0Jd1eKPVl6lHoxzWXnr/Ax9w.YOg5k/HK94K', '2025-04-18 09:08:07', 0, 0),
(12389, 'Mahinda', 'Rajapaksa', 'imazjau2@gmail.com', '0762381520', '200136202736', 1, '$2y$10$NAkm9irEjXiXum88FU1oVuyA6OpuX8KgHqR1ShWtMI0LDNVuoLb.y', '2025-02-11 10:07:16', 0, 0),
(12880, 'Maahela ', 'Weerasinghe', 'imalsha.contact1@gmail.com', '0792378567', '200136202812', 251, '$2y$10$Siws4tsg.L3aRt6drkKTWOzWHqlZUIItRkYd7Y47IiWaiCOPxx0qq', '2025-02-17 19:37:16', 0, 0),
(13002, 'Ramal', 'Dissanayaka', 'ramaldis@gmial.com', '0775502345', '197812332345', 2, '$2y$10$9DCQBWRUdeoeGbDFKIah6enpR0jTaDmquflkf3Y4PUrhrTtmAMoMy', '2025-01-05 06:33:46', 0, 0),
(13459, 'Abhishek', 'Pulasthi', 'abipula18@gmail.com', '0773385190', '198854209876', 364, '$2y$10$IJZU2cJ59hV8D3WEMT70EefRhyu6Yf9uNS8gRen/2VyEhkHqTNexS', '2025-01-05 07:08:11', 0, 0),
(13567, 'Imalsha', 'Akalanka', 'imazja222@gmail.com', '0762381520', '200136202736', 183, '$2y$10$hyipOW2QzHBOhLX1jDG0E.1S8H.qdDk6ALWsJaSLWFyO6vS7JyUwq', '2025-02-11 10:04:27', 0, 0),
(14900, 'Nifal', 'Munhim', 'nifaaaalmun@gmail.com', '0762234532', '199623438977', 128, '$2y$10$9eqklAoXll26LeMpKu2Execy5KNXo1z/nNjZdQLo.cgk95uV7jAMW', '2025-01-05 06:24:38', 1, 0),
(14904, 'Waleed', 'Zubair', 'waleedzue@gmail.com', '0723345670', '199523451212', 469, '$2y$10$9a9tTq9AZzqWzXQBK4lYLOo2aG720wt2D2SUKzjWqNewlUq9tMEEa', '2025-01-05 06:25:57', 1, 0),
(15222, 'Prasanna', 'Baddeiwthana', 'prasannabadde@gmail.com', '0774401290', '196312775360', 485, '$2y$10$jU8aOdJncH6otman01ymfe.O4J6OW29gQ.WAG7NPG/ROcenwREO0C', '2025-01-05 07:12:23', 0, 0),
(15224, 'Chamara', 'Ranawaka', 'imazja33@gmail.com', '0782390234', '200438892312', 482, '$2y$10$TjkixbrtauYiDIff/MjUE.xTCEljMASdFtM/djGG4vyYhPdXW22Em', '2025-04-20 17:12:48', 0, 0),
(15234, 'Senira', 'Smarasinghe', 'sunnysamre@gmail.com', '0778892345', '197512341290', 411, '$2y$10$UWUvjokFzkIy3bXnKM9rDuLHDY2lyuACv2QU2LlGn16B1es/Cekzq', '2025-01-05 06:36:26', 1, 0),
(15256, 'Imalsha', 'Akalanka', 'imazja223@gmail.com', '0762381520', '200136202736', 183, '$2y$10$EljB3nTPFq62vKpv79CyvuihnzoR7IiQ2UIBpzqKm3FWSLPhCQ0nO', '2025-02-11 09:20:58', 0, 0),
(15257, 'Imalsha', 'Akalanka', 'imazja224@gmail.com', '0762381520', '200136202736', 1, '$2y$10$WR0BnbDKATrGkxfWzY3Blu8PKEuXkAuyNle6vEssET1iR/GQbqQT6', '2025-02-11 09:31:53', 0, 0),
(15360, 'Siripala', 'Munasinghe', 'imazja225@gmail.com', '0766743755', '200136202812', 1, '$2y$10$raWeoMdw/2a.XFbgj4qUOOebDnmWv9YBC1pSCO4iGwps12XbEraCy', '2025-02-17 05:02:42', 0, 0),
(15364, 'Sakuna', 'Manamperi', 'sakunasanka@gmail.com', '0762381520', '200238273819', 1, '$2y$10$4CW08N6ppv2o190oQC5MfOnKZ6s6W9P3O.KeOH6xpCihr5fyBIpF2', '2024-11-17 16:35:01', 1, 0),
(15365, 'Pawan', 'Weearasuriya', 'pawan@gmail.com', '0782381520', '200223356485', 1, '$2y$10$vegPhLKyMQdySvO6A5udrOC9KXHV.cMY.TL8ih1QtFT72JqGcOJl6', '2024-11-17 16:36:28', 0, 0),
(15366, 'Roneth', 'Nimlaka', 'rowneth@gmail.com', '0784564436', '200234254634', 529, '$2y$10$1Nqfly5VSZLFya.L1g62fu4gDsI6LgxKfSZ7FfJczIDY/n/NOA9eS', '2024-11-17 16:45:20', 1, 0),
(15368, 'Thisara', 'Ruwanpathirana', 'thisara@gmail.com', '0732381520', '200136202812', 515, '$2y$10$Aq8zGHjAWJh65cpBJWdNPeWcIO9hZyi8RBT.88bm/qBSiSYB9/.6q', '2024-11-23 15:01:17', 0, 0),
(15370, 'Nimna', 'Pathum', 'nimnapathum@gmail.com', '0761389678', '200128673839', 52, '$2y$10$WsPWaoT80f1GBr0SGmGND.V2m.4Kzlw86bkz5zYua5rDk5qW2iSR.', '2024-11-28 16:36:59', 1, 0),
(16320, 'Mahinda', 'Rajapaksha', 'imalsha.contact2@gmail.com', '0766743755', '200136202812', 361, '$2y$10$m9LZjSlkYMBMwuR9G0RiQOAgmOs1WcWHjRckejr0m6WlQVpJcHeia', '2025-02-17 19:32:11', 0, 0),
(17001, 'Janaka', 'Abeywardana', 'janakaabey@gmail.com', '0740235478', '197353601274', 410, '$2y$10$p7w9Bd4XoZCkb2cRxteUNuSLXePMXqYQXBTO8d53p51nh0mZac3Cu', '2025-01-05 07:10:05', 1, 0),
(23230, 'Ramal', 'Aarchchi', 'ramalarachchi12@gmail.com', '0740827454', '299612341019', 515, '$2y$10$UVA3TSYUv9a7STiu9QUln.oiJzGouocYtXfIa86CSv3pW2RBqXiAi', '2025-04-16 08:22:59', 0, 0),
(23233, 'Imalsha', 'Akalanka', 'cozifink@gmail.com', '0766743755', '2001362824646', 515, '$2y$10$wOQvLiecSbq93fNNWst6EOV9MYnYjEtF7HXi1B2W6AlRD9YOP29SG', '2024-11-10 17:03:05', 1, 0),
(44112, 'Risafa', 'Imtiyas', 'shadmin@gmail.com', '0740827454', '00000000V', 1, '$2y$10$LQZjLGbIwOTDcUcBpk.w0.b60ptx58jsqOl8/nNOKDoe4PvIoX.hi', '2024-12-24 12:21:27', 0, 0),
(44113, 'Mahinda', 'Rajapaksha', 'imalsha.contact@gmail.com', '0766743755', 'mbkjb', 515, '$2y$10$MJ3Q0PCjxMK7MxB/BVvOW.B3aobZ9u5MK5ippiXO0S.gkDfY/fbri', '2025-02-17 20:30:51', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(1, 'imazja22@gmail.com', 'd9b67b1b74d350800ca8cac337628f2a818df2b58f2b677c3c3ee304fd941009', '2025-02-11 22:50:49', '2025-02-11 09:20:58'),
(2, 'imazja22@gmail.com', '3717e24325d53ba7ad05564406823976b2c5757a655dfbf01cba84cb5f9bbca2', '2025-02-11 23:01:43', '2025-02-11 09:31:53'),
(3, 'imazja22@gmail.com', '9771eeeaf7a40ff25ffdcf7bb3abb23a16f5ecb70b83116184118f5a42706d46', '2025-02-11 23:08:39', '2025-02-11 09:38:49'),
(4, 'imazja22@gmail.com', '97782c12aa701d9c3b01cd3d90d67eb41ccd3acd0b90c00cd2fb427bdc19e038', '2025-02-11 23:11:21', '2025-02-11 09:41:30'),
(5, 'imazja22@gmail.com', '6005164c99ff0a2f4bb0ba008b3385908a3c007b145c2819e984c14391b21c7d', '2025-02-11 23:34:18', '2025-02-11 10:04:28'),
(6, 'imazja22@gmail.com', '9fb7f3d04f1ea5d46a27c95123ab4889aa67ca7fa95c85290593866ed09948f8', '2025-02-11 23:37:06', '2025-02-11 10:07:16'),
(7, 'imazja22@gmail.com', '7ca62b607e947787343e24e26101b6dfebe46b9a1d40c4c777207567ce358bec', '2025-02-18 05:02:42', '2025-02-17 05:02:43'),
(8, 'imalsha.contact@gmail.com', 'a4ff0ec696a83f3c3b5aa96545336ca37b80b63ebe7c45f67dffb8ed3771191b', '2025-02-18 19:32:12', '2025-02-17 19:32:11'),
(9, 'imalsha.contact@gmail.com', '248d47034536ace25d09b533464e73a3b3861c8f5077621ba28a406ab3f2652b', '2025-02-18 19:37:17', '2025-02-17 19:37:16'),
(10, 'imalsha.contact@gmail.com', '850801ff8af3c1c4f6e07c998ccaddf7e65424bf19bfee0a07161668305935ad', '2025-02-18 20:30:52', '2025-02-17 20:30:51'),
(11, 'imazja22@gmail.com', 'e2fdecab0113fb2871659a5dd5c70db31d98e92a3023c1086a76fbeca126996a', '2025-04-15 13:40:34', '2025-04-14 13:40:35'),
(12, 'mrclocktd@gmail.com', '4234cd8f679a20feffde0a51f8c71890cb7c66462b6d4adb91d61e179d0de781', '2025-04-19 09:08:07', '2025-04-18 09:08:08'),
(13, 'imazja33@gmail.com', '3635651c2dfab7ea7fbec15acdb01ff0e3c3c2c86b96a60299d90c74f1b2c9f9', '2025-04-21 17:12:47', '2025-04-20 17:12:48');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `fine_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `paid_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`fine_id`, `amount`, `created_at`, `paid_at`) VALUES
(259, 2000.00, '2025-04-19 04:03:50', NULL),
(260, 3000.00, '2025-04-18 21:23:54', NULL),
(261, 1000.00, '2025-04-19 04:05:50', NULL),
(262, 3000.00, '2025-04-19 04:35:52', NULL),
(263, 1000.00, '2025-04-19 04:58:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `police_stations`
--

CREATE TABLE `police_stations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `province` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `police_stations`
--

INSERT INTO `police_stations` (`id`, `name`, `telephone`, `province`) VALUES
(1, 'Gampaha', '0332222224', 1),
(2, 'Minuwangoda', '0112295222', 1),
(3, 'Ganemulla', '0332260222', 1),
(4, 'Weliweriya', '0332255222', 1),
(5, 'Weeragula', '0332279442', 1),
(6, 'Yakkala', '0332225924', 1),
(7, 'Malwathuhiripitiya', '0332278800', 1),
(8, 'Pugoda', '0112405224', 1),
(9, 'Dompe', '0112409222', 1),
(10, 'Kirindiwela', '0332267222', 1),
(11, 'Nittambuwa', '0332287224', 1),
(12, 'Mirigama', '0332273222', 1),
(13, 'Veyangoda', '0332287222', 1),
(14, 'Pallewela', '0332271413', 1),
(15, 'Bemmulla', '0332252600', 1),
(16, 'Aththanagalla', '0332280626', 1),
(17, 'Nalla', '0372288669', 1),
(18, 'Danowita', '0352239054', 1),
(19, 'Nungamuwa', '0718592950', 1),
(20, 'Kadawatha', '0112925222', 1),
(21, 'Peliyagoda', '0112911222', 1),
(22, 'Kiribathgoda', '0112914985', 1),
(23, 'JaEla', '0112236222', 1),
(24, 'Wattala', '0112930222', 1),
(25, 'Kandana', '0112236333', 1),
(26, 'Biyagama', '0112487574', 1),
(27, 'Kelaniya', '0112911922', 1),
(28, 'Sapugaskanda', '0112400315', 1),
(29, 'Ragama', '0112958222', 1),
(30, 'Mahabage', '0112948469', 1),
(31, 'Meegahawatta', '0112977233', 1),
(32, 'Ederamulla', '0112940966', 1),
(33, 'Negombo', '0312222222', 1),
(34, 'Katunayaka Airport', '0312252948', 1),
(35, 'Katana', '0312246377', 1),
(36, 'Kotadeniyawa', '0312255616', 1),
(37, 'Divulapitiya', '0312293322', 1),
(38, 'Katunayaka', '0312237223', 1),
(39, 'Raddolugama', '0312247108', 1),
(40, 'Kochchikade', '0312275222', 1),
(41, 'Seeduwa', '0112256222', 1),
(42, 'Pamunugama', '0312251807', 1),
(43, 'Dungalpitiya', '0312277722', 1),
(44, 'Badalgama', '0312241492', 1),
(45, 'Panadura South', '0382236222', 1),
(46, 'Panadura North', '0382234222', 1),
(47, 'Wadduwa', '0382296222', 1),
(48, 'Millaniya', '0342258663', 1),
(49, 'Bandaragama', '0342269222', 1),
(50, 'Anguruwatota', '0342260492', 1),
(51, 'Morontuduwa', '0342265525', 1),
(52, 'Horana', '0342262222', 1),
(53, 'Ingiriya', '0342283222', 1),
(54, 'Moragahahena', '0342283772', 1),
(55, 'Pinwatta', '0382296224', 1),
(56, 'Hirana', '0382232224', 1),
(57, 'Alubomulla', '0342295624', 1),
(58, 'Mount Lavinia', '0112738222', 1),
(59, 'Angulana', '0112738333', 1),
(60, 'Dehiwala', '0112733224', 1),
(61, 'Kohuwala', '0112825222', 1),
(62, 'Moratuwa', '0112643222', 1),
(63, 'Egoda Uyana', '0112646222', 1),
(64, 'Moratumulla', '0112645122', 1),
(65, 'Piliyandala', '0112706222', 1),
(66, 'Kahathuduwa', '0112725222', 1),
(67, 'Mattegoda', '0112741222', 1),
(68, 'Kesbewa', '0112710222', 1),
(69, 'Mirihana', '0112859222', 1),
(70, 'Boralesgamuwa', '0112501322', 1),
(71, 'Gothatuwa', '0112570222', 1),
(72, 'Welikada', '0112873222', 1),
(73, 'Thalangama', '0112786222', 1),
(74, 'Meepe', '0342269114', 1),
(75, 'Athurugiriya', '0112768222', 1),
(76, 'Padukka', '0362242233', 1),
(77, 'Hanwella', '0362256222', 1),
(78, 'Kottawa', '0112843222', 1),
(79, 'Maharagama', '0112857222', 1),
(80, 'Homagama', '0112857222', 1),
(81, 'Mulleriyava', '0112790922', 1),
(82, 'Wellampitiya', '0112533222', 1),
(83, 'Nawagamuwa', '0112746222', 1),
(84, 'Kaduwela', '0112576111', 1),
(85, 'Malabe', '0112787777', 1),
(86, 'Kalutara South', '0342222224', 1),
(87, 'Kalutara North', '0342231224', 1),
(88, 'Beruwala', '0342276222', 1),
(89, 'Aluthgama', '0342272022', 1),
(90, 'Payagala', '0342254066', 1),
(91, 'Dodangoda', '0342254266', 1),
(92, 'Thebuwana', '0342261426', 1),
(93, 'Mathugama', '0342248264', 1),
(94, 'Agalawatta', '0342242624', 1),
(95, 'Warakagoda', '0342243246', 1),
(96, 'Welipanna', '0342243222', 1),
(97, 'Baduraliya', '0342277222', 1),
(98, 'Bulathsinhala', '0342282222', 1),
(99, 'Meegahathenna', '0342288222', 1),
(100, 'Thiniyawala', '0342242222', 1),
(101, 'Iththapana', '0342252346', 1),
(102, 'Pettah', '0112322893', 1),
(103, 'Keselwatta', '0112434303', 1),
(104, 'Fort', '0112327991', 1),
(105, 'Slave Island', '0112434317', 1),
(106, 'Maradana', '0112691545', 1),
(107, 'Maligawatta', '0112434307', 1),
(108, 'Wolfendhal', '0112331746', 1),
(109, 'Dam Street', '0112446443', 1),
(110, 'Colombo Harbour', '0112327558', 1),
(111, 'Fore Shore', '0112332084', 1),
(112, 'Kotahena', '0112446004', 1),
(113, 'Modara', '0112541846', 1),
(114, 'Grandpass', '0112522278', 1),
(115, 'Dematagoda', '0112678764', 1),
(116, 'Mattakkuliya', '0112526445', 1),
(117, 'Bluemandal', '0112593514', 1),
(118, 'Mihijayasewana', '0112536014', 1),
(119, 'Bambalapitiya', '0112580678', 1),
(120, 'Kollupitiya', '0112572210', 1),
(121, 'Kirulapana', '0112519544', 1),
(122, 'Narahenpita', '0112508203', 1),
(123, 'Wellawatta', '0112589347', 1),
(124, 'Borella', '0112691224', 1),
(125, 'Cinnamon Garden', '0112695881', 1),
(126, 'BMICH', '0112681567', 1),
(127, 'Police Headquarters', '0112433333', 1),
(128, 'Batticaloa', '0111234567', 9),
(129, 'Vavunathive', '0111234567', 9),
(130, 'Kaththankudi', '0111234567', 9),
(131, 'Eravur', '0111234567', 9),
(132, 'Karadiyanaru', '0111234567', 9),
(133, 'Aiththamalai', '0111234567', 9),
(134, 'Valachchanai', '0111234567', 9),
(135, 'Kalkudah', '0111234567', 9),
(136, 'Kalawanchikudy', '0111234567', 9),
(137, 'Vellavali', '0111234567', 9),
(138, 'Kokkadicholai', '0111234567', 9),
(139, 'Kokuwill', '0111234567', 9),
(140, 'Ampara', '0111234567', 9),
(141, 'Damana', '0111234567', 9),
(142, 'Inginiyagala', '0111234567', 9),
(143, 'Uhana', '0111234567', 9),
(144, 'Bakkiella', '0111234567', 9),
(145, 'Central Camp', '0111234567', 9),
(146, 'Mangalagama', '0111234567', 9),
(147, 'Mahaoya', '0111234567', 9),
(148, 'Dehiaththakandiya', '0111234567', 9),
(149, 'Padiyathalawa', '0111234567', 9),
(150, 'Kalmunai', '0111234567', 9),
(151, 'Samanthurai', '0111234567', 9),
(152, 'Chawalakade', '0111234567', 9),
(153, 'Akkaraipattu', '0111234567', 9),
(154, 'Thirukkovil', '0111234567', 9),
(155, 'Pothuvil', '0111234567', 9),
(156, 'Panama', '0111234567', 9),
(157, 'Eragama', '0111234567', 9),
(158, 'Bandaraduwa', '0111234567', 9),
(159, 'Karthivue', '0111234567', 9),
(160, 'Saindamarudu', '0111234567', 9),
(161, 'Periyanilawali', '0111234567', 9),
(162, 'Nindavur', '0111234567', 9),
(163, 'Trincomalee', '0111234567', 9),
(164, 'Trinco Harbour Police', '0111234567', 9),
(165, 'Chinabay', '0111234567', 9),
(166, 'Kuchchaweli', '0111234567', 9),
(167, 'Uppuweli', '0111234567', 9),
(168, 'Pulmudai', '0111234567', 9),
(169, 'Sri pura', '0111234567', 9),
(170, 'Nilawely', '0111234567', 9),
(171, 'Mutur', '0111234567', 9),
(172, 'Sampoor', '0111234567', 9),
(173, 'Kinniya', '0111234567', 9),
(174, 'Kantale', '0111234567', 9),
(175, 'Agbopura', '0111234567', 9),
(176, 'Thambalagamuva', '0111234567', 9),
(177, 'Serunuwara', '0111234567', 9),
(178, 'Sooriyapura', '0111234567', 9),
(179, 'Wan Ela', '0111234567', 9),
(180, 'Morawewa', '0111234567', 9),
(181, 'Gomarankadawala', '0111234567', 9),
(182, 'Echchallmpaththu', '0111234567', 9),
(183, 'Anuradhapura', '0111234567', 7),
(184, 'Hidogama', '0111234567', 7),
(185, 'Udamaluwa', '0111234567', 7),
(186, 'Mihintale', '0111234567', 7),
(187, 'Kekirawa', '0111234567', 7),
(188, 'Thirappone', '0111234567', 7),
(189, 'Eppawala', '0111234567', 7),
(190, 'Galnewa', '0111234567', 7),
(191, 'Galkiriyagama', '0111234567', 7),
(192, 'Thambuttegama', '0111234567', 7),
(193, 'Rajangane', '0111234567', 7),
(194, 'Nochchiyagama', '0111234567', 7),
(195, 'Thalawa', '0111234567', 7),
(196, 'Thanthirimalaya', '0111234567', 7),
(197, 'Mahawilachchiya', '0111234567', 7),
(198, 'Padavi Sri Pura', '0111234567', 7),
(199, 'Ipallogama', '0111234567', 7),
(200, 'Parasangaswewa', '0111234567', 7),
(201, 'Moragoda', '0111234567', 7),
(202, 'Oyamaduwa', '0111234567', 7),
(203, 'Kallanchiya', '0111234567', 7),
(204, 'Hurigaswewea', '0111234567', 7),
(205, 'Elayapathtuwa', '0111234567', 7),
(206, 'Giranegama', '0111234567', 7),
(207, 'Kawarakkulam', '0111234567', 7),
(208, 'Madatugama', '0111234567', 7),
(209, 'Maradankadawala', '0111234567', 7),
(210, 'Priyankaragama', '0111234567', 7),
(211, 'Siwalakulam', '0111234567', 7),
(212, 'Puhudiula', '0111234567', 7),
(213, 'Kebithigollawa', '0111234567', 7),
(214, 'Horowpathana', '0111234567', 7),
(215, 'Padaviya', '0111234567', 7),
(216, 'Medawachchiya', '0111234567', 7),
(217, 'Galenbidunuwewa', '0111234567', 7),
(218, 'Kahatagasdigiliya', '0111234567', 7),
(219, 'Pihibiyagollewa', '0111234567', 7),
(220, 'Kapugollewa', '0111234567', 7),
(221, 'Kokawewa', '0111234567', 7),
(222, 'Devuolwewa', '0111234567', 7),
(223, 'Etaweeragollewa', '0111234567', 7),
(224, 'Wahalkada', '0111234567', 7),
(225, 'Punewa', '0111234567', 7),
(226, 'Polonnaruwa', '0111234567', 7),
(227, 'Pulasthipura', '0111234567', 7),
(228, 'Welikanda', '0111234567', 7),
(229, 'Aralaganwila', '0111234567', 7),
(230, 'Hingurakgoda', '0111234567', 7),
(231, 'Medirigiriya', '0111234567', 7),
(232, 'Habarana', '0111234567', 7),
(233, 'Bakamuna', '0111234567', 7),
(234, 'Minneriya', '0111234567', 7),
(235, 'Siripura', '0111234567', 7),
(236, 'Diyabeduma', '0111234567', 7),
(237, 'Megaswewa', '0111234567', 7),
(238, 'Manampitiya', '0111234567', 7),
(239, 'Kankasanthurai', '0111234567', 8),
(240, 'Illawali', '0111234567', 8),
(241, 'Achchuweli', '0111234567', 8),
(242, 'Thelippalai', '0111234567', 8),
(243, 'Palali', '0111234567', 8),
(244, 'Point Pedro', '0111234567', 8),
(245, 'Velvatithurai', '0111234567', 8),
(246, 'Jaffna', '0111234567', 8),
(247, 'Manippai', '0111234567', 8),
(248, 'Chunnakam', '0111234567', 8),
(249, 'Kayts', '0111234567', 8),
(250, 'Delft', '0111234567', 8),
(251, 'Wadukotte', '0111234567', 8),
(252, 'Chawakachcheri', '0111234567', 8),
(253, 'Kopai', '0111234567', 8),
(254, 'Kodikamam', '0111234567', 8),
(255, 'Vavuniya', '0111234567', 8),
(256, 'Bogaswewa', '0111234567', 8),
(257, 'Puliyankulam', '0111234567', 8),
(258, 'Chettikulam', '0111234567', 8),
(259, 'Ulukkulam', '0111234567', 8),
(260, 'Puwarasamkulam', '0111234567', 8),
(261, 'Echchanmkulam', '0111234567', 8),
(262, 'MaMaduwa', '0111234567', 8),
(263, 'Irattaperiyakulam', '0111234567', 8),
(264, 'Parayanakulam', '0111234567', 8),
(265, 'Omantha', '0111234567', 8),
(266, 'Nedumkenrni', '0111234567', 8),
(267, 'Kanagarayankulam', '0111234567', 8),
(268, 'Nerukkulam', '0111234567', 8),
(269, 'Sidambarampuram', '0111234567', 8),
(270, 'Kilinochchi', '0111234567', 8),
(271, 'Palei', '0111234567', 8),
(272, 'Punarin', '0111234567', 8),
(273, 'Jayapuram', '0111234567', 8),
(274, 'Mulankavil', '0111234567', 8),
(275, 'Dharmapuram', '0111234567', 8),
(276, 'Akkarayankulam', '0111234567', 8),
(277, 'Maradankerni', '0111234567', 8),
(278, 'Mannar', '0111234567', 8),
(279, 'Silawathura', '0111234567', 8),
(280, 'Iluppakadawai', '0111234567', 8),
(281, 'Thalaimannar', '0111234567', 8),
(282, 'Murunkan', '0111234567', 8),
(283, 'Madu', '0111234567', 8),
(284, 'Wankala', '0111234567', 8),
(285, 'Pesale', '0111234567', 8),
(286, 'Uilankulam', '0111234567', 8),
(287, 'Mullaittivu', '0111234567', 8),
(288, 'Pudukuduiruppu', '0111234567', 8),
(289, 'Mulliyaweli', '0111234567', 8),
(290, 'Oddusudan', '0111234567', 8),
(291, 'Welioya', '0111234567', 8),
(292, 'Mankulam', '0111234567', 8),
(293, 'Mallavi', '0111234567', 8),
(294, 'Aiyankulam', '0111234567', 8),
(295, 'Kokilai', '0111234567', 8),
(296, 'Nattonkadda', '0111234567', 8),
(297, 'Kurunegala', '0111234567', 6),
(298, 'Polgahawela', '0111234567', 6),
(299, 'Alawwa', '0111234567', 6),
(300, 'Pothuhera', '0111234567', 6),
(301, 'Wariyapola', '0111234567', 6),
(302, 'Weerambugedara', '0111234567', 6),
(303, 'Wellawa', '0111234567', 6),
(304, 'Gokarella', '0111234567', 6),
(305, 'Mawathagama', '0111234567', 6),
(306, 'Rideegama', '0111234567', 6),
(307, 'Kumbukgatei', '0111234567', 6),
(308, 'Thalamalgama', '0111234567', 6),
(309, 'Bogamuwa', '0111234567', 6),
(310, 'Malsiripura', '0111234567', 6),
(311, 'Maduragoda', '0111234567', 6),
(312, 'Delvita', '0111234567', 6),
(313, 'Inguruwatta', '0111234567', 6),
(314, 'Welagedara', '0111234567', 6),
(315, 'Boyawalana', '0111234567', 6),
(316, 'Doratiyawa', '0111234567', 6),
(317, 'Gonagama', '0111234567', 6),
(318, 'Kuliyapitiya', '0111234567', 6),
(319, 'Narammala', '0111234567', 6),
(320, 'Pannala', '0111234567', 6),
(321, 'Giriulla', '0111234567', 6),
(322, 'Hettipola', '0111234567', 6),
(323, 'Katupotha', '0111234567', 6),
(324, 'Dummalasooriya', '0111234567', 6),
(325, 'Bingiriya', '0111234567', 6),
(326, 'Kadanegedara', '0111234567', 6),
(327, 'Erapola', '0111234567', 6),
(328, 'Welipannagahamula', '0111234567', 6),
(329, 'Mohottawa', '0111234567', 6),
(330, 'Nikaweratiya', '0111234567', 6),
(331, 'Kobeigane', '0111234567', 6),
(332, 'Kotawehera', '0111234567', 6),
(333, 'Rasnayakapura', '0111234567', 6),
(334, 'Maho', '0111234567', 6),
(335, 'Ambanpola', '0111234567', 6),
(336, 'Polpithigama', '0111234567', 6),
(337, 'Galgamuwa', '0111234567', 6),
(338, 'Meegalewa', '0111234567', 6),
(339, 'Giribawa', '0111234567', 6),
(340, 'Pansiyagama', '0111234567', 6),
(341, 'Girilla', '0111234567', 6),
(342, 'Nagollagama', '0111234567', 6),
(343, 'Moragollagama', '0111234567', 6),
(344, 'Nannariya', '0111234567', 6),
(345, 'Ehatuwewa', '0111234567', 6),
(346, 'Anamaduwa', '0111234567', 6),
(347, 'Pallama', '0111234567', 6),
(348, 'Nawagaththegama', '0111234567', 6),
(349, 'Saliyawewa', '0111234567', 6),
(350, 'Rathnapura', '0111234567', 5),
(351, 'Wewalwatta', '0111234567', 5),
(352, 'Balangoda', '0111234567', 5),
(353, 'Opanayaka', '0111234567', 5),
(354, 'Kaltota', '0111234567', 5),
(355, 'Weligepola', '0111234567', 5),
(356, 'Kahawatta', '0111234567', 5),
(357, 'Rakwana', '0111234567', 5),
(358, 'Ayagama', '0111234567', 5),
(359, 'Kalawana', '0111234567', 5),
(360, 'Nivithigala', '0111234567', 5),
(361, 'Kuruwita', '0111234567', 5),
(362, 'Ehaliyagoda', '0111234567', 5),
(363, 'Kiriella', '0111234567', 5),
(364, 'Pinnawala', '0111234567', 5),
(365, 'Siripagama', '0111234567', 5),
(366, 'Palmadulla', '0111234567', 5),
(367, 'Enadana', '0111234567', 5),
(368, 'Samanala wewa', '0111234567', 5),
(369, 'Delwala', '0111234567', 5),
(370, 'Elapatha', '0111234567', 5),
(371, 'Pothupitiya', '0111234567', 5),
(372, 'Embilipitiya', '0111234567', 5),
(373, 'Kuttigala', '0111234567', 5),
(374, 'Godakawela', '0111234567', 5),
(375, 'Kolonna', '0111234567', 5),
(376, 'Panamure', '0111234567', 5),
(377, 'Sewanagala', '0111234567', 5),
(378, 'Udawalawa', '0111234567', 5),
(379, 'Sooriyakanda', '0111234567', 5),
(380, 'Kegalle', '0111234567', 5),
(381, 'Warakapola', '0111234567', 5),
(382, 'Aranayaka', '0111234567', 5),
(383, 'Dedigama', '0111234567', 5),
(384, 'Mawanella', '0111234567', 5),
(385, 'Rambukkana', '0111234567', 5),
(386, 'Pindeniya', '0111234567', 5),
(387, 'Hemmathagama', '0111234567', 5),
(388, 'Dewalegama', '0111234567', 5),
(389, 'Awissawella', '0111234567', 5),
(390, 'Kosgama', '0111234567', 5),
(391, 'Deraniyagala', '0111234567', 5),
(392, 'Ruwanwella', '0111234567', 5),
(393, 'Yatiyantota', '0111234567', 5),
(394, 'Kithulgala', '0111234567', 5),
(395, 'Bulathkohupitiya', '0111234567', 5),
(396, 'Nuriya', '0111234567', 5),
(397, 'Galapitamada', '0111234567', 5),
(398, 'Dehiowita', '0111234567', 5),
(399, 'Morontota', '0111234567', 5),
(400, 'Matara', '0111234567', 3),
(401, 'Gandara', '0111234567', 3),
(402, 'Dikwella', '0111234567', 3),
(403, 'Thihagoda', '0111234567', 3),
(404, 'Kamburupitiya', '0111234567', 3),
(405, 'Hakmana', '0111234567', 3),
(406, 'Morawaka', '0111234567', 3),
(407, 'Urubokka', '0111234567', 3),
(408, 'Mawarala', '0111234567', 3),
(409, 'Deniyaya', '0111234567', 3),
(410, 'Weligama', '0111234567', 3),
(411, 'Akuressa', '0111234567', 3),
(412, 'Kananke', '0111234567', 3),
(413, 'Pitabeddara', '0111234567', 3),
(414, 'Kotawila', '0111234567', 3),
(415, 'Kosmodara', '0111234567', 3),
(416, 'Malimbada', '0111234567', 3),
(417, 'Rotumba', '0111234567', 3),
(418, 'Deyyandara', '0111234567', 3),
(419, 'Bangama', '0111234567', 3),
(420, 'Midigama', '0111234567', 3),
(421, 'Galle', '0111234567', 3),
(422, 'Rathgama', '0111234567', 3),
(423, 'Hikkaduwa', '0111234567', 3),
(424, 'Akmeemana', '0111234567', 3),
(425, 'Wanduramba', '0111234567', 3),
(426, 'Yakkalamulla', '0111234567', 3),
(427, 'Poddala', '0111234567', 3),
(428, 'Habaraduwa', '0111234567', 3),
(429, 'Ahangama', '0111234567', 3),
(430, 'Galle Harbour', '0111234567', 3),
(431, 'Nagoda', '0111234567', 3),
(432, 'Hiniduma', '0111234567', 3),
(433, 'Neluwa', '0111234567', 3),
(434, 'Udugama', '0111234567', 3),
(435, 'Imaduwa', '0111234567', 3),
(436, 'Opatha', '0111234567', 3),
(437, 'Gonapinuwala', '0111234567', 3),
(438, 'Elpitiya', '0111234567', 3),
(439, 'Uragasmanhandiya', '0111234567', 3),
(440, 'Pitigala', '0111234567', 3),
(441, 'Karandeniya', '0111234567', 3),
(442, 'Baddegama', '0111234567', 3),
(443, 'Batapola', '0111234567', 3),
(444, 'Meetiyagoda', '0111234567', 3),
(445, 'Thelikada', '0111234567', 3),
(446, 'Ambalangoda', '0111234567', 3),
(447, 'Ahungalla', '0111234567', 3),
(448, 'Kosgoda', '0111234567', 3),
(449, 'Bentota', '0111234567', 3),
(450, 'Tangalle', '0111234567', 3),
(451, 'Hungama', '0111234567', 3),
(452, 'Weeraketiya', '0111234567', 3),
(453, 'Angunakolapelessa', '0111234567', 3),
(454, 'Walasmulla', '0111234567', 3),
(455, 'Beliaththa', '0111234567', 3),
(456, 'Katuwana', '0111234567', 3),
(457, 'Middeniya', '0111234567', 3),
(458, 'Sooriyawewa', '0111234567', 3),
(459, 'Hambantota', '0111234567', 3),
(460, 'Ambalantota', '0111234567', 3),
(461, 'Tissamaharamaya', '0111234567', 3),
(462, 'Kataragama', '0111234567', 3),
(463, 'Lunugamwehera', '0111234567', 3),
(464, 'Weeravila', '0111234567', 3),
(465, 'Kirinda', '0111234567', 3),
(466, 'Hambantota Haber', '0111234567', 3),
(467, 'Mathtala', '0111234567', 3),
(468, 'Okewella', '0111234567', 3),
(469, 'Badulla', '0111234567', 4),
(470, 'Passara', '0111234567', 4),
(471, 'Madolseema', '0111234567', 4),
(472, 'Lunugala', '0111234567', 4),
(473, 'Mahiyangane', '0111234567', 4),
(474, 'Giradurukotte', '0111234567', 4),
(475, 'Kandaketiya', '0111234567', 4),
(476, 'Haliela', '0111234567', 4),
(477, 'Rideemaliyadda (Kanugolla P/P)', '0111234567', 4),
(478, 'Kahataruppa', '0111234567', 4),
(479, 'Diulapalassa', '0111234567', 4),
(480, 'Higurukaduwa', '0111234567', 4),
(481, 'Arawa', '0111234567', 4),
(482, 'Galauda', '0111234567', 4),
(483, 'Namunukula', '0111234567', 4),
(484, 'Bandarawela', '0111234567', 4),
(485, 'Ella', '0111234567', 4),
(486, 'Welimada', '0111234567', 4),
(487, 'Uvaparanagama', '0111234567', 4),
(488, 'Etampitiya', '0111234567', 4),
(489, 'Haputale', '0111234567', 4),
(490, 'Diyathalawa', '0111234567', 4),
(491, 'Koslanda', '0111234567', 4),
(492, 'Haldummulla', '0111234567', 4),
(493, 'Bogahakumbura', '0111234567', 4),
(494, 'Ambagasdowa', '0111234567', 4),
(495, 'Ballaketuwa', '0111234567', 4),
(496, 'Keppattipola', '0111234567', 4),
(497, 'Liyangahawela', '0111234567', 4),
(498, 'Medagama', '0111234567', 4),
(499, 'Monaragala', '0111234567', 4),
(500, 'Karandugala', '0111234567', 4),
(501, 'Bibila', '0111234567', 4),
(502, 'Buththala', '0111234567', 4),
(503, 'Gonaganara', '0111234567', 4),
(504, 'Okkampitiya', '0111234567', 4),
(505, 'Badalkumbura', '0111234567', 4),
(506, 'Thanamalwila', '0111234567', 4),
(507, 'Wellawaya', '0111234567', 4),
(508, 'Hambegamuwa', '0111234567', 4),
(509, 'Kudaoya', '0111234567', 4),
(510, 'Siyambalanduwa', '0111234567', 4),
(511, 'Govindupura', '0111234567', 4),
(512, 'Ethimale', '0111234567', 4),
(513, 'Mahakalugolla', '0111234567', 4),
(514, 'Dambagalla', '0111234567', 4),
(515, 'Alawathugoda', '0111234567', 2),
(516, 'Ankumbura', '0111234567', 2),
(517, 'Dalada Maligawa', '0111234567', 2),
(518, 'Daulagala', '0111234567', 2),
(519, 'Galagedara', '0111234567', 2),
(520, 'Hanguranketha', '0111234567', 2),
(521, 'Hatharaliyadda', '0111234567', 2),
(522, 'Kadugannawa', '0111234567', 2),
(523, 'Kandy', '0111234567', 2),
(524, 'Katugastota', '0111234567', 2),
(525, 'Pallakele', '0111234567', 2),
(526, 'Peradeniya', '0111234567', 2),
(527, 'Poojapitiya', '0111234567', 2),
(528, 'Poththapitiya', '0111234567', 2),
(529, 'Welambada', '0111234567', 2),
(530, 'Bokkawala', '0111234567', 2),
(531, 'Aladeniya', '0111234567', 2),
(532, 'Hasalaka', '0111234567', 2),
(533, 'Manikhinna', '0111234567', 2),
(534, 'Panwila', '0111234567', 2),
(535, 'Rangala', '0111234567', 2),
(536, 'Theldeniya', '0111234567', 2),
(537, 'Ududumbara', '0111234567', 2),
(538, 'Wattegama', '0111234567', 2),
(539, 'Galaha', '0111234567', 2),
(540, 'Gampola', '0111234567', 2),
(541, 'Nawa Kurunduwatta', '0111234567', 2),
(542, 'Nawalapitiya', '0111234567', 2),
(543, 'Pupuressa', '0111234567', 2),
(544, 'Pussellawa', '0111234567', 2),
(545, 'Parrateasse', '0111234567', 2),
(546, 'Ethgala', '0111234567', 2),
(547, 'Frotop', '0111234567', 2),
(548, 'Dambulla', '0111234567', 2),
(549, 'Galewela', '0111234567', 2),
(550, 'Laggala', '0111234567', 2),
(551, 'Mahawela', '0111234567', 2),
(552, 'Makulugaswewa', '0111234567', 2),
(553, 'Matale', '0111234567', 2),
(554, 'Naula', '0111234567', 2),
(555, 'Rattota', '0111234567', 2),
(556, 'Sigiriya', '0111234567', 2),
(557, 'Wilgamuwa', '0111234567', 2),
(558, 'Yatawatta', '0111234567', 2),
(559, 'Kandenuwara', '0111234567', 2),
(560, 'Handungamuwa', '0111234567', 2),
(561, 'Agarapathana', '0111234567', 2),
(562, 'Dayagama', '0111234567', 2),
(563, 'Dimbulapathana', '0111234567', 2),
(564, 'Kandapola', '0111234567', 2),
(565, 'Keerthi Bandarapura', '0111234567', 2),
(566, 'Kothmale', '0111234567', 2),
(567, 'Lindula', '0111234567', 2),
(568, 'Mandaram Nuwara', '0111234567', 2),
(569, 'Maturata', '0111234567', 2),
(570, 'Nanuoya', '0111234567', 2),
(571, 'Nuwaraeliya', '0111234567', 2),
(572, 'Pattipola', '0111234567', 2),
(573, 'Pudalu oya', '0111234567', 2),
(574, 'Ragala', '0111234567', 2),
(575, 'Thalawakale', '0111234567', 2),
(576, 'Theripaha', '0111234567', 2),
(577, 'Udupussellawa', '0111234567', 2),
(578, 'Walapone', '0111234567', 2),
(579, 'Highforest', '0111234567', 2),
(580, 'Hatton', '0111234567', 2),
(581, 'Watawala', '0111234567', 2),
(582, 'Ginigathhena', '0111234567', 2),
(583, 'Nortonbridge', '0111234567', 2),
(584, 'Nallathanni', '0111234567', 2),
(585, 'Bagawanthalawa', '0111234567', 2),
(586, 'Norwood', '0111234567', 2),
(587, 'Maskeliya', '0111234567', 2),
(588, 'Polpitiya', '0111234567', 2);

-- --------------------------------------------------------

--
-- Table structure for table `police_stations_draft`
--

CREATE TABLE `police_stations_draft` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `telephone` varchar(10) NOT NULL,
  `province` int(11) NOT NULL DEFAULT 1 COMMENT 'Province Index from 1 to 9 which represents the 9 provinces in Sri Lanka.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `police_stations_draft`
--

INSERT INTO `police_stations_draft` (`id`, `name`, `telephone`, `province`) VALUES
(1, 'Gampaha', '0332222224', 1),
(2, 'Minuwangoda', '0112295222', 1),
(3, 'Ganemulla', '0332260222', 1),
(4, 'Weliweriya', '0332255222', 1),
(5, 'Weeragula', '0332279442', 1),
(6, 'Yakkala', '0332225924', 1),
(7, 'Malwathuhiripitiya', '0332278800', 1),
(8, 'Pugoda', '0112405224', 1),
(9, 'Dompe', '0112409222', 1),
(10, 'Kirindiwela', '0332267222', 1),
(11, 'Nittambuwa', '0332287224', 1),
(12, 'Mirigama', '0332273222', 1),
(13, 'Veyangoda', '0332287222', 1),
(14, 'Pallewela', '0332271413', 1),
(15, 'Bemmulla', '0332252600', 1),
(16, 'Aththanagalla', '0332280626', 1),
(17, 'Nalla', '0372288669', 1),
(18, 'Danowita', '0352239054', 1),
(19, 'Nungamuwa', '0718592950', 1),
(20, 'Kadawatha', '0112925222', 1),
(21, 'Peliyagoda', '0112911222', 1),
(22, 'Kiribathgoda', '0112914985', 1),
(23, 'JaEla', '0112236222', 1),
(24, 'Wattala', '0112930222', 1),
(25, 'Kandana', '0112236333', 1),
(26, 'Biyagama', '0112487574', 1),
(27, 'Kelaniya', '0112911922', 1),
(28, 'Sapugaskanda', '0112400315', 1),
(29, 'Ragama', '0112958222', 1),
(30, 'Mahabage', '0112948469', 1),
(31, 'Meegahawatta', '0112977233', 1),
(32, 'Ederamulla', '0112940966', 1),
(33, 'Negombo', '0312222222', 1),
(34, 'Katunayaka Airport', '0312252948', 1),
(35, 'Katana', '0312246377', 1),
(36, 'Kotadeniyawa', '0312255616', 1),
(37, 'Divulapitiya', '0312293322', 1),
(38, 'Katunayaka', '0312237223', 1),
(39, 'Raddolugama', '0312247108', 1),
(40, 'Kochchikade', '0312275222', 1),
(41, 'Seeduwa', '0112256222', 1),
(42, 'Pamunugama', '0312251807', 1),
(43, 'Dungalpitiya', '0312277722', 1),
(44, 'Badalgama', '0312241492', 1),
(45, 'Panadura South', '0382236222', 1),
(46, 'Panadura North', '0382234222', 1),
(47, 'Wadduwa', '0382296222', 1),
(48, 'Millaniya', '0342258663', 1),
(49, 'Bandaragama', '0342269222', 1),
(50, 'Anguruwatota', '0342260492', 1),
(51, 'Morontuduwa', '0342265525', 1),
(52, 'Horana', '0342262222', 1),
(53, 'Ingiriya', '0342283222', 1),
(54, 'Moragahahena', '0342283772', 1),
(55, 'Pinwatta', '0382296224', 1),
(56, 'Hirana', '0382232224', 1),
(57, 'Alubomulla', '0342295624', 1),
(58, 'Mount Lavinia', '0112738222', 1),
(59, 'Angulana', '0112738333', 1),
(60, 'Dehiwala', '0112733224', 1),
(61, 'Kohuwala', '0112825222', 1),
(62, 'Moratuwa', '0112643222', 1),
(63, 'Egoda Uyana', '0112646222', 1),
(64, 'Moratumulla', '0112645122', 1),
(65, 'Piliyandala', '0112706222', 1),
(66, 'Kahathuduwa', '0112725222', 1),
(67, 'Mattegoda', '0112741222', 1),
(68, 'Kesbewa', '0112710222', 1),
(69, 'Mirihana', '0112859222', 1),
(70, 'Boralesgamuwa', '0112501322', 1),
(71, 'Gothatuwa', '0112570222', 1),
(72, 'Welikada', '0112873222', 1),
(73, 'Thalangama', '0112786222', 1),
(74, 'Meepe', '0342269114', 1),
(75, 'Athurugiriya', '0112768222', 1),
(76, 'Padukka', '0362242233', 1),
(77, 'Hanwella', '0362256222', 1),
(78, 'Kottawa', '0112843222', 1),
(79, 'Maharagama', '0112857222', 1),
(80, 'Homagama', '0112857222', 1),
(81, 'Mulleriyava', '0112790922', 1),
(82, 'Wellampitiya', '0112533222', 1),
(83, 'Nawagamuwa', '0112746222', 1),
(84, 'Kaduwela', '0112576111', 1),
(85, 'Malabe', '0112787777', 1),
(86, 'Kalutara South', '0342222224', 1),
(87, 'Kalutara North', '0342231224', 1),
(88, 'Beruwala', '0342276222', 1),
(89, 'Aluthgama', '0342272022', 1),
(90, 'Payagala', '0342254066', 1),
(91, 'Dodangoda', '0342254266', 1),
(92, 'Thebuwana', '0342261426', 1),
(93, 'Mathugama', '0342248264', 1),
(94, 'Agalawatta', '0342242624', 1),
(95, 'Warakagoda', '0342243246', 1),
(96, 'Welipanna', '0342243222', 1),
(97, 'Baduraliya', '0342277222', 1),
(98, 'Bulathsinhala', '0342282222', 1),
(99, 'Meegahathenna', '0342288222', 1),
(100, 'Thiniyawala', '0342242222', 1),
(101, 'Iththapana', '0342252346', 1),
(102, 'Pettah', '0112322893', 1),
(103, 'Keselwatta', '0112434303', 1),
(104, 'Fort', '0112327991', 1),
(105, 'Slave Island', '0112434317', 1),
(106, 'Maradana', '0112691545', 1),
(107, 'Maligawatta', '0112434307', 1),
(108, 'Wolfendhal', '0112331746', 1),
(109, 'Dam Street', '0112446443', 1),
(110, 'Colombo Harbour', '0112327558', 1),
(111, 'Fore Shore', '0112332084', 1),
(112, 'Kotahena', '0112446004', 1),
(113, 'Modara', '0112541846', 1),
(114, 'Grandpass', '0112522278', 1),
(115, 'Dematagoda', '0112678764', 1),
(116, 'Mattakkuliya', '0112526445', 1),
(117, 'Bluemandal', '0112593514', 1),
(118, 'Mihijayasewana', '0112536014', 1),
(119, 'Bambalapitiya', '0112580678', 1),
(120, 'Kollupitiya', '0112572210', 1),
(121, 'Kirulapana', '0112519544', 1),
(122, 'Narahenpita', '0112508203', 1),
(123, 'Wellawatta', '0112589347', 1),
(124, 'Borella', '0112691224', 1),
(125, 'Cinnamon Garden', '0112695881', 1),
(126, 'BMICH', '0112681567', 1),
(127, 'Police Headquarters', '0112433333', 1),
(128, 'Batticaloa', '0652222101', 1),
(129, 'Vavunathive', '0652222102', 1),
(130, 'Kaththankudi', '0652222103', 1),
(131, 'Eravur', '0652222104', 1),
(132, 'Karadiyanaru', '0652222105', 1),
(133, 'Aiththamalai', '0652222106', 1),
(134, 'Valachchanai', '0652222107', 1),
(135, 'Kalkudah', '0652222108', 1),
(136, 'Kalawanchikudy', '0652222109', 1),
(137, 'Vellavali', '0652222110', 1),
(138, 'Kokkadicholai', '0652222111', 1),
(139, 'Kokuwill', '0652222112', 1),
(140, 'Ampara', '0652222113', 1),
(141, 'Damana', '0652222114', 1),
(142, 'Inginiyagala', '0652222115', 1),
(143, 'Uhana', '0652222116', 1),
(144, 'Bakkiella', '0652222117', 1),
(145, 'Central Camp', '0652222118', 1),
(146, 'Mangalagama', '0652222119', 1),
(147, 'Mahaoya', '0652222120', 1),
(148, 'Dehiaththakandiya', '0652222121', 1),
(149, 'Padiyathalawa', '0652222122', 1),
(150, 'Kalmunai', '0652222123', 1),
(151, 'Samanthurai', '0652222124', 1),
(152, 'Chawalakade', '0652222125', 1),
(153, 'Akkaraipattu', '0652222126', 1),
(154, 'Thirukkovil', '0652222127', 1),
(155, 'Pothuvil', '0652222128', 1),
(156, 'Panama', '0652222129', 1),
(157, 'Eragama', '0652222130', 1),
(158, 'Bandaraduwa', '0652222131', 1),
(159, 'Karthivue', '0652222132', 1),
(160, 'Saindamarudu', '0652222133', 1),
(161, 'Periyanilawali', '0652222134', 1),
(162, 'Nindavur', '0652222135', 1),
(163, 'Trincomalee', '0652222136', 1),
(164, 'Trinco Harbour Police', '0652222137', 1),
(165, 'Chinabay', '0652222138', 1),
(166, 'Kuchchaweli', '0652222139', 1),
(167, 'Uppuweli', '0652222140', 1),
(168, 'Pulmudai', '0652222141', 1),
(169, 'Sri pura', '0652222142', 1),
(170, 'Nilawely', '0652222143', 1),
(171, 'Mutur', '0652222144', 1),
(172, 'Sampoor', '0652222145', 1),
(173, 'Kinniya', '0652222146', 1),
(174, 'Kantale', '0652222147', 1),
(175, 'Agbopura', '0652222148', 1),
(176, 'Thambalagamuva', '0652222149', 1),
(177, 'Serunuwara', '0652222150', 1),
(178, 'Sooriyapura', '0652222151', 1),
(179, 'Wan Ela', '0652222152', 1),
(180, 'Morawewa', '0652222153', 1),
(181, 'Gomarankadawala', '0652222154', 1),
(182, 'Echchallmpaththu', '0652222155', 1),
(183, 'Anuradhapura', '0662222201', 1),
(184, 'Hidogama', '0662222202', 1),
(185, 'Udamaluwa', '0662222203', 1),
(186, 'Mihintale', '0662222204', 1),
(187, 'Kekirawa', '0662222205', 1),
(188, 'Thirappone', '0662222206', 1),
(189, 'Eppawala', '0662222207', 1),
(190, 'Galnewa', '0662222208', 1),
(191, 'Galkiriyagama', '0662222209', 1),
(192, 'Thambuttegama', '0662222210', 1),
(193, 'Rajangane', '0662222211', 1),
(194, 'Nochchiyagama', '0662222212', 1),
(195, 'Thalawa', '0662222213', 1),
(196, 'Thanthirimalaya', '0662222214', 1),
(197, 'Mahawilachchiya', '0662222215', 1),
(198, 'Padavi Sri Pura', '0662222216', 1),
(199, 'Ipallogama', '0662222217', 1),
(200, 'Parasangaswewa', '0662222218', 1),
(201, 'Moragoda', '0662222219', 1),
(202, 'Oyamaduwa', '0662222220', 1),
(203, 'Kallanchiya', '0662222221', 1),
(204, 'Hurigaswewea', '0662222222', 1),
(205, 'Elayapathtuwa', '0662222223', 1),
(206, 'Giranegama', '0662222224', 1),
(207, 'Kawarakkulam', '0662222225', 1),
(208, 'Madatugama', '0662222226', 1),
(209, 'Maradankadawala', '0662222227', 1),
(210, 'Priyankaragama', '0662222228', 1),
(211, 'Siwalakulam', '0662222229', 1),
(212, 'Puhudiula', '0662222230', 1),
(213, 'Kebithigollawa', '0662222231', 1),
(214, 'Horowpathana', '0662222232', 1),
(215, 'Padaviya', '0662222233', 1),
(216, 'Medawachchiya', '0662222234', 1),
(217, 'Galenbidunuwewa', '0662222235', 1),
(218, 'Kahatagasdigiliya', '0662222236', 1),
(219, 'Pihibiyagollewa', '0662222237', 1),
(220, 'Kapugollewa', '0662222238', 1),
(221, 'Kokawewa', '0662222239', 1),
(222, 'Devuolwewa', '0662222240', 1),
(223, 'Etaweeragollewa', '0662222241', 1),
(224, 'Wahalkada', '0662222242', 1),
(225, 'Punewa', '0662222243', 1),
(226, 'Polonnaruwa', '0662222244', 1),
(227, 'Pulasthipura', '0662222245', 1),
(228, 'Welikanda', '0662222246', 1),
(229, 'Aralaganwila', '0662222247', 1),
(230, 'Hingurakgoda', '0662222248', 1),
(231, 'Medirigiriya', '0662222249', 1),
(232, 'Habarana', '0662222250', 1),
(233, 'Bakamuna', '0662222251', 1),
(234, 'Minneriya', '0662222252', 1),
(235, 'Siripura', '0662222253', 1),
(236, 'Diyabeduma', '0662222254', 1),
(237, 'Megaswewa', '0662222255', 1),
(238, 'Manampitiya', '0662222256', 1),
(239, 'Kankasanthurai', '0652222001', 1),
(240, 'Illawali', '0652222002', 1),
(241, 'Achchuweli', '0652222003', 1),
(242, 'Thelippalai', '0652222004', 1),
(243, 'Palali', '0652222005', 1),
(244, 'Point Pedro', '0652222006', 1),
(245, 'Velvatithurai', '0652222007', 1),
(246, 'Jaffna', '0652222008', 1),
(247, 'Manippai', '0652222009', 1),
(248, 'Chunnakam', '0652222010', 1),
(249, 'Kayts', '0652222011', 1),
(250, 'Delft', '0652222012', 1),
(251, 'Wadukotte', '0652222013', 1),
(252, 'Chawakachcheri', '0652222014', 1),
(253, 'Kopai', '0652222015', 1),
(254, 'Kodikamam', '0652222016', 1),
(255, 'Vavuniya', '0652222017', 1),
(256, 'Bogaswewa', '0652222018', 1),
(257, 'Puliyankulam', '0652222019', 1),
(258, 'Chettikulam', '0652222020', 1),
(259, 'Ulukkulam', '0652222021', 1),
(260, 'Puwarasamkulam', '0652222022', 1),
(261, 'Echchanmkulam', '0652222023', 1),
(262, 'MaMaduwa', '0652222024', 1),
(263, 'Irattaperiyakulam', '0652222025', 1),
(264, 'Parayanakulam', '0652222026', 1),
(265, 'Omantha', '0652222027', 1),
(266, 'Nedumkenrni', '0652222028', 1),
(267, 'Kanagarayankulam', '0652222029', 1),
(268, 'Nerukkulam', '0652222030', 1),
(269, 'Sidambarampuram', '0652222031', 1),
(270, 'Kilinochchi', '0652222032', 1),
(271, 'Palei', '0652222033', 1),
(272, 'Punarin', '0652222034', 1),
(273, 'Jayapuram', '0652222035', 1),
(274, 'Mulankavil', '0652222036', 1),
(275, 'Dharmapuram', '0652222037', 1),
(276, 'Akkarayankulam', '0652222038', 1),
(277, 'Maradankerni', '0652222039', 1),
(278, 'Mannar', '0652222040', 1),
(279, 'Silawathura', '0652222041', 1),
(280, 'Iluppakadawai', '0652222042', 1),
(281, 'Thalaimannar', '0652222043', 1),
(282, 'Murunkan', '0652222044', 1),
(283, 'Madu', '0652222045', 1),
(284, 'Wankala', '0652222046', 1),
(285, 'Pesale', '0652222047', 1),
(286, 'Uilankulam', '0652222048', 1),
(287, 'Mullaittivu', '0652222049', 1),
(288, 'Pudukuduiruppu', '0652222050', 1),
(289, 'Mulliyaweli', '0652222051', 1),
(290, 'Oddusudan', '0652222052', 1),
(291, 'Welioya', '0652222053', 1),
(292, 'Mankulam', '0652222054', 1),
(293, 'Mallavi', '0652222055', 1),
(294, 'Aiyankulam', '0652222056', 1),
(295, 'Kokilai', '0652222057', 1),
(296, 'Nattonkaddal', '0652222058', 1),
(350, 'Kurunegala', '0372222201', 1),
(351, 'Polgahawela', '0372222202', 1),
(352, 'Alawwa', '0372222203', 1),
(353, 'Pothuhera', '0372222204', 1),
(354, 'Wariyapola', '0372222205', 1),
(355, 'Weerambugedara', '0372222206', 1),
(356, 'Wellawa', '0372222207', 1),
(357, 'Gokarella', '0372222208', 1),
(358, 'Mawathagama', '0372222209', 1),
(359, 'Rideegama', '0372222210', 1),
(360, 'Kumbukgatei', '0372222211', 1),
(361, 'Thalamalgama', '0372222212', 1),
(362, 'Bogamuwa', '0372222213', 1),
(363, 'Malsiripura', '0372222214', 1),
(364, 'Maduragoda', '0372222215', 1),
(365, 'Delvita', '0372222216', 1),
(366, 'Inguruwatta', '0372222217', 1),
(367, 'Welagedara', '0372222218', 1),
(368, 'Boyawalana', '0372222219', 1),
(369, 'Doratiyawa', '0372222220', 1),
(370, 'Gonagama', '0372222221', 1),
(371, 'Kuliyapitiya', '0372222222', 1),
(372, 'Narammala', '0372222223', 1),
(373, 'Pannala', '0372222224', 1),
(374, 'Giriulla', '0372222225', 1),
(375, 'Hettipola', '0372222226', 1),
(376, 'Katupotha', '0372222227', 1),
(377, 'Dummalasooriya', '0372222228', 1),
(378, 'Bingiriya', '0372222229', 1),
(379, 'Kadanegedara', '0372222230', 1),
(380, 'Erapola', '0372222231', 1),
(381, 'Welipannagahamula', '0372222232', 1),
(382, 'Mohottawa', '0372222233', 1),
(383, 'Nikaweratiya', '0372222234', 1),
(384, 'Kobeigane', '0372222235', 1),
(385, 'Kotawehera', '0372222236', 1),
(386, 'Rasnayakapura', '0372222237', 1),
(387, 'Maho', '0372222238', 1),
(388, 'Ambanpola', '0372222239', 1),
(389, 'Polpithigama', '0372222240', 1),
(390, 'Galgamuwa', '0372222241', 1),
(391, 'Meegalewa', '0372222242', 1),
(392, 'Giribawa', '0372222243', 1),
(393, 'Pansiyagama', '0372222244', 1),
(394, 'Girilla', '0372222245', 1),
(395, 'Nagollagama', '0372222246', 1),
(396, 'Moragollagama', '0372222247', 1),
(397, 'Nannariya', '0372222248', 1),
(398, 'Ehatuwewa', '0372222249', 1),
(399, 'Anamaduwa', '0372222250', 1),
(400, 'Pallama', '0372222251', 1),
(401, 'Nawagaththegama', '0372222252', 1),
(402, 'Saliyawewa', '0372222253', 1),
(403, 'Rathnapura', '0452222201', 1),
(404, 'Wewalwatta', '0452222202', 1),
(405, 'Balangoda', '0452222203', 1),
(406, 'Opanayaka', '0452222204', 1),
(407, 'Kaltota', '0452222205', 1),
(408, 'Weligepola', '0452222206', 1),
(409, 'Kahawatta', '0452222207', 1),
(410, 'Rakwana', '0452222208', 1),
(411, 'Ayagama', '0452222209', 1),
(412, 'Kalawana', '0452222210', 1),
(413, 'Nivithigala', '0452222211', 1),
(414, 'Kuruwita', '0452222212', 1),
(415, 'Ehaliyagoda', '0452222213', 1),
(416, 'Kiriella', '0452222214', 1),
(417, 'Pinnawala', '0452222215', 1),
(418, 'Siripagama', '0452222216', 1),
(419, 'Palmadulla', '0452222217', 1),
(420, 'Enadana', '0452222218', 1),
(421, 'Samanala wewa', '0452222219', 1),
(422, 'Delwala', '0452222220', 1),
(423, 'Elapatha', '0452222221', 1),
(424, 'Pothupitiya', '0452222222', 1),
(425, 'Embilipitiya', '0452222223', 1),
(426, 'Kuttigala', '0452222224', 1),
(427, 'Godakawela', '0452222225', 1),
(428, 'Kolonna', '0452222226', 1),
(429, 'Panamure', '0452222227', 1),
(430, 'Sewanagala', '0452222228', 1),
(431, 'Udawalawa', '0452222229', 1),
(432, 'Sooriyakanda', '0452222230', 1),
(433, 'Kegalle', '0352222231', 1),
(434, 'Warakapola', '0352222232', 1),
(435, 'Aranayaka', '0352222233', 1),
(436, 'Dedigama', '0352222234', 1),
(437, 'Mawanella', '0352222235', 1),
(438, 'Rambukkana', '0352222236', 1),
(439, 'Pindeniya', '0352222237', 1),
(440, 'Hemmathagama', '0352222238', 1),
(441, 'Dewalegama', '0352222239', 1),
(442, 'Awissawella', '0352222240', 1),
(443, 'Kosgama', '0352222241', 1),
(444, 'Deraniyagala', '0352222242', 1),
(445, 'Ruwanwella', '0352222243', 1),
(446, 'Yatiyantota', '0352222244', 1),
(447, 'Kithulgala', '0352222245', 1),
(448, 'Bulathkohupitiya', '0352222246', 1),
(449, 'Nuriya', '0352222247', 1),
(450, 'Galapitamada', '0352222248', 1),
(451, 'Dehiowita', '0352222249', 1),
(452, 'Morontota', '0352222250', 1),
(522, 'Matara', '0412222201', 1),
(523, 'Gandara', '0412222202', 1),
(524, 'Dikwella', '0412222203', 1),
(525, 'Thihagoda', '0412222204', 1),
(526, 'Kamburupitiya', '0412222205', 1),
(527, 'Hakmana', '0412222206', 1),
(528, 'Morawaka', '0412222207', 1),
(529, 'Urubokka', '0412222208', 1),
(530, 'Mawarala', '0412222209', 1),
(531, 'Deniyaya', '0412222210', 1),
(532, 'Weligama', '0412222211', 1),
(533, 'Akuressa', '0412222212', 1),
(534, 'Kananke', '0412222213', 1),
(535, 'Pitabeddara', '0412222214', 1),
(536, 'Kotawila', '0412222215', 1),
(537, 'Kosmodara', '0412222216', 1),
(538, 'Malimbada', '0412222217', 1),
(539, 'Rotumba', '0412222218', 1),
(540, 'Deyyandara', '0412222219', 1),
(541, 'Bangama', '0412222220', 1),
(542, 'Midigama', '0412222221', 1),
(543, 'Galle', '0912222222', 1),
(544, 'Rathgama', '0912222223', 1),
(545, 'Hikkaduwa', '0912222224', 1),
(546, 'Akmeemana', '0912222225', 1),
(547, 'Wanduramba', '0912222226', 1),
(548, 'Yakkalamulla', '0912222227', 1),
(549, 'Poddala', '0912222228', 1),
(550, 'Habaraduwa', '0912222229', 1),
(551, 'Ahangama', '0912222230', 1),
(552, 'Galle Harbour', '0912222231', 1),
(553, 'Nagoda', '0912222232', 1),
(554, 'Hiniduma', '0912222233', 1),
(555, 'Neluwa', '0912222234', 1),
(556, 'Udugama', '0912222235', 1),
(557, 'Imaduwa', '0912222236', 1),
(558, 'Opatha', '0912222237', 1),
(559, 'Gonapinuwala', '0912222238', 1),
(560, 'Elpitiya', '0912222239', 1),
(561, 'Uragasmanhandiya', '0912222240', 1),
(562, 'Pitigala', '0912222241', 1),
(563, 'Karandeniya', '0912222242', 1),
(564, 'Baddegama', '0912222243', 1),
(565, 'Batapola', '0912222244', 1),
(566, 'Meetiyagoda', '0912222245', 1),
(567, 'Thelikada', '0912222246', 1),
(568, 'Ambalangoda', '0912222247', 1),
(569, 'Ahungalla', '0912222248', 1),
(570, 'Kosgoda', '0912222249', 1),
(571, 'Bentota', '0912222250', 1),
(572, 'Tangalle', '0472222251', 1),
(573, 'Hungama', '0472222252', 1),
(574, 'Weeraketiya', '0472222253', 1),
(575, 'Angunakolapelessa', '0472222254', 1),
(576, 'Walasmulla', '0472222255', 1),
(577, 'Beliaththa', '0472222256', 1),
(578, 'Katuwana', '0472222257', 1),
(579, 'Middeniya', '0472222258', 1),
(580, 'Sooriyawewa', '0472222259', 1),
(581, 'Hambantota', '0472222260', 1),
(582, 'Ambalantota', '0472222261', 1),
(583, 'Tissamaharamaya', '0472222262', 1),
(584, 'Kataragama', '0472222263', 1),
(585, 'Lunugamwehera', '0472222264', 1),
(586, 'Weeravila', '0472222265', 1),
(587, 'Kirinda', '0472222266', 1),
(588, 'Hambantota Harbour', '0472222267', 1),
(589, 'Mathtala', '0472222268', 1),
(590, 'Okewella', '0472222269', 1),
(591, 'Badulla', '0552222201', 1),
(592, 'Passara', '0552222202', 1),
(593, 'Madolseema', '0552222203', 1),
(594, 'Lunugala', '0552222204', 1),
(595, 'Mahiyangane', '0552222205', 1),
(596, 'Giradurukotte', '0552222206', 1),
(597, 'Kandaketiya', '0552222207', 1),
(598, 'Haliela', '0552222208', 1),
(599, 'Rideemaliyadda (Kanugolla P/P)', '0552222209', 1),
(600, 'Kahataruppa', '0552222210', 1),
(601, 'Diulapalassa', '0552222211', 1),
(602, 'Higurukaduwa', '0552222212', 1),
(603, 'Arawa', '0552222213', 1),
(604, 'Galauda', '0552222214', 1),
(605, 'Namunukula', '0552222215', 1),
(606, 'Bandarawela', '0552222216', 1),
(607, 'Ella', '0552222217', 1),
(608, 'Welimada', '0552222218', 1),
(609, 'Uvaparanagama', '0552222219', 1),
(610, 'Etampitiya', '0552222220', 1),
(611, 'Haputale', '0552222221', 1),
(612, 'Diyathalawa', '0552222222', 1),
(613, 'Koslanda', '0552222223', 1),
(614, 'Haldummulla', '0552222224', 1),
(615, 'Bogahakumbura', '0552222225', 1),
(616, 'Ambagasdowa', '0552222226', 1),
(617, 'Ballaketuwa', '0552222227', 1),
(618, 'Keppattipola', '0552222228', 1),
(619, 'Liyangahawela', '0552222229', 1),
(620, 'Medagama', '0552222230', 1),
(621, 'Monaragala', '0572222231', 1),
(622, 'Karandugala', '0572222232', 1),
(623, 'Bibila', '0572222233', 1),
(624, 'Buththala', '0572222234', 1),
(625, 'Gonaganara', '0572222235', 1),
(626, 'Okkampitiya', '0572222236', 1),
(627, 'Badalkumbura', '0572222237', 1),
(628, 'Thanamalwila', '0572222238', 1),
(629, 'Wellawaya', '0572222239', 1),
(630, 'Hambegamuwa', '0572222240', 1),
(631, 'Kudaoya', '0572222241', 1),
(632, 'Siyambalanduwa', '0572222242', 1),
(633, 'Govindupura', '0572222243', 1),
(634, 'Ethimale', '0572222244', 1),
(635, 'Mahakalugolla', '0572222245', 1),
(636, 'Dambagalla', '0572222246', 1),
(637, 'Alawathugoda', '0812222201', 1),
(638, 'Ankumbura', '0812222202', 1),
(639, 'Dalada Maligawa', '0812222203', 1),
(640, 'Daulagala', '0812222204', 1),
(641, 'Galagedara', '0812222205', 1),
(642, 'Hanguranketha', '0812222206', 1),
(643, 'Hatharaliyadda', '0812222207', 1),
(644, 'Kadugannawa', '0812222208', 1),
(645, 'Kandy', '0812222209', 1),
(646, 'Katugastota', '0812222210', 1),
(647, 'Pallakele', '0812222211', 1),
(648, 'Peradeniya', '0812222212', 1),
(649, 'Poojapitiya', '0812222213', 1),
(650, 'Poththapitiya', '0812222214', 1),
(651, 'Welambada', '0812222215', 1),
(652, 'Bokkawala', '0812222216', 1),
(653, 'Aladeniya', '0812222217', 1),
(654, 'Hasalaka', '0812222218', 1),
(655, 'Manikhinna', '0812222219', 1),
(656, 'Panwila', '0812222220', 1),
(657, 'Rangala', '0812222221', 1),
(658, 'Theldeniya', '0812222222', 1),
(659, 'Ududumbara', '0812222223', 1),
(660, 'Wattegama', '0812222224', 1),
(661, 'Galaha', '0812222225', 1),
(662, 'Gampola', '0812222226', 1),
(663, 'Nawa Kurunduwatta', '0812222227', 1),
(664, 'Nawalapitiya', '0812222228', 1),
(665, 'Pupuressa', '0812222229', 1),
(666, 'Pussellawa', '0812222230', 1),
(667, 'Parrateasse', '0812222231', 1),
(668, 'Ethgala', '0812222232', 1),
(669, 'Frotop', '0812222233', 1),
(670, 'Dambulla', '0662222234', 1),
(671, 'Galewela', '0662222235', 1),
(672, 'Laggala', '0662222236', 1),
(673, 'Mahawela', '0662222237', 1),
(674, 'Makulugaswewa', '0662222238', 1),
(675, 'Matale', '0662222239', 1),
(676, 'Naula', '0662222240', 1),
(677, 'Rattota', '0662222241', 1),
(678, 'Sigiriya', '0662222242', 1),
(679, 'Wilgamuwa', '0662222243', 1),
(680, 'Yatawatta', '0662222244', 1),
(681, 'Kandenuwara', '0662222245', 1),
(682, 'Handungamuwa', '0662222246', 1),
(683, 'Agarapathana', '0522222247', 1),
(684, 'Dayagama', '0522222248', 1),
(685, 'Dimbulapathana', '0522222249', 1),
(686, 'Kandapola', '0522222250', 1),
(687, 'Keerthi Bandarapura', '0522222251', 1),
(688, 'Kothmale', '0522222252', 1),
(689, 'Lindula', '0522222253', 1),
(690, 'Mandaram Nuwara', '0522222254', 1),
(691, 'Maturata', '0522222255', 1),
(692, 'Nanuoya', '0522222256', 1),
(693, 'Nuwaraeliya', '0522222257', 1),
(694, 'Pattipola', '0522222258', 1),
(695, 'Pudalu oya', '0522222259', 1),
(696, 'Ragala', '0522222260', 1),
(697, 'Thalawakale', '0522222261', 1),
(698, 'Theripaha', '0522222262', 1),
(699, 'Udupussellawa', '0522222263', 1),
(700, 'Walapone', '0522222264', 1),
(701, 'Highforest', '0522222265', 1),
(702, 'Hatton', '0522222266', 1),
(703, 'Watawala', '0522222267', 1),
(704, 'Ginigathhena', '0522222268', 1),
(705, 'Nortonbridge', '0522222269', 1),
(706, 'Nallathanni', '0522222270', 1),
(707, 'Bagawanthalawa', '0522222271', 1),
(708, 'Norwood', '0522222272', 1),
(709, 'Maskeliya', '0522222273', 1),
(710, 'Polpitiya', '0522222274', 1);

-- --------------------------------------------------------

--
-- Table structure for table `seized_vehicle`
--

CREATE TABLE `seized_vehicle` (
  `id` int(11) NOT NULL,
  `license_plate_number` varchar(50) NOT NULL,
  `officer_id` varchar(50) NOT NULL,
  `officer_name` varchar(50) DEFAULT NULL,
  `police_station` varchar(50) NOT NULL,
  `driver_NIC` int(11) NOT NULL,
  `seizure_date_time` datetime NOT NULL DEFAULT current_timestamp(),
  `seized_location` varchar(100) NOT NULL,
  `owner_name` varchar(50) DEFAULT NULL,
  `is_released` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seized_vehicle`
--

INSERT INTO `seized_vehicle` (`id`, `license_plate_number`, `officer_id`, `officer_name`, `police_station`, `driver_NIC`, `seizure_date_time`, `seized_location`, `owner_name`, `is_released`) VALUES
(42, 'EP|BCD-2345', '12004', 'Shiva Karthik', 'Delft', 2147483647, '2025-04-17 09:40:00', 'Delft', 'Kasun Ekanayake', 1),
(43, 'CP|YZA-8901', '12004', 'Shiva Karthik', 'Delft', 2147483647, '2025-04-17 09:43:00', 'Delft', 'Tharindu Dias', 1),
(63, 'CP|JKL-2345', '12004', 'Shiva Karthik', '250', 2147483647, '2025-04-22 09:44:00', 'Delft', 'Sunil Fernando', 0);

-- --------------------------------------------------------

--
-- Table structure for table `stolen_vehicles`
--

CREATE TABLE `stolen_vehicles` (
  `id` int(11) NOT NULL,
  `police_id` int(10) NOT NULL,
  `license_plate_number` varchar(20) NOT NULL,
  `absolute_owner` varchar(100) NOT NULL,
  `engine_no` varchar(50) NOT NULL,
  `make` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `colour` varchar(30) NOT NULL,
  `date_of_registration` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `date_reported_stolen` date NOT NULL,
  `location_last_seen` varchar(100) NOT NULL,
  `last_seen_date` date NOT NULL,
  `vehicle_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stolen_vehicles`
--

INSERT INTO `stolen_vehicles` (`id`, `police_id`, `license_plate_number`, `absolute_owner`, `engine_no`, `make`, `model`, `colour`, `date_of_registration`, `status`, `date_reported_stolen`, `location_last_seen`, `last_seen_date`, `vehicle_image`) VALUES
(127, 0, 'WP|VWX-4567', 'Asela Karunaratne', '890', '3647', 'Suzuki', 'Black', '2025-04-10', 'Bad', '2025-04-16', 'Wellawatta Junction', '2025-04-09', '68049a89911c9_67ffcaf186eff_WhatsApp Image 2025-04-16 at 20.40.50.jpeg');

--
-- Triggers `stolen_vehicles`
--
DELIMITER $$
CREATE TRIGGER `before_insert_stolen_vehicles` BEFORE INSERT ON `stolen_vehicles` FOR EACH ROW BEGIN
    IF NEW.date_of_registration > CURDATE() 
        OR NEW.date_reported_stolen > CURDATE()
        OR NEW.last_seen_date > CURDATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Date fields cannot have future dates';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_stolen_vehicles` BEFORE UPDATE ON `stolen_vehicles` FOR EACH ROW BEGIN
    IF NEW.date_of_registration > CURDATE() 
        OR NEW.date_reported_stolen > CURDATE()
        OR NEW.last_seen_date > CURDATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Date fields cannot have future dates';
    END IF;
END
$$
DELIMITER ;

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

--
-- Dumping data for table `update_driver_profile_requests`
--

INSERT INTO `update_driver_profile_requests` (`id`, `fname`, `lname`, `email`, `phone_no`, `nic`, `password`, `created_at`) VALUES
('b1234567', 'John', 'Doess', 'imazjzen@gmail.com', '0766743755', '200224903146', '$2y$10$ONrxj3tNOHmSKZp/5KgBo.b1XLlsW95VHXBzTnCmYdJDZtc04ECBy', '2025-04-05 16:45:02'),
('b2222001', 'Siripala', 'Ariyarathna De Silva', 'siripala@gmail.com', '0792378567', '1956345678557', NULL, '2024-11-28 15:03:18'),
('b2222002', 'Kasun', 'Senanayka', 'kasunsenanayaka01@gmail.com', '0758767457', '199523788967', NULL, '2024-11-28 15:09:14'),
('b2222003', 'Thisakya ', 'Hewagamage', 'thisakkya@gmail.com', '0764568909', '199345678956', NULL, '2024-11-28 15:11:53'),
('b2222004', 'Sanudi', 'Thisera', 'sanudithi@gmail.com', '0710979659', '199623537945', NULL, '2024-11-28 15:18:12'),
('b2222005', 'Nadhiya', 'Nashathh', 'nadhiyanash@gmail.com', '0792381520', '200336457823', NULL, '2024-11-28 16:04:26'),
('b2222006', 'Hansaja', 'Kithmal', 'hansajakithh@gmail.com', '0743478987', '199323896865', NULL, '2024-11-28 16:11:47'),
('b2222007', 'Gishan', 'Maduranga', 'gishanmadur@gmail.com', '0794567896', '199534985948', NULL, '2024-11-28 16:16:07'),
('b2381520', 'Pasindu', 'Munasinghe', 'pasindumunasinghe@gmail.com', '0753478678', '200435678986', NULL, '2024-11-29 05:49:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `date`) VALUES
(1, 'imalsha@gmail.com', '$2y$10$9fF0AEiLXxQX4BPS5kjEWOORM9minOLZICGqmDX9z7hXRUd1DdaEW', '2025-01-08 18:43:56'),
(2, 'mary@yahoo.com', '$2y$10$a0MIykWxZ16sFvvOSeOWQeAwmD2me.sH1huni2E1kUtwRY.iiSLEO', '2025-01-08 18:43:56'),
(3, 'imalsha.contact@gmail.com', '', '2025-01-09 08:03:06');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_release_log`
--

CREATE TABLE `vehicle_release_log` (
  `id` int(11) NOT NULL,
  `license_plate_number` varchar(20) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `national_id` varchar(50) NOT NULL,
  `release_date` date NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_release_log`
--

INSERT INTO `vehicle_release_log` (`id`, `license_plate_number`, `owner_name`, `national_id`, `release_date`, `notes`) VALUES
(2, 'CP|YZA-8901', 'Sunil Fernando', '200153601277', '2025-04-03', 'ASAS'),
(3, 'EP|BCD-2345', 'Risafa Imtiyas', '200234123411', '2025-04-01', 'Vehicle is relaeased');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `police_station` (`police_station`);

--
-- Indexes for table `assigned_duties`
--
ALTER TABLE `assigned_duties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `police_id` (`police_id`),
  ADD KEY `assigned_by` (`assigned_by`);

--
-- Indexes for table `codes`
--
ALTER TABLE `codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`),
  ADD KEY `expire` (`expire`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `dmt_drivers`
--
ALTER TABLE `dmt_drivers`
  ADD UNIQUE KEY `license_id` (`license_id`),
  ADD UNIQUE KEY `nic` (`nic`);

--
-- Indexes for table `dmt_vehicles`
--
ALTER TABLE `dmt_vehicles`
  ADD PRIMARY KEY (`vehicle_number`),
  ADD UNIQUE KEY `license_plate_number` (`license_plate_number`),
  ADD UNIQUE KEY `nic` (`nic`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_no` (`phone_no`),
  ADD UNIQUE KEY `nic` (`nic`);

--
-- Indexes for table `duty_locations`
--
ALTER TABLE `duty_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `police_station_id` (`police_station_id`);

--
-- Indexes for table `duty_submissions`
--
ALTER TABLE `duty_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_police_id` (`police_id`),
  ADD KEY `fk_assigned_duty_id` (`assigned_duty_id`);

--
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `police_id` (`police_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `fk_police_station` (`police_station`);

--
-- Indexes for table `offences`
--
ALTER TABLE `offences`
  ADD PRIMARY KEY (`offence_id`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD KEY `police_station` (`police_station`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`fine_id`);

--
-- Indexes for table `police_stations`
--
ALTER TABLE `police_stations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `police_stations_draft`
--
ALTER TABLE `police_stations_draft`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seized_vehicle`
--
ALTER TABLE `seized_vehicle`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_plate_number` (`license_plate_number`);

--
-- Indexes for table `stolen_vehicles`
--
ALTER TABLE `stolen_vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `license_plate_number` (`license_plate_number`);

--
-- Indexes for table `update_driver_profile_requests`
--
ALTER TABLE `update_driver_profile_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_no` (`phone_no`),
  ADD UNIQUE KEY `nic` (`nic`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `vehicle_release_log`
--
ALTER TABLE `vehicle_release_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `license_plate_number` (`license_plate_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `assigned_duties`
--
ALTER TABLE `assigned_duties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `codes`
--
ALTER TABLE `codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `duty_locations`
--
ALTER TABLE `duty_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `duty_submissions`
--
ALTER TABLE `duty_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281;

--
-- AUTO_INCREMENT for table `offences`
--
ALTER TABLE `offences`
  MODIFY `offence_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44114;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `police_stations`
--
ALTER TABLE `police_stations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=589;

--
-- AUTO_INCREMENT for table `police_stations_draft`
--
ALTER TABLE `police_stations_draft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=711;

--
-- AUTO_INCREMENT for table `seized_vehicle`
--
ALTER TABLE `seized_vehicle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `stolen_vehicles`
--
ALTER TABLE `stolen_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vehicle_release_log`
--
ALTER TABLE `vehicle_release_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`police_station`) REFERENCES `police_stations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assigned_duties`
--
ALTER TABLE `assigned_duties`
  ADD CONSTRAINT `assigned_duties_ibfk_1` FOREIGN KEY (`police_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assigned_duties_ibfk_2` FOREIGN KEY (`assigned_by`) REFERENCES `officers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `duty_locations`
--
ALTER TABLE `duty_locations`
  ADD CONSTRAINT `duty_locations_ibfk_1` FOREIGN KEY (`police_station_id`) REFERENCES `police_stations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `duty_submissions`
--
ALTER TABLE `duty_submissions`
  ADD CONSTRAINT `fk_assigned_duty_id` FOREIGN KEY (`assigned_duty_id`) REFERENCES `assigned_duties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_police_id` FOREIGN KEY (`police_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fines_ibfk_1` FOREIGN KEY (`police_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fines_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_police_station` FOREIGN KEY (`police_station`) REFERENCES `police_stations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `officers`
--
ALTER TABLE `officers`
  ADD CONSTRAINT `officers_ibfk_2` FOREIGN KEY (`police_station`) REFERENCES `police_stations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`fine_id`) REFERENCES `fines` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seized_vehicle`
--
ALTER TABLE `seized_vehicle`
  ADD CONSTRAINT `fk_license_plate_number` FOREIGN KEY (`license_plate_number`) REFERENCES `dmt_vehicles` (`license_plate_number`);

--
-- Constraints for table `stolen_vehicles`
--
ALTER TABLE `stolen_vehicles`
  ADD CONSTRAINT `stolen_vehicles_ibfk_1` FOREIGN KEY (`license_plate_number`) REFERENCES `dmt_vehicles` (`license_plate_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `update_driver_profile_requests`
--
ALTER TABLE `update_driver_profile_requests`
  ADD CONSTRAINT `update_driver_profile_requests_ibfk_1` FOREIGN KEY (`id`) REFERENCES `drivers` (`id`);

--
-- Constraints for table `vehicle_release_log`
--
ALTER TABLE `vehicle_release_log`
  ADD CONSTRAINT `vehicle_release_log_ibfk_1` FOREIGN KEY (`license_plate_number`) REFERENCES `seized_vehicle` (`license_plate_number`) ON UPDATE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`digifine_db`@`%` EVENT `update_fine_status` ON SCHEDULE EVERY 1 DAY STARTS '2025-01-28 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE fines
SET status = 'overdue'
WHERE status = 'pending' AND expire_date < CURDATE()$$

CREATE DEFINER=`digifine_db`@`%` EVENT `update_overdue_fines` ON SCHEDULE EVERY 1 DAY STARTS '2025-04-21 18:57:09' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE fines 
  SET fine_status = 'overdue' 
  WHERE fine_status = 'pending' 
  AND DATEDIFF(CURRENT_DATE, issued_date) > 30$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
