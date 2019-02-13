-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2019 at 02:25 PM
-- Server version: 5.7.17
-- PHP Version: 7.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `forecast`
--

-- --------------------------------------------------------

--
-- Table structure for table `drain1`
--

CREATE TABLE `drain1` (
  `rain_id` int(11) NOT NULL,
  `forecast_time` varchar(6) NOT NULL,
  `rainfall_intensity` varchar(500) NOT NULL,
  `status` varchar(25) NOT NULL,
  `date` int(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `drains`
--

CREATE TABLE `drains` (
  `drain_id` int(11) NOT NULL,
  `drain` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `drains`
--

INSERT INTO `drains` (`drain_id`, `drain`) VALUES
(1, 1),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `rainfall`
--

CREATE TABLE `rainfall` (
  `rain_id` int(100) NOT NULL,
  `forecast_time` varchar(6) NOT NULL,
  `rainfall_intensity` varchar(500) NOT NULL,
  `status` varchar(25) NOT NULL,
  `date` varchar(30) NOT NULL,
  `water_level` varchar(100) NOT NULL DEFAULT '0',
  `drain_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rainfall`
--

INSERT INTO `rainfall` (`rain_id`, `forecast_time`, `rainfall_intensity`, `status`, `date`, `water_level`, `drain_id`) VALUES
(6650, '23:00', '0', 'active', '12/02/2019 19:00', '0.1', 1),
(6651, '23:00', '0', 'active', '12/02/2019 19:00', '0.545', 2),
(6678, '15:00', '0', 'active', '13/02/2019 11:24', '0.545', 2),
(6679, '15:00', '0', 'active', '13/02/2019 11:24', '0.044', 3),
(6682, '16:00', '0', 'active', '13/02/2019 12:40', '0.044', 3),
(6681, '16:00', '0', 'active', '13/02/2019 12:40', '0.545', 2),
(6680, '16:00', '0', 'active', '13/02/2019 12:40', '0.1', 1),
(6676, '14:00', '0', 'active', '13/02/2019 11:24', '0.044', 3),
(6677, '15:00', '0', 'active', '13/02/2019 11:24', '0.1', 1),
(6675, '14:00', '0', 'active', '13/02/2019 11:24', '0.545', 2),
(6674, '14:00', '0', 'active', '13/02/2019 11:24', '0.1', 1),
(6673, '13:00', '0', 'active', '13/02/2019 11:24', '0.044', 3),
(6672, '13:00', '0', 'active', '13/02/2019 11:24', '0.545', 2),
(6671, '13:00', '0', 'active', '13/02/2019 11:24', '0.1', 1),
(6670, '12:00', '0', 'inactive', '13/02/2019 11:24', '0.044', 3),
(6669, '12:00', '0', 'inactive', '13/02/2019 11:24', '0.545', 2),
(6668, '12:00', '0', 'inactive', '13/02/2019 11:24', '0', 1),
(6667, '00:00', '0', 'inactive', '12/02/2019 20:08', '0.044', 3),
(6664, '00:00', '0', 'inactive', '12/02/2019 20:00', '0.044', 3),
(6665, '00:00', '0', 'inactive', '12/02/2019 20:08', '0', 1),
(6666, '00:00', '0', 'inactive', '12/02/2019 20:08', '0.545', 2),
(6663, '00:00', '0', 'inactive', '12/02/2019 20:00', '0.545', 2),
(6652, '23:00', '0', 'active', '12/02/2019 19:00', '0.044', 3),
(6653, '20:00', '0', 'inactive', '12/02/2019 19:01', '0.1', 1),
(6654, '20:00', '0', 'inactive', '12/02/2019 19:01', '0.545', 2),
(6655, '20:00', '0', 'inactive', '12/02/2019 19:01', '0.044', 3),
(6656, '21:00', '0', 'active', '12/02/2019 19:01', '0.1', 1),
(6657, '21:00', '0', 'active', '12/02/2019 19:01', '0.545', 2),
(6658, '21:00', '0', 'active', '12/02/2019 19:01', '0.044', 3),
(6659, '22:00', '0', 'active', '12/02/2019 19:01', '0.1', 1),
(6660, '22:00', '0', 'active', '12/02/2019 19:01', '0.545', 2),
(6661, '22:00', '0', 'active', '12/02/2019 19:01', '0.044', 3),
(6662, '00:00', '0', 'inactive', '12/02/2019 20:00', '0', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drain1`
--
ALTER TABLE `drain1`
  ADD PRIMARY KEY (`rain_id`);

--
-- Indexes for table `drains`
--
ALTER TABLE `drains`
  ADD PRIMARY KEY (`drain_id`);

--
-- Indexes for table `rainfall`
--
ALTER TABLE `rainfall`
  ADD PRIMARY KEY (`rain_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `drain1`
--
ALTER TABLE `drain1`
  MODIFY `rain_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `drains`
--
ALTER TABLE `drains`
  MODIFY `drain_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `rainfall`
--
ALTER TABLE `rainfall`
  MODIFY `rain_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6683;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
