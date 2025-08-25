-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2024 at 05:51 PM
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
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `activityid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `detail` text DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT 'no_img.jpg',
  `points` int(11) DEFAULT NULL,
  `adminid` varchar(20) NOT NULL,
  `groupid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`activityid`, `title`, `detail`, `time`, `location`, `image`, `points`, `adminid`, `groupid`) VALUES
(27, 'Quiz Game', 'its interesting', '2024-07-20 13:33:00', 'Outer Hall', 'no_img.jpg', 50, 'admin_1', 1),
(31, 'mm', 'wdjqcb', '2024-06-28 00:28:00', 'Room 69', 'Kafka.(Honkai.Star.Rail).600.4001105.jpg', 40, 'admin_1', 1),
(34, 'New Activity', 'Join for action and fun times', '2024-07-27 15:45:00', 'Room 34-W', 'no_img.jpg', 30, 'admin_1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminid` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminid`, `password`, `username`) VALUES
('admin_1', 'woah', 'Lead Admin');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendanceid` int(11) NOT NULL,
  `userid` varchar(20) NOT NULL,
  `activityid` int(11) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `groupid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendanceid`, `userid`, `activityid`, `status`, `groupid`) VALUES
(56, 'ahmad_23', 27, 'Present', 1),
(54, 'ahmad_23', 31, 'Absent', 1),
(62, 'ahmad_23', 34, 'Absent', 1),
(57, 'womp_1', 27, 'Present', 1),
(55, 'womp_1', 31, 'Present', 1),
(63, 'womp_1', 34, 'Present', 1);

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE `group` (
  `groupid` int(11) NOT NULL,
  `groupname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`groupid`, `groupname`) VALUES
(2, 'Finance'),
(1, 'indubitably'),
(4, 'test');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `groupid` int(11) NOT NULL,
  `image` varchar(250) DEFAULT 'pfp.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `password`, `username`, `groupid`, `image`) VALUES
('ahmad_23', 'despair', 'noway', 1, 'pfp.png'),
('fanum', 'fanumtax', 'fanum_', 1, 'pfp.png'),
('ooo', 'oo', 'ooooo', 2, 'pfp.png'),
('Test tickle', 'wymdawg', 'test', 4, 'pfp.png'),
('womp_1', 'dontcare', 'wompwomp', 1, 'GKnHahnbEAA1qoK.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`activityid`),
  ADD KEY `fk_activity_admin` (`adminid`),
  ADD KEY `groupid` (`groupid`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminid`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`userid`,`activityid`),
  ADD KEY `attendanceid` (`attendanceid`),
  ADD KEY `fk_attendance_activity` (`activityid`),
  ADD KEY `fk_attendance_user` (`userid`) USING BTREE;

--
-- Indexes for table `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`groupid`),
  ADD UNIQUE KEY `groupname` (`groupname`),
  ADD UNIQUE KEY `fk_group_admin` (`groupid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `fk_user_group` (`groupid`),
  ADD KEY `fk_user_attendance` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `activityid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendanceid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
  MODIFY `groupid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `activity_ibfk_1` FOREIGN KEY (`groupid`) REFERENCES `group` (`groupid`),
  ADD CONSTRAINT `fk_activity_admin` FOREIGN KEY (`adminid`) REFERENCES `admin` (`adminid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `fk_attendance_activity` FOREIGN KEY (`activityid`) REFERENCES `activity` (`activityid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_attendance_user` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_group` FOREIGN KEY (`groupid`) REFERENCES `group` (`groupid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
