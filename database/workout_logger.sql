-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2025 at 07:36 PM
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
-- Database: `workout_logger`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'rafael', 'rafaelWalder@gmail.com', '$2y$10$lbDcXlL2EttpOZeavvxQM.2z4g1TLGAZqNqajM6dKdC1ucs/CbzGS', '2025-04-01 08:29:49'),
(2, 'test', 'test@gmail.com', '$2y$10$1Ls/CCnq9iW4HQqrcLcIR.cwp.piBAxiPersEVXHsMx4pv7UiNb6e', '2025-04-01 13:20:05');

-- -------------------------------------------------------- 

--
-- Table structure for table `workouts`
--

CREATE TABLE `workouts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('running','cycling') NOT NULL,
  `distance` decimal(10,2) NOT NULL,
  `duration` int(11) NOT NULL,
  `cadence` int(11) DEFAULT NULL,
  `elevationGain` int(11) DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workouts`
--

INSERT INTO `workouts` (`id`, `user_id`, `type`, `distance`, `duration`, `cadence`, `elevationGain`, `latitude`, `longitude`, `description`, `created_at`) VALUES
(8, 2, 'cycling', 34.00, 32, NULL, 34, 38.03442156, 23.69081497, 'Cycling on April1', '2025-03-31 21:00:00'),
(9, 2, 'running', 4.00, 30, 23, NULL, 38.01836266, 23.67414617, 'Running on April1', '2025-03-31 21:00:00'),
(10, 2, 'cycling', 34.00, 23, NULL, 2, 38.03216119, 23.72340698, 'Cycling on April1', '2025-03-31 21:00:00'),
(11, 2, 'cycling', 34.00, 34, NULL, 34, 37.99578714, 23.65113910, 'Cycling on April1', '2025-03-31 21:00:00'),
(12, 2, 'running', 3.00, 30, 2, NULL, 37.98837469, 23.72647762, 'Running on April1', '2025-03-31 21:00:00'),
(13, 2, 'running', 34.00, 3, 34, NULL, 37.98909043, 23.68961334, 'Running on April1', '2025-03-31 21:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `workouts`
--
ALTER TABLE `workouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workouts`
--
ALTER TABLE `workouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `workouts`
--
ALTER TABLE `workouts`
  ADD CONSTRAINT `workouts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
