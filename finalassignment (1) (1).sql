-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2026 at 04:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finalassignment`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminactivitylog`
--

CREATE TABLE `adminactivitylog` (
  `LogID` int(10) NOT NULL,
  `ActionType` text NOT NULL,
  `TimeStamp` time NOT NULL,
  `UserID` int(10) NOT NULL,
  `ActionDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminactivitylog`
--

INSERT INTO `adminactivitylog` (`LogID`, `ActionType`, `TimeStamp`, `UserID`, `ActionDate`) VALUES
(1, 'Banned user account for User ID 1', '16:38:00', 5, '2026-08-01'),
(2, 'Banned user account for User ID 2', '08:03:00', 5, '2026-08-02'),
(3, 'Updated Penalty ID 1 status to Active', '08:59:00', 6, '2026-08-03'),
(4, 'Banned user account for User ID 3', '10:36:00', 8, '2026-08-04'),
(5, 'Issued Penalty ID 2 to User ID 4', '17:13:00', 10, '2026-08-05'),
(6, 'Updated Penalty ID 2 status to Completed', '23:50:00', 6, '2026-08-06'),
(7, 'Banned user account for User ID 9', '08:58:00', 5, '2026-08-07'),
(8, 'Banned user account for User ID 11', '15:56:00', 8, '2026-08-08'),
(9, 'Unbanned user account for User ID 2', '16:22:00', 10, '2026-08-09'),
(10, 'Updated Penalty ID 3 status to Pending', '06:42:00', 5, '2026-08-10'),
(11, 'Issued Penalty ID 4 to User ID 12', '20:36:00', 6, '2026-08-11'),
(12, 'Banned user account for User ID 13', '19:37:00', 8, '2026-08-12'),
(13, 'Updated Penalty ID 4 status to Active', '14:45:00', 10, '2026-08-13'),
(14, 'Banned user account for User ID 14', '15:56:00', 5, '2026-08-14'),
(15, 'Banned user account for User ID 15', '14:47:00', 6, '2026-08-15'),
(16, 'Issued Penalty ID 5 to User ID 18', '03:55:00', 8, '2026-08-16'),
(17, 'Updated Penalty ID 5 status to Completed', '05:13:00', 10, '2026-08-17'),
(18, 'Banned user account for User ID 19', '12:38:00', 5, '2026-08-18'),
(19, 'Unbanned user account for User ID 14', '12:22:00', 6, '2026-08-19'),
(20, 'Updated Penalty ID 6 status to Active', '16:38:00', 8, '2026-08-20');

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `CarPlateNumber` varchar(20) NOT NULL,
  `VehicleModel` varchar(30) NOT NULL,
  `VehicleColour` varchar(20) NOT NULL,
  `VehicleCapacity` int(10) NOT NULL,
  `VolunteerID` int(10) NOT NULL,
  `VehicleType` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`CarPlateNumber`, `VehicleModel`, `VehicleColour`, `VehicleCapacity`, `VolunteerID`, `VehicleType`) VALUES
('DNW 7886', 'Ford Mustang', 'Teal', 4, 3, 'pickup'),
('GDU 2184', 'Chevrolet Silverado', 'Maroon', 7, 5, 'van'),
('IHP 0370', 'Honda Civic', 'Goldenrod', 4, 2, 'hatchback'),
('IHZ 6109', 'Toyota Camry', 'Black', 4, 1, 'van'),
('LJC 7749', 'Subaru Outback', 'Purple', 4, 6, 'SUV'),
('PMT 8422', 'Chevrolet Silverado', 'Goldenrod', 7, 4, 'hatchback');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `EventID` int(10) NOT NULL,
  `EventName` varchar(30) NOT NULL,
  `EventPoints` int(30) NOT NULL,
  `EventStartDate` date NOT NULL,
  `EventEndDate` date NOT NULL,
  `EventRequiredRide` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`EventID`, `EventName`, `EventPoints`, `EventStartDate`, `EventEndDate`, `EventRequiredRide`) VALUES
(1, 'Weekend Outing', 168, '2026-04-11', '2026-04-23', 45),
(2, 'Late Night Ride', 467, '2026-07-12', '2026-07-21', 12),
(3, 'Afternoon Pickup', 351, '2025-09-21', '2025-09-24', 6),
(4, 'Daily Drive', 346, '2026-02-14', '2026-02-27', 74),
(5, 'Charity Gala', 125, '2025-09-13', '2025-09-23', 16),
(6, 'Trivia Night', 514, '2026-01-10', '2026-01-13', 45),
(7, 'Fashion Show', 328, '2026-06-13', '2026-06-22', 26),
(8, 'Fitness Bootcamp\n', 812, '2025-12-19', '2025-12-25', 4),
(9, 'Outdoor Movie Night', 644, '2025-12-08', '2025-12-18', 26),
(10, 'Cooking Class\n', 339, '2026-06-27', '2026-07-11', 64),
(11, 'Tech Conference\n', 420, '2026-06-19', '2026-06-27', 100),
(12, 'Yoga Retreat\n', 370, '2026-03-04', '2026-03-18', 39),
(13, 'Film Screening\n', 610, '2026-04-28', '2026-05-09', 71),
(14, 'Dance Competition\n', 602, '2026-06-28', '2026-07-08', 71),
(15, 'Craft Beer Festival\n', 938, '2025-12-14', '2025-12-17', 46),
(16, 'Hiking Event', 260, '2025-10-18', '2025-10-27', 19),
(17, 'Singing Performance', 383, '2025-10-30', '2025-11-09', 50),
(18, 'Hackathon', 395, '2025-09-20', '2025-10-04', 25),
(19, 'Red Week', 140, '2025-11-21', '2025-11-23', 100),
(20, 'Career Fair', 266, '2026-03-29', '2026-04-11', 76);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `FeedbackID` int(10) NOT NULL,
  `RideID` int(10) NOT NULL,
  `StarRating` decimal(2,1) NOT NULL,
  `Comment` text NOT NULL,
  `FeedbackDirection` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`FeedbackID`, `RideID`, `StarRating`, `Comment`, `FeedbackDirection`) VALUES
(1, 1, 4.0, 'Smooth ride and very polite driver!', 'Passenger to Driver'),
(2, 1, 5.0, 'Very polite and punctual passenger.', 'Driver to Passenger'),
(3, 2, 5.0, 'Excellent service, car was clean and comfortable.', 'Passenger to Driver'),
(4, 2, 4.0, 'Great passenger, easy drop-off location.', 'Driver to Passenger'),
(5, 3, 3.0, 'Driver took a longer route than necessary.', 'Passenger to Driver'),
(6, 3, 3.0, 'Passenger was a bit unclear with directions.', 'Driver to Passenger'),
(7, 4, 4.0, 'Very punctual and friendly.', 'Passenger to Driver'),
(8, 4, 5.0, 'Pleasantly chatted during the trip, wonderful passenger.', 'Driver to Passenger'),
(9, 5, 2.0, 'Car had a strange smell, could be cleaner.', 'Passenger to Driver'),
(10, 5, 4.0, 'Quiet passenger, smooth trip.', 'Driver to Passenger'),
(11, 6, 4.0, 'Good experience overall.', 'Passenger to Driver'),
(12, 6, 4.0, 'Ready right on time.', 'Driver to Passenger'),
(13, 7, 3.0, 'Acceptable ride, nothing special.', 'Passenger to Driver'),
(14, 7, 3.0, 'Passenger made a last-minute change to the destination.', 'Driver to Passenger'),
(15, 8, 5.0, 'Amazing driver! Helped with my luggage.', 'Passenger to Driver'),
(16, 8, 5.0, 'Very courteous passenger.', 'Driver to Passenger'),
(17, 9, 4.0, 'Safe and steady driving.', 'Passenger to Driver'),
(18, 9, 4.0, 'No issues at all.', 'Driver to Passenger'),
(19, 10, 3.0, 'Arrived a bit late due to traffic, but fine otherwise.', 'Passenger to Driver'),
(20, 10, 4.0, 'Patient passenger despite the traffic.', 'Driver to Passenger'),
(47, 11, 5.0, 'Very professional driver.', 'Passenger to Driver'),
(48, 11, 4.0, 'Prompt and polite.', 'Driver to Passenger'),
(49, 12, 4.0, 'Quick and efficient trip.', 'Passenger to Driver'),
(50, 12, 4.0, 'Straightforward ride.', 'Driver to Passenger'),
(51, 13, 3.0, 'Driver was talking on the phone while driving.', 'Passenger to Driver'),
(52, 13, 3.8, 'Passenger was on the phone the entire trip.', 'Driver to Passenger'),
(53, 14, 5.0, 'Fantastic service!', 'Passenger to Driver'),
(54, 14, 5.0, 'A model passenger.', 'Driver to Passenger'),
(55, 15, 4.1, 'Comfortable car.', 'Passenger to Driver'),
(56, 15, 4.0, 'Friendly and respectful.', 'Driver to Passenger'),
(57, 16, 3.0, 'Air conditioning was not working well.', 'Passenger to Driver'),
(58, 16, 3.0, 'Passenger slammed the door slightly on exit.', 'Driver to Passenger'),
(59, 17, 5.0, 'Perfect ride! Highly recommended.', 'Passenger to Driver'),
(60, 17, 5.0, 'Absolute pleasure to drive.', 'Driver to Passenger'),
(61, 18, 3.0, 'Average ride quality.', 'Passenger to Driver'),
(62, 18, 4.0, 'Standard trip.', 'Driver to Passenger'),
(63, 19, 5.0, 'Very nice driver, smooth music playing.', 'Passenger to Driver'),
(64, 19, 5.0, 'Great taste in music and very friendly.', 'Driver to Passenger'),
(65, 20, 4.0, 'Clean vehicle and good communication.', 'Passenger to Driver'),
(66, 20, 5.0, 'Clear pickup instructions provided.', 'Driver to Passenger');

-- --------------------------------------------------------

--
-- Table structure for table `passengerprofile`
--

CREATE TABLE `passengerprofile` (
  `PassengerID` int(10) NOT NULL,
  `UserID` int(10) NOT NULL,
  `PreferredPickUpLocation` varchar(200) NOT NULL,
  `PreferredDropOffLocation` varchar(200) NOT NULL,
  `StarRating` decimal(2,1) NOT NULL,
  `DistanceBetweenKM` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `passengerprofile`
--

INSERT INTO `passengerprofile` (`PassengerID`, `UserID`, `PreferredPickUpLocation`, `PreferredDropOffLocation`, `StarRating`, `DistanceBetweenKM`) VALUES
(1, 1, 'City of Green', 'Asia Pacific University', 4.1, '6'),
(2, 2, 'Asia Pacific University', 'Pavillion Bukit Jalil', 4.0, '4'),
(3, 3, 'Harmony', 'Asia Pacific University', 4.0, '3'),
(4, 13, 'LRT Bukit Jalil', 'Asia Pacific University', 4.7, '5'),
(5, 18, 'Asia Pacific University', 'Pavillion Bukit Jalil', 4.2, '4'),
(6, 19, 'Pavillion Bukit Jalil\r\n', 'Asia Pacific University\r\n', 4.1, '4'),
(7, 20, 'City of Green\r\n', 'Asia Pacific University', 3.7, '6');

-- --------------------------------------------------------

--
-- Table structure for table `penaltysystem`
--

CREATE TABLE `penaltysystem` (
  `PenaltyID` int(10) NOT NULL,
  `RideID` int(10) NOT NULL,
  `UserID` int(10) NOT NULL,
  `PenaltyDate` date NOT NULL,
  `PenaltyReason` text NOT NULL,
  `PenaltyStatus` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penaltysystem`
--

INSERT INTO `penaltysystem` (`PenaltyID`, `RideID`, `UserID`, `PenaltyDate`, `PenaltyReason`, `PenaltyStatus`) VALUES
(1, 20, 3, '2026-08-26', 'Late arrival', 'Completed'),
(2, 15, 20, '2026-08-29', 'Inappropriate behavior', 'Active'),
(3, 17, 8, '2026-08-12', 'Reckless driving', 'Completed'),
(4, 12, 7, '2026-07-10', 'Inappropriate behavior', 'Active'),
(5, 17, 19, '2026-03-05', 'Late arrival', 'Pending'),
(6, 6, 3, '2026-04-06', 'Late arrival', 'Pending'),
(7, 9, 12, '2026-04-15', 'Late arrival', 'Active'),
(8, 1, 5, '2026-02-01', 'Reckless driving', 'Completed'),
(9, 18, 10, '2026-06-05', 'Inappropriate behavior', 'Active'),
(10, 7, 12, '2026-07-01', 'Late arrival', 'Pending'),
(11, 12, 19, '2026-07-02', 'Late arrival', 'Active'),
(12, 8, 17, '2026-05-22', 'Late arrival', 'Pending'),
(13, 9, 14, '2026-06-09', 'Inappropriate behavior', 'Active'),
(14, 2, 12, '2026-02-19', 'Reckless driving', 'Completed'),
(15, 18, 6, '2026-08-19', 'Late arrival', 'Completed'),
(16, 20, 12, '2026-08-26', 'Inappropriate behavior', 'Completed'),
(17, 17, 15, '2026-08-15', 'Inappropriate behavior', 'Active'),
(18, 17, 8, '2026-08-19', 'Inappropriate behavior', 'Pending'),
(19, 4, 5, '2026-04-10', 'Late arrival', 'Active'),
(20, 6, 16, '2026-05-10', 'Reckless driving', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `ReportID` int(10) NOT NULL,
  `UserID` int(10) NOT NULL,
  `RideID` int(10) NOT NULL,
  `ReportDate` date NOT NULL,
  `ReportDescription` text NOT NULL,
  `ReportStatus` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`ReportID`, `UserID`, `RideID`, `ReportDate`, `ReportDescription`, `ReportStatus`) VALUES
(1, 16, 20, '2026-07-07', 'Late arrival reported.', 'Resolved'),
(2, 16, 16, '2026-05-08', 'Air conditioning issue.', 'Resolved'),
(3, 17, 5, '2025-08-27', 'Vehicle cleanliness issue.', 'Resolved'),
(4, 16, 5, '2026-03-20', 'Vehicle had unpleasant smell.', 'Resolved'),
(5, 6, 11, '2026-06-17', 'Ride review requested.', 'Resolved'),
(6, 2, 7, '2025-12-04', 'Late arrival reported.', 'Resolved'),
(7, 2, 10, '2025-11-27', 'Driver arrived late.', 'Resolved'),
(8, 9, 12, '2025-09-23', 'Inappropriate behavior reported.', 'Resolved'),
(9, 1, 13, '2025-12-18', 'Phone use during ride.', 'Resolved'),
(10, 17, 11, '2026-01-12', 'Ride review requested.', 'Resolved'),
(11, 16, 1, '2026-01-22', 'Reckless driving reported.', 'Resolved'),
(12, 9, 16, '2026-06-02', 'Passenger slammed car door.', 'Resolved'),
(13, 2, 7, '2026-04-07', 'Passenger changed destination suddenly.', 'Resolved'),
(14, 15, 3, '2026-07-18', 'Driver took a longer route.', 'Resolved'),
(15, 11, 8, '2026-02-22', 'Late arrival reported.', 'Resolved'),
(16, 18, 2, '2026-06-20', 'Reckless driving reported.', 'Resolved'),
(17, 16, 9, '2026-03-30', 'Inappropriate behavior reported.', 'Resolved'),
(18, 8, 2, '2026-03-02', 'Reckless driving reported.', 'Resolved'),
(19, 4, 10, '2025-09-22', 'Driver arrived late.', 'Resolved'),
(20, 13, 16, '2026-07-03', 'Air conditioning issue.', 'Resolved');

-- --------------------------------------------------------

--
-- Table structure for table `reward`
--

CREATE TABLE `reward` (
  `RewardID` int(20) NOT NULL,
  `RewardName` varchar(100) NOT NULL,
  `RewardCost` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reward`
--

INSERT INTO `reward` (`RewardID`, `RewardName`, `RewardCost`) VALUES
(1, 'Nut Butter Cups', 50),
(2, 'Travel Size Toiletry Bottles', 500),
(3, 'Sesame Garlic Noodles', 200),
(4, 'Miso Soup Mix', 25),
(5, 'Marinara Parmesan Baked Ziti', 15),
(6, 'Vintage Button-Down Shirt', 300),
(7, 'Portable Electric Fan', 300),
(8, 'Coconut Curry Sauce', 25),
(9, 'Portable Air Compressor', 400),
(10, 'Lightweight Workout Tank', 30),
(11, 'Sketchbook', 20),
(12, 'Watercolor Paint Set', 10),
(13, 'Phone Screen Protector', 60),
(14, 'Silicone Baking Mat', 60),
(15, 'Smartphone Tripod', 200),
(16, 'Savory Snack Mix', 90),
(17, 'Artisan Cornbread Mix', 15),
(18, 'Pet Hair Remover', 30),
(19, 'Marinara Sauce', 60),
(20, 'Pet Water Fountain with Filtration', 20);

-- --------------------------------------------------------

--
-- Table structure for table `rewardvolunteerdriver`
--

CREATE TABLE `rewardvolunteerdriver` (
  `RewardVolunteerDriverID` int(10) NOT NULL,
  `RewardID` int(10) NOT NULL,
  `VolunteerID` int(10) NOT NULL,
  `DateClaimed` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rewardvolunteerdriver`
--

INSERT INTO `rewardvolunteerdriver` (`RewardVolunteerDriverID`, `RewardID`, `VolunteerID`, `DateClaimed`) VALUES
(1, 5, 1, '2026-08-19'),
(2, 1, 6, '2026-08-05'),
(3, 2, 5, '2026-08-05'),
(4, 15, 2, '2026-08-06'),
(5, 16, 3, '2026-08-06'),
(6, 3, 4, '2026-08-07'),
(7, 17, 5, '2026-08-07'),
(8, 19, 3, '2026-08-07'),
(9, 5, 2, '2026-08-08'),
(10, 17, 4, '2026-08-08'),
(11, 7, 6, '2026-08-09'),
(12, 12, 6, '2026-08-10'),
(13, 11, 5, '2026-08-10'),
(14, 9, 3, '2026-08-11'),
(15, 2, 2, '2026-08-13'),
(16, 7, 5, '2026-08-13'),
(17, 8, 3, '2026-08-14'),
(18, 2, 2, '2026-08-15'),
(19, 4, 2, '2026-08-17'),
(20, 3, 5, '2026-08-18');

-- --------------------------------------------------------

--
-- Table structure for table `riderequest`
--

CREATE TABLE `riderequest` (
  `RideRequestID` int(10) NOT NULL,
  `PassengerID` int(10) NOT NULL,
  `VolunteerID` int(10) NOT NULL,
  `RideRequestStatus` varchar(20) NOT NULL,
  `RequestTime` time NOT NULL,
  `PickUpLocation` varchar(200) NOT NULL,
  `DropOffLocation` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riderequest`
--

INSERT INTO `riderequest` (`RideRequestID`, `PassengerID`, `VolunteerID`, `RideRequestStatus`, `RequestTime`, `PickUpLocation`, `DropOffLocation`) VALUES
(1, 1, 1, 'Completed', '08:00:00', 'City of Green', 'Asia Pacific University'),
(2, 2, 2, 'Completed', '08:15:00', 'LRT Bukit Jalil', 'Asia Pacific University'),
(3, 3, 3, 'Completed', '08:30:00', 'Pavillion Bukit Jalil', 'Asia Pacific University\r\n'),
(4, 4, 4, 'Completed', '09:00:00', 'Asia Pacific University\r\n', 'Pavillion Bukit Jalil'),
(5, 5, 5, 'Completed', '09:25:00', 'Asia Pacific University', 'Pavillion Bukit Jalil'),
(6, 1, 6, 'Completed', '10:10:00', 'City of Green', 'Asia Pacific University'),
(7, 2, 1, 'Completed', '10:45:00', 'Asia Pacific University', 'LRT Bukit Jalil'),
(8, 3, 2, 'Completed', '11:00:00', 'Harmony', 'Asia Pacific University'),
(9, 4, 3, 'Completed', '11:30:00', 'Pavillion Bukit Jalil', 'Asia Pacific University\r\n'),
(10, 5, 4, 'Completed', '12:15:00', 'Asia Pacific University\r\n', 'Pavillion Bukit Jalil'),
(11, 1, 5, 'Completed', '13:00:00', 'City of Green', 'Asia Pacific University'),
(12, 2, 6, 'Completed', '13:40:00', 'Asia Pacific University', 'Pavillion Bukit Jalil'),
(13, 3, 1, 'Completed', '14:20:00', 'Asia Pacific University', 'LRT Bukit Jalil'),
(14, 4, 2, 'Completed', '15:00:00', 'LRT Bukit Jalil', 'Asia Pacific University'),
(15, 5, 3, 'Completed', '15:45:00', 'Asia Pacific University', 'Pavillion Bukit Jalil'),
(16, 1, 4, 'Completed', '16:30:00', 'City of Green', 'Asia Pacific University'),
(17, 2, 5, 'Completed', '17:10:00', 'Asia Pacific University', 'City of Green'),
(18, 3, 6, 'Completed', '18:00:00', 'City of Green', 'Asia Pacific University'),
(19, 4, 1, 'Completed', '18:50:00', 'LRT Bukit Jalil', 'Asia Pacific University'),
(20, 5, 2, 'Completed', '19:30:00', 'Asia Pacific University', 'Pavillion Bukit Jalil');

-- --------------------------------------------------------

--
-- Table structure for table `rides`
--

CREATE TABLE `rides` (
  `RideID` int(10) NOT NULL,
  `DateOfRides` date NOT NULL,
  `RideRequestID` int(10) NOT NULL,
  `RideStatus` varchar(20) NOT NULL,
  `CarPlateNumber` varchar(20) NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rides`
--

INSERT INTO `rides` (`RideID`, `DateOfRides`, `RideRequestID`, `RideStatus`, `CarPlateNumber`, `StartTime`, `EndTime`) VALUES
(1, '2026-01-30', 4, 'completed', 'IHZ 6109', '05:17:00', '23:55:00'),
(2, '2026-02-10', 11, 'completed', 'DNW 7886', '11:51:00', '10:53:00'),
(3, '2026-02-11', 14, 'completed', 'DNW 7886', '02:24:00', '23:53:00'),
(4, '2026-03-02', 20, 'completed', 'GDU 2184', '21:51:00', '08:26:00'),
(5, '2026-03-29', 3, 'completed', 'IHP 0370', '13:23:00', '14:23:00'),
(6, '2026-04-05', 9, 'completed', 'IHP 0370', '01:13:00', '16:08:00'),
(7, '2026-04-09', 4, 'completed', 'IHZ 6109', '17:55:00', '12:00:00'),
(8, '2026-05-21', 7, 'completed', 'LJC 7749', '10:35:00', '19:57:00'),
(9, '2026-06-01', 6, 'completed', 'PMT 8422', '21:45:00', '18:58:00'),
(10, '2026-06-25', 2, 'completed', 'LJC 7749', '09:54:00', '14:42:00'),
(11, '2026-07-01', 8, 'completed', 'GDU 2184', '02:16:00', '14:27:00'),
(12, '2026-07-08', 5, 'completed', 'DNW 7886', '05:42:00', '12:17:00'),
(13, '2026-07-20', 6, 'completed', 'GDU 2184', '09:56:00', '23:54:00'),
(14, '2026-07-22', 7, 'completed', 'PMT 8422', '03:56:00', '21:21:00'),
(15, '2026-07-30', 20, 'completed', 'DNW 7886', '09:57:00', '22:32:00'),
(16, '2026-08-09', 12, 'completed', 'IHP 0370', '06:56:00', '17:03:00'),
(17, '2026-08-10', 20, 'completed', 'LJC 7749', '21:56:00', '15:36:00'),
(18, '2026-08-14', 17, 'completed', 'PMT 8422', '23:37:00', '13:22:00'),
(19, '2026-08-20', 3, 'completed', 'GDU 2184', '01:29:00', '09:48:00'),
(20, '2026-08-25', 10, 'completed', 'GDU 2184', '22:37:00', '02:36:00');

--
-- Triggers `rides`
--
DELIMITER $$
CREATE TRIGGER `AddRideAndPoints` AFTER INSERT ON `rides` FOR EACH ROW BEGIN
    UPDATE volunteerdriverprofile v
    JOIN car c ON v.VolunteerID = c.VolunteerID
    SET v.numberofridesdone = v.numberofridesdone + 1,
        v.currentpoints = v.currentpoints + 10
    WHERE c.CarPlateNumber = NEW.CarPlateNumber;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `RoleID` int(10) NOT NULL,
  `RoleName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`RoleID`, `RoleName`) VALUES
(1, 'Volunteer Driver'),
(2, 'Passenger'),
(3, 'Admin'),
(4, 'Reward Manager');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `TransactionID` int(10) NOT NULL,
  `VolunteerID` int(10) NOT NULL,
  `Points` int(10) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `TransactionDate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`TransactionID`, `VolunteerID`, `Points`, `Description`, `TransactionDate`) VALUES
(1, 6, 100, 'Flood relief logistics delivery', '2026-08-01 09:28:00'),
(2, 1, 50, 'Emergency supplies distribution drive', '2026-08-05 13:02:00'),
(3, 3, -25, 'Redeemed grocery discount code', '2026-08-04 16:29:00'),
(4, 2, 20, 'Long-distance patient transfer task', '2026-08-01 10:29:00'),
(5, 1, 75, 'Disaster recovery equipment haul', '2026-08-16 19:35:00'),
(6, 2, 40, 'Bonus reward for 10 completed rides', '2026-08-05 11:42:00'),
(7, 1, 60, 'Weekend volunteer incentive', '2026-08-18 10:59:00'),
(8, 6, 40, 'Completed weekend food delivery task', '2026-08-03 14:14:00'),
(9, 1, 20, 'Redeemed beverage voucher', '2026-08-16 10:56:00'),
(10, 1, 50, 'Disaster recovery equipment haul', '2026-08-01 14:07:00'),
(11, 4, -50, 'Redeemed eWallet credit', '2026-08-10 11:27:00'),
(12, 1, 60, 'Long-distance patient transfer task', '2026-08-01 16:03:00'),
(13, 4, 20, 'Disaster recovery equipment haul', '2026-08-07 19:04:00'),
(14, 1, -15, 'Redeemed beverage voucher', '2026-08-16 19:38:00'),
(15, 2, -30, 'Redeemed petrol voucher', '2026-08-02 18:27:00'),
(16, 4, 60, 'Community center volunteer shuttle', '2026-08-11 11:06:00'),
(17, 1, 100, 'Senior citizen medical transport trip', '2026-08-12 17:34:00'),
(18, 6, 75, 'Assisted disabled passenger', '2026-08-04 09:22:00'),
(19, 2, -50, 'Redeemed petrol voucher', '2026-08-12 17:41:00'),
(20, 4, 30, 'Completed weekend food delivery task', '2026-08-02 20:25:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(10) NOT NULL,
  `RoleID` int(10) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `AccountStatus` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `RoleID`, `Email`, `Password`, `Username`, `Name`, `Gender`, `AccountStatus`) VALUES
(1, 2, 'gwalling0@purevolume.com', 'yG2*FY', 'gwalling0', 'Gray Walling', 'Female', 'Active'),
(2, 2, 'iletts1@umn.edu', 'jQ7\\Y1', 'iletts1', 'Ingaborg Letts', 'Female', 'Active'),
(3, 2, 'ghumbatch2@jiathis.com', 'sI1=x7YrT', 'ghumbatch2', 'Gena Humbatch', 'Female', 'Active'),
(4, 1, 'wzelake3@slashdot.org', 'gS9*c4C88', 'wzelake3', 'Weylin Zelake', 'Female', 'Active'),
(5, 3, 'rshackleton4@geocities.jp', 'fN2}HCa', 'rshackleton4', 'Robbie Shackleton', 'Female', 'Active'),
(6, 3, 'telliott5@friendfeed.com', 'qL4=)gL_D7', 'telliott5', 'Thurston Elliott', 'Female', 'Active'),
(7, 4, 'fglencrosche6@washingtonpost.', 'vG5@Pllm', 'fglencrosche6', 'Francine Glencrosche', 'Female', 'Active'),
(8, 3, 'dwoodrup7@cocolog-nifty.com', 'bU6}5&Vjam', 'dwoodrup7', 'Donni Woodrup', 'Female', 'Active'),
(9, 1, 'jlinkleter8@nba.com', 'dT4+yY#n5', 'jlinkleter8', 'Jocko Linkleter', 'Female', 'Active'),
(10, 3, 'mwhatmough9@npr.org', 'nF4(hkn9>$ig', 'mwhatmough9', 'Meta Whatmough', 'Female', 'Active'),
(11, 1, 'hedgesona@nifty.com', 'rP7_3W', 'hedgesona', 'Hercules Edgeson', 'Male', 'Active'),
(12, 1, 'alambillionb@weather.com', 'xQ0)Rw\\.\\a', 'alambillionb', 'Ally Lambillion', 'Female', 'Active'),
(13, 2, 'orushbyc@redcross.org', 'vZ2\\f)S@', 'orushbyc', 'Ola Rushby', 'Male', 'Active'),
(14, 1, 'jalbertd@wp.com', 'fI2@pEJ', 'jalbertd', 'Jonie Albert', 'Female', 'Active'),
(15, 1, 'ikeemere@jugem.jp', 'aO2,.`8{\"', 'ikeemere', 'Isac Keemer', 'Male', 'Active'),
(16, 4, 'ssetfordf@flickr.com', 'kY8\\yi', 'ssetfordf', 'Sheena Setford', 'Female', 'Active'),
(17, 4, 'hwadieg@salon.com', 'oO8`Sa', 'hwadieg', 'Hermine Wadie', 'Female', 'Active'),
(18, 2, 'hskipph@theguardian.com', 'mZ5\'9_d_O=*>', 'hskipph', 'Hersch Skipp', 'Male', 'Active'),
(19, 2, 'sburki@angelfire.com', 'dU9\\3`~0Mv2*', 'sburki', 'Salaidh Burk', 'Female', 'Active'),
(20, 2, 'vsaiensj@nationalgeographic.c', 'oV5#p21MYS', 'vsaiensj', 'Vilma Saiens', 'Female', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `userevent`
--

CREATE TABLE `userevent` (
  `UserEventID` int(10) NOT NULL,
  `EventID` int(10) NOT NULL,
  `VolunteerID` int(10) NOT NULL,
  `EventProgressStatus` varchar(20) NOT NULL,
  `CompletionDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userevent`
--

INSERT INTO `userevent` (`UserEventID`, `EventID`, `VolunteerID`, `EventProgressStatus`, `CompletionDate`) VALUES
(1, 20, 2, 'In Progress', '2026-08-09'),
(2, 3, 2, 'Completed', '2026-04-22'),
(3, 5, 2, 'Cancelled', '2026-03-14'),
(4, 13, 5, 'In Progress', '2025-08-14'),
(5, 4, 1, 'In Progress', '2025-10-02'),
(6, 10, 5, 'In Progress', '2025-09-17'),
(7, 4, 4, 'Cancelled', '2025-11-02'),
(8, 19, 3, 'Cancelled', '2025-10-28'),
(9, 15, 4, 'In Progress', '2026-01-04'),
(10, 9, 1, 'Completed', '2026-05-30'),
(11, 11, 3, 'Completed', '2026-04-02'),
(12, 2, 5, 'Completed', '2026-05-10'),
(13, 3, 4, 'Cancelled', '2026-03-15'),
(14, 12, 6, 'In Progress', '2026-03-14'),
(15, 14, 4, 'In Progress', '2025-11-10'),
(16, 6, 1, 'Cancelled', '2025-10-27'),
(17, 11, 3, 'In Progress', '2026-07-04'),
(18, 9, 5, 'In Progress', '2026-05-10'),
(19, 12, 1, 'In Progress', '2026-05-14'),
(20, 6, 3, 'In Progress', '2026-04-06');

-- --------------------------------------------------------

--
-- Table structure for table `volunteerdriverprofile`
--

CREATE TABLE `volunteerdriverprofile` (
  `VolunteerID` int(10) NOT NULL,
  `PreferredPickUpLocation` varchar(200) NOT NULL,
  `PreferredDropOffLocation` varchar(200) NOT NULL,
  `UserID` int(10) NOT NULL,
  `StarRating` decimal(2,1) NOT NULL,
  `CurrentPoints` int(10) NOT NULL,
  `NumberOfRidesDone` int(10) NOT NULL,
  `DriverStatus` varchar(20) NOT NULL,
  `DistanceBetweenKM` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteerdriverprofile`
--

INSERT INTO `volunteerdriverprofile` (`VolunteerID`, `PreferredPickUpLocation`, `PreferredDropOffLocation`, `UserID`, `StarRating`, `CurrentPoints`, `NumberOfRidesDone`, `DriverStatus`, `DistanceBetweenKM`) VALUES
(1, 'Asia Pacific University', 'LRT Bukit Jalil', 4, 3.5, 20, 2, 'Available', '5'),
(2, 'LRT Bukit Jalil', 'Asia Pacific University', 9, 3.0, 30, 3, 'Available', '5'),
(3, 'Pavillion Bukit Jalil', 'Asia Pacific University\r\n', 11, 4.0, 40, 4, 'Available', '4'),
(4, 'Asia Pacific University\r\n', 'Pavillion Bukit Jalil', 12, 4.2, 40, 3, 'Available', '4'),
(5, 'Asia Pacific University', 'City of Green', 14, 4.2, 50, 5, 'Available', '6'),
(6, 'City of Green', 'Asia Pacific University', 15, 4.3, 30, 3, 'Available', '6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminactivitylog`
--
ALTER TABLE `adminactivitylog`
  ADD PRIMARY KEY (`LogID`),
  ADD KEY `FKUSERID1` (`UserID`);

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`CarPlateNumber`),
  ADD KEY `FKvolun` (`VolunteerID`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`EventID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`FeedbackID`),
  ADD KEY `FKRID3` (`RideID`);

--
-- Indexes for table `passengerprofile`
--
ALTER TABLE `passengerprofile`
  ADD PRIMARY KEY (`PassengerID`),
  ADD KEY `FKUserID` (`UserID`);

--
-- Indexes for table `penaltysystem`
--
ALTER TABLE `penaltysystem`
  ADD PRIMARY KEY (`PenaltyID`),
  ADD KEY `FKRID4` (`RideID`),
  ADD KEY `FKUID1` (`UserID`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`ReportID`),
  ADD KEY `FKUSERID2` (`UserID`),
  ADD KEY `FKRIDEID2` (`RideID`);

--
-- Indexes for table `reward`
--
ALTER TABLE `reward`
  ADD PRIMARY KEY (`RewardID`);

--
-- Indexes for table `rewardvolunteerdriver`
--
ALTER TABLE `rewardvolunteerdriver`
  ADD PRIMARY KEY (`RewardVolunteerDriverID`),
  ADD KEY `FKrewar` (`RewardID`),
  ADD KEY `FKV` (`VolunteerID`);

--
-- Indexes for table `riderequest`
--
ALTER TABLE `riderequest`
  ADD PRIMARY KEY (`RideRequestID`),
  ADD KEY `FKPI` (`PassengerID`),
  ADD KEY `FKVID` (`VolunteerID`);

--
-- Indexes for table `rides`
--
ALTER TABLE `rides`
  ADD PRIMARY KEY (`RideID`),
  ADD KEY `FKRID2` (`RideRequestID`),
  ADD KEY `FKCPN` (`CarPlateNumber`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`RoleID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`TransactionID`),
  ADD KEY `VolunteerID` (`VolunteerID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`) USING BTREE,
  ADD KEY `FKRoleID` (`RoleID`);

--
-- Indexes for table `userevent`
--
ALTER TABLE `userevent`
  ADD PRIMARY KEY (`UserEventID`),
  ADD KEY `FKevent` (`EventID`),
  ADD KEY `FKvolunteer` (`VolunteerID`);

--
-- Indexes for table `volunteerdriverprofile`
--
ALTER TABLE `volunteerdriverprofile`
  ADD PRIMARY KEY (`VolunteerID`),
  ADD KEY `FKUser` (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminactivitylog`
--
ALTER TABLE `adminactivitylog`
  MODIFY `LogID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `EventID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FeedbackID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `passengerprofile`
--
ALTER TABLE `passengerprofile`
  MODIFY `PassengerID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `penaltysystem`
--
ALTER TABLE `penaltysystem`
  MODIFY `PenaltyID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `ReportID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `reward`
--
ALTER TABLE `reward`
  MODIFY `RewardID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `rewardvolunteerdriver`
--
ALTER TABLE `rewardvolunteerdriver`
  MODIFY `RewardVolunteerDriverID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `riderequest`
--
ALTER TABLE `riderequest`
  MODIFY `RideRequestID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `rides`
--
ALTER TABLE `rides`
  MODIFY `RideID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `RoleID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `TransactionID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `userevent`
--
ALTER TABLE `userevent`
  MODIFY `UserEventID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `volunteerdriverprofile`
--
ALTER TABLE `volunteerdriverprofile`
  MODIFY `VolunteerID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adminactivitylog`
--
ALTER TABLE `adminactivitylog`
  ADD CONSTRAINT `FKUSERID1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `FKvolun` FOREIGN KEY (`VolunteerID`) REFERENCES `volunteerdriverprofile` (`VolunteerID`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `FKRID3` FOREIGN KEY (`RideID`) REFERENCES `riderequest` (`RideRequestID`);

--
-- Constraints for table `passengerprofile`
--
ALTER TABLE `passengerprofile`
  ADD CONSTRAINT `FKUserID` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `penaltysystem`
--
ALTER TABLE `penaltysystem`
  ADD CONSTRAINT `FKRID4` FOREIGN KEY (`RideID`) REFERENCES `rides` (`RideID`),
  ADD CONSTRAINT `FKUID1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `FKRIDEID2` FOREIGN KEY (`RideID`) REFERENCES `rides` (`RideID`),
  ADD CONSTRAINT `FKUSERID2` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `rewardvolunteerdriver`
--
ALTER TABLE `rewardvolunteerdriver`
  ADD CONSTRAINT `FKV` FOREIGN KEY (`VolunteerID`) REFERENCES `volunteerdriverprofile` (`VolunteerID`),
  ADD CONSTRAINT `FKrewar` FOREIGN KEY (`RewardID`) REFERENCES `reward` (`RewardID`);

--
-- Constraints for table `riderequest`
--
ALTER TABLE `riderequest`
  ADD CONSTRAINT `FKPI` FOREIGN KEY (`PassengerID`) REFERENCES `passengerprofile` (`PassengerID`),
  ADD CONSTRAINT `FKVID` FOREIGN KEY (`VolunteerID`) REFERENCES `volunteerdriverprofile` (`VolunteerID`);

--
-- Constraints for table `rides`
--
ALTER TABLE `rides`
  ADD CONSTRAINT `FKCPN` FOREIGN KEY (`CarPlateNumber`) REFERENCES `car` (`CarPlateNumber`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FKRID2` FOREIGN KEY (`RideRequestID`) REFERENCES `riderequest` (`RideRequestID`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`VolunteerID`) REFERENCES `volunteerdriverprofile` (`VolunteerID`) ON DELETE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FKRoleID` FOREIGN KEY (`RoleID`) REFERENCES `role` (`RoleID`);

--
-- Constraints for table `userevent`
--
ALTER TABLE `userevent`
  ADD CONSTRAINT `FKevent` FOREIGN KEY (`EventID`) REFERENCES `event` (`EventID`),
  ADD CONSTRAINT `FKvolunteer` FOREIGN KEY (`VolunteerID`) REFERENCES `volunteerdriverprofile` (`VolunteerID`);

--
-- Constraints for table `volunteerdriverprofile`
--
ALTER TABLE `volunteerdriverprofile`
  ADD CONSTRAINT `FKUser` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
