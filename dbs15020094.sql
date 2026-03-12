-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-03-2026 a las 19:43:04
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
-- Base de datos: `dbs15020094`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campañas`
--

CREATE TABLE `campañas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `objetivo_tipo` varchar(50) DEFAULT NULL,
  `objetivo_detalle` text DEFAULT NULL,
  `publico_objetivo` text DEFAULT NULL,
  `presupuesto` decimal(12,2) DEFAULT 0.00,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'activo',
  `notas` text DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `creada_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `servicio_id` int(11) DEFAULT NULL,
  `redes_sociales` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campaña_estrategias`
--

CREATE TABLE `campaña_estrategias` (
  `id` int(11) NOT NULL,
  `campaña_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `propuesta` text NOT NULL,
  `canal` varchar(80) DEFAULT NULL,
  `tipo_accion` enum('publicacion','email','evento','ads','otro') DEFAULT 'otro',
  `cta` varchar(160) DEFAULT NULL,
  `aprobado` tinyint(1) DEFAULT 0,
  `completada` tinyint(1) DEFAULT 0,
  `orden` int(11) DEFAULT 0,
  `fecha_programada` date DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campaña_planes`
--

CREATE TABLE `campaña_planes` (
  `id` int(11) NOT NULL,
  `campaña_id` int(11) NOT NULL,
  `plan_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campaña_resultados`
--

CREATE TABLE `campaña_resultados` (
  `id` int(11) NOT NULL,
  `campaña_id` int(11) NOT NULL,
  `fecha_registro` date NOT NULL,
  `kpi_nombre` varchar(100) NOT NULL,
  `valor_objetivo` decimal(10,2) DEFAULT 0.00,
  `valor_real` decimal(10,2) DEFAULT 0.00,
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes_potenciales`
--

CREATE TABLE `clientes_potenciales` (
  `id` int(11) NOT NULL,
  `nombre` varchar(120) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `tipo_servicio_id` int(11) DEFAULT NULL,
  `campaña_id` int(11) DEFAULT NULL,
  `canal` varchar(50) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_empresa`
--

CREATE TABLE `configuracion_empresa` (
  `id` int(11) NOT NULL,
  `nombre_empresa` varchar(120) DEFAULT NULL,
  `acerca_nosotros` longtext DEFAULT NULL,
  `mision` longtext DEFAULT NULL,
  `vision` longtext DEFAULT NULL,
  `valores` longtext DEFAULT NULL,
  `fortalezas_json` longtext DEFAULT NULL,
  `debilidades_json` longtext DEFAULT NULL,
  `oportunidades_json` longtext DEFAULT NULL,
  `amenazas_json` longtext DEFAULT NULL,
  `rubro_sector` varchar(120) DEFAULT NULL,
  `publico_objetivo` text DEFAULT NULL,
  `competidores_json` text DEFAULT NULL,
  `anios_mercado` int(11) DEFAULT NULL,
  `ubicacion` varchar(150) DEFAULT NULL,
  `url_web` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `email_contacto` varchar(120) DEFAULT NULL,
  `actualizado_por` int(11) DEFAULT NULL,
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` tinyint(4) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(120) NOT NULL,
  `contraseña_hash` varchar(255) NOT NULL,
  `rol_id` tinyint(4) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `campañas`
--
ALTER TABLE `campañas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creado_por` (`creado_por`),
  ADD KEY `fk_campania_servicio` (`servicio_id`);

--
-- Indices de la tabla `campaña_estrategias`
--
ALTER TABLE `campaña_estrategias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_estrategia_campaña` (`campaña_id`);

--
-- Indices de la tabla `campaña_planes`
--
ALTER TABLE `campaña_planes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_campaña_planes_campaña_id` (`campaña_id`);

--
-- Indices de la tabla `campaña_resultados`
--
ALTER TABLE `campaña_resultados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaña_id` (`campaña_id`);

--
-- Indices de la tabla `clientes_potenciales`
--
ALTER TABLE `clientes_potenciales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lead_servicio` (`tipo_servicio_id`),
  ADD KEY `fk_lead_campaña` (`campaña_id`);

--
-- Indices de la tabla `configuracion_empresa`
--
ALTER TABLE `configuracion_empresa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `campañas`
--
ALTER TABLE `campañas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `campaña_estrategias`
--
ALTER TABLE `campaña_estrategias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `campaña_planes`
--
ALTER TABLE `campaña_planes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `campaña_resultados`
--
ALTER TABLE `campaña_resultados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes_potenciales`
--
ALTER TABLE `clientes_potenciales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuracion_empresa`
--
ALTER TABLE `configuracion_empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `campañas`
--
ALTER TABLE `campañas`
  ADD CONSTRAINT `campañas_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_campania_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `campaña_estrategias`
--
ALTER TABLE `campaña_estrategias`
  ADD CONSTRAINT `fk_estrategia_campaña` FOREIGN KEY (`campaña_id`) REFERENCES `campañas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `campaña_planes`
--
ALTER TABLE `campaña_planes`
  ADD CONSTRAINT `fk_campaña_planes_campaña` FOREIGN KEY (`campaña_id`) REFERENCES `campañas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `campaña_resultados`
--
ALTER TABLE `campaña_resultados`
  ADD CONSTRAINT `campaña_resultados_ibfk_1` FOREIGN KEY (`campaña_id`) REFERENCES `campañas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `clientes_potenciales`
--
ALTER TABLE `clientes_potenciales`
  ADD CONSTRAINT `fk_lead_campaña` FOREIGN KEY (`campaña_id`) REFERENCES `campañas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lead_servicio` FOREIGN KEY (`tipo_servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `configuracion_empresa`
--
ALTER TABLE `configuracion_empresa`
  ADD CONSTRAINT `configuracion_empresa_ibfk_1` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
