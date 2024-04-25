-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2022 at 05:35 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

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
-- Table structure for table `bookmark`
--

-- Table for Language Learners
CREATE TABLE LanguageLearners (
    LearnerID INT PRIMARY KEY AUTO_INCREMENT,
    FirstName VARCHAR(15) NOT NULL,
    LastName VARCHAR(15) NOT NULL,
    Email VARCHAR(30) UNIQUE NOT NULL,
    Password VARCHAR(50) NOT NULL,
    Photo VARCHAR(255), -- File path to optional photo
    City VARCHAR(20),
    Location VARCHAR(100),
    -- SignUpDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- LastLogin TIMESTAMP,
    -- Ensuring unique combination of first and last name
);

-- Table for Language Learning Requests
CREATE TABLE LearningRequests (
    RequestID INT PRIMARY KEY AUTO_INCREMENT,
    LearnerID INT,
    LanguageToLearn VARCHAR(15) NOT NULL,
    ProficiencyLevel VARCHAR(15),
    PreferredSchedule VARCHAR(50),
    SessionDuration INT,
    RequestDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Status ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
    FOREIGN KEY (LearnerID) REFERENCES LanguageLearners(LearnerID) ON DELETE CASCADE
);

-- Table for Language Partners/Native Speakers
CREATE TABLE LanguagePartners (
    PartnerID INT PRIMARY KEY AUTO_INCREMENT,
    FirstName VARCHAR(15) NOT NULL,
    LastName VARCHAR(15) NOT NULL,
    Age INT,
    Gender ENUM('Male', 'Female'),
    Email VARCHAR(100) UNIQUE NOT NULL,
    Photo VARCHAR(255), -- File path to optional photo
    Password VARCHAR(50) NOT NULL,
    Phone VARCHAR(15),
    City VARCHAR(20),
    Bio TEXT,
    -- SignUpDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- LastLogin TIMESTAMP,
    -- Ensuring unique combination of first and last name
);

-- Table for Learning Sessions
CREATE TABLE LearningSessions (
    SessionID INT PRIMARY KEY AUTO_INCREMENT,
    LearnerID INT,
    PartnerID INT,
    SessionDate DATETIME,
    SessionDuration INT,
    Status ENUM('Scheduled', 'Completed', 'Canceled') DEFAULT 'Scheduled',
    FOREIGN KEY (LearnerID) REFERENCES LanguageLearners(LearnerID) ON DELETE CASCADE,
    FOREIGN KEY (PartnerID) REFERENCES LanguagePartners(PartnerID) ON DELETE CASCADE
);

-- Table for Reviews and Ratings
CREATE TABLE ReviewsRatings (
    ReviewID INT PRIMARY KEY AUTO_INCREMENT,
    SessionID INT,
    Rating INT,
    ReviewText TEXT,
    ReviewDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (SessionID) REFERENCES LearningSessions(SessionID) ON DELETE CASCADE
);



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
