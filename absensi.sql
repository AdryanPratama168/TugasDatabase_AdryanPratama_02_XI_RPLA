-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 02:54 PM
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
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `absens`
--

CREATE TABLE `absens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `jurusan` varchar(255) NOT NULL,
  `nik_nip` varchar(255) NOT NULL,
  `no_absen` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `time` datetime NOT NULL,
  `status` enum('hadir','sakit','izin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absens`
--

INSERT INTO `absens` (`id`, `user_id`, `name`, `class`, `jurusan`, `nik_nip`, `no_absen`, `role`, `time`, `status`) VALUES
(3, 2, 'yayan', '12', 'rpl', '12', '12', 'siswa', '2025-01-23 12:21:36', 'sakit'),
(9, 2, 'yayan', '1', 'rpl', '12', '12', 'siswa', '2025-01-24 14:46:38', 'hadir');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
  `nik_nip` varchar(50) DEFAULT NULL,
  `no_absen` int(11) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','siswa') NOT NULL DEFAULT 'siswa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `class`, `jurusan`, `nik_nip`, `no_absen`, `password`, `created_at`, `role`) VALUES
(2, 'yayan', '1', 'rpl', '12', 12, '$2y$10$158prDNl6udcgt0oBOnR8eZeA2gZIY8QxC3ALeX2bw1QaPgGIdKIC', '2025-01-22 12:33:50', 'siswa'),
(4, NULL, NULL, NULL, 'admin123', NULL, '$2y$10$bMKaZecN6b21g90JGoZLC.jXE.HiZDbKs4E95PwqMAcqQc6kcXgEi', '2025-01-22 13:20:25', 'admin'),
(7, 'Zaydan', '10', 'RPL', '120', 1, '12', '2025-01-23 10:30:07', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absens`
--
ALTER TABLE `absens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik_nip` (`nik_nip`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absens`
--
ALTER TABLE `absens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absens`
--
ALTER TABLE `absens`
  ADD CONSTRAINT `absens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
