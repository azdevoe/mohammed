-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2025 at 09:11 PM
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
-- Database: `here_i_am`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `user_id`, `filename`, `uploaded_at`, `updated_at`) VALUES
(9, 11, '_20220713_023310.png', '2025-05-14 13:26:22', NULL),
(11, 1, 'CSC 320 HERE I AM REPORT.docx', '2025-05-20 10:06:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `user_id`, `content`, `created_at`) VALUES
(8, 13, 'hi, i love reading\r\n', '2025-05-15 23:27:26'),
(9, 1, '1. WHAT IS DATA PRIVACY\r\n2. CAN DATA PRIVACY BE PREVENTED', '2025-05-20 10:05:39');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `age` varchar(10) DEFAULT NULL,
  `profession` varchar(100) DEFAULT NULL,
  `hobby` varchar(100) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `education` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `likes` text DEFAULT NULL,
  `dislikes` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `age`, `profession`, `hobby`, `about`, `sex`, `education`, `dob`, `email`, `phone`, `address`, `likes`, `dislikes`, `updated_at`, `created_at`) VALUES
(1, 1, '22', 'IT  Professional', 'Coding', 'ALL IS WELL', 'Male', 'Graduate', '2002-08-03', 'mohammed.aribidesi@gmail.com', '08114837663', 'No 9, Olanibukun way Akan-un Laara off Agunfoye road, via Adamo, Ikorodu, Lagos', 'Money', 'Peanut', '2025-05-15 11:51:55', '2025-05-15 11:30:18'),
(3, 11, '32', 'IT  Professional', 'Coding', 'everything is fine', 'Male', 'Graduate', '1992-10-14', 'temitope123@gmail.com', '08114837663', 'No 9, Olanibukun way Akan-un Laara off Agunfoye road, via Adamo, Ikorodu, Lagos', 'coding, dancing', 'Peanut', NULL, '2025-05-15 11:30:18'),
(5, 13, '34', 'Civil Engineer', 'Reading', 'Disciplined, cooperative', 'Female', 'Graduate', '1991-02-13', 'mohammed1.aribidesi@gmail.com', '09018865113', 'No 9, Olanibukun way , via Adamo, Ikorodu, Lagos', 'Reading and Music', 'Noise', NULL, '2025-05-16 00:26:21'),
(6, 15, '37', 'IT  Professional', 'Coding', 'Very Good', 'Female', 'Graduate', '1987-12-06', 'mohammedaribidesi@yahoo.com', '08114837663', 'No 9, Olanibukun way Akan-un Laara off Agunfoye road, via Adamo, Ikorodu, Lagos', 'Money', 'Peanut', NULL, '2025-06-03 20:59:50');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `user_id`, `event_date`, `event_time`, `description`) VALUES
(12, 11, '2025-05-23', '02:27:00', 'REST'),
(13, 11, '2025-05-15', '08:00:00', 'csc 316'),
(16, 13, '2025-05-16', '01:26:00', 'meeting'),
(20, 1, '2025-05-20', '12:02:00', 'CSC 312 ENDS'),
(21, 1, '2025-05-21', '11:27:00', 'Gaming'),
(26, 1, '2025-06-10', '15:23:00', 'Exam'),
(27, 1, '2025-06-04', '14:20:00', 'resy');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `is_approved`, `is_admin`, `created_at`) VALUES
(1, 'Mohammed Aribidesi', 'mohammed.aribidesi@gmail.com', '$2y$10$yhxHMqC0dkVIEjnUode1L.3syNEWFWwAnFB4g2wjLvOU8Ww99dlBq', 1, 0, '2025-06-03 17:02:02'),
(11, 'Temitope12', 'temitope123@gmail.com', '$2y$10$o.MeqVU/6j1nD7C/1vFLY..bgoZqBmov854rHn496nzwk//Lkt6bW', 1, 0, '2025-06-03 17:02:02'),
(13, 'Aribidesi Temitope', 'mohammed1.aribidesi@gmail.com', '$2y$10$Vj82XdVoB4Jq2XN3.Qvnn.1f8DIYKAEVmi5ArrBt4HqX8FjTcxjaO', 1, 0, '2025-06-03 17:02:02'),
(14, 'Dennis', 'dennis@gmail.com', '$2y$10$VDqArONFBIMS84zaenYldO8LSNL2WgOcRZZh2EV2ksQ8cjr3WD9by', 1, 0, '2025-06-03 17:02:02'),
(15, 'Master Mohammed', 'mohammedaribidesi@yahoo.com', '$2y$10$B8RWFIqCXcgeV47c1QBIFeyfGchVd0dpnTdd1Zaz9amtPqfhcrjma', 1, 1, '2025-06-03 17:02:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_files_user_id` (`user_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notes_user_id` (`user_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_profiles_user_id` (`user_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_schedules_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `fk_files_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `fk_notes_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `fk_profiles_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `fk_schedules_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
