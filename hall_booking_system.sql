-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 02, 2025 at 03:54 PM
-- Server version: 8.0.35
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hall_booking_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `hall_id` int NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `purpose` text,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `hall_id`, `booking_date`, `start_time`, `end_time`, `purpose`, `total_amount`, `status`, `created_at`) VALUES
(6, 5, 9, '2025-09-07', '12:00:00', '12:45:00', '', 1500.00, 'confirmed', '2025-08-30 14:51:19');

-- --------------------------------------------------------

--
-- Table structure for table `halls`
--

CREATE TABLE `halls` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `capacity` int NOT NULL,
  `location` varchar(100) NOT NULL,
  `price_per_hour` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `amenities` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `halls`
--

INSERT INTO `halls` (`id`, `name`, `description`, `capacity`, `location`, `price_per_hour`, `image_url`, `amenities`, `created_at`, `type`) VALUES
(2, 'NAT', 'Modern main hall perfect for events and seminars', 800, 'Near UCSC', 104500.00, 'uploads/hall_68b34c29d50ba9.32552906.jpeg', 'Projector, AC, WiFi', '2025-08-20 10:09:12', 'Auditorium'),
(3, 'Stat-Computer Lab', 'Computer lab', 50, 'Stats Department,2nd Floor', 19100.00, 'uploads/hall_68b34bc89c4278.71592795.jpg', 'Projector, Computers, AC, White Board', '2025-08-20 10:09:12', 'Lecture Hall'),
(6, 'NLT', 'Lecture hall that can use for 200 students.', 200, 'Chemistry Department', 10000.00, 'uploads/hall_68b31574033602.79439670.jpeg', 'White Board, Projector, WiFi', '2025-08-20 10:09:12', 'Lecture Hall'),
(9, 'PLT', 'This is a classical lecture hall', 300, 'Maths Department', 12000.00, 'uploads/hall_68b30e4edd78d5.86127348.jpeg', 'Projector, Black Board', '2025-08-30 13:07:56', 'Lecture Hall');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`) VALUES
(3, 'Thinula ', '$2y$10$DcC9VRkUes9zVYm4XerlgusCuqbA3cXR0aT4cARL/8t2nSOj6vjJ2', 'nethaka78.21@gmail.com', 'user', '2025-08-20 10:17:37'),
(4, 'ThinulaH', '$2y$10$4V775FM1ZFSW.DrZpzxT/eAHL54RZyI2qLr2Hux5r8HxX5rjeONcm', 'thinula.haris@gmail.com', 'user', '2025-08-22 05:45:15'),
(5, 'uoc', '$2y$10$tfaX.LzhNqPeuhP0Xvzghuul6lK.nFBjkATqH4mhk2nUZY0zRXZmm', 'uoc@edu.lk', 'user', '2025-08-30 14:39:25'),
(8, 'admin', '$2y$10$sHnfmcpoarvHX7XiuTvWGuwzEunKv/TzWokC7bKrPy0PxbYyu4pQy', 'admin@uniquate.com', 'admin', '2025-08-20 10:09:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hall_id` (`hall_id`);

--
-- Indexes for table `halls`
--
ALTER TABLE `halls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `halls`
--
ALTER TABLE `halls`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`hall_id`) REFERENCES `halls` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
