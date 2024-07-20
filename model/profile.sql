-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2024 at 05:32 PM
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
-- Database: `profile`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_address`
--

CREATE TABLE `tbl_address` (
  `id` int(10) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `street_address` varchar(250) DEFAULT NULL,
  `post_office` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_zip` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_address2`
--

CREATE TABLE `tbl_address2` (
  `id` int(10) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `street_address` varchar(250) DEFAULT NULL,
  `post_office` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_zip` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_advertiser`
--

CREATE TABLE `tbl_advertiser` (
  `id` int(10) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `register_date` varchar(50) DEFAULT NULL,
  `lastlogin_date` varchar(50) DEFAULT NULL,
  `profile_image` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `active_status` varchar(15) DEFAULT NULL,
  `verified` varchar(20) DEFAULT NULL,
  `industry` varchar(200) DEFAULT NULL,
  `contact_personname` varchar(100) DEFAULT NULL,
  `contact_personnumber` varchar(50) DEFAULT NULL,
  `address` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(10) NOT NULL,
  `fname` varchar(50) DEFAULT '0',
  `lname` varchar(50) NOT NULL DEFAULT '0',
  `midlename` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `verified` int(10) DEFAULT 0,
  `active` varchar(10) NOT NULL DEFAULT '0',
  `role` varchar(20) DEFAULT NULL,
  `registrationDate` varchar(50) DEFAULT NULL,
  `lastlogin` varchar(10) DEFAULT NULL,
  `dateofbirth` varchar(50) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `profilepic` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_address`
--
ALTER TABLE `tbl_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_advertiser`
--
ALTER TABLE `tbl_advertiser`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
