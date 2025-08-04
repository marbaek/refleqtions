-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Nov 27, 2024 at 03:38 PM
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
-- Database: `logindb`
--

-- --------------------------------------------------------

--
-- Table structure for table `journal_tbl`
--

CREATE TABLE `journal_tbl` (
  `journal_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `mood` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` varchar(255) DEFAULT date_format(current_timestamp(),'%M %d | %h:%i %p'),
  `updated_at` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `journal_tbl`
--

INSERT INTO `journal_tbl` (`journal_id`, `username`, `title`, `mood`, `description`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Marvic', 'Welcome!', 'Happy', 'Welcome to your Journal', 1, 'November 27 | 10:25 PM', 'November 27 | 10:37 PM'),
(7, 'Leandro', 'Welcome!', 'Relaxed', 'Welcomkjfgddfgd', 2, 'November 27 | 10:25 PM', NULL),
(8, NULL, 'sadsad', '', 'dsada', 2, 'November 27 | 10:25 PM', NULL),
(9, NULL, 'hello', 'Angry', 'tangianmo nyo po', 2, 'November 27 | 10:25 PM', NULL),
(10, NULL, 'sasaS', 'Happy', 'Asa', 2, 'November 27 | 10:25 PM', NULL),
(11, NULL, 'zxZx', 'Sad', 'xzzxXasASA', 2, 'November 27 | 10:25 PM', NULL),
(12, NULL, 'sdsa', 'Happy', 'dsadsa', 2, 'November 27 | 10:25 PM', NULL),
(15, NULL, 'Tang ina', 'Relaxed', 'Why why Delilah', 1, 'November 27 | 10:25 PM', 'November 27 | 10:37 PM');

--
-- Triggers `journal_tbl`
--
DELIMITER $$
CREATE TRIGGER `update_updated_at` BEFORE UPDATE ON `journal_tbl` FOR EACH ROW BEGIN
    SET NEW.updated_at = DATE_FORMAT(NOW(), '%M %d | %h:%i %p');
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `journal_tbl`
--
ALTER TABLE `journal_tbl`
  ADD PRIMARY KEY (`journal_id`),
  ADD KEY `username` (`username`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `journal_tbl`
--
ALTER TABLE `journal_tbl`
  MODIFY `journal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `journal_tbl`
--
ALTER TABLE `journal_tbl`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `profile_tbl` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `journal_tbl_ibfk_1` FOREIGN KEY (`username`) REFERENCES `profile_tbl` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
