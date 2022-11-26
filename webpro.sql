-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2022 at 08:20 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webpro`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `departmentid` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`departmentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(255) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `phone` int(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `startdate` date NOT NULL DEFAULT current_timestamp(),
  `salary` int(255) NOT NULL DEFAULT 1000,
  `departmentid` int(255) NOT NULL,
  PRIMARY KEY (`userid`),
  FOREIGN KEY (`departmentid`) REFERENCES `department` (`departmentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `request`
--


CREATE TABLE `request` (
  `requestid` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `userid` int(255) NOT NULL,
  `departmentid` int(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `datesent` date NOT NULL DEFAULT current_timestamp(),
  `datedecided` date NOT NULL,
  PRIMARY KEY (`requestid`),
  FOREIGN KEY (`userid`) REFERENCES `user` (`userid`),
  FOREIGN KEY (`departmentid`) REFERENCES `department` (`departmentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--



CREATE TABLE `task` (
  `taskid` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `userid` int(255) NOT NULL,
  `departmentid` int(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'unfinished',
  `assigneddate` date NOT NULL DEFAULT current_timestamp(),
  `deadline` date NOT NULL,
  `checkoutdate` date NOT NULL,
  PRIMARY KEY (`taskid`),
  FOREIGN KEY (`userid`) REFERENCES `user` (`userid`),
  FOREIGN KEY (`departmentid`) REFERENCES `department` (`departmentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insert department
INSERT INTO `department` (`name`) VALUES
('Admin'),
('Konoha'),
('Suna'),
('Kumo'),
('Iwa'),
('Kiri');
-- --------------------------------------------------------
-- Insert admin and head user
INSERT INTO `user` (`username`, `password`, `level`, `name`, `gender`, `dob`, `phone`, `address`, `salary`, `departmentid`) VALUES
('admin', 'admin', 'admin', 'Admin', 'other', '2002-10-10', '0900000000','admin','1000',1),
('hokage', 'hokage', 'head','Hokage', 'female', '2002-10-10', '0900000000','langla','1000',2),
('kazekage', 'kazekage', 'head','Kazekage', 'male', '2002-10-10', '0900000000','langcat','1000',3),
('raikage', 'raikage', 'head','Raikage', 'male', '2002-10-10', '0900000000','langmay','1000',4),
('mizukage', 'mizukage', 'head', 'Mizukage', 'female', '2002-10-10', '0900000000','langsuongmu','1000',5),
('tsuchikage', 'tsuchikage', 'head','Tsuchikage', 'male', '2002-10-10', '0900000000','langda','1000',6);


-- Insert Konoha officer user
INSERT INTO `user` (`username`, `password`, `level`, `name`, `gender`, `dob`, `phone`, `address`, `salary`, `departmentid`) VALUES
('naruto', 'naruto', 'officer', 'Uzumaki Naruto', 'male', '2002-10-10', '0900000000','langla','1000',2),
('sasuke', 'sasuke', 'officer', 'Uchiha Sasuke', 'male', '2002-10-10', '0900000000','langla','1000',2),
('sakura', 'sakura', 'officer', 'Haruno Sakura', 'female', '2002-10-10', '0900000000','langla','1000',2),
('kakashi', 'kakashi', 'officer', 'Hatake Kakashi', 'male', '2002-10-10', '0900000000','langla','1000',2);

-- Insert Suna officer user
INSERT INTO `user` (`username`, `password`, `level`, `name`, `gender`, `dob`, `phone`, `address`, `salary`, `departmentid`) VALUES
('gaara', 'gaara', 'officer', 'Gaara', 'male', '2002-10-10', '0900000000','langcat','1000',3),
('temari', 'temari', 'officer', 'Temari', 'female', '2002-10-10', '0900000000','langcat','1000',3),
('kankuro', 'kankuro', 'officer', 'Kankuro', 'male', '2002-10-10', '0900000000','langcat','1000',3);

-- Insert Kumo officer user
INSERT INTO `user` (`username`, `password`, `level`, `name`, `gender`, `dob`, `phone`, `address`, `salary`, `departmentid`) VALUES
('raikagea', 'raikagea', 'officer', 'Raikage A', 'male', '2002-10-10', '0900000000','langmay','1000',4),
('killerb', 'killerb', 'officer', 'Killer B', 'male', '2002-10-10', '0900000000','langmay','1000',4);

-- Insert Kiri officer user
INSERT INTO `user` (`username`, `password`, `level`, `name`, `gender`, `dob`, `phone`, `address`, `salary`, `departmentid`) VALUES
('mei', 'mei', 'officer', 'Mei', 'female', '2002-10-10', '0900000000','langsuongmu','1000',5),
('chojuro ', 'chojuro ', 'officer', 'Chojuro', 'male', '2002-10-10', '0900000000','langsuongmu','1000',5);

-- Insert Iwa officer user
INSERT INTO `user` (`username`, `password`, `level`, `name`, `gender`, `dob`, `phone`, `address`, `salary`, `departmentid`) VALUES
('onoki', 'onoki', 'officer', 'Onoki', 'male', '2002-10-10', '0900000000','langda','1000',6),
('kurotsuchi ', 'kurotsuchi ', 'officer', 'Kurotsuchi', 'female', '2002-10-10', '0900000000','langda','1000',6),
('deidara', 'deidara', 'officer', 'Deidara', 'male', '2002-10-10', '0900000000','langda','1000',6);



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
