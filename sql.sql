-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Nov 07, 2016 at 07:25 AM
-- Server version: 5.5.42
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `menuFake`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartId` varchar(255) NOT NULL,
  `userId` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartId`, `userId`) VALUES
('581d43e49cd35', 21),
('581d4412abdd4', 21);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(20) NOT NULL,
  `food` int(20) DEFAULT NULL,
  `value` int(20) DEFAULT NULL,
  `speed` int(20) DEFAULT NULL,
  `overall` int(20) NOT NULL,
  `comments` text,
  `uid` int(20) DEFAULT NULL,
  `restaurantId` int(20) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `food`, `value`, `speed`, `overall`, `comments`, `uid`, `restaurantId`, `time`) VALUES
(10, 5, 5, 5, 5, 'Good', 21, 1, '2016-10-29 01:33:30'),
(11, 5, 5, 5, 5, 'sfsdf', 21, 1, '2016-10-29 01:33:44'),
(12, 3, 3, 4, 3, 'bdbf', 21, 1, '2016-10-29 01:34:00'),
(13, 3, 1, 3, 2, 'Fuck', 21, 1, '2016-10-29 02:22:53'),
(14, 1, 2, 3, 2, 'Shit', 21, 1, '2016-10-29 02:30:27'),
(15, 1, 1, 1, 1, 'fgsgr', 21, 1, '2016-10-29 02:30:35'),
(16, 1, 1, 1, 1, 'xzvcczvzc', 21, 1, '2016-10-29 02:30:46'),
(17, 1, 3, 1, 2, 'hjhnkhkjnkl', 21, 1, '2016-10-29 02:44:23');

-- --------------------------------------------------------

--
-- Table structure for table `cuisines`
--

CREATE TABLE `cuisines` (
  `id` int(20) NOT NULL,
  `cuisineName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cuisines`
--

INSERT INTO `cuisines` (`id`, `cuisineName`) VALUES
(4, 'Thai'),
(5, 'Chinese'),
(6, 'Indian'),
(7, 'Asian'),
(8, 'Italian'),
(9, 'Vegetarian');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `id` int(20) NOT NULL,
  `foodTitle` varchar(115) NOT NULL,
  `price` int(20) NOT NULL,
  `description` varchar(225) DEFAULT NULL,
  `menu_id` int(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`id`, `foodTitle`, `price`, `description`, `menu_id`) VALUES
(1, 'Aloo Poorie', 16, '4 Poories (deep fry wholemeal), potatoes curry, yoghurt, pickle ', 1),
(2, 'Channa Bhtturra', 10, 'White chickpeas cooked with medium spices, and serve with deep fry white flour bread two in serve.', 1),
(3, 'Tender Chicken', 5, 'Just five price tender', 2),
(4, 'Fry Rice', 20, 'This is a Fry Ricekewe', 1),
(5, 'Fry Egg', 15, 'This is a Fry Egg3232', 1),
(6, 'Buger Meal', 15, 'This is a Buger Meal', 2),
(47, 'fuk', 89, 'knkn', 3),
(48, 'dsfd', 33, 'ddfsdf', 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(20) NOT NULL,
  `menuTitle` varchar(115) NOT NULL,
  `about` text NOT NULL,
  `restaurant_id` int(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `menuTitle`, `about`, `restaurant_id`) VALUES
(1, 'Breakfast', '', 1),
(2, 'Children Menus', '', 1),
(3, 'Sea-fare', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orderItem`
--

CREATE TABLE `orderItem` (
  `id` int(20) NOT NULL,
  `orderId` varchar(255) DEFAULT NULL,
  `foodId` int(20) NOT NULL,
  `restaurantId` int(20) NOT NULL,
  `foodTitle` varchar(115) NOT NULL,
  `qty` int(20) NOT NULL,
  `price` int(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderItem`
--

INSERT INTO `orderItem` (`id`, `orderId`, `foodId`, `restaurantId`, `foodTitle`, `qty`, `price`) VALUES
(43, '581d43e49cd35', 1, 1, 'Aloo Poorie', 1, 16),
(44, '581d43e49cd35', 2, 1, 'Channa Bhtturra', 1, 10),
(45, '581d43e49cd35', 4, 1, 'Fry Rice', 2, 40),
(46, '581d43e49cd35', 48, 1, 'dsfd', 1, 33),
(47, '581d4412abdd4', 47, 2, 'fuk', 2, 178);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant`
--

CREATE TABLE `restaurant` (
  `id` int(20) NOT NULL,
  `title` varchar(115) NOT NULL,
  `cuisineName` varchar(115) NOT NULL,
  `openTime` time NOT NULL,
  `closeTime` time NOT NULL,
  `minOrder` int(20) DEFAULT NULL,
  `description` varchar(225) DEFAULT NULL,
  `image_path` text,
  `address` varchar(225) NOT NULL,
  `cartType` varchar(115) NOT NULL,
  `owner` int(20) DEFAULT NULL,
  `overall` int(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `restaurant`
--

INSERT INTO `restaurant` (`id`, `title`, `cuisineName`, `openTime`, `closeTime`, `minOrder`, `description`, `image_path`, `address`, `cartType`, `owner`, `overall`) VALUES
(1, 'KFC', 'Thai', '04:04:00', '15:02:00', 39, 'Just a KFC', 'http://localhost:8888/img/cover/default.jpg', '177 CRESTWOOD SOUTHPORT', 'delivery', 20, 2),
(2, 'McCafe', 'Chinese', '11:03:56', '00:00:00', 49, 'just a cofe', 'http://localhost:8888/img/cover/Screen Shot 2016-10-26 at 2.50.37 pm.png', '177 anne st SOUTHPORT 4215', 'delivery', 20, 0),
(4, 'Shang_Court', 'Asian', '18:15:28', '00:00:00', 50, 'Just asian food', 'http://localhost:8888/img/cover/default.jpg', '123 lucky st southport', 'pickUp', 21, 0),
(8, 'case', 'Italian', '10:35:25', '12:00:00', 12, '1233', 'http://localhost:8888/img/cover/default.jpg', 'southport', 'pickUp', 20, 0),
(17, 'asds_3211', 'Vegetarian', '01:00:00', '12:02:00', 12, 'dsadsa', 'http://localhost:8888/img/cover/Scan 17.jpeg', 'Ashmore', 'delivery', 20, 0),
(18, 'fuck_me', 'asd', '00:12:00', '14:11:00', 123, '12', 'http://localhost:8888/img/cover/1', 'southport', 'pickUp', 20, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(20) NOT NULL,
  `firstName` varchar(115) NOT NULL,
  `lastName` varchar(115) NOT NULL,
  `userName` varchar(115) NOT NULL,
  `email` varchar(115) NOT NULL,
  `password` varchar(225) NOT NULL,
  `address` varchar(225) NOT NULL,
  `validationCode` text NOT NULL,
  `activate` tinyint(1) NOT NULL DEFAULT '0',
  `role` enum('1','2','3','') NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `userName`, `email`, `password`, `address`, `validationCode`, `activate`, `role`) VALUES
(20, 'Lisheng', 'Liu', 'Aliu', 'aliu45836@gmail.com', '202cb962ac59075b964b07152d234b70', 'anne st', '0', 1, '2'),
(21, 'fuck', 'alan', 'Aliu123', 'aliu45836@hotmail.com', '202cb962ac59075b964b07152d234b70', '13 CRESTWOOD DR ', '0', 1, '1'),
(22, 'admin', 'admin', 'admin', 'admin@gmail.com', '202cb962ac59075b964b07152d234b70', 'none', '0', 1, '3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartId`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `restaurantId` (`restaurantId`);

--
-- Indexes for table `cuisines`
--
ALTER TABLE `cuisines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_ibfk_1` (`restaurant_id`);

--
-- Indexes for table `orderItem`
--
ALTER TABLE `orderItem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orderId` (`orderId`);

--
-- Indexes for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_ibfk_1` (`owner`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `cuisines`
--
ALTER TABLE `cuisines`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `orderItem`
--
ALTER TABLE `orderItem`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `restaurant`
--
ALTER TABLE `restaurant`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`restaurantId`) REFERENCES `restaurant` (`id`);

--
-- Constraints for table `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `food_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orderItem`
--
ALTER TABLE `orderItem`
  ADD CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `cart` (`cartId`);

--
-- Constraints for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD CONSTRAINT `restaurant_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
