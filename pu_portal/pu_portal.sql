-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2024 at 03:01 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pu_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `auditoriums`
--

CREATE TABLE `auditoriums` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `status` enum('Available','Under Maintenance') DEFAULT 'Available',
  `in_charge_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `features` set('Wi-Fi','Smart Board','Projector','Blackboard','AC','Microphone','Video Conferencing') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `auditoriums`
--

INSERT INTO `auditoriums` (`room_id`, `room_name`, `capacity`, `location`, `status`, `in_charge_id`, `image`, `features`) VALUES
(1, 'Auditorium 1', 300, 'North', 'Available', 18, 'img/a/a1.jpg', 'Wi-Fi,Projector,Microphone'),
(2, 'Auditorium 2', 350, 'East', 'Available', 18, 'img/a/a2.jpg', 'Wi-Fi,AC,Microphone'),
(3, 'Auditorium 3', 400, 'South', 'Available', 18, 'img/a/a1.jpg', 'Wi-Fi,Projector,Video Conferencing'),
(4, 'Auditorium 4', 250, 'West', 'Available', 18, 'img/a/a2.jpg', 'Wi-Fi,Projector,Microphone'),
(5, 'Auditorium 5', 200, 'North', 'Available', 18, 'img/a/a1.jpg', 'Wi-Fi,AC');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `room_type` enum('auditoriums','seminar_halls','lecture_hall_rooms') NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `status` enum('Pending','Confirmed','Cancelled') DEFAULT NULL,
  `booking_type` enum('Auditorium','Seminar Hall','Lecture Hall') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `room_id`, `room_type`, `user_id`, `start_time`, `end_time`, `booking_date`, `status`, `booking_type`, `created_at`, `updated_at`) VALUES
(18, 1, 'auditoriums', 16, '08:59:00', '08:59:00', '2024-09-30', 'Confirmed', NULL, '2024-09-28 15:29:50', '2024-09-28 15:32:40'),
(19, 6, 'seminar_halls', 16, '09:00:00', '09:00:00', '2024-09-26', 'Confirmed', NULL, '2024-09-28 15:30:31', '2024-09-28 15:31:30'),
(20, 1, 'auditoriums', 16, '09:02:00', '10:02:00', '2024-09-06', 'Confirmed', NULL, '2024-09-28 15:33:00', '2024-09-28 15:33:40'),
(21, 1, 'auditoriums', 16, '21:15:00', '22:15:00', '2024-10-30', 'Pending', NULL, '2024-09-28 15:45:59', '2024-09-30 06:14:35'),
(22, 6, 'seminar_halls', 16, '09:17:00', '10:17:00', '2024-09-26', 'Confirmed', NULL, '2024-09-28 15:47:54', '2024-09-28 15:48:10'),
(23, 10, 'seminar_halls', 16, '09:24:00', '10:24:00', '2024-09-29', 'Pending', NULL, '2024-09-28 15:54:28', '2024-09-28 15:54:28'),
(24, 10, 'seminar_halls', 16, '09:27:00', '09:27:00', '2024-09-19', 'Pending', NULL, '2024-09-28 15:57:29', '2024-09-28 15:57:29');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(1, 'Department of Computer Science and Engineering'),
(2, 'Department of Physics'),
(3, 'Department of Maths'),
(4, 'Department of Chemistry');

-- --------------------------------------------------------

--
-- Table structure for table `lecture_hall_complex`
--

CREATE TABLE `lecture_hall_complex` (
  `complex_id` int(11) NOT NULL,
  `complex_name` varchar(100) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `in_charge_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lecture_hall_complex`
--

INSERT INTO `lecture_hall_complex` (`complex_id`, `complex_name`, `location`, `in_charge_id`, `image`) VALUES
(1, 'Lecture Hall Complex 1', 'North Campus', 16, 'img/lh/lh1.jpg'),
(2, 'Lecture Hall Complex 2', 'South Campus', 17, 'img/lh/lh2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `lecture_hall_rooms`
--

CREATE TABLE `lecture_hall_rooms` (
  `room_id` int(11) NOT NULL,
  `complex_id` int(11) DEFAULT NULL,
  `room_name` varchar(100) NOT NULL,
  `capacity` int(11) DEFAULT NULL,
  `features` set('Wi-Fi','Smart Board','Projector','Blackboard','None') DEFAULT NULL,
  `status` enum('Available','Under Maintenance') DEFAULT 'Available',
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lecture_hall_rooms`
--

INSERT INTO `lecture_hall_rooms` (`room_id`, `complex_id`, `room_name`, `capacity`, `features`, `status`, `image`) VALUES
(16, 1, 'Room 101', 100, 'Wi-Fi,Projector,Blackboard', 'Available', 'img/lh/lh1.jpg'),
(17, 1, 'Room 102', 120, 'Wi-Fi', 'Available', 'img/lh/lh1.jpg'),
(18, 1, 'Room 103', 80, 'Projector', 'Under Maintenance', 'img/lh/lh1.jpg'),
(19, 1, 'Room 104', 90, 'Wi-Fi,Projector', 'Available', 'img/lh/lh1.jpg'),
(20, 1, 'Room 105', 110, 'Wi-Fi,Smart Board,Projector', 'Available', 'img/lh/lh1.jpg'),
(21, 2, 'Room 201', 150, 'Wi-Fi', 'Available', 'img/lh/lh2.jpg'),
(22, 2, 'Room 202', 130, 'Wi-Fi', 'Available', 'img/lh/lh2.jpg'),
(23, 2, 'Room 203', 140, 'Smart Board', 'Under Maintenance', 'img/lh/lh2.jpg'),
(24, 2, 'Room 204', 160, 'Wi-Fi', 'Available', 'img/lh/lh2.jpg'),
(25, 2, 'Room 205', 170, 'Wi-Fi', 'Available', 'img/lh/lh2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `lhc_rooms`
--

CREATE TABLE `lhc_rooms` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `num_of_seats` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lhc_rooms`
--

INSERT INTO `lhc_rooms` (`room_id`, `room_name`, `capacity`, `num_of_seats`, `department_id`) VALUES
(1, 'CSE Seminar Hall 1', 100, 100, 1),
(2, 'Physics Seminar Hall', 75, 75, 2),
(4, 'CSE Seminar Hall 2', 75, 75, 1);

-- --------------------------------------------------------

--
-- Table structure for table `room_gallery`
--

CREATE TABLE `room_gallery` (
  `gallery_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room_gallery`
--

INSERT INTO `room_gallery` (`gallery_id`, `room_id`, `image_url`) VALUES
(1, 6, 'img/sh/sh1.jpg'),
(2, 6, 'img/sh/sh2.jpg'),
(3, 6, 'img/sh/sh3.jpg'),
(4, 7, 'img/sh/sh1.jpg'),
(5, 7, 'img/sh/sh2.jpg'),
(6, 7, 'img/sh/sh3.jpg'),
(7, 8, 'img/sh/sh1.jpg'),
(8, 8, 'img/sh/sh2.jpg'),
(9, 8, 'img/sh/sh3.jpg'),
(10, 9, 'img/sh/sh1.jpg'),
(11, 9, 'img/sh/sh2.jpg'),
(12, 9, 'img/sh/sh3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `seminar_halls`
--

CREATE TABLE `seminar_halls` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `status` enum('Available','Under Maintenance') DEFAULT 'Available',
  `in_charge_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `features` set('Wi-Fi','Smart Board','Projector','Blackboard','AC','Microphone','Video Conferencing') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `seminar_halls`
--

INSERT INTO `seminar_halls` (`room_id`, `room_name`, `capacity`, `location`, `status`, `in_charge_id`, `image`, `features`) VALUES
(6, 'Seminar Hall 1', 200, 'School of Management', 'Available', 16, 'img/sh/sh1.jpg', 'Wi-Fi,Projector'),
(7, 'Seminar Hall 2', 200, 'Cultural-cum-Convention Convention Centre', 'Available', 16, 'img/sh/sh2.jpg', 'Smart Board,Projector'),
(8, 'Seminar Hall 3', 200, 'Computer Science (Science Block II )', 'Available', 17, 'img/sh/sh3.jpg', 'Wi-Fi,Smart Board,AC'),
(9, 'Seminar Hall 4', 150, 'Management Studies', 'Available', 17, 'img/sh/sh1.jpg', 'Projector,Microphone'),
(10, 'Seminar Hall 5', 80, 'Computer Science (Science Block II )', 'Available', 17, 'img/sh/sh2.jpg', 'Wi-Fi,Projector'),
(11, 'Seminar Hall 6', 100, 'Mathematics', 'Available', 17, 'img/sh/sh3.jpg', 'Wi-Fi,Projector,Video Conferencing'),
(12, 'Seminar Hall 7', 100, 'Chemistry', 'Available', 17, 'img/sh/sh1.jpg', 'AC,Video Conferencing'),
(13, 'Seminar Hall 8', 100, 'Physics', 'Available', 18, 'img/sh/sh2.jpg', 'Wi-Fi,Smart Board'),
(14, 'Seminar Hall 9', 100, 'Council Hall (for High Level Meeting)', 'Available', 18, 'img/sh/sh3.jpg', 'Wi-Fi,Microphone');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('Prof','Asst Prof','HOD','Dean','Registrar','Admin') NOT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `role`, `department_id`) VALUES
(14, 'prof1', '$2y$10$BUrJ8a5w9mhy7GcItx4xBOYV0gYhrzDAdYdtFOLS/5R9hkgt/QkoW', 'prof1@gmail.com', 'Prof', 1),
(15, 'prof2', '$2y$10$IzU39bqoqPAuKHzdgN0PFu0./c3fDVGrPpsPnjFfjwpV1FTDQ/HVO', 'prof2@gmail.com', 'Prof', 2),
(16, 'dean1', '$2y$10$mdDtJh8UwwgCHqR.0afMeugJUah00v/3LB1gGn9lxpy3JHbLLpL9G', 'dean1@gmail.com', 'Dean', 1),
(17, 'dean2', '$2y$10$15zXp2GPHgGbGw6E2JmFJe3S5zAneHwIKhrDSn0vOlHQEaivsNS7e', 'dean2@gmail.com', 'Dean', 2),
(18, 'Admin', '$2y$10$r2Q5BKGjLBTb5Rxez5roOu6CnkFJ82Hq75TU.tgX5p6vHF4dfyw.m', 'admin@gmail.com', 'Admin', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auditoriums`
--
ALTER TABLE `auditoriums`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `in_charge_id` (`in_charge_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `lecture_hall_complex`
--
ALTER TABLE `lecture_hall_complex`
  ADD PRIMARY KEY (`complex_id`),
  ADD KEY `in_charge_id` (`in_charge_id`);

--
-- Indexes for table `lecture_hall_rooms`
--
ALTER TABLE `lecture_hall_rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `complex_id` (`complex_id`);

--
-- Indexes for table `lhc_rooms`
--
ALTER TABLE `lhc_rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `room_gallery`
--
ALTER TABLE `room_gallery`
  ADD PRIMARY KEY (`gallery_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `seminar_halls`
--
ALTER TABLE `seminar_halls`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `in_charge_id` (`in_charge_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auditoriums`
--
ALTER TABLE `auditoriums`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lecture_hall_complex`
--
ALTER TABLE `lecture_hall_complex`
  MODIFY `complex_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lecture_hall_rooms`
--
ALTER TABLE `lecture_hall_rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `lhc_rooms`
--
ALTER TABLE `lhc_rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `room_gallery`
--
ALTER TABLE `room_gallery`
  MODIFY `gallery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `seminar_halls`
--
ALTER TABLE `seminar_halls`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auditoriums`
--
ALTER TABLE `auditoriums`
  ADD CONSTRAINT `FK_incharge` FOREIGN KEY (`in_charge_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `lecture_hall_rooms`
--
ALTER TABLE `lecture_hall_rooms`
  ADD CONSTRAINT `lecture_hall_rooms_ibfk_1` FOREIGN KEY (`complex_id`) REFERENCES `lecture_hall_complex` (`complex_id`);

--
-- Constraints for table `lhc_rooms`
--
ALTER TABLE `lhc_rooms`
  ADD CONSTRAINT `lhc_rooms_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
