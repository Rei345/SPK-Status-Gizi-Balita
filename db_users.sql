-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2024 at 01:19 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_users`
--

-- --------------------------------------------------------

--
-- Table structure for table `peserta`
--

CREATE TABLE `peserta` (
  `id_peserta` int(11) NOT NULL,
  `jadwal` date NOT NULL,
  `nik` char(20) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `berat_badan` float NOT NULL,
  `tinggi_badan` float NOT NULL,
  `gender` varchar(20) NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `peserta`
--

INSERT INTO `peserta` (`id_peserta`, `jadwal`, `nik`, `nama`, `berat_badan`, `tinggi_badan`, `gender`, `status`) VALUES
(2, '2024-08-01', '9029682092662', 'HAHAEHEEEEEE', 10.2, 60.5, 'Laki-Laki', 'Obesitas');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id_akun` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `verify_token` varchar(150) NOT NULL,
  `verify_status` int(11) NOT NULL,
  `reset_pascode` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id_akun`, `username`, `email`, `password`, `verify_token`, `verify_status`, `reset_pascode`) VALUES
(1, 'Reinhard03', 'mlbbprogramers@gmail.com', '$2y$10$WXbUEnpC38a2mYaNehkPUurQhbjDwIgXNpJk8qog6g2eHQikSswx.', '8cfbc63faac57c3208ee72e0998e2a07', 1, '0'),
(2, 'raki', 'raflysiahaan9@gmail.com', '$2y$10$A0sIAwm77Y2rdC/0jYqLWeW0tOFNe6hF/yAFE7BzghMzlC9/f45OK', '9c6043c845d0e834d4721c4681e3b1b1', 1, '0'),
(3, 'Reinhard Sitompul', 'reinhardmarcelinositompul@students.polmed.ac.id', '$2y$10$w6Jgw8dBZZi.e65ewwe2SucLDEdlzYrGVVgBlT7oNta5TV/PNQhRS', '9df5c99bc2b73a79fe852a5e5f205147', 1, '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `peserta`
--
ALTER TABLE `peserta`
  ADD PRIMARY KEY (`id_peserta`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id_akun`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id_peserta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id_akun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
