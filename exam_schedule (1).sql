-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2025 at 03:20 PM
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
-- Database: `exam_schedule`
--

-- --------------------------------------------------------

--
-- Table structure for table `canbo`
--

CREATE TABLE `canbo` (
  `id_admin` int(10) NOT NULL,
  `ho_dem` varchar(100) NOT NULL,
  `ten` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dangkythi`
--

CREATE TABLE `dangkythi` (
  `ma_dang_ky` int(11) NOT NULL,
  `ma_sinh_vien` int(10) NOT NULL,
  `ma_lich_thi` int(11) NOT NULL,
  `ngay_dang_ky` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `giangvien`
--

CREATE TABLE `giangvien` (
  `ma_giang_vien` int(10) NOT NULL,
  `ho_dem` varchar(50) NOT NULL,
  `ten` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ngay_sinh` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lichthi`
--

CREATE TABLE `lichthi` (
  `ma_lich_thi` int(11) NOT NULL,
  `ma_mon_hoc` int(10) NOT NULL,
  `ngay_thi` date NOT NULL,
  `gio_bat_dau` time NOT NULL,
  `gio_ket_thuc` time NOT NULL,
  `ma_phong` varchar(10) NOT NULL,
  `ma_giang_vien` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monhoc`
--

CREATE TABLE `monhoc` (
  `ma_mon_hoc` int(10) NOT NULL,
  `ten_mon_hoc` varchar(100) NOT NULL,
  `so_tin_chi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phongthi`
--

CREATE TABLE `phongthi` (
  `ma_phong` varchar(10) NOT NULL,
  `suc_chua` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sinhvien`
--

CREATE TABLE `sinhvien` (
  `ma_sinh_vien` int(10) NOT NULL,
  `ho_dem` varchar(50) NOT NULL,
  `ten` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ngay_sinh` date NOT NULL,
  `khoa` int(11) NOT NULL,
  `lop` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `canbo`
--
ALTER TABLE `canbo`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `dangkythi`
--
ALTER TABLE `dangkythi`
  ADD PRIMARY KEY (`ma_dang_ky`),
  ADD KEY `ma_sinh_vien` (`ma_sinh_vien`),
  ADD KEY `ma_lich_thi` (`ma_lich_thi`);

--
-- Indexes for table `giangvien`
--
ALTER TABLE `giangvien`
  ADD PRIMARY KEY (`ma_giang_vien`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `lichthi`
--
ALTER TABLE `lichthi`
  ADD PRIMARY KEY (`ma_lich_thi`),
  ADD KEY `ma_mon_hoc` (`ma_mon_hoc`),
  ADD KEY `ma_phong` (`ma_phong`),
  ADD KEY `ma_giang_vien` (`ma_giang_vien`);

--
-- Indexes for table `monhoc`
--
ALTER TABLE `monhoc`
  ADD PRIMARY KEY (`ma_mon_hoc`);

--
-- Indexes for table `phongthi`
--
ALTER TABLE `phongthi`
  ADD PRIMARY KEY (`ma_phong`);

--
-- Indexes for table `sinhvien`
--
ALTER TABLE `sinhvien`
  ADD PRIMARY KEY (`ma_sinh_vien`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dangkythi`
--
ALTER TABLE `dangkythi`
  MODIFY `ma_dang_ky` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lichthi`
--
ALTER TABLE `lichthi`
  MODIFY `ma_lich_thi` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dangkythi`
--
ALTER TABLE `dangkythi`
  ADD CONSTRAINT `dangkythi_ibfk_1` FOREIGN KEY (`ma_sinh_vien`) REFERENCES `sinhvien` (`ma_sinh_vien`) ON DELETE CASCADE,
  ADD CONSTRAINT `dangkythi_ibfk_2` FOREIGN KEY (`ma_lich_thi`) REFERENCES `lichthi` (`ma_lich_thi`) ON DELETE CASCADE;

--
-- Constraints for table `lichthi`
--
ALTER TABLE `lichthi`
  ADD CONSTRAINT `lichthi_ibfk_1` FOREIGN KEY (`ma_mon_hoc`) REFERENCES `monhoc` (`ma_mon_hoc`) ON DELETE CASCADE,
  ADD CONSTRAINT `lichthi_ibfk_2` FOREIGN KEY (`ma_phong`) REFERENCES `phongthi` (`ma_phong`) ON DELETE CASCADE,
  ADD CONSTRAINT `lichthi_ibfk_3` FOREIGN KEY (`ma_giang_vien`) REFERENCES `giangvien` (`ma_giang_vien`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
