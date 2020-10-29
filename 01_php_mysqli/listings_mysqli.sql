-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2020 at 08:00 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `real_estate_listings`
--

-- --------------------------------------------------------

--
-- Table structure for table `listings_mysqli`
--

CREATE TABLE `listings_mysqli` (
  `id` int(11) NOT NULL,
  `price` double NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `beds` varchar(5) NOT NULL,
  `baths` varchar(5) NOT NULL,
  `front_img` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `author` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `listings_mysqli`
--

INSERT INTO `listings_mysqli` (`id`, `price`, `address`, `city`, `province`, `beds`, `baths`, `front_img`, `description`, `author`, `created_at`) VALUES
(1, 300000, '3233 83 Street NW', 'Toronto', 'Ontario', '2', '2', 'photo-4-sm.jpg', 'Greenwood Village still boasts the lowest pad rental fees in the City and the owners are committed to keeping the park operating into the future. This park offers a club house/social space, laundry and mail building, two playgrounds, plenty of walking spaces and friendly neighbours.', 'John666', '2020-10-29 18:46:39'),
(2, 1000000, '#177 3223 83 ST NW', 'Vancouver', 'British Columbia', '2', '1+', 'photo-3-sm.jpg', 'This double wide mobile home features a large master bedroom at the back with attached three piece ensuite. There is hallway storage across from the second bedroom which has two large windows.The main bath is four piece with upscale dark wood and granite vanity. Stacking washer and dryer.', 'Mary888', '2020-10-29 18:52:11'),
(3, 8000000, '1307, 738 1 Avenue SW', 'Calgary', 'Alberta', '4', '3', 'photo-2-sm.jpg', 'Enjoy spectacular waterfront sub -penthouse living with captivating panoramic views of the Bow River, Princes Island Park, downtown core, and vistas of the Rocky Mountains.', 'Andrew999', '2020-10-29 18:54:09'),
(4, 6500000, '6 ASPEN RIDGE LANE SW', 'Ottawa', 'Quebec', '3', '3', 'photo-4-sm.jpg', 'The Manor House was designed to capture old-world charm while highlighting luxurious new world amenities and modern convenience in a warm family home - just minutes to top renowned private schools on the west side.', 'Pola777', '2020-10-29 18:55:48'),
(5, 5250000, '1028 Bel-Aire DR SW', 'Red Deer', 'Alberta', '3', '2+', '', 'Welcome to this stately custom-built home, situated in the prestigious community of Bel-Aire; located within walking distance of the shops and restaurants of Britannia. This masterpiece is currently 70% completed, offering finishes that will reflect the numerous luxurious upgrades. ', 'Tomoko666', '2020-10-29 18:57:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `listings_mysqli`
--
ALTER TABLE `listings_mysqli`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `listings_mysqli`
--
ALTER TABLE `listings_mysqli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
