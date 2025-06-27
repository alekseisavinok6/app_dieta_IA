-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2025 at 10:17 AM
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
-- Database: `dieta_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `edad` int(11) NOT NULL,
  `sexo` enum('hombre','mujer') NOT NULL,
  `talla` decimal(6,2) DEFAULT NULL,
  `peso` decimal(6,2) NOT NULL,
  `peso_ideal` decimal(6,2) NOT NULL,
  `enfermedades` text DEFAULT NULL,
  `alergias` text DEFAULT NULL,
  `intolerancias` text DEFAULT NULL,
  `clasificacion` varchar(50) DEFAULT NULL,
  `actividad` enum('sedentario','ligera','moderada','intensa','muy_intensa') NOT NULL,
  `imc` decimal(5,2) DEFAULT NULL,
  `geb` decimal(7,2) NOT NULL,
  `get1` decimal(7,2) DEFAULT NULL,
  `vct` decimal(7,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nombre`, `apellido`, `correo`, `contrasena`, `edad`, `sexo`, `talla`, `peso`, `peso_ideal`, `enfermedades`, `alergias`, `intolerancias`, `clasificacion`, `actividad`, `imc`, `geb`, `get1`, `vct`) VALUES
(27, 'Anna', 'Dias', 'anna94barcelona@gmail.com', '$2y$10$VbNmXnLOj3kpdJBCxAEdT.Wm5MArcfzYn0eCsDMMRtVGkGEz9pLRu', 30, 'mujer', 1.88, 75.50, 77.76, 'diabetes, úlcera de estómago', 'huevos, café, leche', 'lactosa, gluten', 'peso normal', 'moderada', 21.36, 1584.63, 2456.17, 2489.52);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
