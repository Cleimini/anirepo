-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2021 at 03:33 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `anirepo`
--

-- --------------------------------------------------------

--
-- Table structure for table `anime`
--

CREATE TABLE `anime` (
  `Anime_ID` int(11) NOT NULL,
  `MAL_Anime_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Anime_Title` varchar(255) NOT NULL,
  `Anime_Type` varchar(7) NOT NULL,
  `Premiered` varchar(11) NOT NULL,
  `Studios` longtext NOT NULL,
  `Source` varchar(12) NOT NULL,
  `Genres` longtext NOT NULL,
  `Anime_MAL_URL` text NOT NULL,
  `Anime_Thumbnail` text NOT NULL,
  `Date_Anime_Added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `Favorite_ID` int(11) NOT NULL,
  `MAL_Favorite_ID` int(11) DEFAULT NULL,
  `Anime_ID` int(11) DEFAULT NULL,
  `User_ID` int(11) NOT NULL,
  `Favorite_Name` varchar(100) NOT NULL,
  `Placement` smallint(6) NOT NULL,
  `Favorite_Type` varchar(11) NOT NULL,
  `Favorite_MAL_URL` text NOT NULL,
  `Favorite_Thumbnail` text NOT NULL,
  `Date_Favorite_Added` datetime NOT NULL,
  `Date_Favorite_Updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `Log_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Log_Type` varchar(50) NOT NULL,
  `Date_Cataloged` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `monitorings`
--

CREATE TABLE `monitorings` (
  `Anime_ID` int(11) NOT NULL,
  `Monitoring_Type` varchar(25) NOT NULL,
  `Date_Started` date DEFAULT NULL,
  `Date_Finished` date DEFAULT NULL,
  `Date_Scheduled` date DEFAULT NULL,
  `Date_Monitoring_Added` datetime NOT NULL,
  `Date_Monitoring_Updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `resets`
--

CREATE TABLE `resets` (
  `User_ID` int(11) NOT NULL,
  `Selector` text NOT NULL,
  `Token_Code` longtext NOT NULL,
  `Date_Token_Code_Expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `Anime_ID` int(11) NOT NULL,
  `Opinion` text NOT NULL,
  `Score` decimal(8,2) NOT NULL,
  `Date_Review_Added` datetime NOT NULL,
  `Date_Review_Updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `trendings`
--

CREATE TABLE `trendings` (
  `Trending_ID` int(11) NOT NULL,
  `Trending_Title` varchar(255) NOT NULL,
  `Trending_Type` varchar(8) NOT NULL,
  `Trending_MAL_URL` text NOT NULL,
  `Trending_Thumbnail` text NOT NULL,
  `Date_Trending_Added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `Email_Address` text NOT NULL,
  `Password` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anime`
--
ALTER TABLE `anime`
  ADD PRIMARY KEY (`Anime_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`Favorite_ID`),
  ADD KEY `Anime_ID` (`Anime_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`Log_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `monitorings`
--
ALTER TABLE `monitorings`
  ADD PRIMARY KEY (`Anime_ID`);

--
-- Indexes for table `resets`
--
ALTER TABLE `resets`
  ADD PRIMARY KEY (`User_ID`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`Anime_ID`);

--
-- Indexes for table `trendings`
--
ALTER TABLE `trendings`
  ADD PRIMARY KEY (`Trending_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anime`
--
ALTER TABLE `anime`
  MODIFY `Anime_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `Favorite_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `Log_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trendings`
--
ALTER TABLE `trendings`
  MODIFY `Trending_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anime`
--
ALTER TABLE `anime`
  ADD CONSTRAINT `anime_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`Anime_ID`) REFERENCES `anime` (`Anime_ID`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`);

--
-- Constraints for table `monitorings`
--
ALTER TABLE `monitorings`
  ADD CONSTRAINT `monitorings_ibfk_1` FOREIGN KEY (`Anime_ID`) REFERENCES `anime` (`Anime_ID`);

--
-- Constraints for table `resets`
--
ALTER TABLE `resets`
  ADD CONSTRAINT `resets_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`Anime_ID`) REFERENCES `anime` (`Anime_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
