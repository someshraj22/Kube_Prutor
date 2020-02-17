-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 02, 2015 at 08:13 PM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `its`
--
CREATE DATABASE IF NOT EXISTS `its` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `its`;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `id` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `section` varchar(10) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_role` tinyint(4) DEFAULT NULL,
  `auth_type` tinyint(4) DEFAULT NULL,
  `roll` varchar(20) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `hash` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

CREATE TABLE IF NOT EXISTS `assignment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `question` int(11) DEFAULT NULL,
  `problem_id` int(11) DEFAULT NULL,
  `max_marks` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `comments` text NOT NULL,
  `evaluator` varchar(50) DEFAULT NULL,
  `grading_time` timestamp NULL DEFAULT NULL,
  `is_submitted` tinyint(1) NOT NULL DEFAULT '0',
  `submission` int(11) DEFAULT NULL,
  `regrade_requested` int(1) DEFAULT '0',
  `regrade_reason` text DEFAULT NULL,
  `regrade_reqtime` timestamp DEFAULT '1970-01-01 00:00:01',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `problem_id` (`problem_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=226407 ;

-- --------------------------------------------------------

--
-- Table structure for table `attempt`
--

CREATE TABLE IF NOT EXISTS `attempt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `evaluate_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `success` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `code_id` (`code_id`),
  KEY `assignment_id` (`assignment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=207459 ;

-- --------------------------------------------------------

--
-- Table structure for table `audit_admin`
--

CREATE TABLE IF NOT EXISTS `audit_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` varchar(50) NOT NULL,
  `target` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=701 ;

-- --------------------------------------------------------

--
-- Table structure for table `audit_student`
--

CREATE TABLE IF NOT EXISTS `audit_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) DEFAULT NULL,
  `context` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `details` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bug_report`
--

CREATE TABLE IF NOT EXISTS `bug_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `affected` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `request_type` varchar(10) DEFAULT NULL,
  `request_uri` varchar(255) DEFAULT NULL,
  `request_params` longtext,
  `response` text,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2045 ;

-- --------------------------------------------------------

--
-- Table structure for table `code`
--

CREATE TABLE IF NOT EXISTS `code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assignment_id` int(11) DEFAULT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `contents` text,
  `save_type` tinyint(4) DEFAULT '0',
  `save_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `assignment_id` (`assignment_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5513089 ;

-- --------------------------------------------------------

--
-- Table structure for table `compilation`
--

CREATE TABLE IF NOT EXISTS `compilation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assignment_id` int(11) NOT NULL,
  `code_id` int(11) DEFAULT NULL,
  `compiled` tinyint(1) NOT NULL,
  `raw_output` longtext,
  `compile_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `code_id` (`code_id`),
  KEY `assignment_id` (`assignment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1524578 ;

-- --------------------------------------------------------

--
-- Table structure for table `compilation_error`
--

CREATE TABLE IF NOT EXISTS `compilation_error` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compilation_id` int(11) NOT NULL,
  `code_id` int(11) NOT NULL,
  `line` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `compilation_id` (`compilation_id`),
  KEY `code_id` (`code_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=768494 ;

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(255) DEFAULT NULL,
  `setting` varchar(255) DEFAULT NULL,
  `value` text,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation`
--

CREATE TABLE IF NOT EXISTS `evaluation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assignment_id` int(11) NOT NULL,
  `compilation_id` int(11) NOT NULL,
  `code_id` int(11) NOT NULL,
  `testcase_id` int(11) NOT NULL,
  `output` longtext,
  `result` varchar(255) NOT NULL,
  `verdict` varchar(255) DEFAULT NULL,
  `evaluate_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `assignment_id` (`assignment_id`),
  KEY `compilation_id` (`compilation_id`),
  KEY `code_id` (`code_id`),
  KEY `testcase_id` (`testcase_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1822569 ;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=136 ;

-- --------------------------------------------------------

--
-- Table structure for table `execution`
--

CREATE TABLE IF NOT EXISTS `execution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compilation_id` int(11) DEFAULT NULL,
  `code_id` int(11) DEFAULT NULL,
  `result` varchar(255) DEFAULT NULL,
  `input` text,
  `output` longtext,
  `execute_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `compilation_id` (`compilation_id`),
  KEY `code_id` (`code_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3422858 ;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tool` varchar(50) NOT NULL,
  `code_id` int(11) NOT NULL,
  `output` text NOT NULL,
  `errors` text NOT NULL,
  `generate_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `code_id` (`code_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=490368 ;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `composer` varchar(50) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `contents` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `thread_id` (`thread_id`),
  KEY `composer` (`composer`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=171 ;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient` varchar(50) NOT NULL,
  `event` varchar(20) NOT NULL,
  `reference` varchar(50) DEFAULT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `trigger_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `recipient` (`recipient`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7614 ;

-- --------------------------------------------------------

--
-- Table structure for table `problem`
--

CREATE TABLE IF NOT EXISTS `problem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `is_practice` tinyint(1) NOT NULL DEFAULT '0',
  `visibility` tinyint(4) NOT NULL DEFAULT '0',
  `category` varchar(255) DEFAULT NULL,
  `statement` text,
  `solution` text,
  `template` text,
  `id_p` int(11) NOT NULL,
  `id_v` int(11) NOT NULL,
  `id_d` int(11) NOT NULL,
  `env` varchar(100) NOT NULL DEFAULT 'C',
  `marks` float NOT NULL DEFAULT '20',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2784 ;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `time_start` datetime DEFAULT NULL,
  `time_stop` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=370 ;

-- --------------------------------------------------------

--
-- Table structure for table `slot`
--

CREATE TABLE IF NOT EXISTS `slot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) DEFAULT NULL,
  `section` varchar(10) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `schedule_id` (`schedule_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1799 ;

-- --------------------------------------------------------

--
-- Table structure for table `source`
--

CREATE TABLE IF NOT EXISTS `source` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) DEFAULT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `contents` text,
  `last_saved` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_program` (`user_id`,`path`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20387 ;

-- --------------------------------------------------------

--
-- Table structure for table `specification`
--

CREATE TABLE IF NOT EXISTS `specification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(11) NOT NULL,
  `contents` text NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `inputs` text,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `problem_id` (`problem_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assignee` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `reference` int(11) NOT NULL,
  `deadline` timestamp NULL DEFAULT NULL,
  `is_complete` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27393 ;

-- --------------------------------------------------------

--
-- Table structure for table `tempStudents`
--

CREATE TABLE IF NOT EXISTS `tempStudents` (
  `Roll` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Dept` varchar(255) NOT NULL,
  `Sec` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Roll`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `test_case`
--

CREATE TABLE IF NOT EXISTS `test_case` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(11) DEFAULT NULL,
  `input` text,
  `output` text,
  `visibility` tinyint(4) DEFAULT '0',
  `type` tinyint(4) DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `problem_id` (`problem_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19666 ;

-- --------------------------------------------------------

--
-- Table structure for table `thread`
--

CREATE TABLE IF NOT EXISTS `thread` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator` varchar(50) NOT NULL,
  `context` varchar(20) NOT NULL,
  `reference` int(11) DEFAULT NULL,
  `visibility` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `initiator` (`initiator`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=88 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignment`
--
ALTER TABLE `assignment`
  ADD CONSTRAINT `assignment_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assignment_ibfk_3` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assignment_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attempt`
--
ALTER TABLE `attempt`
  ADD CONSTRAINT `attempt_ibfk_1` FOREIGN KEY (`code_id`) REFERENCES `code` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attempt_ibfk_2` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `code`
--
ALTER TABLE `code`
  ADD CONSTRAINT `code_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `code_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `compilation`
--
ALTER TABLE `compilation`
  ADD CONSTRAINT `compilation_ibfk_1` FOREIGN KEY (`code_id`) REFERENCES `code` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `compilation_ibfk_2` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `compilation_error`
--
ALTER TABLE `compilation_error`
  ADD CONSTRAINT `compilation_error_ibfk_1` FOREIGN KEY (`compilation_id`) REFERENCES `compilation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `compilation_error_ibfk_2` FOREIGN KEY (`code_id`) REFERENCES `code` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `evaluation_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluation_ibfk_2` FOREIGN KEY (`compilation_id`) REFERENCES `compilation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluation_ibfk_3` FOREIGN KEY (`code_id`) REFERENCES `code` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluation_ibfk_4` FOREIGN KEY (`testcase_id`) REFERENCES `test_case` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `execution`
--
ALTER TABLE `execution`
  ADD CONSTRAINT `execution_ibfk_1` FOREIGN KEY (`compilation_id`) REFERENCES `compilation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `execution_ibfk_2` FOREIGN KEY (`code_id`) REFERENCES `code` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`code_id`) REFERENCES `code` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `thread` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`composer`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`recipient`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `slot`
--
ALTER TABLE `slot`
  ADD CONSTRAINT `slot_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `source`
--
ALTER TABLE `source`
  ADD CONSTRAINT `source_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `specification`
--
ALTER TABLE `specification`
  ADD CONSTRAINT `specification_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `test_case`
--
ALTER TABLE `test_case`
  ADD CONSTRAINT `test_case_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `thread`
--
ALTER TABLE `thread`
  ADD CONSTRAINT `thread_ibfk_1` FOREIGN KEY (`initiator`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
