-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3307
-- Tiempo de generación: 20-05-2026 a las 04:42:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `terapia_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductas`
--

CREATE TABLE `conductas` (
  `id_conducta` int(11) NOT NULL,
  `id_plantilla` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `intensidad` int(11) DEFAULT NULL,
  `duracion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `conductas`
--

INSERT INTO `conductas` (`id_conducta`, `id_plantilla`, `id_paciente`, `descripcion`, `fecha`, `intensidad`, `duracion`) VALUES
(1, 1, 16, 'Mordi', '2026-05-01', 4, 10),
(2, 2, 16, 'Comí', '2026-05-01', 4, 5),
(3, 3, 16, 'grité', '2026-05-01', 3, 0),
(4, 2, 16, 'Sucedió', '2026-05-01', 3, 3),
(5, 2, 16, 'Comí mucho', '2026-05-01', 5, 60),
(6, 1, 19, 'Mordí', '2026-05-02', 7, 20),
(7, 3, 19, 'Grité', '2026-05-03', 7, 0),
(8, 2, 19, 'comí mucho', '2026-05-04', 7, 20),
(9, 1, 19, 'Mordí a mi mamá', '2026-05-17', 5, 10),
(10, 1, 19, 'Mordí', '2026-05-06', 3, 30),
(11, 1, 19, 'Mordí', '2026-05-07', 9, 30),
(12, 2, 20, 'Comí ', '2026-05-01', 6, 30),
(13, 2, 20, 'Comí', '2026-05-02', 3, 20),
(14, 2, 20, 'Comí', '2026-05-03', 8, 40),
(15, 2, 20, 'Comí', '2026-05-04', 9, 34),
(16, 2, 20, 'Comí mucho', '2026-05-14', 5, 50),
(17, 2, 20, 'Papas', '2026-05-06', 2, 33),
(18, 1, 19, '', '2026-05-10', 9, 34),
(19, 2, 20, 'Papa', '2026-05-10', 9, 60),
(20, 2, 19, 'Comí demasiado', '2026-05-01', 7, 70);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductas_plantilla`
--

CREATE TABLE `conductas_plantilla` (
  `id_plantilla` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `usa_intensidad` tinyint(1) DEFAULT 1,
  `usa_duracion` tinyint(1) DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `conductas_plantilla`
--

INSERT INTO `conductas_plantilla` (`id_plantilla`, `titulo`, `usa_intensidad`, `usa_duracion`, `is_active`) VALUES
(1, 'Morder', 1, 1, 1),
(2, 'Comer', 1, 1, 1),
(3, 'Gritar', 1, 0, 0),
(4, 'Correr', 0, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relaciones`
--

CREATE TABLE `relaciones` (
  `id_relacion` int(11) NOT NULL,
  `id_terapeuta` int(11) DEFAULT NULL,
  `id_paciente` int(11) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `relaciones`
--

INSERT INTO `relaciones` (`id_relacion`, `id_terapeuta`, `id_paciente`, `fecha_inicio`, `fecha_fin`, `is_active`) VALUES
(1, 15, 16, '2026-04-28', NULL, 1),
(2, 15, 17, '2026-04-30', NULL, 0),
(3, 18, 19, '2026-04-30', NULL, 1),
(4, 18, 20, '2026-04-30', NULL, 1),
(5, 18, 21, '2026-05-19', NULL, 1),
(6, 18, 22, '2026-05-19', NULL, 1),
(7, 18, 23, '2026-05-19', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` enum('M','F','O') DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `rol` enum('paciente','terapeuta') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `username`, `fecha_nacimiento`, `sexo`, `email`, `password`, `rol`) VALUES
(9, '', 'Omero', NULL, NULL, 'omero@Sinso', '$2y$10$8IaFP2Lqh6cJKXsJZxtJ4.KmfSAYkR6w6uCSrYIz8bDbmSSawpm3u', 'terapeuta'),
(15, '', 'Hola', NULL, NULL, 'Hola@mail.com', '$2y$10$dXgFUFYieMWIv0y/h5oqqu3Ett4OIosMmaasG30eGEcB/kJbUDDES', 'terapeuta'),
(16, NULL, 'Paci', '2026-04-10', 'M', 'Paci@mail.com', '$2y$10$eR6ugJUbAq.bGWv/GDjK9.k5.7SksTEFP40wznpmLhBakVxZmAytW', 'paciente'),
(17, NULL, 'paci2', '2026-04-09', 'M', 'paci2@mail.com', '$2y$10$z7T5Qw9jc4DcyA2fDGJDJO2zAUBgM7B0mBnq.s.6Sm3ovNcH3iwpW', 'paciente'),
(18, '', 'Ramiro Ramírez', NULL, NULL, 'profesional@gmail.com', '$2y$10$q98xbpTOR9NvFAuZf2GaTuRwgaLDTw9VEqRJHeAnH8UrviCI/dXIm', 'terapeuta'),
(19, NULL, 'Pedro Ramírez', '0000-00-00', '', 'ramirez@gmail.com', '$2y$10$DPGSFuxupfVIkbSOYmuwHObtZvYRWBkNZWg5WTNNxJ55VRYWVBMy6', 'paciente'),
(20, NULL, 'Carlos Perez', '0000-00-00', '', 'perez@gmail.com', '$2y$10$nHfCb6/E.McjWAwJpQ3xu.dBSPeyVhnYX78g5z9T.t/5MaJFs2zDO', 'paciente'),
(21, NULL, 'Fono Perez', '0000-00-00', '', 'fono@gmail.com', '$2y$10$5oOybB9ON4.p9beVS40nQe1rSpEHQOExsqMZ8clw7nNb4TD8EAbpi', 'paciente'),
(22, NULL, 'Tapioca Ramirez', NULL, NULL, 'tapioca@gmail.com', '$2y$10$XJAPU8O1nFa9HaD3QPlAo.mn.z39GFSweHxZjuSzPLQ3htcOV0WXW', 'paciente'),
(23, NULL, 'Pedro Perez', NULL, NULL, 'pedro@gmail.com', '$2y$10$7qHGfaYOlx6R4A6aEY.jNeVmXfgGceixu23C0N6aacaQTntPZiazq', 'paciente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `conductas`
--
ALTER TABLE `conductas`
  ADD PRIMARY KEY (`id_conducta`),
  ADD KEY `id_plantilla` (`id_plantilla`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `conductas_plantilla`
--
ALTER TABLE `conductas_plantilla`
  ADD PRIMARY KEY (`id_plantilla`);

--
-- Indices de la tabla `relaciones`
--
ALTER TABLE `relaciones`
  ADD PRIMARY KEY (`id_relacion`),
  ADD KEY `id_terapeuta` (`id_terapeuta`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `conductas`
--
ALTER TABLE `conductas`
  MODIFY `id_conducta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `conductas_plantilla`
--
ALTER TABLE `conductas_plantilla`
  MODIFY `id_plantilla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `relaciones`
--
ALTER TABLE `relaciones`
  MODIFY `id_relacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `conductas`
--
ALTER TABLE `conductas`
  ADD CONSTRAINT `conductas_ibfk_1` FOREIGN KEY (`id_plantilla`) REFERENCES `conductas_plantilla` (`id_plantilla`),
  ADD CONSTRAINT `conductas_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `relaciones`
--
ALTER TABLE `relaciones`
  ADD CONSTRAINT `relaciones_ibfk_1` FOREIGN KEY (`id_terapeuta`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `relaciones_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
