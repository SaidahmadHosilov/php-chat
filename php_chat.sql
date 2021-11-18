-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2021 at 03:35 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_chat`
--

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

CREATE TABLE `sms` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_to_id` int(11) DEFAULT NULL,
  `text` longtext DEFAULT NULL,
  `time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sms`
--

INSERT INTO `sms` (`id`, `user_id`, `user_to_id`, `text`, `time`) VALUES
(1, 6, 2, 'Salom qalesan', '2021-11-15 13:21:44'),
(2, 6, 2, 'Nima gaplar o\'rtoq?', '2021-11-15 13:23:21'),
(3, 2, 6, 'Hammasi joyida, o\'zingda nima gaplar?', '2021-11-15 13:23:21'),
(4, 6, 2, 'ha yaxshi', '2021-11-15 16:07:05'),
(5, 6, 6, 'shunaqa de', '2021-11-15 20:22:32'),
(6, 6, 6, '.', '2021-11-15 20:22:52'),
(7, 6, NULL, 's', '2021-11-15 20:26:30'),
(8, 6, 2, 'Shunaqa degin', '2021-11-15 20:40:00'),
(9, 6, 2, 'salom', '2021-11-15 20:42:04'),
(10, 6, 2, 'nima gap', '2021-11-15 20:42:59'),
(11, 6, 2, 'sss', '2021-11-15 20:43:27'),
(12, 6, 2, 'nima ga ozi', '2021-11-16 17:05:22'),
(13, 6, 2, 'nima gap', '2021-11-16 17:20:13'),
(14, 6, 2, 'asdasd', '2021-11-16 17:22:36'),
(15, 6, 6, 'asdasd', '2021-11-16 17:23:08'),
(16, 6, 2, 'asd', '2021-11-16 17:24:23'),
(17, 6, 2, 'asd', '2021-11-16 17:24:27'),
(18, 6, 2, 'asd', '2021-11-16 17:25:25'),
(19, 6, 2, 's', '2021-11-16 17:25:45'),
(20, 6, 2, 'test', '2021-11-16 17:30:19'),
(21, 6, 2, 'asd', '2021-11-16 17:36:25'),
(22, 6, 2, 'test for test', '2021-11-16 17:38:03'),
(23, 6, 2, 'test', '2021-11-16 17:38:35'),
(24, 6, 2, 's', '2021-11-16 17:38:55'),
(25, 6, 2, 'a', '2021-11-16 17:39:00'),
(26, 2, 2, 'salom', '2021-11-16 18:51:56'),
(27, 2, 2, 'nima gap kamol', '2021-11-16 18:55:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `image` varchar(128) DEFAULT NULL,
  `password` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `image`, `password`) VALUES
(2, 'Kamoliddin', '+998 (95) 665-45-63', 'pexels-pixabay-268917.jpg', '$2y$10$vfVZfnUP8d0kwt2Rhw9EWOkujAJVnscxo4NWd4Xzk5aZBrpVM7eC.'),
(3, 'Test User', '+998 (95) 002-56-18', 'no-image.png', '$2y$10$10DV6ASf2f/xhWdQ7X5ux.KfkOfc9UIQwMu66zZgQuyAOmaKobIgS'),
(6, 'Saidahmad', '+998 (90) 110-56-15', 'flowers-1273066_1920.jpg', '$2y$10$4XSun0m0iPSfmeulgj2RSOv.nzFVs/RLhf4aXzZ0LOwIYPOjm4.Lm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_to_id` (`user_to_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sms`
--
ALTER TABLE `sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sms`
--
ALTER TABLE `sms`
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_to_id_fk` FOREIGN KEY (`user_to_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
