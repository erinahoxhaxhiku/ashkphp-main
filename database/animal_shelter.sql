-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2025 at 12:46 PM
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
-- Database: `animal_shelter`
--

-- --------------------------------------------------------

--
-- Table structure for table `adoption_applications`
--

CREATE TABLE `adoption_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `application_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adoption_applications`
--

INSERT INTO `adoption_applications` (`id`, `user_id`, `animal_id`, `status`, `application_date`) VALUES
(1, 3, 6, 'approved', '2025-06-28 10:34:28'),
(2, 2, 7, 'pending', '2025-06-28 10:45:19');

-- --------------------------------------------------------

--
-- Table structure for table `animals`
--

CREATE TABLE `animals` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `species` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('available','pending','adopted') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `animals`
--

INSERT INTO `animals` (`id`, `name`, `species`, `description`, `status`, `created_at`, `updated_at`) VALUES
(5, 'max', 'dog', 'hi', 'pending', '2025-06-28 10:08:11', '2025-06-28 10:14:53'),
(6, 'max', 'dog', 'A friendly dog who likes to cuddle.', 'available', '2025-06-28 10:15:03', '2025-06-28 10:15:03'),
(7, 'luna', 'cat', 'A friendly cat who likes to cuddle.', 'available', '2025-06-28 10:16:28', '2025-06-28 10:16:28'),
(8, 'luna', 'cat', 'A friendly cat who likes to cuddle.', 'adopted', '2025-06-28 10:19:36', '2025-06-28 10:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `animal_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `animal_id`, `file_name`, `file_path`, `uploaded_at`) VALUES
(5, 5, '685fbf0b9454b_max.jpg', 'uploads/animals/685fbf0b9454b_max.jpg', '2025-06-28 10:08:11'),
(6, 6, '685fc0a79ca94_max.jpg', 'uploads/animals/685fc0a79ca94_max.jpg', '2025-06-28 10:15:03'),
(7, 7, '685fc0fc15fcd_cat.jpg', 'uploads/animals/685fc0fc15fcd_cat.jpg', '2025-06-28 10:16:28'),
(8, 8, '685fc1b8b0e9c_cat.jpg', 'uploads/animals/685fc1b8b0e9c_cat.jpg', '2025-06-28 10:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'admin', 'admin@animalshelter.com', '$2y$10$/jhpsxuUTR/kEiuYarJeFOXMdHhFDBJ7VDulG4gLm60W3P663aDfO', 'admin', '2025-06-28 09:12:07'),
(3, 'test', 'test@gmail.com', '$2y$10$/jhpsxuUTR/kEiuYarJeFOXMdHhFDBJ7VDulG4gLm60W3P663aDfO', 'user', '2025-06-28 09:14:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adoption_applications`
--
ALTER TABLE `adoption_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `animal_id` (`animal_id`);

--
-- Indexes for table `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `animal_id` (`animal_id`);

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
-- AUTO_INCREMENT for table `adoption_applications`
--
ALTER TABLE `adoption_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `animals`
--
ALTER TABLE `animals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adoption_applications`
--
ALTER TABLE `adoption_applications`
  ADD CONSTRAINT `adoption_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `adoption_applications_ibfk_2` FOREIGN KEY (`animal_id`) REFERENCES `animals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `animals` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
