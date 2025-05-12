-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql301.byetcluster.com
-- Generation Time: Apr 23, 2025 at 03:03 AM
-- Server version: 10.6.19-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_37772329_rmis`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', '$2y$10$YR4wkzdHXVDF0t2CECXm6.j.bVM8oO/g7/eUQ8T8BjXGGGVlCiPZK');

-- --------------------------------------------------------

--
-- Table structure for table `archive`
--

CREATE TABLE `archive` (
  `id` int(11) NOT NULL,
  `research_title` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `co_authors` varchar(255) DEFAULT NULL,
  `abstract` varchar(1000) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `adviser_name` varchar(255) DEFAULT NULL,
  `faculty_code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `archive`
--

INSERT INTO `archive` (`id`, `research_title`, `author`, `co_authors`, `abstract`, `keywords`, `file_path`, `UserID`, `adviser_name`, `faculty_code`) VALUES
(39, 'Student Management System', 'mark anthony Villiones', 'rel ace tenorio', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through lorem the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.', 'Management System', '../Archive/IT140_-Capstone-Project-and-Research-1_Proposal_v2_Final.pdf', 7, 'Mark Villiones', 12345678),
(50, 'Document Archiving Management System', 'Mark Anthony Villiones', 'Rel Ace Tenorio', 'lorem ipsum', 'Document Management', '../Archive/document-management.pdf', 7, 'Mark Villiones', 12345678),
(53, 'Management System Leveraging Machine Learning Technologies', 'Mark Anthony Villiones', 'Rel Ace Tenorio, Kaycee Vergara', 'There are many management variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.', 'Management System, machine learning', '../../Archive/0125_ocms_ecms2021_0052.pdf', 13, NULL, 12345678),
(56, 'Document Archiving', 'Kayce Vergara', 'Mark Villiones', 'cxfvsfwearerazxrf', 'archiving', '../Archive/fili-2112-notes.pdf', 65, 'Chang Alkie', 12345678),
(57, 'Asdsadasd', 'Asasdasd', 'Sadasdasd', 'asdasdasd', 'asdasd', '../Archive/CAPSTONE-test-cases.pdf', 7, 'Mark Anthony  Villiones', 12345678),
(58, 'Document Evaluation', 'Kayce Vergara', 'Mark Villiones', 'sdfzsfvsgesdgbxxf', 'Evaluation', '../Archive/VISA DISCLOSURE.pdf', 69, NULL, 12345678),
(59, 'Eeee', 'Eeeee', 'Eeee', 'aeerqrqw', 'management', '../Archive/CHAPTER 2_1.pdf', 79, 'Johnny Depp', 77771),
(62, 'Arduino Based Machine Learning', 'Rel Ace Tenorio', 'Mark Vil', 'sadaaaaaaaa', 'machine learning', '../Archive/415511_9.pdf', 85, 'Mark Villiones', 8888);

-- --------------------------------------------------------

--
-- Table structure for table `citation`
--

CREATE TABLE `citation` (
  `id` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `research_id` int(11) DEFAULT NULL,
  `college_code` int(11) DEFAULT NULL,
  `department_code` int(11) DEFAULT NULL,
  `cited_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `citation`
--

INSERT INTO `citation` (`id`, `UserID`, `research_id`, `college_code`, `department_code`, `cited_at`) VALUES
(1, 7, 49, 86945396, 54656744, '2024-11-18 17:22:11'),
(2, 13, 38, 86945396, 54656744, '2024-11-18 18:35:23'),
(3, 7, 51, 86945396, 54656744, '2024-11-18 19:08:06'),
(4, 7, 58, 86945396, 12345678, '2024-12-13 17:00:26'),
(5, 7, 62, 12345678, 8888, '2025-01-10 04:36:07');

-- --------------------------------------------------------

--
-- Table structure for table `colleges`
--

CREATE TABLE `colleges` (
  `id` int(11) NOT NULL,
  `college_name` varchar(255) NOT NULL,
  `college_code` int(11) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) DEFAULT 'No Account'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `colleges`
--

INSERT INTO `colleges` (`id`, `college_name`, `college_code`, `creation_date`, `status`) VALUES
(1, 'College of Computing Studies', 86945396, '2024-08-26 07:25:22', 'USED'),
(14, 'College Of Engineering', 12345678, '2024-09-29 19:11:23', 'USED'),
(15, 'College Of Nursing', 86945393, '2024-10-28 16:56:58', 'No Account'),
(17, 'College Of Medicine', 7777, '2024-12-06 09:45:20', 'USED'),
(18, 'College Of Agriculture', 923874, '2024-12-13 15:55:00', 'No Account'),
(19, 'College Of Liberal Arts', 2134234, '2024-12-13 15:56:17', 'No Account');

-- --------------------------------------------------------

--
-- Table structure for table `college_account`
--

CREATE TABLE `college_account` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `google_id` int(11) DEFAULT NULL,
  `reset_code` int(11) DEFAULT NULL,
  `college_code` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `college_account`
--

INSERT INTO `college_account` (`id`, `email`, `password`, `google_id`, `reset_code`, `college_code`, `status`, `image_path`) VALUES
(3, 'ccs@gmail.com', '$2y$10$YlAVL/5i6RR4wPSZeoWhou2sDXoTxCaX62vpoH7hoN6LoDJMtXfGi', NULL, NULL, 86945396, 'Active', '../profile-photo/user-profile-icon-free-vector.jpg'),
(21, 'com@gmail.com', '$2y$10$D34.BQ4t4qDOUZZxLV3KQOMabhzsmLZwMi//IefHuV9zjjinULBt.', NULL, NULL, 7777, 'Active', NULL),
(26, 'coe@gmail.com', '$2y$10$LRyIpS5BDrTTNgWy3eDbe.jNYnLf0PFs5cibdnn7HNN1VnhletxxS', NULL, NULL, 12345678, 'Active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_name` varchar(255) NOT NULL,
  `department_code` int(11) DEFAULT NULL,
  `college_code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_name`, `department_code`, `college_code`) VALUES
('Computer Science', 23523423, 86945396),
('Information Technology', 12345678, 86945396),
('Mickey Mouse', 77771, 7777),
('Computer Engineering', 8888, 12345678),
('North Pole', 18377244, 86945396);

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `id` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `research_id` int(11) DEFAULT NULL,
  `college_code` int(11) DEFAULT NULL,
  `department_code` int(11) DEFAULT NULL,
  `downloaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `downloads`
--

INSERT INTO `downloads` (`id`, `UserID`, `research_id`, `college_code`, `department_code`, `downloaded_at`) VALUES
(1, 51, 38, 86945396, 54656744, '2024-11-06 15:14:04'),
(2, 13, 38, 86945396, 54656744, '2024-11-06 15:14:04'),
(3, 7, 49, 86945396, 54656744, '2024-11-18 17:36:43'),
(4, 13, 49, 86945396, 54656744, '2024-11-18 18:36:42'),
(5, 13, 39, 86945396, 54656744, '2024-11-18 18:39:43'),
(6, 13, 50, 86945396, 54656744, '2024-11-18 18:43:41'),
(8, 7, 51, 86945396, 54656744, '2024-11-18 19:08:21');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `title` varchar(255) DEFAULT NULL,
  `is_viewed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `UserID`, `message`, `created_at`, `title`, `is_viewed`) VALUES
(15, 13, 'Research submission titled \"Data Mining Using Natural Language Processing\" has been submitted to you, please check it.', '2024-11-10 13:39:18', 'Review Submission', 1),
(16, 7, 'Your adviser has requested a revision on your research submission titled \"Data Mining Using Natural Language Processing\". Comments: revise', '2024-11-10 13:44:54', 'Submission Revision Requested', 1),
(17, 7, 'Your adviser has requested a revision on your research submission titled \"Data Mining Using Natural Language Processing\". Comments: revise', '2024-11-10 13:47:57', 'Submission Revision Requested', 1),
(18, 7, 'Your adviser has requested a revision on your research submission titled \"Data Mining Using Natural Language Processing\". Comments: revise this please', '2024-11-14 17:06:48', 'Submission Revision Requested', 1),
(19, 7, 'Your adviser has requested a revision on your research submission titled \"Data Mining Using Natural Language Processing\". Comments: chapter 3 is incorrect', '2024-11-14 17:11:21', 'Submission Revision Requested', 1),
(20, 7, 'Your research submission titled \"Data Mining Using Natural Language Processing\" has been rejected by your adviser. If you think this is a mistake, contact your adviser immediately. Comments: asdasd', '2024-11-18 14:59:28', 'Submission Rejected', 1),
(21, 13, 'Research submission titled \"Data Mining Using Natural Language Processing\" has been submitted to you, please check it.', '2024-11-18 17:04:07', 'Review Submission', 1),
(22, 51, 'Your research submission titled \"Data Mining Using Natural Language Processing\" has been accepted by your adviser. Check it now!', '2024-11-18 17:04:49', 'Submission Accepted!', 1),
(23, 13, 'Research submission titled \"Document Archiving Management System\" has been submitted to you, please check it.', '2024-11-18 18:42:25', 'Review Submission', 1),
(24, 7, 'Your research submission titled \"Document Archiving Management System\" has been accepted by your adviser. Check it now!', '2024-11-18 18:43:09', 'Submission Accepted!', 1),
(25, 13, 'Research submission titled \"Computer Vision With Convolutional Neural Networks (cnn)\" has been submitted to you, please check it.', '2024-11-22 16:15:36', 'Review Submission', 1),
(26, 51, 'Your adviser has requested a revision on your research submission titled \"Computer Vision With Convolutional Neural Networks (cnn)\". Comments: sadasd', '2024-11-23 11:11:01', 'Submission Revision Requested', 1),
(27, 13, 'Research submission titled \"Computer Vision With Convolutional Neural Networks (cnn)\" has been submitted to you, please check it.', '2024-11-25 15:56:44', 'Review Submission', 1),
(28, 7, 'Your adviser has requested a revision on your research submission titled \"Computer Vision With Convolutional Neural Networks (cnn)\". Comments: revise the info', '2024-11-25 15:59:20', 'Submission Revision Requested', 1),
(29, 60, 'Your account has been approved by your adviser.', '2024-11-25 16:59:26', 'Account Approved!', 1),
(30, 60, 'Your account has been approved by your adviser.', '2024-11-25 16:59:31', 'Account Approved!', 1),
(31, 60, 'Your account has been rejected by your adviser.', '2024-11-25 17:10:10', 'Account Rejected', 1),
(32, 60, 'Your account has been approved by your adviser.', '2024-11-25 17:11:24', 'Account Approved!', 1),
(33, 60, 'Your account has been approved by your adviser.', '2024-11-25 17:29:38', 'Account Approved!', 1),
(34, 65, 'Your account has been approved by your adviser.', '2024-12-01 15:50:20', 'Account Approved!', 1),
(35, 69, 'Research submission titled \"Document Archiving\" has been submitted to you, please check it.', '2024-12-01 17:21:39', 'Review Submission', 1),
(36, 13, 'Research submission titled \"Asdsadasd\" has been submitted to you, please check it.', '2024-12-01 17:22:21', 'Review Submission', 1),
(37, 62, 'Your account has been approved by your adviser.', '2024-12-04 07:31:49', 'Account Approved!', 0),
(38, 74, 'Your account has been approved by your adviser.', '2024-12-04 07:55:08', 'Account Approved!', 0),
(39, 7, 'Your adviser has requested a revision on your research submission titled \"Asdsadasd\". Comments: Revise chapter 6', '2024-12-04 08:21:06', 'Submission Revision Requested', 1),
(40, 7, 'Your adviser has requested a revision on your research submission titled \"Asdsadasd\". Comments: Revise chapter 6', '2024-12-04 08:21:45', 'Submission Revision Requested', 1),
(41, 7, 'Your adviser has requested a revision on your research submission titled \"Asdsadasd\". Comments: Revise chapter 6', '2024-12-04 08:21:48', 'Submission Revision Requested', 1),
(42, 7, 'Your adviser has requested a revision on your research submission titled \"Asdsadasd\". Comments: Revise chapter 6', '2024-12-04 08:31:54', 'Submission Revision Requested', 1),
(43, 72, 'Your account has been approved by your adviser.', '2024-12-04 10:25:10', 'Account Approved!', 0),
(44, 7, 'Your adviser has requested a revision on your research submission titled \"Asdsadasd\". Comments: Revise chapter 6', '2024-12-04 10:50:46', 'Submission Revision Requested', 1),
(45, 75, 'Your account has been approved by your adviser.', '2024-12-04 13:06:59', 'Account Approved!', 1),
(46, 7, 'Your adviser has requested a revision on your research submission titled \"Computer Vision With Convolutional Neural Networks (cnn)\". Comments: revise the chapter 2', '2024-12-04 13:17:59', 'Submission Revision Requested', 1),
(47, 7, 'Your research submission titled \"Computer Vision With Convolutional Neural Networks (cnn)\" has been accepted by your adviser. Check it now!', '2024-12-04 13:28:11', 'Submission Accepted!', 1),
(48, 79, 'Your account has been approved by your adviser.', '2024-12-06 10:06:23', 'Account Approved!', 0),
(49, 78, 'Research submission titled \"Eeee\" has been submitted to you, please check it.', '2024-12-06 10:15:12', 'Review Submission', 0),
(50, 79, 'Your adviser has requested a revision on your research submission titled \"Eeee\". Comments: remove blank pages', '2024-12-06 10:17:26', 'Submission Revision Requested', 0),
(51, 79, 'Your research submission titled \"Eeee\" has been accepted by your adviser. Check it now!', '2024-12-06 10:18:59', 'Submission Accepted!', 0),
(52, 82, 'Your account has been approved by your adviser.', '2024-12-13 16:11:26', 'Account Approved!', 0),
(53, 81, 'Research submission titled \"Data Mining Using Natural Language Processing\" has been submitted to you, please check it.', '2024-12-13 16:13:21', 'Review Submission', 0),
(54, 82, 'Your adviser has requested a revision on your research submission titled \"Data Mining Using Natural Language Processing\". Comments: wrong pdf', '2024-12-13 16:14:23', 'Submission Revision Requested', 0),
(55, 82, 'Your research submission titled \"Data Mining Using Natural Language Processing\" has been accepted by your adviser. Check it now!', '2024-12-13 16:15:45', 'Submission Accepted!', 0),
(56, 13, 'Research submission titled \"Data Mining Using Natural Language Processing\" has been submitted to you, please check it.', '2024-12-13 16:49:53', 'Review Submission', 1),
(57, 7, 'Your adviser has requested a revision on your research submission titled \"Data Mining Using Natural Language Processing\". Comments: asdasd', '2024-12-13 16:50:05', 'Submission Revision Requested', 1),
(58, 7, 'Your research submission titled \"Data Mining Using Natural Language Processing\" has been accepted by your adviser. Check it now!', '2024-12-13 16:52:13', 'Submission Accepted!', 1),
(59, 85, 'Your account has been approved by your adviser.', '2024-12-14 05:54:23', 'Account Approved!', 0),
(60, 84, 'Research submission titled \"Arduino Based Machine Learning\" has been submitted to you, please check it.', '2024-12-14 05:57:32', 'Review Submission', 0),
(61, 85, 'Your adviser has requested a revision on your research submission titled \"Arduino Based Machine Learning\". Comments: chapter 7', '2024-12-14 05:58:36', 'Submission Revision Requested', 0),
(62, 85, 'Your research submission titled \"Arduino Based Machine Learning\" has been accepted by your adviser. Check it now!', '2024-12-14 06:06:35', 'Submission Accepted!', 0);

-- --------------------------------------------------------

--
-- Table structure for table `submission_history`
--

CREATE TABLE `submission_history` (
  `id` int(11) NOT NULL,
  `submission_id` int(11) DEFAULT NULL,
  `research_title` text DEFAULT NULL,
  `author` text DEFAULT NULL,
  `co_authors` text DEFAULT NULL,
  `abstract` mediumtext DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `file_path` text DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `adviser_name` text DEFAULT NULL,
  `faculty_code` int(11) DEFAULT NULL,
  `dateofsubmission` datetime DEFAULT NULL,
  `comments` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `submission_history`
--

INSERT INTO `submission_history` (`id`, `submission_id`, `research_title`, `author`, `co_authors`, `abstract`, `keywords`, `file_path`, `UserID`, `adviser_name`, `faculty_code`, `dateofsubmission`, `comments`) VALUES
(38, 45, 'Management System Leveraging Machine Learning Technologies', 'Mark Anthony Villiones', 'Rel Ace Tenorio, Kaycee Vergara', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.', 'Management System, machine learning', '../Archive/Artificial Intelligence-Machine Learning Explained.pdf', 13, NULL, 54656744, '2024-11-23 02:33:01', NULL),
(41, 51, 'Eeee', 'Eeeee', 'Eeee', 'aeerqrqw', 'management', '../Archive/CHAPTER 2.pdf', 79, 'Johnny Depp', 77771, '2024-12-06 02:17:26', 'remove blank pages'),
(44, 54, 'Arduino Based Machine Learning', 'Rel Ace Tenorio', 'Mark Vil', 'sadaaaaaaaa', 'machine learning', '../Archive/robotics-with-machine-learning.pdf', 85, 'Mark Villiones', 8888, '2024-12-13 21:58:36', 'chapter 7');

-- --------------------------------------------------------

--
-- Table structure for table `submission_status`
--

CREATE TABLE `submission_status` (
  `id` int(11) NOT NULL,
  `submission_id` int(11) DEFAULT NULL,
  `dateofsubmission` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_accepted` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `submission_code` int(11) DEFAULT NULL,
  `comments` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `submission_status`
--

INSERT INTO `submission_status` (`id`, `submission_id`, `dateofsubmission`, `date_accepted`, `status`, `submission_code`, `comments`) VALUES
(31, 39, '2025-01-17 08:19:35', '2025-10-29', 'Locked', 12345671, ''),
(42, 50, '2024-12-04 16:24:08', '2024-11-19', 'Locked', 12345671, ''),
(45, 53, '2024-12-04 17:21:08', '2024-11-23', 'Locked', NULL, NULL),
(48, 56, '2024-12-01 20:21:39', NULL, 'Pending', 11222334, NULL),
(49, 57, '2024-12-04 16:24:08', NULL, 'Revise', 12345671, 'Revise chapter 6'),
(50, 58, '2024-12-01 17:41:20', '2024-12-01', 'Accepted', NULL, NULL),
(51, 59, '2024-12-06 10:35:03', '2024-12-06', 'Locked', 77778888, ''),
(54, 62, '2024-12-14 06:06:35', '2024-12-13', 'Accepted', 4444, '');

-- --------------------------------------------------------

--
-- Table structure for table `useraccount`
--

CREATE TABLE `useraccount` (
  `UserID` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `google_id` int(11) DEFAULT NULL,
  `reset_code` int(11) DEFAULT NULL,
  `is_student` int(11) DEFAULT NULL,
  `is_faculty` int(11) DEFAULT NULL,
  `adviser_code` int(11) DEFAULT NULL,
  `department_code` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_emailverified` int(11) DEFAULT 0,
  `otp` int(11) DEFAULT NULL,
  `is_verified` int(11) DEFAULT 0,
  `otp_timeout` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `useraccount`
--

INSERT INTO `useraccount` (`UserID`, `email`, `password`, `google_id`, `reset_code`, `is_student`, `is_faculty`, `adviser_code`, `department_code`, `status`, `creation_date`, `is_emailverified`, `otp`, `is_verified`, `otp_timeout`) VALUES
(7, 'markvil64@gmail.com', '$2y$10$pplHVQXpUUnXXR0ecPnUR.a25XKpOhCQMIbdjqtFpma.hLGo48Sx6', NULL, NULL, 1, 0, NULL, NULL, 'Active', '2024-12-04 12:05:08', 1, 627047, 1, NULL),
(13, 'QB202100648@wmsu.edu.ph', '$2y$10$9YqLB7EXrhhJhz89IdJs5./pskfD7U71tWud7gBAGDDC62H6hTyLC', NULL, NULL, 0, 1, 12345671, 12345678, 'Active', '2024-12-04 16:24:08', 1, 880943, 1, NULL),
(61, 'jbatuigas60@gmail.com', '$2y$10$BCvLcV3eFVkEc6JK9aZ8ouLflqpuHBhNThNTiK/fhx9SVvntNT8uO', NULL, NULL, 0, 1, 12345679, 12345678, 'Active', '2024-12-04 13:53:18', 1, 799723, 1, NULL),
(64, '09976317588', '$2y$10$mFFlXwZvlAI8FrrQp4Yqkuf79kkNd4mcrVPw6yeBq9zXRVCgJexrm', NULL, NULL, 1, 0, NULL, NULL, NULL, '2024-12-01 14:49:35', 0, NULL, 0, NULL),
(65, 'qb202100652@wmsu.edu.ph', '$2y$10$woIFVTUbTcnvxH88vDpZ2uUe8w7ujw6Ewjpa8fNkcJo4HTlJvOUGi', NULL, NULL, 1, 0, NULL, NULL, 'Active', '2025-01-20 08:16:21', 1, 235737, 1, NULL),
(66, 'qb202100475@wmsu.edu.ph', '$2y$10$p5FLXRXvNHT64092JfuYReaJrTTiEbeavNlRXXaV0dtGDMur4p6u2', NULL, NULL, 1, 0, NULL, NULL, 'Waiting', '2024-12-01 16:43:09', 0, NULL, 0, NULL),
(67, 'vergara24@gmail.com', '$2y$10$Av.8bKn50CQNEq754xVlPevKbrpP1igWCA4caIuX/rTeEjyFstL6i', NULL, NULL, 1, 0, NULL, NULL, NULL, '2024-12-01 15:55:33', 0, NULL, 0, NULL),
(68, 'qb202100653@wmsu.edu.ph', '$2y$10$zpY/iOoRvkZrFSsPdH3gZuNCfxqJy1ZxdSL5ISY8lHMbeNQ0aXhxi', NULL, NULL, 0, 1, 12345678, 12345678, 'Waiting', '2024-12-03 08:27:11', 0, NULL, 0, NULL),
(69, 'qb202100655@wmsu.edu.ph', '$2y$10$NPL1ARjQfv30E/to.LOnQe4UA2UxkZi3mSpBUjXmpJvJVCFycikCG', NULL, NULL, 0, 1, 11222334, 12345678, 'Active', '2024-12-03 08:27:11', 0, NULL, 1, NULL),
(70, 'qb202100656@wmsu.edu.ph', '$2y$10$A0ECU.TmCc.u7algS5ZQVeH1wEbtENeVylfDGUY6A9cLdhUB1Fc5C', NULL, NULL, 0, 1, 11223344, 12345678, 'Waiting', '2024-12-03 08:27:11', 0, NULL, 0, NULL),
(71, 'yugiohsantillan@gmail.com', '$2y$10$QjT3mv5iA4B9geUVjsZWTeJ7Qg2WNHj6gaf3gRNnEwDR9BnwIuaRK', NULL, NULL, 1, 0, NULL, NULL, NULL, '2024-12-02 20:27:08', 0, NULL, 0, NULL),
(73, 'qb202100984@wmsu.edu.ph', '$2y$10$Hft4rUK3ajuy4pLp0J/eEuAaYA4CnNnkT0epWyTHw8LA8ynxZcJR.', NULL, NULL, 1, 0, NULL, NULL, NULL, '2024-12-04 07:18:19', 0, NULL, 0, NULL),
(77, 'QB202104510@wmsu.edu.ph', '$2y$10$X36.jSMBQ26f1Xn88hYSrubwTLozfpK4wbOXbNqXmCXLb92Kh5eLS', NULL, NULL, 1, 0, NULL, NULL, NULL, '2024-12-06 05:47:09', 1, 676811, 0, NULL),
(78, 'johnny@gmail.com', '$2y$10$bo1QHqSuUdf46.fYbsIO7O38wbmB1MLPivmcU3kWuswXMsqmeEOAm', NULL, NULL, 0, 1, 77778888, 77771, 'Active', '2024-12-06 09:57:50', 0, NULL, 1, NULL),
(79, 'student@gmail.com', '$2y$10$0Iq7/w7iWdIiE1wZHZUKW.vfPnSmGxbzRvaE41pcY9.FyjisW23/O', NULL, NULL, 1, 0, NULL, NULL, 'Active', '2024-12-06 10:06:23', 0, NULL, 1, NULL),
(80, 'markvil644@gmail.com', '$2y$10$bS0Q13RHAy7IFPVF4EMQ6.ZdBsy.QfvHF/XrqZ2KEO//geLDhoM02', NULL, NULL, 0, 1, 12345, 12345678, 'Active', '2024-12-12 18:11:20', 0, NULL, 1, NULL),
(84, 'qb202101983@wmsu.edu.ph', '$2y$10$k2dAF38Xh6piUZ7AGvNAeeJoE9nhMNz7jmEhL8pB/EzmECB8AnZlu', NULL, NULL, 0, 1, 4444, 8888, 'Active', '2024-12-14 05:47:59', 0, NULL, 1, NULL),
(85, 'markvil327@gmail.com', '$2y$10$NCcqxCOY56cdNW7yn0ux1..EgLjhePJBXqCgCGEi9t0EmskxUo0k6', NULL, NULL, 1, 0, NULL, NULL, 'Active', '2024-12-14 05:54:23', 0, NULL, 1, NULL),
(86, 'lm0503030@gmail.com', '$2y$10$7eWTSwKgj1zzylpmm.XqMu5ExKGW7qPUYvhRrh69s9ougzeJSKlgG', NULL, NULL, 1, 0, NULL, NULL, NULL, '2025-01-10 03:59:02', 0, NULL, 0, NULL),
(89, 'ceed.lorenzo@wmsu.edu.ph', '$2y$10$gFrJS4bgjf3YzRkoqKb8cO8Zt.c4h8ewfqmDNc9F.tX5I4GjInaly', NULL, NULL, 0, 1, 62111468, 12345678, 'Active', '2025-01-15 05:48:20', 1, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `userinteractions`
--

CREATE TABLE `userinteractions` (
  `id` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `research_id` int(11) DEFAULT NULL,
  `college_code` int(11) DEFAULT NULL,
  `department_code` int(11) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `userinteractions`
--

INSERT INTO `userinteractions` (`id`, `UserID`, `research_id`, `college_code`, `department_code`, `time`) VALUES
(15, 13, 20, 86945396, 54656744, '2024-09-29 15:10:59'),
(16, 13, 20, 86945396, 54656744, '2024-09-29 15:14:01'),
(17, 13, 20, 86945396, 54656744, '2024-09-29 15:17:11'),
(18, 13, 20, 86945396, 54656744, '2024-09-29 15:17:11'),
(19, 13, 30, 86945396, 54656744, '2024-09-29 15:17:51'),
(20, 13, 30, 86945396, 54656744, '2024-09-29 15:18:07'),
(21, 13, 30, 86945396, 54656744, '2024-09-29 15:26:27'),
(22, 13, 30, 86945396, 54656744, '2024-09-29 15:26:28'),
(23, 13, 30, 86945396, 54656744, '2024-09-29 15:26:47'),
(24, 13, 30, 86945396, 54656744, '2024-09-29 15:26:47'),
(25, 7, 20, 86945396, 54656744, '2024-09-29 15:53:16'),
(26, 13, 30, 86945396, 54656744, '2024-09-29 16:04:08'),
(27, 7, 33, 86945396, 54656744, '2024-09-29 16:44:16'),
(28, 14, 33, 86945396, 54656744, '2024-09-29 17:18:06'),
(29, 14, 33, 86945396, 54656744, '2024-09-29 17:18:22'),
(30, 14, 33, 86945396, 54656744, '2024-09-29 17:18:22'),
(31, 14, 20, 86945396, 54656744, '2024-09-29 17:18:54'),
(32, 14, 20, 86945396, 54656744, '2024-09-29 17:21:21'),
(33, 14, 20, 86945396, 54656744, '2024-09-29 17:21:21'),
(34, 13, 34, 86945396, 54656744, '2024-09-30 16:51:46'),
(35, 7, 36, 86945396, 54656744, '2024-10-01 06:10:15'),
(36, 7, 33, 86945396, 54656744, '2024-10-01 06:11:01'),
(37, 7, 36, 86945396, 54656744, '2024-10-01 06:11:42'),
(38, 7, 36, 86945396, 54656744, '2024-10-01 06:11:42'),
(39, 13, 30, 86945396, 54656744, '2024-10-01 06:27:43'),
(40, 13, 30, 86945396, 54656744, '2024-10-01 06:27:43'),
(41, 7, 20, 86945396, 54656744, '2024-10-15 13:59:53'),
(42, 14, 20, 86945396, 54656744, '2024-10-20 13:42:57'),
(43, 13, 30, 86945396, 54656744, '2024-10-20 13:46:23'),
(44, 13, 30, 86945396, 54656744, '2024-10-20 13:47:46'),
(45, 13, 33, 86945396, 54656744, '2024-10-20 14:48:15'),
(46, 13, 33, 86945396, 54656744, '2024-10-20 14:49:49'),
(47, 7, 20, 86945396, 54656744, '2024-10-20 14:57:08'),
(48, 14, 20, 86945396, 54656744, '2024-10-20 15:03:34'),
(49, 13, 33, 86945396, 54656744, '2024-10-20 15:06:45'),
(50, 13, 33, 86945396, 54656744, '2024-10-20 15:47:49'),
(51, 13, 33, 86945396, 54656744, '2024-10-20 16:12:24'),
(52, 7, 20, 86945396, 54656744, '2024-10-20 16:16:41'),
(53, 7, 33, 86945396, 54656744, '2024-10-20 16:16:45'),
(54, 13, 33, 86945396, 54656744, '2024-10-20 16:23:28'),
(55, 13, 33, 86945396, 54656744, '2024-10-20 16:28:14'),
(56, 13, 33, 86945396, 54656744, '2024-10-20 16:35:08'),
(57, 7, 33, 86945396, 54656744, '2024-10-22 16:05:03'),
(58, 7, 20, 86945396, 54656744, '2024-10-22 16:05:55'),
(59, 7, 20, 86945396, 54656744, '2024-10-22 16:05:55'),
(60, 7, 20, 86945396, 54656744, '2024-10-22 16:48:49'),
(61, 7, 20, 86945396, 54656744, '2024-10-22 16:48:49'),
(62, 7, 20, 86945396, 54656744, '2024-10-22 16:52:59'),
(63, 7, 20, 86945396, 54656744, '2024-10-22 17:01:33'),
(64, 7, 20, 86945396, 54656744, '2024-10-22 17:07:43'),
(65, 7, 20, 86945396, 54656744, '2024-10-22 17:07:43'),
(66, 7, 20, 86945396, 54656744, '2024-10-22 17:07:49'),
(67, 7, 20, 86945396, 54656744, '2024-10-22 17:07:49'),
(68, 7, 20, 86945396, 54656744, '2024-10-22 17:07:55'),
(69, 13, 20, 86945396, 54656744, '2024-10-22 17:10:50'),
(70, 7, 38, 86945396, 54656744, '2024-10-28 14:38:13'),
(71, 7, 38, 86945396, 54656744, '2024-10-28 14:38:31'),
(72, 7, 38, 86945396, 54656744, '2024-10-28 14:38:32'),
(73, 44, 38, 86945396, 54656744, '2024-10-28 14:46:43'),
(74, 46, 38, 86945396, 54656744, '2024-10-28 14:47:31'),
(75, 13, 38, 86945396, 54656744, '2024-10-28 14:48:08'),
(76, 22, 38, 86945396, 54656744, '2024-10-28 14:48:31'),
(77, 7, 38, 86945396, 54656744, '2024-10-28 14:48:57'),
(78, 7, 38, 86945396, 54656744, '2024-11-02 15:56:27'),
(79, 7, 38, 86945396, 54656744, '2024-11-02 15:56:27'),
(80, 49, 38, 86945396, 54656744, '2024-11-02 16:02:15'),
(81, 7, 39, 86945396, 54656744, '2024-11-02 16:02:58'),
(82, 13, 38, 86945396, 54656744, '2024-11-02 16:07:24'),
(83, 50, 39, 86945396, 54656744, '2024-11-02 16:11:25'),
(84, 50, 39, 86945396, 54656744, '2024-11-02 16:19:00'),
(85, 7, 38, 86945396, 54656744, '2024-11-02 16:23:58'),
(86, 7, 38, 86945396, 54656744, '2024-11-02 16:42:29'),
(87, 51, 38, 86945396, 54656744, '2024-11-02 16:50:31'),
(88, 51, 38, 86945396, 54656744, '2024-11-02 16:52:57'),
(89, 51, 38, 86945396, 54656744, '2024-11-02 16:52:57'),
(90, 51, 38, 86945396, 54656744, '2024-11-02 16:58:28'),
(91, 51, 38, 86945396, 54656744, '2024-11-02 16:58:28'),
(92, 51, 38, 86945396, 54656744, '2024-11-02 16:58:35'),
(93, 51, 38, 86945396, 54656744, '2024-11-02 16:58:35'),
(94, 51, 38, 86945396, 54656744, '2024-11-02 16:59:03'),
(95, 51, 38, 86945396, 54656744, '2024-11-02 16:59:03'),
(96, 51, 38, 86945396, 54656744, '2024-11-02 16:59:13'),
(97, 51, 38, 86945396, 54656744, '2024-11-02 16:59:14'),
(98, 51, 38, 86945396, 54656744, '2024-11-02 16:59:45'),
(99, 51, 38, 86945396, 54656744, '2024-11-02 16:59:45'),
(100, 51, 39, 86945396, 54656744, '2024-11-02 17:04:38'),
(101, 7, 38, 86945396, 54656744, '2024-11-06 13:52:37'),
(102, 7, 38, 86945396, 54656744, '2024-11-06 13:52:37'),
(103, 7, 39, 86945396, 54656744, '2024-11-06 13:52:41'),
(104, 7, 39, 86945396, 54656744, '2024-11-06 13:52:42'),
(105, 7, 39, 86945396, 54656744, '2024-11-06 13:52:45'),
(106, 7, 39, 86945396, 54656744, '2024-11-06 13:52:45'),
(107, 51, 39, 86945396, 54656744, '2024-11-06 13:53:47'),
(108, 51, 38, 86945396, 54656744, '2024-11-06 13:53:52'),
(109, 51, 38, 86945396, 54656744, '2024-11-06 13:58:29'),
(110, 51, 38, 86945396, 54656744, '2024-11-06 13:58:29'),
(111, 51, 38, 86945396, 54656744, '2024-11-06 13:59:53'),
(112, 13, 38, 86945396, 54656744, '2024-11-06 14:18:05'),
(113, 51, 38, 86945396, 54656744, '2024-11-06 14:23:54'),
(114, 51, 38, 86945396, 54656744, '2024-11-06 14:23:54'),
(115, 52, 39, 86945396, 54656744, '2024-11-06 14:50:16'),
(116, 7, 39, 86945396, 54656744, '2024-11-06 14:51:00'),
(117, 7, 39, 86945396, 54656744, '2024-11-06 14:51:00'),
(118, 53, 39, 86945396, 54656744, '2024-11-06 14:53:01'),
(119, 53, 38, 86945396, 54656744, '2024-11-06 14:53:06'),
(120, 7, 39, 86945396, 54656744, '2024-11-18 16:58:19'),
(121, 7, 39, 86945396, 54656744, '2024-11-18 16:58:19'),
(122, 7, 38, 86945396, 54656744, '2024-11-18 16:58:31'),
(123, 7, 38, 86945396, 54656744, '2024-11-18 16:58:31'),
(124, 7, 49, 86945396, 54656744, '2024-11-18 17:05:35'),
(125, 7, 49, 86945396, 54656744, '2024-11-18 17:36:42'),
(126, 13, 38, 86945396, 54656744, '2024-11-18 18:34:02'),
(127, 13, 49, 86945396, 54656744, '2024-11-18 18:36:17'),
(128, 13, 38, 86945396, 54656744, '2024-11-18 18:37:05'),
(129, 13, 38, 86945396, 54656744, '2024-11-18 18:37:40'),
(130, 13, 49, 86945396, 54656744, '2024-11-18 18:37:43'),
(131, 13, 38, 86945396, 54656744, '2024-11-18 18:38:40'),
(132, 13, 39, 86945396, 54656744, '2024-11-18 18:39:37'),
(133, 13, 50, 86945396, 54656744, '2024-11-18 18:43:38'),
(134, 7, 49, 86945396, 54656744, '2024-11-18 18:46:07'),
(135, 7, 50, 86945396, 54656744, '2024-11-18 18:46:12'),
(136, 7, 50, 86945396, 54656744, '2024-11-18 18:46:58'),
(137, 7, 50, 86945396, 54656744, '2024-11-18 18:46:59'),
(138, 7, 50, 86945396, 54656744, '2024-11-18 18:51:17'),
(139, 7, 50, 86945396, 54656744, '2024-11-18 18:51:17'),
(140, 13, 50, 86945396, 54656744, '2024-11-18 18:54:43'),
(141, 7, 39, 86945396, 54656744, '2024-11-18 19:07:29'),
(142, 7, 51, 86945396, 54656744, '2024-11-18 19:08:03'),
(143, 7, 53, 86945396, 54656744, '2024-11-22 18:38:34'),
(144, 7, 53, 86945396, 54656744, '2024-11-23 12:02:52'),
(145, 7, 53, 86945396, 54656744, '2024-11-23 12:04:12'),
(146, 7, 53, 86945396, 54656744, '2024-11-23 12:04:12'),
(147, 7, 53, 86945396, 54656744, '2024-11-23 12:21:05'),
(148, 7, 53, 86945396, 54656744, '2024-11-23 12:21:05'),
(149, 7, 53, 86945396, 54656744, '2024-11-23 12:25:07'),
(150, 7, 53, 86945396, 54656744, '2024-11-23 12:25:07'),
(151, 7, 39, 86945396, 54656744, '2024-11-23 13:29:35'),
(152, 55, 39, 86945396, 54656744, '2024-11-23 13:30:19'),
(153, 55, 49, 86945396, 54656744, '2024-11-23 13:54:04'),
(154, 7, 54, 86945396, 23523423, '2024-11-23 17:28:26'),
(155, 13, 50, 86945396, 54656744, '2024-11-24 12:40:33'),
(156, 13, 50, 86945396, 54656744, '2024-11-24 15:42:47'),
(157, 13, 39, 86945396, 54656744, '2024-11-24 15:51:58'),
(158, 13, 50, 86945396, 54656744, '2024-11-24 15:52:07'),
(159, 60, 53, 86945396, 54656744, '2024-11-25 17:13:27'),
(160, 60, 53, 86945396, 54656744, '2024-11-25 17:13:37'),
(161, 60, 53, 86945396, 54656744, '2024-11-25 17:13:37'),
(162, 7, 50, 86945396, 54656744, '2024-11-30 16:50:30'),
(163, 7, 50, 86945396, 54656744, '2024-11-30 16:50:59'),
(164, 7, 53, 86945396, 54656744, '2024-11-30 16:52:36'),
(165, 7, 39, 86945396, 54656744, '2024-11-30 16:52:51'),
(166, 7, 53, 86945396, 54656744, '2024-11-30 16:52:58'),
(167, 7, 50, 86945396, 54656744, '2024-11-30 16:53:03'),
(168, 7, 39, 86945396, 54656744, '2024-11-30 16:53:06'),
(169, 7, 53, 86945396, 54656744, '2024-11-30 16:53:30'),
(170, 7, 39, 86945396, 54656744, '2024-11-30 16:53:36'),
(171, 7, 50, 86945396, 54656744, '2024-11-30 16:53:56'),
(172, 7, 50, 86945396, 54656744, '2024-11-30 16:53:57'),
(173, 7, 53, 86945396, 54656744, '2024-11-30 16:56:13'),
(174, 7, 53, 86945396, 54656744, '2024-11-30 17:11:19'),
(175, 7, 53, 86945396, 54656744, '2024-11-30 17:20:08'),
(176, 7, 50, 86945396, 54656744, '2024-11-30 17:20:55'),
(177, 7, 53, 86945396, 54656744, '2024-11-30 17:21:44'),
(178, 7, 50, 86945396, 54656744, '2024-11-30 17:43:23'),
(179, 7, 50, 86945396, 54656744, '2024-11-30 17:43:23'),
(180, 7, 53, 86945396, 54656744, '2024-11-30 17:48:07'),
(181, 7, 53, 86945396, 54656744, '2024-11-30 17:48:07'),
(182, 7, 53, 86945396, 54656744, '2024-11-30 18:01:22'),
(183, 7, 53, 86945396, 54656744, '2024-11-30 18:01:22'),
(184, 7, 53, 86945396, 54656744, '2024-11-30 18:13:37'),
(185, 7, 53, 86945396, 54656744, '2024-11-30 18:13:37'),
(186, 62, 53, 86945396, 54656744, '2024-12-01 11:34:24'),
(187, 7, 58, 86945396, 54656744, '2024-12-02 20:18:17'),
(188, 7, 58, 86945396, 12345678, '2024-12-03 23:06:35'),
(189, 72, 58, 86945396, 12345678, '2024-12-04 09:35:34'),
(190, 69, 58, 86945396, 12345678, '2024-12-04 09:58:56'),
(191, 69, 53, 86945396, 12345678, '2024-12-04 09:59:41'),
(192, 73, 58, 86945396, 12345678, '2024-12-04 10:19:49'),
(193, 75, 58, 86945396, 12345678, '2024-12-04 16:04:12'),
(194, 75, 58, 86945396, 12345678, '2024-12-04 16:04:47'),
(195, 75, 58, 86945396, 12345678, '2024-12-04 16:04:47'),
(196, 75, 58, 86945396, 12345678, '2024-12-04 16:04:47'),
(197, 7, 55, 86945396, 12345678, '2024-12-04 16:28:55'),
(198, 7, 55, 86945396, 12345678, '2024-12-04 16:29:32'),
(199, 7, 55, 86945396, 12345678, '2024-12-04 16:29:32'),
(200, 7, 55, 86945396, 12345678, '2024-12-04 16:29:32'),
(201, 7, 50, 86945396, 12345678, '2024-12-04 20:21:47'),
(202, 7, 53, 86945396, 12345678, '2024-12-04 20:22:11'),
(203, 77, 58, 86945396, 12345678, '2024-12-06 09:37:10'),
(204, 77, 58, 86945396, 12345678, '2024-12-06 12:15:33'),
(205, 77, 58, 86945396, 12345678, '2024-12-06 12:15:34'),
(206, 13, 50, 86945396, 12345678, '2024-12-06 12:17:10'),
(207, 13, 50, 86945396, 12345678, '2024-12-06 12:17:10'),
(208, 79, 53, 86945396, 12345678, '2024-12-06 13:01:01'),
(209, 79, 59, 7777, 77771, '2024-12-06 13:34:00'),
(210, 78, 59, 7777, 77771, '2024-12-06 13:34:21'),
(211, 7, 59, 7777, 77771, '2024-12-06 13:35:34'),
(212, 82, 60, 2134234, 23435, '2024-12-13 19:19:09'),
(213, 81, 60, 2134234, 23435, '2024-12-13 19:19:44'),
(214, 81, 60, 2134234, 23435, '2024-12-13 19:21:45'),
(215, 81, 60, 2134234, 23435, '2024-12-13 19:21:45'),
(216, 7, 60, 2134234, 23435, '2024-12-13 19:23:18'),
(217, 82, 60, 2134234, 23435, '2024-12-13 19:31:04'),
(218, 82, 60, 2134234, 23435, '2024-12-13 19:31:04'),
(219, 7, 59, 7777, 77771, '2024-12-13 19:48:50'),
(220, 7, 59, 7777, 77771, '2024-12-13 19:48:50'),
(221, 7, 59, 7777, 77771, '2024-12-13 19:48:50'),
(222, 13, 61, 86945396, 12345678, '2024-12-13 19:57:42'),
(223, 7, 59, 7777, 77771, '2024-12-13 20:00:07'),
(224, 7, 59, 7777, 77771, '2024-12-13 20:00:07'),
(225, 7, 59, 7777, 77771, '2024-12-13 20:00:07'),
(226, 13, 50, 86945396, 12345678, '2024-12-14 07:24:21'),
(227, 13, 50, 86945396, 12345678, '2024-12-14 07:24:21'),
(228, 85, 53, 86945396, 12345678, '2024-12-14 08:51:01'),
(229, 85, 53, 86945396, 12345678, '2024-12-14 08:52:06'),
(230, 85, 53, 86945396, 12345678, '2024-12-14 08:52:06'),
(231, 85, 50, 86945396, 12345678, '2024-12-14 09:14:39'),
(232, 85, 62, 12345678, 8888, '2024-12-14 09:15:03'),
(233, 7, 62, 12345678, 8888, '2025-01-09 17:20:47'),
(234, 86, 62, 12345678, 8888, '2025-01-10 07:14:07'),
(235, 86, 58, 86945396, 12345678, '2025-01-10 07:20:25'),
(236, 86, 58, 86945396, 12345678, '2025-01-10 07:22:25'),
(237, 86, 58, 86945396, 12345678, '2025-01-10 07:22:25'),
(238, 7, 62, 12345678, 8888, '2025-01-10 07:35:47'),
(239, 7, 62, 12345678, 8888, '2025-01-10 07:35:47'),
(240, 7, 62, 12345678, 8888, '2025-01-10 07:35:52'),
(241, 13, 50, 86945396, 12345678, '2025-01-13 06:37:25'),
(242, 13, 50, 86945396, 12345678, '2025-01-13 06:37:25'),
(243, 65, 39, 86945396, 12345678, '2025-03-07 12:34:03');

-- --------------------------------------------------------

--
-- Table structure for table `userprofile`
--

CREATE TABLE `userprofile` (
  `UserID` int(11) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT NULL,
  `college` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `profile_path` varchar(255) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `id_number` varchar(11) DEFAULT NULL,
  `cor` varchar(255) DEFAULT NULL,
  `advisor_code` int(11) DEFAULT NULL,
  `college_code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `userprofile`
--

INSERT INTO `userprofile` (`UserID`, `first_name`, `last_name`, `department`, `college`, `id`, `profile_path`, `middle_name`, `id_number`, `cor`, `advisor_code`, `college_code`) VALUES
(13, 'Mark Anthony ', 'Villiones', NULL, NULL, 6, '../../profile-photo/istockphoto-538665020-612x612.jpg', 'Nocalan', '202100648', NULL, NULL, NULL),
(7, 'Mark Anthony', 'Villiones', 'Information Technology', 'College of Computing Studies', 26, '../profile-photo/67448f6266a06_chill-guy-memes-have-flooded-social-media-241142207-16x9_0.jpg', 'Nocalan', '202100648', 'cor/CNN_7.pdf', 12345671, 86945396),
(61, 'Mark Anthony ', 'Villiones', NULL, NULL, 36, '../../profile-photo/photo1.png', 'Nocalan', '2147483647', NULL, NULL, NULL),
(65, 'Kayce', 'Vergara', 'Information Technology', 'College of Computing Studies', 37, '../profile-photo/674c83038bd23_site_logo.png', 'Ramos', '652', 'cor/DAMSID, SHARMILA_DATA PRIVACY ACT.pdf', 12345671, 86945396),
(66, 'Boush', 'Alkie', 'Information Technology', 'College of Computing Studies', 38, '../profile-photo/674c84b42e030_Background.jpg', 'Salian', '475', 'cor/DAMSID, SHARMILA_DATA PRIVACY ACT_1.pdf', 12345671, 86945396),
(68, 'Chang', 'Alkie', NULL, NULL, 39, '', 'Ramos', '9873', NULL, NULL, NULL),
(69, 'Chang', 'Alkie', NULL, NULL, 40, '../../profile-photo/site_logo.png', 'Ramos', '9873', NULL, NULL, NULL),
(70, 'Chang', 'Alkie', NULL, NULL, 41, '', 'Ramos', '9873', NULL, NULL, NULL),
(71, 'Ass', 'Hole', 'Computer Science', 'College of Computing Studies', 42, '../profile-photo/674e1852ea923_Kanojo, Okarishimasu - Chapter 95_ What I Can Do With My Girlfriend 4 - 19.jpg', 'Crack', '715', NULL, NULL, 86945396),
(77, 'Sayn', 'Aiyub', 'Information Technology', 'College of Computing Studies', 48, '../profile-photo/67528f4f1e2b1_1732541035912.jpg', 'Amil', '202104510', NULL, NULL, 86945396),
(78, 'Johnny', 'Depp', NULL, NULL, 49, '../../profile-photo/Screenshot 2024-02-25 141951.png', 'Eeee', '202124', NULL, NULL, NULL),
(79, 'Mark', 'Villiones', 'Mickey Mouse', 'Collegen Of Medicine', 50, '../profile-photo/6752cb45d7929_bgg.jpg', 'Nocalan', '202100648', 'cor/COLOR COSMETICS.pdf', 77778888, 7777),
(80, 'Teacher', 'Teacher', NULL, NULL, 51, '../../profile-photo/beluga2.jpg', '', '12345678', NULL, NULL, NULL),
(84, 'Mark', 'Villiones', NULL, NULL, 55, '../../profile-photo/images (1).jpeg', 'Nocalan', '2178323', NULL, NULL, NULL),
(85, 'Rel', 'Tenorio', 'Computer Engineering', 'College Of Engineering', 56, '../profile-photo/675d1ca59e41d_images (1).jpeg', '', '7324324', 'cor/COR-202420251_3.pdf', 4444, 12345678),
(86, 'Luna', 'Blyte', 'Computer Science', 'College of Computing Studies', 57, '../profile-photo/6780a160bc842_Screenshot_20250110-121417.jpg', '', '111121', NULL, NULL, 86945396),
(89, 'Wmsu', 'Rdec', NULL, NULL, 58, '../../profile-photo/473009324_620167227328916_2716267975931664951_n.jpg', 'Bsit', '121312', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `views`
--

CREATE TABLE `views` (
  `id` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `research_id` int(11) DEFAULT NULL,
  `college_code` int(11) DEFAULT NULL,
  `department_code` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `views`
--

INSERT INTO `views` (`id`, `UserID`, `research_id`, `college_code`, `department_code`, `duration`, `viewed_at`) VALUES
(2, 51, 38, 86945396, 54656744, 10, '2024-11-06 15:13:47'),
(4, 51, 39, 86945396, 54656744, 10, '2024-11-06 15:13:47'),
(5, 13, 38, 86945396, 54656744, 10, '2024-11-06 15:13:47'),
(6, 52, 39, 86945396, 54656744, 10, '2024-11-06 15:13:47'),
(7, 53, 38, 86945396, 54656744, 10, '2024-11-06 15:13:47'),
(8, 7, 49, 86945396, 54656744, 10, '2024-11-18 17:05:45'),
(9, 13, 49, 86945396, 54656744, 10, '2024-11-18 18:36:28'),
(10, 13, 50, 86945396, 54656744, 10, '2024-11-18 18:43:49'),
(11, 7, 51, 86945396, 54656744, 10, '2024-11-18 19:08:15'),
(12, 7, 53, 86945396, 54656744, 10, '2024-11-22 18:38:44'),
(13, 55, 39, 86945396, 54656744, 10, '2024-11-23 13:30:30'),
(14, 55, 49, 86945396, 54656744, 10, '2024-11-23 13:54:15'),
(16, 7, 58, 86945396, 54656744, 10, '2024-12-02 17:18:28'),
(17, 72, 58, 86945396, 12345678, 10, '2024-12-04 06:35:46'),
(18, 73, 58, 86945396, 12345678, 10, '2024-12-04 07:20:00'),
(19, 75, 58, 86945396, 12345678, 10, '2024-12-04 13:04:23'),
(20, 77, 58, 86945396, 12345678, 10, '2024-12-06 06:37:22'),
(21, 79, 53, 86945396, 12345678, 10, '2024-12-06 10:01:12'),
(22, 7, 59, 7777, 77771, 10, '2024-12-06 10:35:45'),
(24, 85, 53, 86945396, 12345678, 10, '2024-12-14 05:52:17'),
(25, 85, 39, 86945396, 12345678, 10, '2024-12-14 06:14:23'),
(26, 7, 62, 12345678, 8888, 10, '2025-01-09 14:20:58'),
(27, 86, 58, 86945396, 12345678, 10, '2025-01-10 04:20:36'),
(28, 65, 39, 86945396, 12345678, 10, '2025-03-07 09:34:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `archive`
--
ALTER TABLE `archive`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `fk_archive_faculty` (`faculty_code`);

--
-- Indexes for table `citation`
--
ALTER TABLE `citation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colleges`
--
ALTER TABLE `colleges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `college_code` (`college_code`),
  ADD UNIQUE KEY `unique_college_code` (`college_code`);

--
-- Indexes for table `college_account`
--
ALTER TABLE `college_account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_college_code` (`college_code`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD UNIQUE KEY `department_code` (`department_code`),
  ADD KEY `college_code` (`college_code`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submission_history`
--
ALTER TABLE `submission_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submission_id` (`submission_id`);

--
-- Indexes for table `submission_status`
--
ALTER TABLE `submission_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submission_id` (`submission_id`);

--
-- Indexes for table `useraccount`
--
ALTER TABLE `useraccount`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `fk_department_code` (`department_code`);

--
-- Indexes for table `userinteractions`
--
ALTER TABLE `userinteractions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userprofile`
--
ALTER TABLE `userprofile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `views`
--
ALTER TABLE `views`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `archive`
--
ALTER TABLE `archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `citation`
--
ALTER TABLE `citation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `colleges`
--
ALTER TABLE `colleges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `college_account`
--
ALTER TABLE `college_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `submission_history`
--
ALTER TABLE `submission_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `submission_status`
--
ALTER TABLE `submission_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `useraccount`
--
ALTER TABLE `useraccount`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `userinteractions`
--
ALTER TABLE `userinteractions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;

--
-- AUTO_INCREMENT for table `userprofile`
--
ALTER TABLE `userprofile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `views`
--
ALTER TABLE `views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `archive`
--
ALTER TABLE `archive`
  ADD CONSTRAINT `archive_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `useraccount` (`UserID`),
  ADD CONSTRAINT `fk_archive_faculty` FOREIGN KEY (`faculty_code`) REFERENCES `departments` (`department_code`);

--
-- Constraints for table `college_account`
--
ALTER TABLE `college_account`
  ADD CONSTRAINT `college_account_ibfk_1` FOREIGN KEY (`college_code`) REFERENCES `colleges` (`college_code`),
  ADD CONSTRAINT `fk_college_code` FOREIGN KEY (`college_code`) REFERENCES `colleges` (`college_code`);

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`college_code`) REFERENCES `colleges` (`college_code`);

--
-- Constraints for table `submission_history`
--
ALTER TABLE `submission_history`
  ADD CONSTRAINT `submission_history_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submission_status` (`id`);

--
-- Constraints for table `submission_status`
--
ALTER TABLE `submission_status`
  ADD CONSTRAINT `submission_status_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `archive` (`id`);

--
-- Constraints for table `useraccount`
--
ALTER TABLE `useraccount`
  ADD CONSTRAINT `fk_department_code` FOREIGN KEY (`department_code`) REFERENCES `departments` (`department_code`);

--
-- Constraints for table `userprofile`
--
ALTER TABLE `userprofile`
  ADD CONSTRAINT `userprofile_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `useraccount` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
