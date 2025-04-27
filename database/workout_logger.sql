-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2025 at 11:10 PM
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
-- Table structure for table `biometrics`
--

CREATE TABLE `biometrics` (
  `id` int(11) NOT NULL,
  `weight` decimal(10,1) DEFAULT NULL,
  `height` decimal(10,0) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `biometrics`
--

INSERT INTO `biometrics` (`id`, `weight`, `height`, `user_id`) VALUES
(1, NULL, 173, 1),
(2, NULL, 160, 8),
(4, NULL, 180, 10);

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` int(11) NOT NULL,
  `step_goal` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `step_goal`, `user_id`) VALUES
(1, 10000, 1),
(2, 12000, 8),
(4, 10000, 10);

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

--
-- Dumping data for table `steps`
--

INSERT INTO `steps` (`id`, `user_id`, `steps`, `date`) VALUES
(10, 1, 5050, '2025-04-18'),
(12, 1, 12406, '2025-04-25'),
(13, 8, 4127, '2025-04-25'),
(14, 10, 5979, '2025-04-27');

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
  `profile_img` varchar(100) DEFAULT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `location`, `bio`, `birth`, `profile_img`, `created_at`) VALUES
(1, 'Rafael', 'rafaelWalder@gmail.com', '$2y$10$lbDcXlL2EttpOZeavvxQM.2z4g1TLGAZqNqajM6dKdC1ucs/CbzGS', 'Greece', 'Few words about me', '1999-12-21', '../assets/uploads/profile_1_1745226712.png', '2025-04-01'),
(8, 'testUser', 'test@gmail.com', '$argon2id$v=19$m=131072,t=4,p=2$TmkvMXo4UU96ek9XWlhtVw$4rXV6Yv6sXyOyn9W9iTIKDHEj89hnEnRDiYcgIeWt7E', '', '', '0000-00-00', NULL, '2025-04-25'),
(10, 'Ist-User', 'ist@gmail.com', '$argon2id$v=19$m=131072,t=4,p=2$bHRpY2lJR05tZm8zTHg3Vg$8wb4XSF3EMHKHa8WQZOc7Gf7PYJ32XNtEc6PWYV5jDw', 'Greece', '', '1999-12-21', NULL, '2025-04-27');

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
(29, 1, 80, '2025-04-01 01:22:40'),
(34, 1, 78, '2025-04-08 12:53:46'),
(37, 1, 70, '2025-04-21 12:16:03'),
(38, 1, 75, '2025-04-25 11:11:13'),
(39, 1, 60, '2025-04-25 12:09:59'),
(40, 10, 78, '2025-04-27 23:35:59');

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
(14, 1, 'running', 4.00, 30, 25, NULL, 38.01359374, 23.67347717, 'Running on April6', '2025-04-05 21:00:00'),
(15, 1, 'cycling', 10.00, 20, NULL, -2, 37.99248216, 23.64789963, 'Cycling on April6', '2025-04-05 21:00:00'),
(51, 1, 'running', 7.00, 50, 0, NULL, 38.05592722, 23.66815567, 'Running on April25', '2025-04-24 21:00:00'),
(52, 1, 'running', 3.00, 12, 0, NULL, 37.99884977, 23.72068405, 'Running on April25', '2025-04-24 21:00:00'),
(53, 8, 'running', 4.00, 30, 0, NULL, 38.03334501, 23.67742538, 'Running on April25', '2025-04-24 21:00:00'),
(54, 8, 'running', 5.00, 30, 0, NULL, 38.02089359, 23.67498713, 'Running on April25', '2025-04-24 21:00:00'),
(55, 8, 'running', 3.00, 23, 0, NULL, 38.00859125, 23.67124557, 'Running on April25', '2025-04-24 21:00:00'),
(57, 10, 'running', 4.00, 45, 4, NULL, 38.01210868, 23.69493484, 'Running on April27', '2025-04-26 21:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `biometrics`
--
ALTER TABLE `biometrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `biometrics`
--
ALTER TABLE `biometrics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `steps`
--
ALTER TABLE `steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `weights`
--
ALTER TABLE `weights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `workouts`
--
ALTER TABLE `workouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `biometrics`
--
ALTER TABLE `biometrics`
  ADD CONSTRAINT `biometrics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

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
