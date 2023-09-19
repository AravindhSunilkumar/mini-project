-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2023 at 08:09 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dental`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_username` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `admin_username`, `admin_password`, `created_at`) VALUES
(1, 'admin', 'admin', '2023-08-17 19:36:51');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_appointments`
--

CREATE TABLE `tbl_appointments` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `doctortime_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `appointmentneed_date` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_appointments`
--

INSERT INTO `tbl_appointments` (`appointment_id`, `patient_id`, `doctor_id`, `service_id`, `doctortime_id`, `status`, `appointmentneed_date`, `created_at`) VALUES
(1, 8, 22, 5, 12, 'Active', '09-05-2023', '2023-09-07 15:48:38'),
(4, 9, 26, 5, 11, 'pending', '04-06-2023', '2023-09-19 15:10:33');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_doctors`
--

CREATE TABLE `tbl_doctors` (
  `doctor_id` int(11) NOT NULL,
  `doctor_name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `services` varchar(255) NOT NULL,
  `qualification` varchar(10) NOT NULL,
  `doctor_image` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL,
  `doctor_created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_doctors`
--

INSERT INTO `tbl_doctors` (`doctor_id`, `doctor_name`, `age`, `gender`, `services`, `qualification`, `doctor_image`, `status`, `doctor_created_at`) VALUES
(22, 'SABU', 34, 'Male', 'Cosmetic Dentistry', 'BDS', 'img/doctors/64ea24299a982.jpg', 'Active', '2023-08-17 19:37:50'),
(26, 'Thomas', 40, 'Male', 'tooth cleaning ', 'MDS', 'img/doctors/64ea4e7506ed9.jpg', 'Active', '2023-08-26 21:11:49');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_doctortime`
--

CREATE TABLE `tbl_doctortime` (
  `doctortime_id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `slot_id` int(11) DEFAULT NULL,
  `A_start_time` time DEFAULT NULL,
  `A_end_time` time DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_doctortime`
--

INSERT INTO `tbl_doctortime` (`doctortime_id`, `doctor_id`, `service_id`, `slot_id`, `A_start_time`, `A_end_time`, `status`, `created_at`) VALUES
(10, 22, 5, 2, '09:00:00', '12:00:00', 'Inactive', '2023-09-01 17:11:10'),
(11, 22, 5, 3, '09:00:00', '12:00:00', 'Inactive', '2023-09-01 17:11:10'),
(12, 22, 5, 4, '09:00:00', '12:00:00', 'Inactive', '2023-09-01 17:11:10'),
(13, 22, 6, 5, '09:00:00', '12:00:00', 'Active', '2023-09-01 17:11:10'),
(14, 22, 1, 6, '09:00:00', '12:00:00', 'Active', '2023-09-01 17:11:10'),
(15, 22, 1, 1, '09:00:00', '12:00:00', 'Active', '2023-09-01 17:14:12'),
(16, 26, 1, 6, '09:00:00', '12:00:00', 'Active', '2023-09-01 18:14:04'),
(17, 26, 6, 1, '09:00:00', '12:00:00', 'Active', '2023-09-07 13:47:30');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_patient`
--

CREATE TABLE `tbl_patient` (
  `patient_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `allergy_info` text DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_patient`
--

INSERT INTO `tbl_patient` (`patient_id`, `full_name`, `gender`, `date_of_birth`, `address`, `profile_picture`, `allergy_info`, `emergency_contact_phone`, `created_at`) VALUES
(8, 'Aravindh sunilkumar', 'Male', '2023-08-09', 'kochi', 'img/patients/64e5d247abd8a.jpg', 'infection', '8137977159', '2023-08-22 22:18:45'),
(9, 'Arun', 'Male', '2023-08-04', 'kochi', 'img/patients/64e8b4fdc19df.jpg', 'skin', '8137977159', '2023-08-25 19:34:45');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_services`
--

CREATE TABLE `tbl_services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_image` varchar(255) DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_services`
--

INSERT INTO `tbl_services` (`service_id`, `service_name`, `service_image`, `additional_info`, `status`, `created_at`) VALUES
(5, 'Cosmetic Dentistry', 'img/service_images/heart.png', 'adsdfff', 'Inactive', '2023-09-06 20:48:47');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_timeslot`
--

CREATE TABLE `tbl_timeslot` (
  `slot_id` int(11) NOT NULL,
  `days` varchar(255) NOT NULL,
  `starting_time` time NOT NULL,
  `ending_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_timeslot`
--

INSERT INTO `tbl_timeslot` (`slot_id`, `days`, `starting_time`, `ending_time`, `created_at`) VALUES
(1, 'Monday', '09:00:00', '21:00:00', '2023-09-01 14:54:11'),
(2, 'Tuesday', '09:00:00', '21:00:00', '2023-09-01 14:55:18'),
(3, 'Wednesday', '09:00:00', '21:00:00', '2023-09-01 14:55:48'),
(4, 'Thursday', '09:00:00', '21:00:00', '2023-09-01 14:56:16'),
(5, 'Friday', '09:00:00', '21:00:00', '2023-09-01 14:56:38'),
(6, 'Saturday', '09:00:00', '21:00:00', '2023-09-01 14:57:05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `user_username` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `user_username`, `user_email`, `user_password`, `created_at`) VALUES
(1, 'aravindh', 'email@gmail.com', '123', '2023-08-17 19:45:24'),
(6, 'User2', 'useremail@gmail.com', '123', '2023-08-17 20:07:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_username` (`admin_username`);

--
-- Indexes for table `tbl_appointments`
--
ALTER TABLE `tbl_appointments`
  ADD PRIMARY KEY (`appointment_id`);

--
-- Indexes for table `tbl_doctors`
--
ALTER TABLE `tbl_doctors`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Indexes for table `tbl_doctortime`
--
ALTER TABLE `tbl_doctortime`
  ADD PRIMARY KEY (`doctortime_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `slot_id` (`slot_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `tbl_patient`
--
ALTER TABLE `tbl_patient`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `tbl_services`
--
ALTER TABLE `tbl_services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `tbl_timeslot`
--
ALTER TABLE `tbl_timeslot`
  ADD PRIMARY KEY (`slot_id`),
  ADD UNIQUE KEY `days` (`days`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_doctors`
--
ALTER TABLE `tbl_doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_doctortime`
--
ALTER TABLE `tbl_doctortime`
  MODIFY `doctortime_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_patient`
--
ALTER TABLE `tbl_patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_services`
--
ALTER TABLE `tbl_services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_timeslot`
--
ALTER TABLE `tbl_timeslot`
  MODIFY `slot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
