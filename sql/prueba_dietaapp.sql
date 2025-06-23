-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2025 at 07:37 PM
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
-- Database: `prueba_dietaapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `edad` int(11) NOT NULL,
  `sexo` enum('hombre','mujer') NOT NULL,
  `altura` float(5,2) NOT NULL,
  `peso` decimal(5,2) NOT NULL,
  `peso_deseado` decimal(5,2) DEFAULT NULL,
  `enfermedades` text DEFAULT NULL,
  `alergias` text DEFAULT NULL,
  `intolerancias` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `apellido`, `correo`, `password`, `edad`, `sexo`, `altura`, `peso`, `peso_deseado`, `enfermedades`, `alergias`, `intolerancias`) VALUES
(59, 'Anna', 'Dias', 'anna94barcelona@gmail.com', '$2y$10$oQCXtl3NgYjBTurZ9APiHOMYejIeoMhSlJHgkDmmcbCT2290b9DA6', 30, 'mujer', 190.00, 80.00, NULL, 'diabetes, hipertensión', 'huevos, frutos secos', 'lactosa, gluten');

-- --------------------------------------------------------

--
-- Table structure for table `datos_cliente`
--

CREATE TABLE `datos_cliente` (
  `id_cliente` int(11) NOT NULL,
  `sexo` enum('hombre','mujer') NOT NULL,
  `edad` int(11) NOT NULL,
  `talla` float(5,2) DEFAULT NULL,
  `peso` decimal(5,2) NOT NULL,
  `peso_ideal` float DEFAULT NULL,
  `clasificacion` varchar(50) DEFAULT NULL,
  `actividad` enum('sedentario','ligera','moderada','intensa','muy_intensa') NOT NULL,
  `imc` float(5,2) DEFAULT NULL,
  `gasto_energetico_basal` decimal(7,2) NOT NULL,
  `gasto_energetico_total` double(7,2) DEFAULT NULL,
  `vct` double(7,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datos_cliente`
--

INSERT INTO `datos_cliente` (`id_cliente`, `sexo`, `edad`, `talla`, `peso`, `peso_ideal`, `clasificacion`, `actividad`, `imc`, `gasto_energetico_basal`, `gasto_energetico_total`, `vct`) VALUES
(59, 'mujer', 34, 1.79, 80.00, 70.49, 'peso normal', 'ligera', 24.97, 1592.31, 2189.42, 2064.24);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indexes for table `datos_cliente`
--
ALTER TABLE `datos_cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `datos_cliente`
--
ALTER TABLE `datos_cliente`
  ADD CONSTRAINT `datos_cliente_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
