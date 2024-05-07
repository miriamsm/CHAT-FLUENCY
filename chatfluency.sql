-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2024 at 12:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chatfluency`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `number` varchar(20) DEFAULT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languagelearners`
--

CREATE TABLE `languagelearners` (
  `LearnerID` int(11) NOT NULL,
  `FirstName` varchar(15) NOT NULL,
  `LastName` varchar(15) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Photo` varchar(255) DEFAULT NULL,
  `City` varchar(20) DEFAULT NULL,
  `Location` varchar(100) DEFAULT NULL,
  `User_Role` enum('learner','partner') NOT NULL DEFAULT 'learner'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `languagelearners`
--

INSERT INTO `languagelearners` (`LearnerID`, `FirstName`, `LastName`, `Email`, `Password`, `Photo`, `City`, `Location`, `User_Role`) VALUES
(123456790, 'Alanood', 'Almozaini', 'alanoud.ibrahim5@gmail.com', '12345', 'profile.png', 'Riyadh', 'KSA', 'learner'),
(123456791, 'Leena', 'Saleh', 'LeenaS@hotmail.com', '12345', 'pic-7.jpg', 'Riyadh', 'KSA', 'learner');

-- --------------------------------------------------------

--
-- Table structure for table `languagepartners`
--

CREATE TABLE `languagepartners` (
  `PartnerID` int(11) NOT NULL,
  `FirstName` varchar(15) NOT NULL,
  `LastName` varchar(15) NOT NULL,
  `Age` int(11) DEFAULT NULL,
  `Gender` enum('Male','Female') DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Photo` varchar(255) DEFAULT NULL,
  `Password` varchar(50) NOT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `City` varchar(20) DEFAULT NULL,
  `Bio` text DEFAULT NULL,
  `User_Role` enum('learner','partner') NOT NULL DEFAULT 'partner',
  `Languages` text NOT NULL,
  `Rating` float DEFAULT NULL,
  `LanguageProf` text DEFAULT NULL,
  `SessionPrice` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `languagepartners`
--

INSERT INTO `languagepartners` (`PartnerID`, `FirstName`, `LastName`, `Age`, `Gender`, `Email`, `Photo`, `Password`, `Phone`, `City`, `Bio`, `User_Role`, `Languages`, `Rating`, `LanguageProf`, `SessionPrice`) VALUES
(123456789, 'Alanood', 'Ibrahim', 21, 'Female', 'alanoud.ibrahim5@gmail.com', 'Profile.png', '12345A', '0592744070', 'Riyadh', 'Hello Im Alanood', 'partner', 'English, Arabic', 4.5, 'Beginner', 300),
(123456792, 'A', 'Almozaini', 21, 'Female', 'aim@hotmail.com', 'profile.png', '12345', '0592744074', 'Riyadh', 'hi', 'partner', 'Spanish', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `learningrequests`
--

CREATE TABLE `learningrequests` (
  `RequestID` int(11) NOT NULL,
  `LearnerID` int(11) NOT NULL,
  `PartnerID` int(11) NOT NULL,
  `LanguageToLearn` set('English','Spanish','French','Mandarian Chinese','Arabic','Hindi','Russian','Portuguese','Bengali','German') NOT NULL,
  `ProficiencyLevel` set('Beginner','Intermediate','Advanced','') DEFAULT NULL,
  `PreferredSchedule` varchar(50) DEFAULT NULL,
  `SessionDuration` varchar(15) DEFAULT NULL,
  `RequestDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `LearnerGoals` text NOT NULL,
  `RequestTimestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `Withdrawn` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learningrequests`
--

INSERT INTO `learningrequests` (`RequestID`, `LearnerID`, `PartnerID`, `LanguageToLearn`, `ProficiencyLevel`, `PreferredSchedule`, `SessionDuration`, `RequestDate`, `Status`, `LearnerGoals`, `RequestTimestamp`, `Withdrawn`) VALUES
(11, 123456790, 123456792, 'Spanish', 'Intermediate', '0000-00-00 00:00:00.000000', '1 hour', '2024-05-07 19:25:38', 'Pending', 'qq', '2024-05-07 19:25:38', 0),
(12, 123456790, 123456793, 'French', 'Intermediate', '2024-05-04 00:26:00.000000', '5 hours', '2024-05-07 19:28:47', 'Pending', '-', '2024-05-07 19:28:47', 0),
(13, 123456790, 123456792, 'French', 'Advanced', '2024-05-07 22:28:00.000000', '3 hours', '2024-05-07 19:36:01', 'Pending', 'jj', '2024-05-07 19:36:01', 0);

--
-- Triggers `learningrequests`
--
DELIMITER $$
CREATE TRIGGER `after_update_learningrequests` AFTER UPDATE ON `learningrequests` FOR EACH ROW BEGIN
    -- Check if the status has been updated to Accepted
    IF NEW.Status = 'Accepted' THEN
        -- Insert a new session into the learningsessions table with the requested date
        INSERT INTO learningsessions (LearnerID, PartnerID, SessionDate, SessionDuration, Status)
        VALUES (NEW.LearnerID, NEW.PartnerID, NEW.PreferredSchedule, NEW.SessionDuration, 'Scheduled');
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `learningsessions`
--

CREATE TABLE `learningsessions` (
  `SessionID` int(11) NOT NULL,
  `LearnerID` int(11) DEFAULT NULL,
  `PartnerID` int(11) DEFAULT NULL,
  `SessionDate` datetime DEFAULT NULL,
  `SessionDuration` int(11) DEFAULT NULL,
  `Status` enum('Scheduled','Completed','Canceled') DEFAULT 'Scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviewsratings`
--

CREATE TABLE `reviewsratings` (
  `ReviewID` int(11) NOT NULL,
  `SessionID` int(11) DEFAULT NULL,
  `Rating` int(11) DEFAULT NULL,
  `ReviewText` text DEFAULT NULL,
  `ReviewDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `PartnerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `reviewsratings`
--
DELIMITER $$
CREATE TRIGGER `after_insert_reviewsratings` AFTER INSERT ON `reviewsratings` FOR EACH ROW BEGIN
    DECLARE avg_rating FLOAT;

    -- Calculate average rating for the partner
    SELECT AVG(Rating) INTO avg_rating
    FROM reviewsratings
    WHERE PartnerID = NEW.PartnerID;

    -- Update rating in languagepartners table
    UPDATE languagepartners
    SET Rating = avg_rating
    WHERE PartnerID = NEW.PartnerID;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `languagelearners`
--
ALTER TABLE `languagelearners`
  ADD PRIMARY KEY (`LearnerID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `languagepartners`
--
ALTER TABLE `languagepartners`
  ADD PRIMARY KEY (`PartnerID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `learningrequests`
--
ALTER TABLE `learningrequests`
  ADD PRIMARY KEY (`RequestID`),
  ADD KEY `LearnerID` (`LearnerID`),
  ADD KEY `PartnerID` (`PartnerID`);

--
-- Indexes for table `learningsessions`
--
ALTER TABLE `learningsessions`
  ADD PRIMARY KEY (`SessionID`),
  ADD KEY `LearnerID` (`LearnerID`),
  ADD KEY `PartnerID` (`PartnerID`);

--
-- Indexes for table `reviewsratings`
--
ALTER TABLE `reviewsratings`
  ADD PRIMARY KEY (`ReviewID`),
  ADD KEY `SessionID` (`SessionID`),
  ADD KEY `PartnerID` (`PartnerID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languagelearners`
--
ALTER TABLE `languagelearners`
  MODIFY `LearnerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123456792;

--
-- AUTO_INCREMENT for table `languagepartners`
--
ALTER TABLE `languagepartners`
  MODIFY `PartnerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123456794;

--
-- AUTO_INCREMENT for table `learningrequests`
--
ALTER TABLE `learningrequests`
  MODIFY `RequestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `learningsessions`
--
ALTER TABLE `learningsessions`
  MODIFY `SessionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviewsratings`
--
ALTER TABLE `reviewsratings`
  MODIFY `ReviewID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `learningrequests`
--
ALTER TABLE `learningrequests`
  ADD CONSTRAINT `learningrequests_ibfk_1` FOREIGN KEY (`LearnerID`) REFERENCES `languagelearners` (`LearnerID`) ON DELETE CASCADE;

--
-- Constraints for table `learningsessions`
--
ALTER TABLE `learningsessions`
  ADD CONSTRAINT `learningsessions_ibfk_1` FOREIGN KEY (`LearnerID`) REFERENCES `languagelearners` (`LearnerID`) ON DELETE CASCADE,
  ADD CONSTRAINT `learningsessions_ibfk_2` FOREIGN KEY (`PartnerID`) REFERENCES `languagepartners` (`PartnerID`) ON DELETE CASCADE;

--
-- Constraints for table `reviewsratings`
--
ALTER TABLE `reviewsratings`
  ADD CONSTRAINT `reviewsratings_ibfk_1` FOREIGN KEY (`SessionID`) REFERENCES `learningsessions` (`SessionID`) ON DELETE CASCADE;
COMMIT;

ALTER TABLE LearningRequests 
ADD COLUMN RequestTimestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

DELIMITER //
CREATE TRIGGER after_insert_reviewsratings
AFTER INSERT ON reviewsratings
FOR EACH ROW
BEGIN
    DECLARE avg_rating FLOAT;

    -- Calculate average rating for the partner
    SELECT AVG(Rating) INTO avg_rating
    FROM reviewsratings
    WHERE PartnerID = NEW.PartnerID;

    -- Update rating in languagepartners table
    UPDATE languagepartners
    SET Rating = avg_rating
    WHERE PartnerID = NEW.PartnerID;
END;
//
DELIMITER ;

DELIMITER //

CREATE TRIGGER after_update_learningrequests
AFTER UPDATE ON learningrequests
FOR EACH ROW
BEGIN
    -- Check if the status has been updated to Accepted
    IF NEW.Status = 'Accepted' THEN
        -- Insert a new session into the learningsessions table with the requested date
        INSERT INTO learningsessions (LearnerID, PartnerID, SessionDate, SessionDuration, Status)
        VALUES (NEW.LearnerID, NEW.PartnerID, NEW.RequestDate, NEW.SessionDuration, 'Scheduled');
    END IF;
END;
//

DELIMITER ;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
