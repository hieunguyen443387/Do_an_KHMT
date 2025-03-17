-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2025 at 10:49 AM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(10) NOT NULL,
  `ho_dem` varchar(100) NOT NULL,
  `ten` varchar(100) NOT NULL,
  `mat_khau` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `ho_dem`, `ten`, `mat_khau`) VALUES
(2121, 'Nguyễn Trung', 'Hiếu', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `dangkythi`
--

CREATE TABLE `dangkythi` (
  `msv` int(10) NOT NULL,
  `ma_lich_thi` int(11) NOT NULL,
  `ngay_dang_ky` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `giangvien`
--

CREATE TABLE `giangvien` (
  `mgv` int(10) NOT NULL,
  `ho_dem` varchar(50) NOT NULL,
  `ten` varchar(50) NOT NULL,
  `khoa` varchar(100) NOT NULL,
  `ngay_sinh` date NOT NULL,
  `gioi_tinh` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `giangvien`
--

INSERT INTO `giangvien` (`mgv`, `ho_dem`, `ten`, `khoa`, `ngay_sinh`, `gioi_tinh`) VALUES
(3131, 'Đoàn Minh', 'Long', 'Công nghệ thông tin', '2025-03-19', 'Nam');

-- --------------------------------------------------------

--
-- Table structure for table `hocphan`
--

CREATE TABLE `hocphan` (
  `ma_hoc_phan` int(10) NOT NULL,
  `ten_hoc_phan` varchar(100) NOT NULL,
  `so_tin_chi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hocphan`
--

INSERT INTO `hocphan` (`ma_hoc_phan`, `ten_hoc_phan`, `so_tin_chi`) VALUES
(7080111, 'Pháp luật đại cương', 3);

-- --------------------------------------------------------

--
-- Table structure for table `lichthi`
--

CREATE TABLE `lichthi` (
  `ma_lich_thi` int(11) NOT NULL,
  `ma_hoc_phan` int(10) NOT NULL,
  `ngay_thi` date NOT NULL,
  `gio_bat_dau` time NOT NULL,
  `gio_ket_thuc` time NOT NULL,
  `ma_phong` varchar(10) NOT NULL,
  `mgv` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lichthi`
--

INSERT INTO `lichthi` (`ma_lich_thi`, `ma_hoc_phan`, `ngay_thi`, `gio_bat_dau`, `gio_ket_thuc`, `ma_phong`, `mgv`) VALUES
(1, 7080111, '2025-03-19', '15:30:00', '16:20:00', 'P501', 3131),
(2, 7080111, '2025-03-06', '18:20:00', '19:10:00', 'P501', 3131),
(6, 7080111, '2025-03-19', '07:20:00', '08:10:00', 'P501', 3131),
(10, 7080111, '2025-03-19', '14:33:00', '15:23:00', 'P501', 3131);

-- --------------------------------------------------------

--
-- Table structure for table `phongthi`
--

CREATE TABLE `phongthi` (
  `ma_phong` varchar(10) NOT NULL,
  `suc_chua` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phongthi`
--

INSERT INTO `phongthi` (`ma_phong`, `suc_chua`) VALUES
('P501', 50);

-- --------------------------------------------------------

--
-- Table structure for table `sinhvien`
--

CREATE TABLE `sinhvien` (
  `msv` int(10) NOT NULL,
  `ho_dem` varchar(50) NOT NULL,
  `ten` varchar(50) NOT NULL,
  `ngay_sinh` date NOT NULL,
  `khoa` varchar(100) NOT NULL,
  `lop` varchar(20) NOT NULL,
  `gioi_tinh` varchar(10) NOT NULL,
  `mat_khau` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sinhvien`
--

INSERT INTO `sinhvien` (`msv`, `ho_dem`, `ten`, `ngay_sinh`, `khoa`, `lop`, `gioi_tinh`, `mat_khau`) VALUES
(7575, 'Đoàn Văn', 'Đoàn Văn', '2003-09-29', 'CNTT', 'DCCTCT66K1', 'Nam', '29092003'),
(9191, 'Đoàn Minh', 'Anh', '2025-03-04', 'Công nghệ thông tin', 'DCCTCT66K1', 'Nam', '04032025');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `dangkythi`
--
ALTER TABLE `dangkythi`
  ADD PRIMARY KEY (`msv`,`ma_lich_thi`),
  ADD KEY `ma_lich_thi` (`ma_lich_thi`),
  ADD KEY `msv` (`msv`) USING BTREE;

--
-- Indexes for table `giangvien`
--
ALTER TABLE `giangvien`
  ADD PRIMARY KEY (`mgv`),
  ADD UNIQUE KEY `email` (`khoa`);

--
-- Indexes for table `hocphan`
--
ALTER TABLE `hocphan`
  ADD PRIMARY KEY (`ma_hoc_phan`);

--
-- Indexes for table `lichthi`
--
ALTER TABLE `lichthi`
  ADD PRIMARY KEY (`ma_lich_thi`),
  ADD KEY `ma_mon_hoc` (`ma_hoc_phan`),
  ADD KEY `ma_phong` (`ma_phong`),
  ADD KEY `ma_giang_vien` (`mgv`);

--
-- Indexes for table `phongthi`
--
ALTER TABLE `phongthi`
  ADD PRIMARY KEY (`ma_phong`);

--
-- Indexes for table `sinhvien`
--
ALTER TABLE `sinhvien`
  ADD PRIMARY KEY (`msv`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lichthi`
--
ALTER TABLE `lichthi`
  MODIFY `ma_lich_thi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dangkythi`
--
ALTER TABLE `dangkythi`
  ADD CONSTRAINT `dangkythi_ibfk_1` FOREIGN KEY (`msv`) REFERENCES `sinhvien` (`msv`) ON DELETE CASCADE,
  ADD CONSTRAINT `dangkythi_ibfk_2` FOREIGN KEY (`ma_lich_thi`) REFERENCES `lichthi` (`ma_lich_thi`) ON DELETE CASCADE;

--
-- Constraints for table `lichthi`
--
ALTER TABLE `lichthi`
  ADD CONSTRAINT `lichthi_ibfk_1` FOREIGN KEY (`ma_hoc_phan`) REFERENCES `hocphan` (`ma_hoc_phan`) ON DELETE CASCADE,
  ADD CONSTRAINT `lichthi_ibfk_2` FOREIGN KEY (`ma_phong`) REFERENCES `phongthi` (`ma_phong`) ON DELETE CASCADE,
  ADD CONSTRAINT `lichthi_ibfk_3` FOREIGN KEY (`mgv`) REFERENCES `giangvien` (`mgv`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
