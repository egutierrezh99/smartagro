-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-11-2023 a las 23:03:18
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `smartagro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estacion`
--

CREATE TABLE `estacion` (
  `id_estacion` int(11) NOT NULL,
  `ubicacion` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estacion`
--

INSERT INTO `estacion` (`id_estacion`, `ubicacion`) VALUES
(1, 'Cianca');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estacion_usuario`
--

CREATE TABLE `estacion_usuario` (
  `fk_usuario` varchar(15) NOT NULL,
  `fk_estacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estacion_usuario`
--

INSERT INTO `estacion_usuario` (`fk_usuario`, `fk_estacion`) VALUES
('prueba', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hsuelo`
--

CREATE TABLE `hsuelo` (
  `fk_id_sensor` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hsuelo`
--

INSERT INTO `hsuelo` (`fk_id_sensor`, `valor`, `hora`) VALUES
(1, 25.00, '2023-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `humedad`
--

CREATE TABLE `humedad` (
  `fk_id_sensor` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ph`
--

CREATE TABLE `ph` (
  `fk_id_sensor` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sensores`
--

CREATE TABLE `sensores` (
  `id_sensor` int(11) NOT NULL,
  `fk_estacion` int(11) NOT NULL,
  `tipo` enum('suelo','humedad','temperatura','ph') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sensores`
--

INSERT INTO `sensores` (`id_sensor`, `fk_estacion`, `tipo`) VALUES
(1, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temperatura`
--

CREATE TABLE `temperatura` (
  `fk_id_sensor` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `nombre` varchar(15) NOT NULL,
  `contra` varchar(20) NOT NULL,
  `email` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`nombre`, `contra`, `email`) VALUES
('prueba', 'prueba', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estacion`
--
ALTER TABLE `estacion`
  ADD PRIMARY KEY (`id_estacion`);

--
-- Indices de la tabla `estacion_usuario`
--
ALTER TABLE `estacion_usuario`
  ADD PRIMARY KEY (`fk_usuario`,`fk_estacion`),
  ADD KEY `fk_estacion` (`fk_estacion`);

--
-- Indices de la tabla `hsuelo`
--
ALTER TABLE `hsuelo`
  ADD PRIMARY KEY (`fk_id_sensor`,`valor`,`hora`);

--
-- Indices de la tabla `humedad`
--
ALTER TABLE `humedad`
  ADD PRIMARY KEY (`fk_id_sensor`,`valor`,`hora`);

--
-- Indices de la tabla `ph`
--
ALTER TABLE `ph`
  ADD PRIMARY KEY (`fk_id_sensor`,`valor`,`hora`);

--
-- Indices de la tabla `sensores`
--
ALTER TABLE `sensores`
  ADD PRIMARY KEY (`id_sensor`),
  ADD KEY `fk_estacion` (`fk_estacion`);

--
-- Indices de la tabla `temperatura`
--
ALTER TABLE `temperatura`
  ADD PRIMARY KEY (`fk_id_sensor`,`valor`,`hora`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`nombre`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estacion`
--
ALTER TABLE `estacion`
  MODIFY `id_estacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `sensores`
--
ALTER TABLE `sensores`
  MODIFY `id_sensor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `estacion_usuario`
--
ALTER TABLE `estacion_usuario`
  ADD CONSTRAINT `estacion_usuario_ibfk_1` FOREIGN KEY (`fk_usuario`) REFERENCES `usuario` (`nombre`),
  ADD CONSTRAINT `estacion_usuario_ibfk_2` FOREIGN KEY (`fk_estacion`) REFERENCES `estacion` (`id_estacion`);

--
-- Filtros para la tabla `hsuelo`
--
ALTER TABLE `hsuelo`
  ADD CONSTRAINT `hsuelo_ibfk_1` FOREIGN KEY (`fk_id_sensor`) REFERENCES `sensores` (`id_sensor`);

--
-- Filtros para la tabla `humedad`
--
ALTER TABLE `humedad`
  ADD CONSTRAINT `humedad_ibfk_1` FOREIGN KEY (`fk_id_sensor`) REFERENCES `sensores` (`id_sensor`);

--
-- Filtros para la tabla `ph`
--
ALTER TABLE `ph`
  ADD CONSTRAINT `ph_ibfk_1` FOREIGN KEY (`fk_id_sensor`) REFERENCES `sensores` (`id_sensor`);

--
-- Filtros para la tabla `sensores`
--
ALTER TABLE `sensores`
  ADD CONSTRAINT `sensores_ibfk_1` FOREIGN KEY (`fk_estacion`) REFERENCES `estacion` (`id_estacion`);

--
-- Filtros para la tabla `temperatura`
--
ALTER TABLE `temperatura`
  ADD CONSTRAINT `temperatura_ibfk_1` FOREIGN KEY (`fk_id_sensor`) REFERENCES `sensores` (`id_sensor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
