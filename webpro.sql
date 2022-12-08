-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2022 at 08:20 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webpro`
--
CREATE DATABASE webpro;
USE webpro;
-- --------------------------------------------------------

--
-- Table structure for table `user`
--

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `departID` varchar(255),
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`departID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- --------------------------------------------------------
CREATE TABLE department_seq(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT
);

DELIMITER $$

CREATE TRIGGER generate_departID
BEFORE INSERT ON `department`
FOR EACH ROW
BEGIN
  INSERT INTO department_seq VALUES (NULL);
  SET NEW.departID = CONCAT('DE', LPAD(LAST_INSERT_ID(), 4, '0'));
END$$
DELIMITER ;
--
-- Table structure for table `user`
--
CREATE TABLE `account` (
  `username` varchar(255),
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `employee` (
  `employeeID` varchar(255),
  `username` varchar(255),
  `name` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `salary` int(255) DEFAULT 1000,
  `startDate` date DEFAULT NOW(),
  `departID` varchar(255),
  `avatar` varchar(255),
  PRIMARY KEY (`employeeID`),
  FOREIGN KEY (`username`) REFERENCES `account` (`username`) ON DELETE CASCADE,
  FOREIGN KEY (`departID`) REFERENCES `department` (`departID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE employee_seq(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT
);

DELIMITER $$

CREATE TRIGGER generate_employeeID
BEFORE INSERT ON `employee`
FOR EACH ROW
BEGIN
  INSERT INTO employee_seq VALUES (NULL);
  SET NEW.employeeID = CONCAT('EM', LPAD(LAST_INSERT_ID(), 4, '0'));
END$$
DELIMITER ;
-- --------------------------------------------------------

--
-- Table structure for table `request`
--


CREATE TABLE `request` (
  `requestID` varchar(255),
  `type` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(255) DEFAULT 'pending',
  `datesent` date DEFAULT NOW(),
  `datedecided` date DEFAULT NULL,
  `officerID` varchar(255) DEFAULT NULL,
  `headID` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`requestID`),
  FOREIGN KEY (`officerID`) REFERENCES `employee` (`employeeID`) ON DELETE CASCADE,
  FOREIGN KEY (`headID`) REFERENCES `employee` (`employeeID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE request_seq(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT
);
DELIMITER $$
CREATE TRIGGER generate_requestID
BEFORE INSERT ON `request`
FOR EACH ROW
BEGIN
  INSERT INTO request_seq VALUES (NULL);
  SET NEW.requestID = CONCAT('REQ', LPAD(LAST_INSERT_ID(), 4, '0'));
END$$
DELIMITER ;

CREATE TABLE `request_absence` (
  `absenceID` varchar(255),
  `date_start_absence` date DEFAULT NULL,
  `date_end_absence` date DEFAULT NULL,
  PRIMARY KEY (`absenceID`),
  FOREIGN KEY (`absenceID`) REFERENCES `request` (`requestID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `request_salary` (
  `salaryID` varchar(255),
  `amount` int(255) DEFAULT NULL,
  PRIMARY KEY (`salaryID`),
  FOREIGN KEY (`salaryID`) REFERENCES `request` (`requestID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--
CREATE TABLE `task` (
  `taskID` varchar(255),
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(255) DEFAULT 'assigned',
  `assignedDate` date DEFAULT NOW(),
  `deadline` date DEFAULT NULL,
  `checkinDate` date DEFAULT NULL,
  `checkoutDate` date DEFAULT NULL,
  `submitFile` varchar(255) DEFAULT NULL,
  `officerID` varchar(255) DEFAULT NULL,
  `headID` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`taskID`),
  FOREIGN KEY (`officerID`) REFERENCES `employee` (`employeeID`) ON DELETE CASCADE,
  FOREIGN KEY (`headID`) REFERENCES `employee` (`employeeID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE task_seq(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT
);
DELIMITER $$
CREATE TRIGGER generate_taskID
BEFORE INSERT ON `task`
FOR EACH ROW
BEGIN
  INSERT INTO task_seq VALUES (NULL);
  SET NEW.taskID = CONCAT('TAS', LPAD(LAST_INSERT_ID(), 4, '0'));
END$$
DELIMITER ;

-- Table noti
CREATE TABLE `announce` (
  `announceID` varchar(255),
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `announceDate` date DEFAULT NOW(),
  `headID` varchar(255) DEFAULT NULL,
  `departID` varchar(255),
  `announceFile` varchar(255),
  PRIMARY KEY (`announceID`),
  FOREIGN KEY (`headID`) REFERENCES `employee` (`employeeID`) ON DELETE CASCADE,
  FOREIGN KEY (`departID`) REFERENCES `department` (`departID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE announce_seq(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT
);
DELIMITER $$
CREATE TRIGGER generate_annouceID
BEFORE INSERT ON `announce`
FOR EACH ROW
BEGIN
  INSERT INTO announce_seq VALUES (NULL);
  SET NEW.announceID = CONCAT('AN', LPAD(LAST_INSERT_ID(), 4, '0'));
END$$
DELIMITER ;
-- Insert department
INSERT INTO `department` (`name`) VALUES
('Admin'),
('Konoha'),
('Suna'),
('Kumo'),
('Iwa'),
('Kiri');
-- -- --------------------------------------------------------
-- Insert admin and head account
INSERT INTO `account` (`username`, `password`, `role`) VALUES
('admin', 'admin', 'admin'),
('hokage', 'hokage', 'head'),
('kazekage', 'kazekage', 'head'),
('raikage', 'raikage', 'head'),
('mizukage', 'mizukage', 'head'),
('tsuchikage', 'tsuchikage', 'head');

-- Insert head employee
INSERT INTO `employee` (`username`, `name`, `gender`, `dob`,`nationality`, `address`, `phone`, `salary`, `startDate`, `departID`, `avatar`) VALUES
('admin', 'admin', 'male', '2002-10-10', 'vnese', 'admin', '090000000','1000','2020-01-01','DE0001','https://upload.wikimedia.org/wikipedia/commons/thumb/8/88/Chapeau_Hiruzen_Sarutobi.svg/1575px-Chapeau_Hiruzen_Sarutobi.svg.png'),
('hokage', 'Hokage', 'female', '2002-10-10', 'vnese','langla',  '0900000000','1000','2020-01-01', 'DE0002','https://pbs.twimg.com/media/EkUBRodXcAEILDa.jpg'),
('kazekage', 'Kazekage', 'male', '2002-10-10', 'vnese','langcat',  '0900000000','1000','2020-01-01', 'DE0003','https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Kazekage_hat_%28Naruto%2C_manga%29.svg/1200px-Kazekage_hat_%28Naruto%2C_manga%29.svg.png'),
('raikage', 'Raikage', 'male', '2002-10-10', 'vnese','langmay',  '0900000000','1000','2020-01-01', 'DE0004','https://upload.wikimedia.org/wikipedia/commons/thumb/e/ec/Raikage_hat_%28Naruto%2C_manga%29.svg/1200px-Raikage_hat_%28Naruto%2C_manga%29.svg.png'),
('mizukage', 'Mizukage', 'female', '2002-10-10', 'vnese','langsuongmu',  '0900000000','1000','2020-01-01', 'DE0005','https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Mizukage_hat_%28Naruto%2C_manga%29.svg/1575px-Mizukage_hat_%28Naruto%2C_manga%29.svg.png'),
('tsuchikage', 'Tsuchikage', 'male', '2002-10-10', 'vnese','langda',  '0900000000','1000','2020-01-01', 'DE0006','https://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/Tsuchikage_hat_%28Naruto%2C_manga%29.svg/1575px-Tsuchikage_hat_%28Naruto%2C_manga%29.svg.png');

-- Insert Konoha officer account
INSERT INTO `account` (`username`, `password`, `role`) VALUES
('naruto', 'naruto', 'officer'),
('sasuke', 'sasuke', 'officer'),
('sakura', 'sakura', 'officer'),
('kakashi', 'kakashi', 'officer');

-- Insert Konoha officer employee
INSERT INTO `employee` (`username`, `name`, `gender`, `dob`,`nationality`, `address`, `phone`, `salary`, `startDate`, `departID`, `avatar`) VALUES
('naruto', 'Uzumaki Naruto', 'male', '2002-10-10', 'vnese','langla',  '0900000000','1000','2020-01-01', 'DE0002', 'https://staticg.sportskeeda.com/editor/2022/08/53e15-16596004347246.png'),
('sasuke', 'Uchiha Sasuke', 'male', '2002-10-10', 'vnese','langla',  '0900000000','1000','2020-01-01', 'DE0002', 'https://i.pinimg.com/originals/05/2a/23/052a23ab1c6742fe4b13fd751c2ed425.png'),
('sakura', 'Haruno Sakura', 'female', '2002-10-10', 'vnese','langla',  '0900000000','1000','2020-01-01', 'DE0002', 'https://manga-kun.com/wp-content/uploads/2020/11/Sakura.png'),
('kakashi', 'Hatake Kakashi', 'male', '2002-10-10', 'vnese','langla',  '0900000000','1000','2020-01-01', 'DE0002', 'https://i.pinimg.com/originals/38/b1/71/38b171304731a9cc8a8f25f7b40c2a7a.jpg');

-- Insert Suna officer account
INSERT INTO `account` (`username`, `password`, `role`) VALUES
('gaara', 'gaara', 'officer'),
('temari', 'temari', 'officer'),
('kankuro', 'kankuro', 'officer');

-- Insert Suna officer employee
INSERT INTO `employee` (`username`, `name`, `gender`, `dob`,`nationality`, `address`, `phone`, `salary`, `startDate`, `departID`, `avatar`) VALUES
('gaara', 'Gaara', 'male', '2002-10-10', 'vnese','langcat',  '0900000000','1000','2020-01-01', 'DE0003', 'https://staticg.sportskeeda.com/editor/2022/07/7ffdc-16590925062039.png'),
('temari', 'Temari', 'female', '2002-10-10', 'vnese','langcat',  '0900000000','1000','2020-01-01', 'DE0003', 'https://static.fandomspot.com/images/03/29080/00-featured-naruto-shippuden-temari-screenshot.jpg'),
('kankuro', 'Kankuru', 'male', '2002-10-10', 'vnese','langcat',  '0900000000','1000','2020-01-01', 'DE0003', 'https://i.pinimg.com/564x/8e/b4/9d/8eb49dc673861fac1dcb7d4bf3c81a39.jpg');

-- Insert Kumo officer account
INSERT INTO `account` (`username`, `password`, `role`) VALUES
('raikagea', 'raikagea', 'officer'),
('killerb', 'killerb', 'officer');

-- Insert Kumo officer employee
INSERT INTO `employee` (`username`, `name`, `gender`, `dob`,`nationality`, `address`, `phone`, `salary`, `startDate`, `departID`, `avatar`) VALUES
('raikagea', 'Raikage A', 'male', '2002-10-10', 'vnese','langmay',  '0900000000','1000','2020-01-01', 'DE0004', 'https://genk.mediacdn.vn/2019/10/22/photo-1-15717315890591912656601.jpg'),
('killerb', 'Killer B', 'male', '2002-10-10', 'vnese','langmay',  '0900000000','1000','2020-01-01', 'DE0004', 'https://www.animesoulking.com/wp-content/uploads/2021/04/naruto-killer-bee-740x414.jpg');

-- Insert Kiri officer account
INSERT INTO `account` (`username`, `password`, `role`) VALUES
('mei', 'mei', 'officer'),
('chojuro', 'chojuro', 'officer');

-- Insert Kiri officer employee
INSERT INTO `employee` (`username`, `name`, `gender`, `dob`,`nationality`, `address`, `phone`, `salary`, `startDate`, `departID`, `avatar`) VALUES
('mei', 'Mei', 'female', '2002-10-10', 'vnese','langsuongmu',  '0900000000','1000','2020-01-01', 'DE0005', 'https://i0.wp.com/rei.animecharactersdatabase.com/uploads/chars/12602-485632184.png'),
('chojuro', 'Chojuro', 'male', '2002-10-10', 'vnese','langsuongmu',  '0900000000','1000','2020-01-01', 'DE0005', 'https://i.pinimg.com/originals/77/6f/ec/776fec89e78e790b8ce11ea60cbafc62.png');

-- Insert Kiri officer account
INSERT INTO `account` (`username`, `password`, `role`) VALUES
('onoki', 'onoki', 'officer'),
('kurotsuchi', 'kurotsuchi', 'officer');

-- Insert Kiri officer employee
INSERT INTO `employee` (`username`, `name`, `gender`, `dob`,`nationality`, `address`, `phone`, `salary`, `startDate`, `departID`, `avatar`) VALUES
('onoki', 'Onoki', 'male', '2002-10-10', 'vnese','langda',  '0900000000','1000','2020-01-01', 'DE0006', 'https://staticg.sportskeeda.com/editor/2022/09/08997-16625499011834.png'),
('kurotsuchi', 'Kurotsuchi', 'female', '2002-10-10', 'vnese','langda',  '0900000000','1000','2020-01-01', 'DE0006', 'https://staticg.sportskeeda.com/editor/2022/09/b511d-16632473097456.png');

-- Insert Konoha task
INSERT INTO `task` (`title`, `description`, `status`, `deadline`, `officerID`, `headID`) VALUES
('tit1', 'des1', 'assigned' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'assigned' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'assigned' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'assigned' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'assigned' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'assigned' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'in progress' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'in progress' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'in progress' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'pending' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'pending' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'pending' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'pending' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0007', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0007', 'EM0002'),


('tit1', 'des1', 'assigned' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'assigned' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'assigned' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'in progress' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'in progress' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'in progress' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'in progress' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'pending' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'pending' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'pending' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'pending' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0008', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0008', 'EM0002'),

('tit1', 'des1', 'assigned' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'assigned' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'in progress' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'in progress' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'in progress' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'pending' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'pending' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'pending' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0009', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0009', 'EM0002'),


('tit1', 'des1', 'assigned' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'assigned' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'in progress' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'completed' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0010', 'EM0002'),
('tit1', 'des1', 'overdue' , '2022-12-30', 'EM0010', 'EM0002');
-- /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
-- /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
-- /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
