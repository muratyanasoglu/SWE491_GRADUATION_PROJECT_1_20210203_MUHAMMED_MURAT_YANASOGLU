-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3307
-- Üretim Zamanı: 07 Haz 2024, 14:15:47
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `form_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `academics`
--

CREATE TABLE `academics` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `title` enum('Prof','AsstProf','Phd','Msc','Bachelor','Instructor') NOT NULL,
  `email` varchar(100) NOT NULL,
  `whatsapp` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `accepted_reservations`
--

CREATE TABLE `accepted_reservations` (
  `id` int(11) NOT NULL,
  `academic_id` int(11) NOT NULL,
  `academic_name` varchar(100) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `reservation_reason` varchar(100) NOT NULL,
  `reservation_details` text NOT NULL,
  `reservation_day` varchar(20) NOT NULL,
  `reservation_time` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pending_reservations`
--

CREATE TABLE `pending_reservations` (
  `id` int(11) NOT NULL,
  `academic_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `reservation_reason` varchar(100) NOT NULL,
  `reservation_details` text NOT NULL,
  `reservation_day` varchar(20) NOT NULL,
  `reservation_time` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `academic_id` int(11) NOT NULL,
  `time_of_day` tinytext NOT NULL,
  `day_of_week` int(11) NOT NULL,
  `activity_type` tinytext NOT NULL,
  `lecture_code` tinytext DEFAULT NULL,
  `year` int(11) NOT NULL,
  `semester` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `academics`
--
ALTER TABLE `academics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Tablo için indeksler `accepted_reservations`
--
ALTER TABLE `accepted_reservations`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `pending_reservations`
--
ALTER TABLE `pending_reservations`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `academics`
--
ALTER TABLE `academics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Tablo için AUTO_INCREMENT değeri `accepted_reservations`
--
ALTER TABLE `accepted_reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Tablo için AUTO_INCREMENT değeri `pending_reservations`
--
ALTER TABLE `pending_reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- Tablo için AUTO_INCREMENT değeri `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6865;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
