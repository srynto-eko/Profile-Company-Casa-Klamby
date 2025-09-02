-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025 at 12:18 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `casa_klamby`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int NOT NULL,
  `nama` varchar(500) NOT NULL,
  `laman` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `nama`, `laman`) VALUES
(1, 'Instagram', 'https:/instagram.com/casaklamby'),
(2, 'Tiktok', 'https://www.tiktok.com/@casa.klamby?is_from_webapp=1&sender_device=pc'),
(3, 'Shopee', 'https://id.shp.ee/HvHhL2i');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int NOT NULL,
  `tanya` varchar(500) NOT NULL,
  `jawab` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `tanya`, `jawab`) VALUES
(1, 'Bagaimana Cara Pemesanan Produk Casa Klamby?', 'Sahabat Casa bisa mengunjungi official store resmi casa secara langsung'),
(2, 'Cek Tanya 2', 'Jawaban 2'),
(3, 'Cek Tanya 3', 'Jawaban 3');

-- --------------------------------------------------------

--
-- Table structure for table `misi`
--

CREATE TABLE `misi` (
  `id` int NOT NULL,
  `misi` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `misi`
--

INSERT INTO `misi` (`id`, `misi`) VALUES
(1, 'Menyediakan koleksi busana Muslim yang modern, nyaman, dan sesuai tren fashion terkini.'),
(2, 'Mengutamakan kualitas bahan dan desain untuk memberikan kepuasan maksimal kepada pelanggan.'),
(3, 'Memberdayakan pengrajin lokal dan mendukung industri fashion dalam negeri.'),
(4, 'Mengedepankan nilai-nilai etika dan keberlanjutan dalam proses produksi.'),
(5, 'Membangun komunitas Muslimah yang percaya diri, berkarakter, dan berbudaya melalui fashion yang syar\'i namun tetap stylish.');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kategori` enum('dad','mom','kid') NOT NULL,
  `deskripsi` varchar(500) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama`, `kategori`, `deskripsi`, `image`) VALUES
(1, 'Contoh 1', 'dad', 'Deskripsi Contoh 1', '1745549285__X7A2299.jpg'),
(2, 'Contoh 2', 'mom', 'Deskripsi Contoh 2', '1745549315__X7A2707.jpg'),
(3, 'Contoh 3', 'kid', 'Deskripsi Contoh 3', '1745549307__X7A2405.jpg'),
(6, 'Ikat Melayu', 'dad', 'Deskripsi singkat Ikat melayu', '1745549620_680af93482f6b.jpg'),
(7, 'Ikat Melayu', 'mom', 'Deskripsi singkat Ikat melayu', '1745549658_680af95a1517e.jpg'),
(8, 'Ikat Melayu', 'kid', 'Deskripsi singkat Ikat melayu', '1745549688_680af9782019a.jpg'),
(9, 'Senada', 'dad', 'Deskripsi singkat Senada', '1745550086_680afb067ab9b.jpg'),
(10, 'Senada', 'kid', 'Deskripsi singkat Senada', '1745550114_680afb2227f2a.jpg'),
(11, 'Senada', 'mom', 'Deskripsi singkat Senada', '1745550141_680afb3dadf01.jpg'),
(12, 'Senada', 'dad', 'Deskripsi singkat Senada', '1745900657_68105471b2676.jpg'),
(13, 'Senada', 'dad', 'Deskripsi singkat Senada', '1745900684_6810548c59218.jpg'),
(14, 'Senada', 'kid', 'Deskripsi singkat Senada', '1745900720_681054b0b6ef9.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `profile_text`
--

CREATE TABLE `profile_text` (
  `id` int NOT NULL,
  `text1` varchar(500) NOT NULL,
  `text2` varchar(500) NOT NULL,
  `text3` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `profile_text`
--

INSERT INTO `profile_text` (`id`, `text1`, `text2`, `text3`) VALUES
(1, 'Casa Klamby merupakan brand fashion muslim yang menghadirkan koleksi modest wear modern dengan desain elegan dan kualitas terbaik. Setiap produk didesain untuk mendukung penampilan yang stylish dan percaya diri, baik di momen spesial maupun keseharian. Dengan semangat local pride with global taste, Casa Klamby siap menjadi pilihan utama dalam gaya berpakaian modest yang berkelas.', 'Menghadirkan keseimbangan antara fungsionalitas dan estetika, menjadikan Anda tampil percaya diri tanpa perlu mengorbankan kenyamanan atau gaya.  ', 'Tertarik untuk mengetahui lebih lanjut atau memiliki pertanyaan? Kami siap membantu Anda!  \nHubungi kami melalui sosial media atau kunjungi kami langsung di toko untuk mendapatkan informasi lebih lanjut tentang produk Casa Klamby.\n');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(500) NOT NULL,
  `password` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$cOmIvTHZ.sE6OudLpVBX9OoqknEf0QwhExzHXz/j0VtUIyjNbBKLm');

-- --------------------------------------------------------

--
-- Table structure for table `visi`
--

CREATE TABLE `visi` (
  `id` int NOT NULL,
  `visi` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `visi`
--

INSERT INTO `visi` (`id`, `visi`) VALUES
(1, 'Menjadi brand busana Muslim kekinian yang menginspirasi gaya hidup modest fashion dengan desain elegan, berkualitas, dan berdaya saing global.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `misi`
--
ALTER TABLE `misi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile_text`
--
ALTER TABLE `profile_text`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visi`
--
ALTER TABLE `visi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `misi`
--
ALTER TABLE `misi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `profile_text`
--
ALTER TABLE `profile_text`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `visi`
--
ALTER TABLE `visi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
