-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 11, 2024 at 05:09 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `university_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `hall_id` int(11) DEFAULT NULL,
  `room_type` enum('auditoriums','seminar_halls','lecture_hall_rooms') NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `slot_start_time` time DEFAULT NULL,
  `slot_end_time` time DEFAULT NULL,
  `status` enum('Pending','Confirmed','Cancelled') DEFAULT NULL,
  `booking_type` enum('Auditorium','Seminar Hall','Lecture Hall') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `session` enum('AN','FN','Both') DEFAULT NULL,
  `booking_start_date` date NOT NULL DEFAULT curdate(),
  `booking_end_date` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `hall_id`, `room_type`, `user_id`, `slot_start_time`, `slot_end_time`, `status`, `booking_type`, `created_at`, `updated_at`, `session`, `booking_start_date`, `booking_end_date`) VALUES
(18, 1, 'auditoriums', 16, NULL, NULL, 'Confirmed', NULL, '2024-09-28 15:29:50', '2024-10-09 01:47:13', 'AN', '2024-10-09', '2024-10-11'),
(19, 6, 'seminar_halls', 16, '09:02:00', '10:02:00', 'Confirmed', NULL, '2024-09-28 15:30:31', '2024-10-08 16:32:37', '', '2024-10-09', '2024-10-09'),
(20, 1, 'auditoriums', 16, NULL, NULL, 'Confirmed', NULL, '2024-09-28 15:33:00', '2024-10-09 01:47:42', 'FN', '2024-10-13', '2024-10-15'),
(21, 3, 'auditoriums', 16, NULL, NULL, 'Pending', NULL, '2024-09-28 15:45:59', '2024-10-10 00:27:01', 'Both', '2024-10-09', '2024-10-09'),
(22, 6, 'seminar_halls', 16, '11:30:00', '12:30:00', 'Confirmed', NULL, '2024-09-28 15:47:54', '2024-10-08 16:39:26', '', '2024-10-09', '2024-10-09'),
(23, 10, 'seminar_halls', 16, '02:30:00', '03:30:00', 'Pending', NULL, '2024-09-28 15:54:28', '2024-10-08 16:40:21', '', '2024-10-09', '2024-10-09'),
(24, 10, 'seminar_halls', 16, '09:30:00', '11:30:00', 'Pending', NULL, '2024-09-28 15:57:29', '2024-10-08 16:40:41', '', '2024-10-09', '2024-10-09'),
(25, 2, 'auditoriums', 14, NULL, NULL, 'Pending', NULL, '2024-10-07 08:02:20', '2024-10-09 01:48:16', 'AN', '2024-10-10', '2024-10-10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
