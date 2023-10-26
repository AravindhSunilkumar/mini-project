-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2023 at 07:55 PM
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
  `email` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `admin_username`, `email`, `admin_password`, `created_at`) VALUES
(1, 'admin', 'Admin@gmail.com', 'admin', '2023-08-17 19:36:51');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_appointments`
--

CREATE TABLE `tbl_appointments` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `patient_email` varchar(255) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `section` varchar(255) NOT NULL,
  `appo_time` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL,
  `appointmentneed_date` varchar(255) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_appointments`
--

INSERT INTO `tbl_appointments` (`appointment_id`, `patient_id`, `patient_email`, `doctor_id`, `service_id`, `section`, `appo_time`, `status`, `appointmentneed_date`, `created_at`) VALUES
(57, 9, 'useremail@gmail.com', 22, 1, 'afternoon', '1:00PM-1:15PM', 'pending', '2023-10-28', '2023-10-12'),
(58, 18, 'aravindhsunilkumar@gmail.com', 22, 1, 'afternoon', '12:30PM-12:45PM', 'approved', '2023-10-27', '2023-10-17'),
(59, 19, 'aravindsunilkumar4@gmail.com', 22, 1, 'afternoon', '12:00PM-12:15PM', 'approved', '2023-10-19', '2023-10-17'),
(60, 19, 'aravindsunilkumar4@gmail.com', 22, 1, 'evening', '4:35PM-4:50PM', 'pending', '2023-10-18', '2023-10-17'),
(61, 19, 'aravindsunilkumar4@gmail.com', 22, 1, 'afternoon', '12:00PM-12:15PM', 'pending', '2023-10-25', '2023-10-24'),
(62, 25, 'aravindsunilkumar4@gmail.com', 22, 2, 'morning', '9:15AM-9:30AM', 'pending', '2023-10-26', '2023-10-25'),
(63, 25, 'aravindsunilkumar4@gmail.com', 22, 1, 'morning', '9:15AM-9:30AM', 'pending', '2023-10-25', '2023-10-25');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_doctors`
--

CREATE TABLE `tbl_doctors` (
  `doctor_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `doctor_name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `services` text NOT NULL,
  `qualification` varchar(10) NOT NULL,
  `doctor_image` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL,
  `doctor_created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_doctors`
--

INSERT INTO `tbl_doctors` (`doctor_id`, `email`, `password`, `doctor_name`, `age`, `gender`, `services`, `qualification`, `doctor_image`, `status`, `doctor_created_at`) VALUES
(22, 'Sabu@gmail.com', 'Sabu', 'SABU', 34, 'Male', 'Cosmetic Dentistry', 'BDS', 'img/doctors/64ea24299a982.jpg', 'Active', '2023-08-17 19:37:50'),
(26, 'thomas@gmail.com', 'thomas', 'Thomas', 40, 'Male', 'tooth cleaning ', 'MDS', 'img/doctors/64ea4e7506ed9.jpg', 'Active', '2023-08-26 21:11:49'),
(27, 'aarji@gmail.com', 'aarji', 'Aarji', 25, 'Male', ' Teeth Whitening', 'MDS', 'img/doctors/65278be5a13dc.jpg', 'Active', '2023-10-12 08:02:13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_doctortime`
--

CREATE TABLE `tbl_doctortime` (
  `doctortime_id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `slot_id` int(11) DEFAULT NULL,
  `morning` varchar(255) DEFAULT NULL,
  `afternoon` varchar(255) DEFAULT NULL,
  `evening` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_doctortime`
--

INSERT INTO `tbl_doctortime` (`doctortime_id`, `doctor_id`, `service_id`, `slot_id`, `morning`, `afternoon`, `evening`, `status`, `created_at`) VALUES
(49, 22, 5, 1, 'Active', 'Active', 'Active', 'Active', '2023-10-04 18:46:04'),
(50, 22, 5, 2, 'Active', 'Active', 'Active', 'Active', '2023-10-04 18:46:04'),
(51, 22, 5, 3, 'Active', 'Active', 'Active', 'Active', '2023-10-04 18:46:04'),
(52, 22, 5, 4, 'Active', 'Active', 'Active', 'Active', '2023-10-04 18:46:04'),
(53, 22, 5, 5, 'Active', 'Active', 'Active', 'Active', '2023-10-04 18:46:04'),
(54, 22, 5, 6, 'Active', 'Active', 'Active', 'Active', '2023-10-04 18:46:04'),
(55, 22, 1, 1, 'Active', 'deactive', 'deactive', 'Active', '2023-10-12 06:07:27');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_patient`
--

CREATE TABLE `tbl_patient` (
  `user_id` int(255) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `allergy_info` text DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `services` text NOT NULL,
  `Details` text NOT NULL,
  `prescription` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_patient`
--

INSERT INTO `tbl_patient` (`user_id`, `patient_id`, `full_name`, `gender`, `date_of_birth`, `address`, `profile_picture`, `allergy_info`, `emergency_contact_phone`, `services`, `Details`, `prescription`, `status`, `created_at`) VALUES
(10, 16, 'sooraj ', 'Male', '2023-10-12', 'velllichapattil(h),pulimchuvadu', NULL, 'no', '7994426297', '1', 'First appointment to teeth whitening', 'no prescription', 'completed', '2023-10-05 12:11:56'),
(0, 20, 'Aravindh sunilkumar', 'Male', '2023-10-04', 'kochi', 'img/patients/6537754a12479.jpg', NULL, '8137977159', '2', '', '', '', '2023-10-24 13:12:02'),
(0, 21, 'Arun', 'Male', '2023-07-05', 'kochi', 'img/patients/653775920c495.jpg', NULL, '8137977159', '3', '', '', '', '2023-10-24 13:13:14'),
(0, 22, 'Anna', 'Male', '2023-10-01', 'kochi', 'img/patients/653775dab37c8.jpg', NULL, '8137977159', '1', '', '', '', '2023-10-24 13:14:26'),
(18, 25, 'aravindh', 'Male', '2023-09-06', 'adsafsdfdfg', NULL, 'dsfdfdffd', '1243334345', '', '', '', '', '2023-10-25 21:22:30');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_services`
--

CREATE TABLE `tbl_services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_image` varchar(255) DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `price` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_services`
--

INSERT INTO `tbl_services` (`service_id`, `service_name`, `service_image`, `additional_info`, `price`, `status`, `created_at`) VALUES
(1, 'Cosmetic Dentistry', 'img/services/65243b59d0a71.jpg', 'Cosmetic Dentistry', 2000, 'Active', '2023-10-09 23:11:45'),
(2, 'Dental Implants', 'img/services/65243bae78003.jpg', 'Dental Implants', 25000, 'Active', '2023-10-09 23:13:10'),
(3, 'Dental Bridges', 'img/services/65243bd5ea986.jpg', 'Dental Bridges', 3000, 'Active', '2023-10-09 23:13:49'),
(4, 'Teeth Whitening', 'img/services/65243c9536b76.jfif', 'Teeth Whitening', 1500, 'Active', '2023-10-09 23:17:01');

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
(17, 'aravindh', 'aravindhsunilkumar@gmail.com', 'ara', '2023-10-17 11:42:14'),
(18, 'user', 'aravindsunilkumar4@gmail.com', '123', '2023-10-17 14:11:52'),
(19, 'Anna', '', 'Anna', '2023-10-24 13:14:26'),
(20, 'Aromal', 'mail@gmail.com', 'Aromal', '2023-10-24 13:32:13');

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
-- AUTO_INCREMENT for table `tbl_appointments`
--
ALTER TABLE `tbl_appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `tbl_doctors`
--
ALTER TABLE `tbl_doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_doctortime`
--
ALTER TABLE `tbl_doctortime`
  MODIFY `doctortime_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tbl_patient`
--
ALTER TABLE `tbl_patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbl_services`
--
ALTER TABLE `tbl_services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_timeslot`
--
ALTER TABLE `tbl_timeslot`
  MODIFY `slot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
