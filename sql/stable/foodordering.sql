-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2018 at 02:11 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodordering`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `branchid` int(11) NOT NULL,
  `restaurantid` int(11) NOT NULL,
  `area` varchar(20) NOT NULL,
  `address` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`branchid`, `restaurantid`, `area`, `address`, `phone`) VALUES
(1, 1, 'Sakhir', '1256:214:2354', '17171717'),
(2, 1, 'Hamala', '123:466:478', '17171717'),
(3, 1, 'Souq Waqef', '156:1568:235', '17171717'),
(4, 2, 'City Center', '123:156:489', '17171818'),
(5, 2, 'Sakhir', '156:456:479', '17171818'),
(6, 3, 'Hamala', '1568:1568:135', '17171616'),
(7, 3, 'City Center', '156:1564:145', '17171616'),
(8, 3, 'Manama', '123:1457:139', '17171616');

-- --------------------------------------------------------

--
-- Table structure for table `feedbackitems`
--

CREATE TABLE `feedbackitems` (
  `orderid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `comment` varchar(150) NOT NULL,
  `response` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedbackitems`
--

INSERT INTO `feedbackitems` (`orderid`, `itemid`, `rating`, `comment`, `response`) VALUES
(2, 4, '4.9', 'Just how I like it.', 'Customized to your liking..'),
(2, 5, '5.0', 'Da big king!', 'You are the king!'),
(2, 6, '4.8', 'Fish is awesome!', 'Awaiting response..'),
(3, 4, '3.9', 'Was a little bit cold this time..', 'Awaiting response..'),
(3, 5, '4.9', 'Greatness in a burger..', 'Awaiting response..');

-- --------------------------------------------------------

--
-- Table structure for table `feedbackrestaurants`
--

CREATE TABLE `feedbackrestaurants` (
  `orderid` int(11) NOT NULL,
  `restaurantid` int(11) NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `comment` varchar(150) NOT NULL,
  `response` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedbackrestaurants`
--

INSERT INTO `feedbackrestaurants` (`orderid`, `restaurantid`, `rating`, `comment`, `response`) VALUES
(2, 3, '5.0', 'Superb, as usual!', 'Our man!'),
(3, 3, '4.4', 'Where kings feed..', 'You are definitely our favorite..!');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `itemid` int(11) NOT NULL,
  `restaurantid` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `price` decimal(6,3) NOT NULL,
  `image` varchar(20) NOT NULL,
  `type` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`itemid`, `restaurantid`, `title`, `description`, `price`, `image`, `type`) VALUES
(1, 1, 'Mighty Zinger', 'KFC’s Spicy Zinger Recipe, lettuce, cheese, spicy.', '1.500', 'rii/1/1.png', 'Burger:Chicken:Crispy:Spicy:Regular'),
(2, 1, 'Zinger Shrimp', 'Zinger shrimp sandwich with fries & soft drink.', '2.100', 'rii/1/2.png', 'Spicy:Shrimp:Sub:Combo'),
(3, 1, 'Twister', 'Crispy chicken strips, diced tomatoes.', '0.800', 'rii/1/3.png', 'Chicken:Spicy:Regular:Wrap'),
(4, 3, 'Whopper', 'Burger King Signature, Lettuce, tomato, pickles.', '2.800', 'rii/3/4.png', 'American:Burger:Beef'),
(5, 3, 'Big King', 'Double Whopper with Big King sauce.', '3.100', 'rii/3/5.png', 'Beef:Burger:American'),
(6, 3, 'Fish Royale', 'Mayo, lettuce, breaded fish.', '2.500', 'rii/3/6.png', 'Fish:Burger:Sea'),
(7, 2, 'Double Deluxe', 'Two 1/4 lb. Patties Stacked with Your Choice of Cheese.', '3.100', 'rii/2/7.png', 'Burger:American:Beef:Double'),
(8, 2, 'Three Cheese', 'Cheddar, Provolone, Swiss Cheese.', '3.500', 'rii/2/8.png', 'Beef:American:Burger'),
(9, 2, 'The Works', 'Smokehouse Bacon, American Cheese, Grilled Mushrooms.', '3.100', 'rii/2/9.png', 'Burger:American:Beef');

-- --------------------------------------------------------

--
-- Table structure for table `orderaddress`
--

CREATE TABLE `orderaddress` (
  `orderid` int(11) NOT NULL,
  `addressid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderaddress`
--

INSERT INTO `orderaddress` (`orderid`, `addressid`) VALUES
(1, 1),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `orderid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`orderid`, `itemid`, `quantity`) VALUES
(1, 1, 2),
(1, 2, 2),
(1, 3, 1),
(2, 4, 1),
(2, 5, 2),
(2, 6, 1),
(3, 4, 1),
(3, 5, 1),
(4, 1, 2),
(4, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderid` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `type` varchar(10) NOT NULL,
  `payment` varchar(10) NOT NULL,
  `stamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderid`, `branchid`, `userid`, `status`, `type`, `payment`, `stamp`) VALUES
(1, 2, 4, 'Pending', 'Delivery', 'cash', '2018-05-01 13:53:58'),
(2, 8, 4, 'Fulfilled', 'Pickup', 'cash', '2018-05-06 17:54:45'),
(3, 8, 4, 'Fulfilled', 'Delivery', 'cash', '2018-05-07 18:57:41'),
(4, 2, 4, 'Pending', 'Pickup', 'cash', '2018-05-13 08:59:42');

-- --------------------------------------------------------

--
-- Table structure for table `restaurantmanagers`
--

CREATE TABLE `restaurantmanagers` (
  `restaurantid` int(11) NOT NULL,
  `managerid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `restaurantmanagers`
--

INSERT INTO `restaurantmanagers` (`restaurantid`, `managerid`) VALUES
(1, 3),
(1, 5),
(2, 3),
(2, 5),
(3, 3),
(3, 6);

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `restaurantid` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `logo` varchar(20) NOT NULL,
  `description` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`restaurantid`, `name`, `logo`, `description`) VALUES
(1, 'KFC', 'rli/1.png', 'Kentucky Fried Chicken'),
(2, 'Fuddruckers', 'rli/2.png', 'World\'s Greatest Hamburgers.'),
(3, 'Burger King', 'rli/3.png', 'Taste is King.');

-- --------------------------------------------------------

--
-- Table structure for table `useraddresses`
--

CREATE TABLE `useraddresses` (
  `addressid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `area` varchar(30) NOT NULL,
  `address` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `useraddresses`
--

INSERT INTO `useraddresses` (`addressid`, `userid`, `area`, `address`) VALUES
(1, 4, 'Riffa', '1649:354:4538'),
(2, 4, 'Hamala', '1574:1432:145'),
(3, 4, 'Manama', '1523:457:123');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `profilepicture` varchar(20) NOT NULL,
  `usertype` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `name`, `email`, `password`, `phone`, `profilepicture`, `usertype`) VALUES
(1, 'root', 'root@root.com', 'f11536bee899541aa233f5c0aa98f625', '0097369998888', 'upi/default.png', 'admin'),
(2, 'admin', 'admin@admin.com', '25e4ee4e9229397b6b17776bfceaf8e7', '0097369998888', 'upi/default.png', 'admin'),
(3, 'manager', 'manager@manager.com', '3fd7488b6fd40f33c5a8e857b6a944aa', '66447755', 'upi/default.png', 'manager'),
(4, 'customer', 'customer@customer.com', '0a1a1c22b9cdf22c736a6f5f5b4a4f01', '0097369996666', 'upi/default.png', 'customer'),
(5, 'Ahmed Ali', 'ahmedali@email.com', 'a050d36a8e5dcaedc99dbb775c7790e2', '36363535', 'upi/default.png', 'manager'),
(6, 'Faisal Jasim', 'faisaljasim@email.com', '2aa92a5dca2abee6e6634c5871b0b75a', '35353636', 'upi/default.png', 'manager'),
(7, 'KFC Sakhir', 'kfc-sakhir@email.com', 'ac55c5d19a47f397631ddc82dc9e66b7', '17171717', 'rli/1.png', 'branch'),
(8, 'KFC Hamala', 'kfc-hamala@email.com', 'b8f1a13e9d7b7e3c4c12ee3e13ec9398', '17171717', 'rli/1.png', 'branch'),
(9, 'KFC Souq Waqef', 'kfc-souqwaqef@email.com', '6e510f51da7f343dc6e0d79fd5810574', '17171717', 'rli/1.png', 'branch'),
(10, 'Fuddruckers City Center', 'fuddruckers-citycenter@email.com', 'fd7eeea6e603f3a76bde99b0605b5144', '17171818', 'rli/2.png', 'branch'),
(11, 'Fuddruckers Sakhir', 'fuddruckers-sakhir@email.com', 'e4d1f074f1cba65cae3bbc28f0fbc55e', '17171818', 'rli/2.png', 'branch'),
(12, 'Burger King Hamala', 'burgerking-hamala@email.com', '88b73c6dfa073387c42b690ca3200f2f', '17171616', 'rli/3.png', 'branch'),
(13, 'Burger King City Center', 'burgerking-citycenter@email.com', '4136415936ff1140c13676ad259a804d', '17171616', 'rli/3.png', 'branch'),
(14, 'Burger King Manama', 'burgerking-manama@email.com', 'e75bef2f8b38ca600dd4644f1e83a841', '17171616', 'rli/3.png', 'branch');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`branchid`),
  ADD KEY `restaurantid` (`restaurantid`);

--
-- Indexes for table `feedbackitems`
--
ALTER TABLE `feedbackitems`
  ADD PRIMARY KEY (`orderid`,`itemid`),
  ADD KEY `itemid` (`itemid`);

--
-- Indexes for table `feedbackrestaurants`
--
ALTER TABLE `feedbackrestaurants`
  ADD PRIMARY KEY (`orderid`,`restaurantid`),
  ADD KEY `restaurantid` (`restaurantid`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`itemid`,`restaurantid`,`title`),
  ADD KEY `restaurantid` (`restaurantid`);

--
-- Indexes for table `orderaddress`
--
ALTER TABLE `orderaddress`
  ADD PRIMARY KEY (`orderid`,`addressid`),
  ADD KEY `addressid` (`addressid`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`orderid`,`itemid`),
  ADD KEY `itemid` (`itemid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderid`),
  ADD KEY `branchid` (`branchid`),
  ADD KEY `customerid` (`userid`);

--
-- Indexes for table `restaurantmanagers`
--
ALTER TABLE `restaurantmanagers`
  ADD PRIMARY KEY (`restaurantid`,`managerid`),
  ADD KEY `managerid` (`managerid`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`restaurantid`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `useraddresses`
--
ALTER TABLE `useraddresses`
  ADD PRIMARY KEY (`addressid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `branchid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `itemid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `restaurantid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `useraddresses`
--
ALTER TABLE `useraddresses`
  MODIFY `addressid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_ibfk_1` FOREIGN KEY (`restaurantid`) REFERENCES `restaurants` (`restaurantid`);

--
-- Constraints for table `feedbackitems`
--
ALTER TABLE `feedbackitems`
  ADD CONSTRAINT `feedbackitems_ibfk_1` FOREIGN KEY (`itemid`) REFERENCES `items` (`itemid`),
  ADD CONSTRAINT `feedbackitems_ibfk_2` FOREIGN KEY (`orderid`) REFERENCES `orders` (`orderid`);

--
-- Constraints for table `feedbackrestaurants`
--
ALTER TABLE `feedbackrestaurants`
  ADD CONSTRAINT `feedbackrestaurants_ibfk_1` FOREIGN KEY (`orderid`) REFERENCES `orders` (`orderid`),
  ADD CONSTRAINT `feedbackrestaurants_ibfk_2` FOREIGN KEY (`restaurantid`) REFERENCES `restaurants` (`restaurantid`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`restaurantid`) REFERENCES `restaurants` (`restaurantid`);

--
-- Constraints for table `orderaddress`
--
ALTER TABLE `orderaddress`
  ADD CONSTRAINT `orderaddress_ibfk_1` FOREIGN KEY (`addressid`) REFERENCES `useraddresses` (`addressid`),
  ADD CONSTRAINT `orderaddress_ibfk_2` FOREIGN KEY (`orderid`) REFERENCES `orders` (`orderid`);

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`itemid`) REFERENCES `items` (`itemid`),
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`orderid`) REFERENCES `orders` (`orderid`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`branchid`) REFERENCES `branches` (`branchid`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `useraddresses`
--
ALTER TABLE `useraddresses`
  ADD CONSTRAINT `useraddresses_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
