-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-10-2023 a las 20:50:37
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS my_checklist_tracker;
USE my_checklist_tracker;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_compromiso` date DEFAULT NULL,
  `prioridad` enum('Baja','Media','Alta') DEFAULT NULL,
  `responsable` varchar(100) DEFAULT NULL,
  `estado` enum('Pendiente','En Proceso','Completado') DEFAULT 'Pendiente',
  `hora_culminacion` time DEFAULT NULL,
  `edited` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcado de datos para la tabla `tareas`
-- Volcado de datos para la tabla `tareas`
INSERT INTO `tareas` (`id`, `nombre`, `descripcion`, `fecha_compromiso`, `prioridad`, `responsable`, `estado`, `hora_culminacion`, `edited`) VALUES
(8, 'Reunión de proyecto', 'Preparar agenda y documentos para la reunión del proyecto X', '2023-11-15', 'Alta', 'Juan Pérez', 'Completado', '07:45:00', 0),
(9, 'Informe de ventas', 'Generar informe mensual de ventas y presentarlo al equipo de ventas', '2023-11-10', 'Media', 'María Gómez', 'Pendiente', '09:00:00', 1),
(10, 'Entrenamiento del personal', 'Realizar sesión de entrenamiento para el personal nuevo', '2023-11-20', 'Baja', 'Luis Rodríguez', 'Completado', '12:15:00', 0),
(11, 'Desarrollo de software', 'Programar nuevas funcionalidades para la aplicación cliente', '2023-12-01', 'Alta', 'Allan Ramirez', 'Completado', '08:20:00', 0),
(12, 'Revisión de contratos', 'Revisar y actualizar los contratos con los proveedores', '2023-11-25', 'Media', 'Carlos Sánchez', 'En Proceso', '10:00:00', 1),
(13, 'Mantenimiento del sitio web', 'Realizar tareas de mantenimiento y actualización del sitio web de la empresa', '2023-12-05', 'Baja', 'Elena López', 'En Proceso', '11:00:00', 0),
(15, 'Informe de gastos', 'Generar informe trimestral de gastos y presentarlo a la dirección', '2023-12-15', 'Media', 'Sofía Ramírez', 'Completado', '15:15:00', 0),
(16, 'Reclutamiento de personal', 'Entrevistar candidatos para los puestos vacantes', '2023-12-20', 'Baja', 'Javier Hernández', 'Pendiente', '13:45:00', 0),
(17, 'Reunión de equipo', 'Realizar reunión semanal del equipo para revisar avances y metas', '2023-12-30', 'Alta', 'Isabel Torres', 'En Proceso', '15:35:00', 0),
(18, 'Capacitación en seguridad', 'Brindar capacitación en seguridad en el lugar de trabajo', '2024-01-05', 'Media', 'Raúl Díaz', 'En Proceso', '12:00:00', 1),
(19, 'Informe de calidad', 'Preparar informe de calidad de productos para la dirección', '2023-10-25', 'Baja', 'Rosalia Andrion', 'Completado', '18:00:00', 1),
(20, 'Lanzamiento de producto', 'Preparar el lanzamiento del nuevo producto en el mercado', '2024-01-15', 'Alta', 'Miguel Rodríguez', 'Pendiente', '21:15:00', 0),
(21, 'Revisión de marketing', 'Revisar y optimizar estrategias de marketing digital', '2024-01-20', 'Media', 'Laura López', 'Pendiente', '17:00:00', 0),
(22, 'Auditoría interna', 'Realizar auditoría interna para asegurar el cumplimiento de normativas', '2024-01-25', 'Baja', 'Diego Martínez', 'Pendiente', '14:20:00', 0);

-- (Se mantiene igual que en tu script original)

--
-- Procedimientos
--
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `FilterByDay` (IN `dayValue` DATE)   BEGIN
    SELECT * FROM tareas
    WHERE DATE(fecha_compromiso) = dayValue;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `FilterByMonth` (IN `monthValue` DATE)   BEGIN
    SET @firstDay = DATE_SUB(monthValue, INTERVAL DAYOFMONTH(monthValue) - 1 DAY);
    SET @lastDay = LAST_DAY(monthValue);
    
    SELECT * FROM tareas
    WHERE DATE(fecha_compromiso) BETWEEN @firstDay AND @lastDay;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `FilterByPriority` (IN `priority_value` ENUM('Baja','Media','Alta'))   BEGIN
    SELECT * FROM tareas WHERE prioridad = priority_value;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `FilterByResponsible` (IN `responsible_value` VARCHAR(100))   BEGIN
    SELECT * FROM tareas WHERE responsable = responsible_value;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `FilterByStatus` (IN `status_value` ENUM('Pendiente','En Proceso','Completado'))   BEGIN
    SELECT * FROM tareas WHERE estado = status_value;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `FilterByWeek` (IN `weekValue` DATE)   BEGIN
    SET @weekStart = weekValue;
    SET @weekEnd = weekValue + INTERVAL 6 DAY;

    SELECT
        nombre,
        descripcion,
        fecha_compromiso,
        prioridad,
        responsable,
        estado,
        hora_culminacion
    FROM tareas
    WHERE fecha_compromiso BETWEEN @weekStart AND @weekEnd;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `FilterByYear` (IN `yearValue` INT)   BEGIN
    SET @yearStart = STR_TO_DATE(CONCAT(yearValue, '-01-01'), '%Y-%m-%d');
    SET @yearEnd = STR_TO_DATE(CONCAT(yearValue, '-12-31'), '%Y-%m-%d');
    
    SELECT * FROM tareas
    WHERE DATE(fecha_compromiso) BETWEEN @yearStart AND @yearEnd;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllTasks` ()   BEGIN
    SELECT
        nombre,
        descripcion,
        fecha_compromiso,
        prioridad,
        responsable,
        estado,
        hora_culminacion
    FROM tareas;
END$$
DELIMITER ;

-- Índices para tablas volcadas
-- (Se mantiene igual que en tu script original)

-- AUTO_INCREMENT de las tablas volcadas
-- (Se mantiene igual que en tu script original)

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
