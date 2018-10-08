-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Jun 17, 2018 at 03:37 PM
-- Server version: 5.5.42
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `bbq_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `position` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `position`) VALUES
('0', '總務處'),
('3', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `bbq`
--

CREATE TABLE `bbq` (
  `no` int(10) NOT NULL,
  `price` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `open_interval` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bbq`
--

INSERT INTO `bbq` (`no`, `price`, `open_interval`) VALUES
(1, '300,500', '08:00~11:00,11:00~14:00,18:00~21:00'),
(2, '300,500', '08:00~11:00,11:00~14:00,18:00~21:00'),
(3, '300,500', '08:00~11:00,11:00~14:00,18:00~21:00'),
(4, '300,500', '08:00~11:00,11:00~14:00,18:00~21:00'),
(5, '300,500', '08:00~11:00,11:00~14:00,18:00~21:00'),
(6, '300,500', '08:00~11:00,11:00~14:00,18:00~21:00'),
(7, '300,500', '08:00~11:00,11:00~14:00,18:00~21:00'),
(8, '300,500', '08:00~11:00,11:00~14:00,18:00~21:00'),
(9, '300,500', '08:00~11:00,11:00~14:00,18:00~21:00'),
(10, '300,500', '08:00~11:00,11:00~14:00,18:00~21:00'),
(11, '300,500', '08:00~11:00,11:00~14:00,18:00~21:00'),
(12, '300,500', '08:00~11:00,11:00~14:00,18:00~21:00');

-- --------------------------------------------------------

--
-- Table structure for table `camp`
--

CREATE TABLE `camp` (
  `no` int(10) NOT NULL,
  `price` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `open_interval` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `camp`
--

INSERT INTO `camp` (`no`, `price`, `open_interval`) VALUES
(13, '300,500', '12:30~11:30'),
(14, '300,500', '12:30~11:30'),
(15, '300,500', '12:30~11:30'),
(16, '300,500', '12:30~11:30'),
(17, '300,500', '12:30~11:30'),
(18, '300,500', '12:30~11:30'),
(19, '300,500', '12:30~11:30'),
(20, '300,500', '12:30~11:30'),
(21, '300,500', '12:30~11:30'),
(22, '300,500', '12:30~11:30'),
(23, '300,500', '12:30~11:30'),
(24, '300,500', '12:30~11:30');

-- --------------------------------------------------------

--
-- Table structure for table `deal_person`
--

CREATE TABLE `deal_person` (
  `id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `belong_to` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `deal_person`
--

INSERT INTO `deal_person` (`id`, `belong_to`) VALUES
('1', '總務處'),
('4', 'NUK');

-- --------------------------------------------------------

--
-- Table structure for table `place`
--

CREATE TABLE `place` (
  `no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `place`
--

INSERT INTO `place` (`no`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24),
(25);

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `receipt_no` int(10) NOT NULL,
  `receipt_serial` int(30) NOT NULL,
  `use_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `time_interval` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `accept` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `receipt`
--

INSERT INTO `receipt` (`id`, `receipt_no`, `receipt_serial`, `use_date`, `time_interval`, `accept`) VALUES
('0', 1, 13, '2018-02-28', '08:00~11:00', 0),
('0', 2, 13, '2018-02-28', '08:00~11:00', 0),
('haha', 1, 1, '2018-01-15', '11:00~14:00', 0),
('haha', 1, 6, '2018-01-21', '08:00~11:00', 0),
('haha', 1, 14, '2018-01-29', '11:00~14:00', 2),
('haha', 2, 1, '2018-01-15', '11:00~14:00', 0),
('haha', 2, 14, '2018-01-29', '11:00~14:00', 2),
('haha', 3, 1, '2018-01-15', '11:00~14:00', 0),
('haha', 3, 14, '2018-01-29', '11:00~14:00', 2),
('haha', 4, 14, '2018-01-29', '11:00~14:00', 2),
('haha', 5, 14, '2018-01-29', '11:00~14:00', 2),
('haha', 6, 14, '2018-01-29', '11:00~14:00', 2),
('haha', 13, 1, '2018-01-15', '12:30~11:30', 0),
('haha', 13, 3, '2018-01-19', '12:30~11:30', 1),
('haha', 13, 6, '2018-01-21', '12:30~11:30', 0),
('haha', 13, 14, '2018-01-29', '12:30~11:30', 2),
('haha', 14, 1, '2018-01-15', '12:30~11:30', 0),
('haha', 14, 14, '2018-01-29', '12:30~11:30', 2),
('haha', 15, 1, '2018-01-15', '12:30~11:30', 0),
('haha', 15, 14, '2018-01-29', '12:30~11:30', 2),
('haha', 16, 1, '2018-01-15', '12:30~11:30', 0),
('haha', 16, 14, '2018-01-29', '12:30~11:30', 2),
('haha', 17, 14, '2018-01-29', '12:30~11:30', 2),
('haha', 18, 14, '2018-01-29', '12:30~11:30', 2),
('haha', 25, 1, '2018-01-15', '15:00~23:59', 0),
('haha', 25, 2, '2018-01-16', '00:00~01:00', 2),
('haha', 25, 3, '2018-01-19', '01:00~07:00', 1),
('haha', 25, 6, '2018-01-21', '00:30~11:00', 0),
('haha', 25, 11, '2018-01-20', '00:00~12:00', 2),
('haha', 25, 14, '2018-01-29', '18:00~23:59', 2),
('haha', 25, 15, '2018-01-30', '00:00~06:00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `rent_person`
--

CREATE TABLE `rent_person` (
  `id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `vat_number` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `identification` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rent_person`
--

INSERT INTO `rent_person` (`id`, `vat_number`, `address`, `identification`) VALUES
('2', '6sef16s1g', '61se5cger6', '0'),
('3', 'se53gc1e5g3c', '3w1sc5g3egc1', '0'),
('4', 'a51xf6wfx1', '5w6ser1x56', '0'),
('a1045505', 'wea4wxfA6EW', '616', '0'),
('haha', 'No', 'NationalHahaUniversity', '0'),
('kuku', 'No', 'No', '1');

-- --------------------------------------------------------

--
-- Table structure for table `show`
--

CREATE TABLE `show` (
  `no` int(10) NOT NULL,
  `price` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `open_interval` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `show`
--

INSERT INTO `show` (`no`, `price`, `open_interval`) VALUES
(25, '150,200', '08:00~11:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `mail`, `phone`, `password`) VALUES
('0', 'admin + rent_person', '123@gmail.com', '0911111111', '098f6bcd4621d373cade4e832627b4f6'),
('1', 'deal_person', '123@gmail.com', '0911111111', '098f6bcd4621d373cade4e832627b4f6'),
('2', 'rent_person', 'sgg01xs', '09122222222', '098f6bcd4621d373cade4e832627b4f6'),
('3', 'admin + rent_person', 's23gc', '0911111111', '098f6bcd4621d373cade4e832627b4f6'),
('4', 'deal_person + rent_person', 'sxr5exg6r156531re51', '3sgc51e3153', '098f6bcd4621d373cade4e832627b4f6'),
('a1045505', 'w1s6xg', 'ecs1g56t156', '51ewcs6g165', '098f6bcd4621d373cade4e832627b4f6'),
('haha', 'XDD', 'haha@gmail.com', '0912345678', '4e4d6c332b6fe62a63afe56171fd3725'),
('kuku', 'QQQ', 'kuku@mail.com', '0900000000', 'f1534cd6b03bca4163d5773a988dc3bc');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `bbq`
--
ALTER TABLE `bbq`
  ADD PRIMARY KEY (`no`),
  ADD KEY `no` (`no`);

--
-- Indexes for table `camp`
--
ALTER TABLE `camp`
  ADD PRIMARY KEY (`no`),
  ADD KEY `no` (`no`);

--
-- Indexes for table `deal_person`
--
ALTER TABLE `deal_person`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `place`
--
ALTER TABLE `place`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`id`,`receipt_no`,`receipt_serial`) USING BTREE,
  ADD KEY `id` (`id`),
  ADD KEY `receipt_no` (`receipt_no`),
  ADD KEY `receipt_serial` (`receipt_serial`);

--
-- Indexes for table `rent_person`
--
ALTER TABLE `rent_person`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`) USING BTREE;

--
-- Indexes for table `show`
--
ALTER TABLE `show`
  ADD PRIMARY KEY (`no`),
  ADD KEY `no` (`no`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bbq`
--
ALTER TABLE `bbq`
  ADD CONSTRAINT `bbq_ibfk_1` FOREIGN KEY (`no`) REFERENCES `place` (`no`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `camp`
--
ALTER TABLE `camp`
  ADD CONSTRAINT `camp_ibfk_1` FOREIGN KEY (`no`) REFERENCES `place` (`no`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `deal_person`
--
ALTER TABLE `deal_person`
  ADD CONSTRAINT `deal_person_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receipt`
--
ALTER TABLE `receipt`
  ADD CONSTRAINT `receipt_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_ibfk_2` FOREIGN KEY (`receipt_no`) REFERENCES `place` (`no`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rent_person`
--
ALTER TABLE `rent_person`
  ADD CONSTRAINT `rent_person_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `show`
--
ALTER TABLE `show`
  ADD CONSTRAINT `show_ibfk_1` FOREIGN KEY (`no`) REFERENCES `show` (`no`) ON DELETE CASCADE ON UPDATE CASCADE;
