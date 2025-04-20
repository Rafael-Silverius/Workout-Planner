-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 12:40 AM
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
-- Table structure for table `steps`
--

CREATE TABLE `steps` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `steps` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `location` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `birth` date DEFAULT NULL,
  `step_goal` int(11) DEFAULT NULL,
  `weight` decimal(10,1) DEFAULT NULL,
  `height` decimal(10,0) DEFAULT NULL,
  `profile_img` varchar(100) DEFAULT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `location`, `bio`, `birth`, `step_goal`, `weight`, `height`, `profile_img`, `created_at`) VALUES
(1, 'Rafael', 'rafaelWalder@gmail.com', '$2y$10$lbDcXlL2EttpOZeavvxQM.2z4g1TLGAZqNqajM6dKdC1ucs/CbzGS', 'Greece', 'Few words about me', '1999-12-21', 10000, 84.6, 174, '../assets/uploads/profile_1_1744959069.png', '2025-04-01'),
(2, 'Test', 'test@gmail.com', '$2y$10$1Ls/CCnq9iW4HQqrcLcIR.cwp.piBAxiPersEVXHsMx4pv7UiNb6e', NULL, '', '2025-02-04', 0, 0.0, 0, '../assets/uploads/profile_2_1744188406.png', '2025-04-01'),
(4, 'asdf', 'asdf@gmail.com', '$2y$10$JJQzDF13hOsPgWACtBzLeuZUOaMyNNl4PFD2lpd9fYGQT2VbQKVNe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-07'),
(5, 'Test2', 'test2@gmail.com', '$2y$10$5Ry8UXurS7vC0Ry70/ffCeylKFttw/dx0ROOreht5K9SE1Ti5niai', NULL, 'asdfasdf', '2025-04-01', 23423, 45.0, 140, '../assets/uploads/profile_5_1744190161.png', '2025-04-09');

-- --------------------------------------------------------

--
-- Table structure for table `weights`
--

CREATE TABLE `weights` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `weight` float NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weights`
--

INSERT INTO `weights` (`id`, `user_id`, `weight`, `date`) VALUES
(5, 1, 23, '2025-04-20 00:36:01'),
(6, 1, 80, '2025-04-20 00:36:06'),
(7, 1, 85, '2025-04-20 00:39:37'),
(8, 1, 90, '2025-04-20 00:50:03'),
(9, 1, 80, '2025-04-20 01:02:40'),
(10, 1, 80, '2025-04-20 01:05:58'),
(11, 1, 80, '2025-04-20 01:07:19'),
(12, 1, 10, '2025-04-20 01:07:24'),
(13, 1, 40, '2025-04-20 01:10:58'),
(14, 1, 40, '2025-04-20 01:11:09'),
(15, 1, 80, '2025-04-20 01:11:16'),
(16, 1, 50, '2025-04-20 01:12:39'),
(17, 1, 70, '2025-04-20 01:14:25'),
(18, 1, 343, '2025-04-20 01:16:25'),
(19, 1, 600, '2025-04-20 01:16:32'),
(20, 1, 123, '2025-04-20 01:16:43'),
(21, 1, 12, '2025-04-20 01:16:46'),
(22, 1, 80, '2025-04-20 01:22:15'),
(23, 1, 46, '2025-04-20 01:22:19'),
(24, 1, 36, '2025-04-20 01:22:23'),
(25, 1, 46, '2025-04-20 01:22:26'),
(26, 1, 643, '2025-04-20 01:22:29'),
(27, 1, 46, '2025-04-20 01:22:31'),
(28, 1, 36, '2025-04-20 01:22:34'),
(29, 1, 36, '2025-04-20 01:22:40'),
(30, 1, 57, '2025-04-20 01:22:43'),
(31, 1, 68, '2025-04-20 01:22:47');

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
(13, 2, 'running', 34.00, 3, 34, NULL, 37.98909043, 23.68961334, 'Running on April1', '2025-03-31 21:00:00'),
(14, 1, 'running', 4.00, 30, 25, NULL, 38.01359374, 23.67347717, 'Running on April6', '2025-04-05 21:00:00'),
(15, 1, 'cycling', 10.00, 20, NULL, -2, 37.99248216, 23.64789963, 'Cycling on April6', '2025-04-05 21:00:00'),
(16, 1, 'running', 34.00, 34, 34, NULL, 37.99700671, 23.67710120, 'Running on April9', '2025-04-08 21:00:00'),
(17, 5, 'running', 4.00, 60, 20, NULL, 38.02230509, 23.70088575, 'Running on April9', '2025-04-08 21:00:00'),
(22, 1, 'running', 4.00, 30, 12, NULL, 38.02116288, 23.70780945, 'Running on April10', '2025-04-09 21:00:00'),
(23, 1, 'running', 35.00, 2, 3, NULL, 37.97690641, 23.73493195, 'Running on April10', '2025-04-09 21:00:00'),
(24, 1, 'running', 3.00, 23, 0, NULL, 38.05403115, 23.74969482, 'Running on April18', '2025-04-17 21:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `steps`
--
ALTER TABLE `steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `weights`
--
ALTER TABLE `weights`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `steps`
--
ALTER TABLE `steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `weights`
--
ALTER TABLE `weights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `workouts`
--
ALTER TABLE `workouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `steps`
--
ALTER TABLE `steps`
  ADD CONSTRAINT `steps_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `weights`
--
ALTER TABLE `weights`
  ADD CONSTRAINT `weights_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `workouts`
--
ALTER TABLE `workouts`
  ADD CONSTRAINT `workouts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
