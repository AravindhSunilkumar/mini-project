-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2023 at 08:23 PM
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
  `user_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `patient_email` varchar(255) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `section` varchar(255) NOT NULL,
  `appo_time` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `paid_amount` varchar(255) NOT NULL,
  `due_amount` varchar(255) NOT NULL,
  `appointmentneed_date` varchar(255) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_appointments`
--

INSERT INTO `tbl_appointments` (`appointment_id`, `user_id`, `patient_id`, `patient_email`, `doctor_id`, `service_id`, `package_id`, `section`, `appo_time`, `status`, `payment_status`, `paid_amount`, `due_amount`, `appointmentneed_date`, `created_at`) VALUES
(16, 17, 27, 'aravindhsunilkumar@gmail.com', 22, 5, 2, 'afternoon', '1:00PM-1:15PM', 'completed', 'Paid', '2500', '25000', '2023-11-10', '2023-11-09'),
(17, 17, 27, 'aravindhsunilkumar@gmail.com', 22, 4, 8, 'afternoon', '1:45PM-2:00PM', 'completed', 'Paid', '500', '0', '2023-11-11', '2023-11-09'),
(18, 17, 27, 'aravindhsunilkumar@gmail.com', 22, 1, 5, 'afternoon', '12:30PM-12:45PM', 'completed', 'Paid', '500', '0', '2023-12-02', '2023-11-09'),
(22, 17, 27, 'aravindhsunilkumar@gmail.com', 22, 4, 8, 'morning', '9:15AM-9:30AM', 'completed', 'Paid', '500', '0', '2023-11-17', '2023-11-10'),
(23, 17, 27, 'aravindhsunilkumar@gmail.com', 22, 5, 2, 'afternoon', '1:00PM-1:15PM', 'completed', 'Paid', '2500', '17500', '2023-11-23', '2023-11-10'),
(25, 17, 27, 'aravindhsunilkumar@gmail.com', 22, 2, 6, 'afternoon', '12:15PM-12:30PM', 'completed', 'Paid', '500', '0', '2023-11-24', '2023-11-10'),
(26, 17, 27, 'aravindhsunilkumar@gmail.com', 22, 3, 7, 'evening', '4:35PM-4:50PM', 'rejected', 'Paid', '500', '0', '2023-12-07', '2023-11-10'),
(36, 26, 29, 'aravindhsunilkumar2@gmail.com', 22, 16, 12, 'morning', '9:15AM-9:30AM', 'rejected', 'Paid', '500', '0', '2023-11-22', '2023-11-14');

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
(22, 'Sabu@gmail.com', 'Sabu', 'SABU', 34, 'Male', 'Cosmetic Dentistry', 'BDS', 'img/doctors/654f88d75c42d.jpg', 'Active', '2023-08-17 19:37:50'),
(26, 'thomas@gmail.com', 'thomas', 'Thomas', 40, 'Male', 'tooth cleaning ', 'MDS', 'img/doctors/64ea4e7506ed9.jpg', 'Active', '2023-08-26 21:11:49'),
(27, 'aarji@gmail.com', 'aarji', 'Aarji', 25, 'Male', ' Teeth Whitening', 'MDS', 'img/doctors/654f88fc7fdda.jpg', 'Active', '2023-10-12 08:02:13');

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
(18, 25, 'aravindh', 'Male', '2023-09-06', 'adsafsdfdfg', NULL, 'dsfdfdffd', '1243334345', '', '', '', '', '2023-10-25 21:22:30'),
(20, 26, 'Aromal v saji', 'Male', '2023-10-17', 'sgfdgsdfgvcggvgvc', NULL, 'no', '', '', '', '', '', '2023-10-29 13:15:11'),
(17, 27, 'Aravindh sunilkumar', 'Male', '2003-06-04', 'Chemmathalayil,kureekad p o ,Ernakulam', 'img/patients/654e3de16d0d1.jpg', 'skin', '8137977159', '', '', '', '', '2023-11-01 21:10:27'),
(25, 28, 'Aromal V Saji', 'Male', '2007-03-06', 'address illa', 'img/patients/654e62053c89a.jpg', 'infection', '9645854261', '', '', '', '', '2023-11-10 22:30:47'),
(26, 29, 'Saji', 'Male', '2009-02-11', 'illa', NULL, 'illa', '7994077150', '', '', '', '', '2023-11-10 22:38:28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_prebuild_questions`
--

CREATE TABLE `tbl_prebuild_questions` (
  `prequestion_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_prebuild_questions`
--

INSERT INTO `tbl_prebuild_questions` (`prequestion_id`, `question`, `answer`) VALUES
(1, 'How do I take care of my root canal treated tooth?', 'To take care of your root canal treated tooth, brush twice daily, floss once daily, and see your dentist regularly. Avoid chewing hard foods and smoking.'),
(2, 'How long do braces take to work?', '\r\nThe amount of time it takes for braces to work varies depending on the individual case. However, the average treatment time is between 1 and 3 years. Some factors that can affect the length of treatment include:\r\n.The severity of the problem\r\n.type of braces\r\n.patient\'s age\r\n.Mostly Patients who follow their orthodontist\'s instructions carefully and keep their braces clean are more likely to see results more quickly.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_price_packages`
--

CREATE TABLE `tbl_price_packages` (
  `package_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `package_discription` text NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_price_packages`
--

INSERT INTO `tbl_price_packages` (`package_id`, `service_id`, `package_name`, `price`, `package_discription`, `status`) VALUES
(1, 5, 'Metal Braces', '18000', 'Metal Braces', 'Active'),
(2, 5, 'Ceramic Braces', '30000', 'Ceramic Braces', 'Active'),
(3, 5, 'Invisalign Braces', '90000', 'Invisalign Braces', 'Active'),
(4, 5, 'Clear Aligner', '60000', 'Clear Aligner', 'Active'),
(5, 1, 'Cosmetic Dentistry', '500', 'Cosmetic Dentistry', 'Active'),
(6, 2, 'Dental Implant', '500', 'Dental Implant', 'Active'),
(7, 3, 'Dental Bridge', '500', 'Dental Bridge', 'Active'),
(8, 4, 'Teeth Whitening', '500', 'Teeth Whitening ', 'Active'),
(12, 16, 'Root Canal', '500', 'Root Canal', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_questions`
--

CREATE TABLE `tbl_questions` (
  `question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `reply` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_questions`
--

INSERT INTO `tbl_questions` (`question_id`, `user_id`, `question`, `reply`) VALUES
(1, 26, 'Nothing', ''),
(2, 26, 'Nothing', ''),
(3, 26, 'Nothing', ''),
(4, 26, 'Nothing', ''),
(5, 26, 'Nothing', ''),
(6, 26, 'Nothing', '');

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
(1, 'Cosmetic Dentistry', 'img/services/65243b59d0a71.jpg', 'Cosmetic Dentistry', 'Active', '2023-10-09 23:11:45'),
(2, 'Dental Implants', 'img/services/65243bae78003.jpg', 'Dental Implants', 'Active', '2023-10-09 23:13:10'),
(3, 'Dental Bridges', 'img/services/65243bd5ea986.jpg', 'Dental Bridges', 'Active', '2023-10-09 23:13:49'),
(4, 'Teeth Whitening', 'img/services/65243c9536b76.jfif', 'Teeth Whitening', 'Active', '2023-10-09 23:17:01'),
(5, 'Dental Braces', 'img/services/653de5dfebae0.jfif', 'Dental Braces', 'Active', '2023-10-29 10:25:59'),
(16, 'Root Canal', 'img/services/65537797bae7e.jpg', 'Root Canal ', 'Inactive', '2023-11-14 19:05:19');

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
(17, 'Aravindh Sunilkumar', 'aravindhsunilkumar@gmail.com', 'aravi', '2023-10-17 11:42:14'),
(18, 'user', 'aravindhsunilkumar4@gmail.com', 'aravindh', '2023-10-17 14:11:52'),
(25, 'Aromal', 'aravindhsunilkumar3@gmail.com', 'aromal', '2023-11-10 22:18:05'),
(26, 'Saji', 'aravindhsunilkumar2@gmail.com', 'saji@123', '2023-11-10 22:37:28');

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
-- Indexes for table `tbl_prebuild_questions`
--
ALTER TABLE `tbl_prebuild_questions`
  ADD PRIMARY KEY (`prequestion_id`);

--
-- Indexes for table `tbl_price_packages`
--
ALTER TABLE `tbl_price_packages`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `tbl_questions`
--
ALTER TABLE `tbl_questions`
  ADD PRIMARY KEY (`question_id`);

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
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_prebuild_questions`
--
ALTER TABLE `tbl_prebuild_questions`
  MODIFY `prequestion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_price_packages`
--
ALTER TABLE `tbl_price_packages`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_questions`
--
ALTER TABLE `tbl_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_services`
--
ALTER TABLE `tbl_services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_timeslot`
--
ALTER TABLE `tbl_timeslot`
  MODIFY `slot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
