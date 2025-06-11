-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2025 at 09:54 PM
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
-- Table structure for table `alimentos`
--

CREATE TABLE `alimentos` (
  `id_alimentos` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `alergenos` text DEFAULT NULL,
  `intolerancias` text DEFAULT NULL,
  `valor_calórico` int(11) NOT NULL,
  `peso_neto` varchar(10) NOT NULL,
  `peso_bruto` varchar(10) NOT NULL,
  `cantidad_vp` varchar(10) NOT NULL,
  `carbohidratos` decimal(2,2) NOT NULL,
  `proteinas` decimal(2,2) NOT NULL,
  `grasas` decimal(2,2) NOT NULL,
  `saturados` decimal(2,2) NOT NULL,
  `monoinsaturados` decimal(2,2) NOT NULL,
  `poliinsaturados` decimal(2,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `sexo` enum('Hombre','Mujer') NOT NULL,
  `altura` int(11) NOT NULL,
  `peso` int(11) NOT NULL,
  `peso_deseado` int(11) DEFAULT NULL,
  `enfermedades` text DEFAULT NULL,
  `alergias` text DEFAULT NULL,
  `intolerancias` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `apellido`, `correo`, `password`, `edad`, `sexo`, `altura`, `peso`, `peso_deseado`, `enfermedades`, `alergias`, `intolerancias`) VALUES
(2, 'Aleksei', 'Savinok', 'aleksei6madrid@gmail.com', '$2y$10$BW0tpo1tlSzbZe.sqSBKm.NGFXlr9jVO4I2F1ihXKBhf.6fpYFz5O', 44, 'Hombre', 180, 80, 80, 'NULL', '', 'NULL');

-- --------------------------------------------------------

--
-- Table structure for table `datos_cliente`
--

CREATE TABLE `datos_cliente` (
  `id_cliente` int(11) NOT NULL,
  `sexo` enum('masculino','femenino') NOT NULL,
  `actividad` enum('sedentario','ligero','moderado','intenso','muy_intenso') NOT NULL,
  `peso` decimal(5,2) NOT NULL,
  `talla` float(5,2) DEFAULT NULL,
  `edad` int(11) NOT NULL,
  `geb` decimal(7,2) NOT NULL,
  `get` double DEFAULT NULL,
  `vct` double DEFAULT NULL,
  `imc` float DEFAULT NULL,
  `peso_ideal` float DEFAULT NULL,
  `clasificacion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datos_cliente`
--

INSERT INTO `datos_cliente` (`id_cliente`, `sexo`, `actividad`, `peso`, `talla`, `edad`, `geb`, `get`, `vct`, `imc`, `peso_ideal`, `clasificacion`) VALUES
(2, '', 'ligero', 80.00, 1.80, 36, 1823.14, 2506.8174999999997, 2342.45, 24.6914, 71.28, 'Peso normal');

-- --------------------------------------------------------

--
-- Table structure for table `dieta`
--

CREATE TABLE `dieta` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `datos_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`datos_json`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredientes`
--

CREATE TABLE `ingredientes` (
  `id_ingrediente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `nutriente_principal` varchar(50) DEFAULT NULL,
  `alergenos` varchar(100) DEFAULT NULL,
  `calorias_por_porcion` int(11) DEFAULT NULL,
  `peso_por_porcion` varchar(20) DEFAULT NULL,
  `medida_porcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredientes`
--

INSERT INTO `ingredientes` (`id_ingrediente`, `nombre`, `nutriente_principal`, `alergenos`, `calorias_por_porcion`, `peso_por_porcion`, `medida_porcion`) VALUES
(1, 'Avena', 'Carbohidrato', 'Gluten', 150, '40g', 'media taza'),
(2, 'Plátano', 'Carbohidrato', 'Ninguno', 90, '100g', '1 unidad'),
(3, 'Leche', 'Proteína', 'Lácteos', 160, '200ml', '1 vaso'),
(4, 'Pechuga de pollo', 'Proteína', 'Ninguno', 200, '150g', '1 filete'),
(5, 'Lechuga', 'Fibra', 'Ninguno', 30, '50g', '1 taza'),
(6, 'Aceite de oliva', 'Grasa saludable', 'Ninguno', 90, '10g', '1 cucharada'),
(7, 'Huevos', 'Proteína', 'Huevo', 200, '100g', '2 unidades'),
(8, 'Calabacín', 'Fibra', 'Ninguno', 50, '100g', '1 taza'),
(9, 'Cebolla', 'Fibra', 'Ninguno', 30, '50g', 'media unidad'),
(10, 'Manzana', 'Carbohidrato', 'Ninguno', 95, '150g', '1 unidad'),
(11, 'Yogur natural', 'Proteína', 'Lácteos', 100, '125g', '1 envase'),
(12, 'Pan integral', 'Carbohidrato', 'Gluten', 110, '50g', '1 rebanada'),
(13, 'Atún en lata', 'Proteína', 'Pescado', 120, '80g', '1 lata'),
(14, 'Tomate', 'Fibra', 'Ninguno', 25, '100g', '1 unidad');

-- --------------------------------------------------------

--
-- Table structure for table `platos`
--

CREATE TABLE `platos` (
  `id_plato` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('Desayuno','Almuerzo','Cena','Merienda','Snack') NOT NULL,
  `calorias_totales` int(11) NOT NULL,
  `objetivo` enum('subirPeso','mantenerPeso','bajarPeso') NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `platos`
--

INSERT INTO `platos` (`id_plato`, `nombre`, `tipo`, `calorias_totales`, `objetivo`, `activo`) VALUES
(1, 'Avena con frutas', 'Desayuno', 400, 'mantenerPeso', 1),
(2, 'Ensalada de pollo', 'Almuerzo', 500, 'mantenerPeso', 1),
(3, 'Tortilla de verduras', 'Cena', 500, 'mantenerPeso', 1),
(4, 'Manzana con yogur', 'Snack', 195, 'mantenerPeso', 1),
(5, 'Sándwich integral de atún', 'Cena', 350, 'mantenerPeso', 1);

-- --------------------------------------------------------

--
-- Table structure for table `plato_ingredientes`
--

CREATE TABLE `plato_ingredientes` (
  `id_plato` int(11) NOT NULL,
  `id_ingrediente` int(11) NOT NULL,
  `cantidad_porcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plato_ingredientes`
--

INSERT INTO `plato_ingredientes` (`id_plato`, `id_ingrediente`, `cantidad_porcion`) VALUES
(1, 1, 'media taza'),
(1, 2, '1 unidad'),
(1, 3, '1 vaso'),
(2, 4, '1 filete'),
(2, 5, '1 taza'),
(2, 6, '1 cucharada'),
(3, 7, '2 unidades'),
(3, 8, '1 taza'),
(3, 9, 'media unidad'),
(4, 10, '1 unidad'),
(4, 11, '1 envase'),
(5, 12, '1 rebanada'),
(5, 13, '1 lata'),
(5, 14, '1 unidad');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alimentos`
--
ALTER TABLE `alimentos`
  ADD PRIMARY KEY (`id_alimentos`);

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
-- Indexes for table `dieta`
--
ALTER TABLE `dieta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD PRIMARY KEY (`id_ingrediente`);

--
-- Indexes for table `platos`
--
ALTER TABLE `platos`
  ADD PRIMARY KEY (`id_plato`);

--
-- Indexes for table `plato_ingredientes`
--
ALTER TABLE `plato_ingredientes`
  ADD PRIMARY KEY (`id_plato`,`id_ingrediente`),
  ADD KEY `id_ingrediente` (`id_ingrediente`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alimentos`
--
ALTER TABLE `alimentos`
  MODIFY `id_alimentos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dieta`
--
ALTER TABLE `dieta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ingredientes`
--
ALTER TABLE `ingredientes`
  MODIFY `id_ingrediente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `platos`
--
ALTER TABLE `platos`
  MODIFY `id_plato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `datos_cliente`
--
ALTER TABLE `datos_cliente`
  ADD CONSTRAINT `datos_cliente_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Constraints for table `plato_ingredientes`
--
ALTER TABLE `plato_ingredientes`
  ADD CONSTRAINT `plato_ingredientes_ibfk_1` FOREIGN KEY (`id_plato`) REFERENCES `platos` (`id_plato`) ON DELETE CASCADE,
  ADD CONSTRAINT `plato_ingredientes_ibfk_2` FOREIGN KEY (`id_ingrediente`) REFERENCES `ingredientes` (`id_ingrediente`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
