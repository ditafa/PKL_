-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2025 at 03:29 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pkl`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggaran`
--

CREATE TABLE `anggaran` (
  `id` int(11) NOT NULL,
  `bagian` varchar(100) NOT NULL,
  `total_anggaran` int(11) NOT NULL,
  `januari` int(11) DEFAULT 0,
  `februari` int(11) DEFAULT 0,
  `maret` int(11) DEFAULT 0,
  `april` int(11) DEFAULT 0,
  `mei` int(11) DEFAULT 0,
  `juni` int(11) DEFAULT 0,
  `juli` int(11) DEFAULT 0,
  `agustus` int(11) DEFAULT 0,
  `september` int(11) DEFAULT 0,
  `oktober` int(11) DEFAULT 0,
  `november` int(11) DEFAULT 0,
  `desember` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anggaran`
--

INSERT INTO `anggaran` (`id`, `bagian`, `total_anggaran`, `januari`, `februari`, `maret`, `april`, `mei`, `juni`, `juli`, `agustus`, `september`, `oktober`, `november`, `desember`, `created_at`) VALUES
(7, 'perencanaan', 24000000, 2000000, 2000000, 2000000, 2000000, 2000000, 2000000, 2000000, 2000000, 2000000, 2000000, 1000000, 3000000, '2025-02-13 14:11:27'),
(10, 'kesejahteraan', 12000000, 1000000, 1000000, 1000000, 1000000, 1000000, 1000000, 1000000, 1000000, 1000000, 1000000, 1500000, 500000, '2025-02-14 02:13:54');

-- --------------------------------------------------------

--
-- Table structure for table `angkas`
--

CREATE TABLE `angkas` (
  `id` int(11) NOT NULL,
  `id_kesejahteraan` int(11) DEFAULT NULL,
  `total_anggaran` int(11) NOT NULL,
  `bulan` varchar(20) NOT NULL,
  `sisa_anggaran` int(11) NOT NULL,
  `keterangan_bukti` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `angkas`
--

INSERT INTO `angkas` (`id`, `id_kesejahteraan`, `total_anggaran`, `bulan`, `sisa_anggaran`, `keterangan_bukti`) VALUES
(1, 1, 10000000, 'Februari', 9000000, '0'),
(2, 2, 10000000, 'Februari', 9000000, '0');

-- --------------------------------------------------------

--
-- Table structure for table `kesejahteraan`
--

CREATE TABLE `kesejahteraan` (
  `id` int(11) NOT NULL,
  `nama_program` varchar(255) NOT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `sub_kegiatan` varchar(255) NOT NULL,
  `kd_rek` varchar(50) NOT NULL,
  `uraian` text NOT NULL,
  `total_anggaran` int(11) NOT NULL,
  `bulan` varchar(20) NOT NULL,
  `bukti` varchar(255) NOT NULL,
  `keterangan_bukti` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kesejahteraan`
--

INSERT INTO `kesejahteraan` (`id`, `nama_program`, `nama_kegiatan`, `sub_kegiatan`, `kd_rek`, `uraian`, `total_anggaran`, `bulan`, `bukti`, `keterangan_bukti`) VALUES
(1, 'Program PEMERINTAHAN DAN KESEJAHTERAAN RAKYAT', 'Pelaksanaan Kebijakan Kesejahteraan Rakyat', 'Fasilitas Pengelolaan Bina Mental Spiritual', '5.1.02.01.01.0002', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Bahan Cetak', 10000000, 'Februari', 'sudah ada', 'sudah ada'),
(2, 'Program PEMERINTAHAN DAN KESEJAHTERAAN RAKYAT', 'Pelaksanaan Kebijakan Kesejahteraan Rakyat', 'Fasilitas Pengelolaan Bina Mental Spiritual', '5.1.02.01.01.0002', 'Belanja Alat/Bahan untuk Kegiatan Kantor-Bahan Cetak', 10000000, 'Februari', 'sudah ada', 'sudah ada');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggaran`
--
ALTER TABLE `anggaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `angkas`
--
ALTER TABLE `angkas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kesejahteraan` (`id_kesejahteraan`);

--
-- Indexes for table `kesejahteraan`
--
ALTER TABLE `kesejahteraan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggaran`
--
ALTER TABLE `anggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `angkas`
--
ALTER TABLE `angkas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kesejahteraan`
--
ALTER TABLE `kesejahteraan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `angkas`
--
ALTER TABLE `angkas`
  ADD CONSTRAINT `angkas_ibfk_1` FOREIGN KEY (`id_kesejahteraan`) REFERENCES `kesejahteraan` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
