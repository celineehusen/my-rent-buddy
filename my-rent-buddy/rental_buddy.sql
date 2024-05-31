-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 23, 2023 at 01:47 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rental_buddy`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `car_id` int(7) NOT NULL,
  `plates` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `cost_per_day` int(11) NOT NULL,
  `cost_overdue_per_day` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`car_id`, `plates`, `model`, `type`, `status`, `cost_per_day`, `cost_overdue_per_day`) VALUES
(2000000, 'YHB102', 'Honda Civic Hybrid', 'compact', 'rented', 100, 20),
(2000001, 'WIH789', 'Toyota Corolla', 'compact', 'available', 80, 10),
(2000002, 'CCC001', 'Toyota Avanza', 'compact', 'available', 60, 10),
(2000003, 'YYY002', 'Hyundai I30', 'compact', 'available', 90, 20),
(2000004, 'YUJ728', 'Mitsubishi Outlander', 'compact', 'available', 100, 10),
(2000005, 'CAH289', 'Cupra Leon ZVx', 'standard', 'rented', 85, 15),
(2000006, 'CAH280', 'Tesla Y', 'ev', 'available', 200, 40),
(2000007, 'ABA101', 'Toyota Yaris', 'sedan', 'overdue', 78, 25),
(2000008, 'DEL234', 'Tesla Y', 'ev', 'available', 200, 23),
(2000009, 'JKL123', 'Hyundai I30', 'standard', 'available', 100, 10),
(2000010, 'WER234', 'Cupra Leon ZVx', 'standard', 'available', 120, 25);

-- --------------------------------------------------------

--
-- Table structure for table `rental_record`
--

CREATE TABLE `rental_record` (
  `rental_id` int(7) NOT NULL,
  `user_id` int(7) NOT NULL,
  `car_id` int(7) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `actual_return_date` date DEFAULT NULL,
  `total_cost` int(50) NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental_record`
--

INSERT INTO `rental_record` (`rental_id`, `user_id`, `car_id`, `start_date`, `end_date`, `actual_return_date`, `total_cost`, `duration`) VALUES
(101, 1000000, 2000005, '2023-05-11', '2023-05-18', '2023-05-20', 795, 9),
(103, 1000000, 2000003, '2023-05-16', '2023-05-20', '2023-05-20', 360, 4),
(104, 1000000, 2000004, '2023-05-12', '2023-05-15', '2023-05-20', 850, 8),
(105, 1000000, 2000000, '2023-05-10', '2023-05-25', '2023-05-20', 1000, 10),
(108, 1000000, 2000005, '2023-05-18', '2023-05-20', NULL, 170, 2),
(109, 1000000, 2000004, '2023-05-21', '2023-05-25', '2023-05-23', 200, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(7) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `surename` varchar(50) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `full_name`, `surename`, `phone`, `email`, `type`, `password`) VALUES
(1000000, 'Celine Amanda Husen', 'Husen', '0444444444', 'cah892@uowmail.edu.au', 'renter', 'b3e508d6e62e50b49eefa3c464d79e00'),
(1000001, 'Celine Husen', 'Husen', '0444444444', 'cah892@uowmail.edu.au', 'admin', '81ce825ec1ace3ee7cf7e92df2cc9905'),
(1000002, 'Joshlyne Angelina', 'Angelina', '0444444444', 'joshangelina@gmail.com', 'renter', '25444f502e1a9e82962a57c5faa5228e'),
(1000003, 'Felix Lee', 'Lee', '0410910321', 'felixlee@test.com', 'renter', '6ae03e5dc053bf58c57f23e8196f29b3'),
(1000004, 'Lily Joan', 'Joan', '0421231231', 'lilyjoan@test.com', 'renter', 'ddc1ef37dcf8c3566ca2d85fd7254761'),
(1000005, 'Brooke Smith', 'Smith', '0421231231', 'brookesmith@test.com', 'renter', '01c9c524c5b77df4082c00102a0324df'),
(1000006, 'Tanya Abby', 'Abby', '0421231231', 'tanyaabby@test.com', 'renter', 'c94fc550bd9253cb723f0985dab7d37e'),
(1000007, 'Jessica Hu', 'Hu', '0410910321', 'jess@myrb.com', 'renter', '41babe20a566fef8acd3c388261be1f0'),
(1000008, 'Felix Husen', 'Husen', '0410910321', 'felixhusen@myrb.com', 'renter', '730f6b48cdef047dd0d87390143905bf'),
(1000009, 'Celine Amanda', 'Amanda', '0410910321', 'celineamanda@myrb.com', 'renter', 'b3e508d6e62e50b49eefa3c464d79e00'),
(1000010, 'Celine Amanda Husen', 'Husen', '0410910321', 'celine@gmail.com', 'renter', 'b3e508d6e62e50b49eefa3c464d79e00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`car_id`);

--
-- Indexes for table `rental_record`
--
ALTER TABLE `rental_record`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `user_id_foreign_key` (`user_id`),
  ADD KEY `car_id_foreign_key` (`car_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `car_id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2000011;

--
-- AUTO_INCREMENT for table `rental_record`
--
ALTER TABLE `rental_record`
  MODIFY `rental_id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000011;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rental_record`
--
ALTER TABLE `rental_record`
  ADD CONSTRAINT `car_id_foreign_key` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`),
  ADD CONSTRAINT `user_id_foreign_key` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
