-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2017 at 10:28 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ordering` int(11) DEFAULT NULL,
  `visibility` tinyint(4) NOT NULL DEFAULT '0',
  `allow_comments` tinyint(4) NOT NULL DEFAULT '0',
  `allow_ads` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `ordering`, `visibility`, `allow_comments`, `allow_ads`) VALUES
(3, 'electronic', '', 2, 0, 0, 0),
(4, 'bike', '', 0, 1, 1, 0),
(5, 'cell phone', '', NULL, 0, 0, 0),
(6, 'computer', '', NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `c_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`c_id`, `comment`, `status`, `comment_date`, `item_id`, `user_id`) VALUES
(5, 'thank you very', 1, '0000-00-00', 2, 1),
(8, 'good', 1, '0000-00-00', 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` varchar(255) NOT NULL,
  `add_date` date NOT NULL,
  `country_made` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `rating` smallint(6) NOT NULL,
  `approve` tinyint(4) NOT NULL DEFAULT '0',
  `cat_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `name`, `description`, `price`, `add_date`, `country_made`, `image`, `status`, `rating`, `approve`, `cat_id`, `member_id`) VALUES
(2, 'ball', 'game', '2$', '2017-12-24', 'Egypt', '', '4', 0, 1, 3, 1),
(3, 'Gta7', 'gta7', '222$', '2017-12-24', 'england', '', '3', 0, 1, 4, 1),
(4, 'pes', 'pes', '20$', '2017-12-24', '', '', '3', 0, 0, 3, 2),
(6, 'nissan', '', '5000$', '2017-12-24', 'Egypt', '', '4', 0, 1, 4, 1),
(7, 'arsenal', 'club', '50000000000$', '2017-12-25', 'england', '', '4', 0, 1, 3, 1),
(8, 'game', 'game', '20', '2017-12-29', 'Egypt', '', '1', 0, 0, 6, 2),
(9, 'fott', 'foot', '200', '2017-12-29', 'Egypt', '', '2', 0, 0, 4, 2),
(10, 'fott', 'foot', '200', '2017-12-29', 'Egypt', '', '2', 0, 0, 4, 2),
(11, 'fott', 'foot', '200', '2017-12-29', 'Egypt', '', '2', 0, 0, 4, 2),
(12, 'fott', 'foot', '200', '2017-12-29', 'Egypt', '', '2', 0, 0, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `groupid` int(11) NOT NULL DEFAULT '0',
  `truststatus` int(11) NOT NULL DEFAULT '0' COMMENT 'seller rank',
  `regstatus` int(11) NOT NULL DEFAULT '0' COMMENT 'user approval',
  `date` date NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `password`, `email`, `fullname`, `groupid`, `truststatus`, `regstatus`, `date`, `image`) VALUES
(1, 'omar', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'omar.elsawy@yahoo.com', 'omarelsawy', 1, 0, 1, '0000-00-00', ''),
(2, 'yosef', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'yosef@yahoo.com', 'yosef elsawy', 0, 0, 0, '2017-12-22', ''),
(3, 'ali', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'ali@yahoo.com', 'ali mohamed', 0, 0, 1, '2017-12-26', ''),
(4, 'blal', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'blal@yahoo.com', '', 0, 0, 0, '2017-12-27', ''),
(5, 'methat', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'methat@yahoo.com', '', 0, 0, 0, '2017-12-27', ''),
(6, 'yosef2', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'yosef@yahoo.com', '', 0, 0, 0, '2017-12-27', ''),
(7, 'ali2', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'ali@yahoo.com', '', 0, 0, 0, '2017-12-28', ''),
(8, 'withimg', '8cb2237d0679ca88db6464eac60da96345513964', 'admin@gmail.com', 'omar elsawy', 0, 0, 1, '2017-12-31', '91687_img.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `cat_id` (`cat_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
