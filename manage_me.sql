-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 06, 2022 at 02:33 PM
-- Server version: 5.7.34
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `manage_me`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `emp_number` int(8) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `job_title` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `emp_number`, `first_name`, `last_name`, `job_title`, `password`) VALUES
(2, 44120001, 'Khalid', 'Alfaris', 'Software Engineer', '$2y$10$Fkl7q074wmka4MDN8BJgDORCUW2pcUZ.macVxIQOGlo/H.oriSj5C'),
(3, 44120002, 'Mona', 'Alyousef', 'Web Designer', '$2y$10$5f1/XOIcOpvRmJTB0b8IHucBSBHrwr3BvlyfMdj2cExj9IX/32hXy'),
(4, 44120003, 'Rashed', 'Alnuaimi', 'SQL Developer', '$2y$10$k1DPd3oej7aG4KLCKciyDeNyr0MJ5.6Zl80Yn8lYst1CJ/Rh2jqH.');

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `id` int(11) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `manager`
--

INSERT INTO `manager` (`id`, `first_name`, `last_name`, `username`, `password`) VALUES
(1, 'Abdulsalam', 'Alghamdi', 'A_ghamdi', '$2y$10$sLbmAnVgZEMjLt1.h61RE.iS1FAJbLZS8mK1AqkfLuwIdGfpPuiem');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `emp_id` int(4) NOT NULL,
  `service_id` int(4) NOT NULL,
  `description` text NOT NULL,
  `attachment1` varchar(255) DEFAULT NULL,
  `attachment2` varchar(255) DEFAULT NULL,
  `status` enum('In progress','Approved','Declined','') NOT NULL DEFAULT 'In progress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`id`, `emp_id`, `service_id`, `description`, `attachment1`, `attachment2`, `status`) VALUES
(2, 2, 1, 'consideration request for promotion to the role of [Job Title].', 'uploads/2/certificate-of-completion.jpeg', 'uploads/2/promotion request.pdf', 'In progress'),
(3, 3, 2, 'request a leave of absence from August 31, 2021, through October 30, 2021.', 'uploads/3/leave request.pdf', 'uploads/3/leave request.pdf', 'Approved'),
(4, 4, 5, 'I am writing this letter to claim my health insurance as I am suffering from heart disease (Actual cause). I am willing to provide you with any documents that are required. I hope you would treat this matter at the earliest as it is very important.', 'uploads/4/health insurance request.pdf', 'uploads/4/health_insurance.png', 'In progress');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `type`) VALUES
(1, 'Promotion'),
(2, 'Leave'),
(3, 'Allowance'),
(4, 'Resignation'),
(5, 'Health Insurance');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `manager`
--
ALTER TABLE `manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `employee` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
