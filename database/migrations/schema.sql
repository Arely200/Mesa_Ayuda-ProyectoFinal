-- ============================================================
-- Mesa de Ayuda — Esquema de Base de Datos
-- Versión corregida: InnoDB + Llaves foráneas + datos limpios
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `mesa_ayuda` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mesa_ayuda`;

-- --------------------------------------------------------
-- usuarios (administran el sistema: admin, agente, supervisor)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('admin','agente','supervisor') COLLATE utf8mb4_unicode_ci DEFAULT 'agente',
  `activo` tinyint(1) DEFAULT '1',
  `intentos_fallidos` int DEFAULT '0',
  `bloqueado` tinyint(1) DEFAULT '0',
  `fecha_bloqueo` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `activo`, `intentos_fallidos`, `bloqueado`, `fecha_bloqueo`, `created_at`, `updated_at`) VALUES
(9, 'Administrador', 'admin@mesaayuda.com', '$2y$10$4t1MMVLMi3DaaEC8O2occevbdGXRMK2/Iq48JpWoo3qRqn5M6M5WO', 'admin', 1, 0, 0, NULL, '2026-06-27 12:04:27', '2026-06-27 12:04:27'),
(10, 'Juan Pérez', 'juan@mesaayuda.com', '$2y$10$k5O6jcHq/CZLoeuX2DoVwew8EkdpO.ZBoMoGxsOk37pxmG.srV1NS', 'agente', 1, 0, 0, NULL, '2026-06-27 12:04:27', '2026-06-27 12:04:27'),
(11, 'María Jiménez', 'maria@mesaayuda.com', '$2y$10$4t1MMVLMi3DaaEC8O2occevbdGXRMK2/Iq48JpWoo3qRqn5M6M5WO', 'agente', 1, 0, 0, NULL, '2026-06-27 12:04:27', '2026-06-27 12:04:27'),
(12, 'Jorge Castillo', 'jorge.castillo@mesaayuda.com', '$2y$10$YEpAgdlQoaG/RK0BWMzeNuUGjTZkz1t2VaCFze8r1zsv.zPG9P6BO', 'supervisor', 1, 0, 0, NULL, '2026-06-28 00:03:51', '2026-06-28 00:03:51'),
(13, 'Arely Mendoza', 'arely@mesaayuda.com', '$2y$10$lNfMWE/LhfikSrcztuVTWen1QCCxIYAxtNJH4ZA6/BgMIhPRY8zRm', 'agente', 1, 0, 0, NULL, '2026-06-28 13:02:51', '2026-06-28 13:02:51');
ALTER TABLE `usuarios` AUTO_INCREMENT = 14;

-- --------------------------------------------------------
-- colaboradores (estudiantes/colaboradores: crean tickets)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `colaboradores`;
CREATE TABLE `colaboradores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `primer_nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundo_nombre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primer_apellido` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundo_apellido` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sexo` enum('M','F','Otro') COLLATE utf8mb4_unicode_ci DEFAULT 'Otro',
  `identificacion` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_perfil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identificacion` (`identificacion`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `colaboradores` (`id`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `sexo`, `identificacion`, `fecha_nacimiento`, `email`, `password`, `foto_perfil`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Ana', 'María', 'Rodríguez', 'Gómez', 'F', '123456789', '2000-05-15', 'ana@prueba.com', '$2y$10$4t1MMVLMi3DaaEC8O2occevbdGXRMK2/Iq48JpWoo3qRqn5M6M5WO', NULL, 1, '2026-06-27 13:57:23', '2026-06-27 13:57:23'),
(2, 'Carlos', 'Andrés', 'Sánchez', 'Pérez', 'M', '987654321', '1998-10-20', 'carlos@prueba.com', '$2y$10$.goRBKaRyCVS0gKNb8Ben.pgjfVqn2KcStpjXviFVDbQqQCTB2NWy', NULL, 1, '2026-06-27 13:57:23', '2026-06-27 16:46:21'),
(3, 'María', 'Elena', 'González', 'López', 'F', '456789123', '2001-03-08', 'maria@prueba.com', '$2y$10$4t1MMVLMi3DaaEC8O2occevbdGXRMK2/Iq48JpWoo3qRqn5M6M5WO', NULL, 0, '2026-06-27 13:57:23', '2026-06-27 18:29:39'),
(4, 'Pedro', NULL, 'Martínez', 'Díaz', 'M', '789123456', '1999-07-12', 'pedro@prueba.com', '$2y$10$4t1MMVLMi3DaaEC8O2occevbdGXRMK2/Iq48JpWoo3qRqn5M6M5WO', NULL, 0, '2026-06-27 13:57:23', '2026-06-27 18:29:24'),
(5, 'Luis', 'Alberto', 'Fernández', 'García', 'M', '555444333', '1995-05-15', 'luis.fernandez@prueba.com', '$2y$10$mdYM8J5gV4F4cG4VRTuo5.A/GU8n38Q7Z4k3cK6m7YDDKyDzJqOfy', '1782622659_Progr.jpg', 1, '2026-06-27 23:57:39', '2026-06-27 23:57:39'),
(6, 'Estrella', 'Fugaz', 'Pino', 'Pino', 'F', '12340056', '2005-11-10', 'estrellapino@gmail.com', '$2y$10$Xx33Fx7qfqG8t88TNKwOluVR3T6X7pBuhaH9jRt.4h757A9LiG.QC', '1782630048_6a40c6a0080b5.jpg', 0, '2026-06-28 01:57:17', '2026-06-28 02:01:14');
ALTER TABLE `colaboradores` AUTO_INCREMENT = 7;

-- --------------------------------------------------------
-- categorias_tickets (con columna "tipo": Soporte / Académico)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `categorias_tickets`;
CREATE TABLE `categorias_tickets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('Soporte','Académico') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Soporte',
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categorias_tickets` (`id`, `nombre`, `tipo`, `descripcion`, `activo`, `created_at`) VALUES
(1, 'Soporte Técnico', 'Soporte', 'Problemas con equipos, redes y sistemas', 1, '2026-06-26 07:20:52'),
(2, 'Soporte Académico', 'Académico', 'Consultas sobre créditos, notas y horarios', 1, '2026-06-26 07:20:52'),
(3, 'Infraestructura', 'Soporte', 'Instalaciones, aulas y laboratorios', 1, '2026-06-26 07:20:52'),
(4, 'Trámites Administrativos', 'Académico', 'Trámites, documentos y certificados', 1, '2026-06-26 07:20:52'),
(5, 'Otros', 'Soporte', 'Otras solicitudes no clasificadas', 1, '2026-06-26 07:20:52'),
(6, 'Solicitudes de TI', 'Soporte', 'Solicitudes de equipos, software y servicios tecnológicos', 1, '2026-06-28 02:05:58'),
(7, 'Incidentes de Red', 'Soporte', 'Incidencias generales del área de tecnología', 1, '2026-06-28 02:11:07'),
(8, 'Mantenimiento', 'Soporte', 'Reparación y mantenimiento de instalaciones y equipos', 1, '2026-06-28 07:08:25');
ALTER TABLE `categorias_tickets` AUTO_INCREMENT = 9;

-- --------------------------------------------------------
-- permisos (rol -> módulo -> acción). Incluye supervisor.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `permisos`;
CREATE TABLE `permisos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rol` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modulo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accion` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permitido` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_permiso` (`rol`,`modulo`,`accion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permisos` (`id`, `rol`, `modulo`, `accion`, `permitido`) VALUES
(1, 'admin', 'dashboard', 'ver', 1),
(2, 'admin', 'usuarios', 'ver', 1),
(3, 'admin', 'usuarios', 'crear', 1),
(4, 'admin', 'usuarios', 'editar', 1),
(5, 'admin', 'usuarios', 'eliminar', 1),
(6, 'admin', 'colaboradores', 'ver', 1),
(7, 'admin', 'colaboradores', 'crear', 1),
(8, 'admin', 'colaboradores', 'editar', 1),
(9, 'admin', 'colaboradores', 'eliminar', 1),
(10, 'admin', 'tickets', 'ver_todos', 1),
(11, 'admin', 'tickets', 'asignar', 1),
(12, 'admin', 'tickets', 'responder', 1),
(13, 'admin', 'tickets', 'culminar', 1),
(14, 'admin', 'tickets', 'eliminar', 1),
(15, 'admin', 'categorias', 'ver', 1),
(16, 'admin', 'categorias', 'crear', 1),
(17, 'admin', 'categorias', 'editar', 1),
(18, 'admin', 'categorias', 'eliminar', 1),
(19, 'admin', 'reportes', 'ver', 1),
(20, 'admin', 'reportes', 'exportar', 1),
(21, 'admin', 'logs', 'ver', 1),
(22, 'admin', 'roles', 'administrar', 1),
(23, 'supervisor', 'dashboard', 'ver', 1),
(24, 'supervisor', 'usuarios', 'ver', 1),
(25, 'supervisor', 'colaboradores', 'ver', 1),
(26, 'supervisor', 'tickets', 'ver_todos', 1),
(27, 'supervisor', 'tickets', 'asignar', 1),
(28, 'supervisor', 'tickets', 'responder', 1),
(29, 'supervisor', 'tickets', 'culminar', 1),
(30, 'supervisor', 'categorias', 'ver', 1),
(31, 'supervisor', 'reportes', 'ver', 1),
(32, 'supervisor', 'reportes', 'exportar', 1),
(33, 'supervisor', 'logs', 'ver', 0),
(34, 'supervisor', 'roles', 'administrar', 0),
(35, 'agente', 'dashboard', 'ver', 0),
(36, 'agente', 'tickets', 'ver_asignados', 1),
(37, 'agente', 'tickets', 'responder', 1),
(38, 'agente', 'tickets', 'culminar', 1),
(39, 'agente', 'reportes', 'ver', 0),
(40, 'agente', 'reportes', 'exportar', 0),
(41, 'agente', 'logs', 'ver', 0),
(42, 'agente', 'roles', 'administrar', 0);
ALTER TABLE `permisos` AUTO_INCREMENT = 43;

-- --------------------------------------------------------
-- tickets
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `colaborador_id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `solucion` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('En proceso','En espera','Culminada') COLLATE utf8mb4_unicode_ci DEFAULT 'En proceso',
  `prioridad` enum('Baja','Media','Alta') COLLATE utf8mb4_unicode_ci DEFAULT 'Media',
  `nivel_soporte` enum('Primer Nivel','Segundo Nivel','Supervisor') COLLATE utf8mb4_unicode_ci DEFAULT 'Primer Nivel',
  `agente_asignado_id` int DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_primer_respuesta` datetime DEFAULT NULL,
  `fecha_culminacion` datetime DEFAULT NULL,
  `ip_creacion` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firma_digital` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `colaborador_id` (`colaborador_id`),
  KEY `agente_asignado_id` (`agente_asignado_id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `fk_tickets_colaborador` FOREIGN KEY (`colaborador_id`) REFERENCES `colaboradores` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_tickets_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_tickets` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_tickets_agente` FOREIGN KEY (`agente_asignado_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tickets` (`id`, `colaborador_id`, `categoria_id`, `titulo`, `descripcion`, `estado`, `prioridad`, `nivel_soporte`, `agente_asignado_id`, `fecha_creacion`, `fecha_primer_respuesta`, `fecha_culminacion`, `ip_creacion`, `firma_digital`) VALUES
(1, 1, 2, 'No puedo acceder al sistema', 'Al intentar iniciar sesión, el sistema muestra un error de autenticación.', 'Culminada', 'Media', 'Primer Nivel', 10, '2026-06-27 15:02:58', '2026-06-27 20:14:56', '2026-06-27 20:15:39', '192.168.1.10', '938cd7fe47b8688717f038240db29a43158090492a9c3df013988078fe9aa5e4'),
(2, 2, 1, 'Problema con el sistema de correo', 'No puedo enviar correos desde la cuenta institucional.', 'Culminada', 'Alta', 'Primer Nivel', 10, '2026-06-27 16:45:28', '2026-06-27 22:00:10', '2026-06-27 22:29:26', '192.168.1.14', 'b1e2c9a5f7d3e6a4c8f1d9b6a3e5c7f9d1b3a5c7e9f1d3b5a7c9e1f3d5b7a9c1'),
(3, 2, 1, 'Consulta sobre encuesta de satisfacción', 'Quisiera saber cómo funciona la encuesta al cerrar un ticket.', 'Culminada', 'Media', 'Primer Nivel', 10, '2026-06-27 17:39:18', '2026-06-27 22:10:02', '2026-06-27 22:40:45', '192.168.1.14', 'c2f3d0b6e8a4f7b5d9c2e4f6a8b0d2f4e6c8a0b2d4f6e8c0a2b4d6f8e0c2a4b6'),
(4, 2, 1, 'No puedo enviar correos electrónicos', 'Desde esta mañana no puedo enviar correos desde mi cuenta institucional. Al intentar enviar, sale un error de conexión con el servidor SMTP.', 'Culminada', 'Alta', 'Primer Nivel', 10, '2026-06-27 18:31:11', '2026-06-27 23:37:43', '2026-06-27 23:37:48', '192.168.1.14', '5276d612c971fc3add0537fb089247a74dafd99bfedda6b5a4711b9a613f5f9d'),
(5, 1, 2, 'Problema con el sistema de archivos', 'No puedo guardar mis archivos en la carpeta compartida. Sale un error de permisos.', 'Culminada', 'Media', 'Primer Nivel', 10, '2026-06-27 19:10:51', '2026-06-28 00:12:06', '2026-06-28 00:12:47', '192.168.1.10', '14392614ca91f3437b99de9438de48fc5248fc7a3f62bd407ac896b440189737'),
(6, 5, 1, 'Solicitud de reinicio de contraseña de red', 'Necesito que me ayuden a restablecer mi acceso a la red del campus.', 'Culminada', 'Media', 'Primer Nivel', 11, '2026-06-28 00:08:51', '2026-06-28 04:50:03', '2026-06-28 05:17:22', '192.168.1.22', '7751d7058c0e2d8de9b4ba5c6446349d0be7b5fe7a2495c59383af53859dc0f4'),
(7, 1, 1, 'Lentitud en el sistema institucional', 'El sistema tarda demasiado en cargar las páginas desde ayer.', 'Culminada', 'Alta', 'Primer Nivel', 10, '2026-06-28 07:26:35', '2026-06-28 12:32:41', '2026-06-28 12:32:44', '192.168.1.10', 'a647be4da30c5dcd1df600b1dabb8cd7f872c6a9ffb5b364b63a8a1dad123542'),
(8, 1, 1, 'Error al subir archivos adjuntos', 'Al intentar adjuntar un archivo en un formulario, el sistema no responde.', 'Culminada', 'Alta', 'Primer Nivel', 10, '2026-06-28 07:43:13', '2026-06-28 12:44:38', '2026-06-28 12:44:43', '192.168.1.10', '61a0bd4f62413ff407b6a650b68edcca124ff25c1e39697a2673ae4a945f52d7'),
(9, 5, 1, 'No puedo acceder a la plataforma de clases', 'Desde esta mañana no puedo acceder a la plataforma virtual. Sale un error de autenticación en distintos dispositivos.', 'En proceso', 'Alta', 'Primer Nivel', 10, '2026-06-28 21:45:24', NULL, NULL, '192.168.1.22', NULL);
ALTER TABLE `tickets` AUTO_INCREMENT = 10;

-- --------------------------------------------------------
-- respuestas_tickets
-- --------------------------------------------------------
DROP TABLE IF EXISTS `respuestas_tickets`;
CREATE TABLE `respuestas_tickets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int NOT NULL,
  `agente_id` int NOT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_respuesta` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `agente_id` (`agente_id`),
  CONSTRAINT `fk_respuestas_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_respuestas_agente` FOREIGN KEY (`agente_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `respuestas_tickets` (`id`, `ticket_id`, `agente_id`, `mensaje`, `fecha_respuesta`) VALUES
(1, 1, 9, 'Buenas tardes, hemos revisado el problema y ya estamos trabajando en la solución. En breve le daremos una respuesta definitiva.', '2026-06-27 20:14:56'),
(2, 4, 9, 'Hola Carlos, revisamos el problema. El servidor SMTP estaba caído, pero ya lo reiniciamos. Por favor intenta enviar el correo nuevamente y confírmanos si funciona.', '2026-06-27 23:37:43'),
(3, 5, 9, 'Hola Ana, hemos revisado el problema. Se debe a un error de permisos en el servidor. Ya se corrigió.', '2026-06-28 00:12:06'),
(4, 6, 10, 'Hola Luis, tu acceso a la red fue restablecido. Por favor intenta ingresar nuevamente.', '2026-06-28 04:50:03'),
(5, 7, 9, 'Estamos trabajando en la solución de tu caso, te contactaremos en breve.', '2026-06-28 12:32:41'),
(6, 8, 9, 'Estamos revisando tu caso, gracias por tu paciencia.', '2026-06-28 12:44:38');
ALTER TABLE `respuestas_tickets` AUTO_INCREMENT = 7;

-- --------------------------------------------------------
-- encuestas_satisfaccion
-- --------------------------------------------------------
DROP TABLE IF EXISTS `encuestas_satisfaccion`;
CREATE TABLE `encuestas_satisfaccion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int NOT NULL,
  `calificacion` enum('Satisfecho','Conforme','Inconforme','No respondida') COLLATE utf8mb4_unicode_ci DEFAULT 'No respondida',
  `comentario` text COLLATE utf8mb4_unicode_ci,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  CONSTRAINT `fk_encuestas_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `encuestas_satisfaccion` (`id`, `ticket_id`, `calificacion`, `comentario`, `fecha`) VALUES
(1, 5, 'Conforme', 'Excelente atención, el problema se resolvió rápido. ¡Gracias!', '2026-06-28 00:15:00'),
(2, 7, 'Satisfecho', 'Me solucionaron el fallo sin problemas.', '2026-06-28 12:35:00'),
(3, 8, 'Conforme', 'Buena atención, aunque tardó un poco.', '2026-06-28 12:50:00'),
(4, 6, 'Satisfecho', 'Todo resuelto correctamente.', '2026-06-28 05:20:00');
ALTER TABLE `encuestas_satisfaccion` AUTO_INCREMENT = 5;

-- --------------------------------------------------------
-- logs_login (registro de anomalías / intentos de login)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `logs_login`;
CREATE TABLE `logs_login` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `exitoso` tinyint(1) DEFAULT '0',
  `mensaje` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Muestra representativa: login normal, fallos y bloqueo a los 3 intentos
INSERT INTO `logs_login` (`id`, `email`, `ip`, `fecha`, `exitoso`, `mensaje`) VALUES
(1, 'admin@mesaayuda.com', '192.168.1.5', '2026-06-27 12:14:23', 1, 'Login exitoso'),
(2, 'juan@mesaayuda.com', '192.168.1.8', '2026-06-27 18:56:52', 0, 'Contraseña incorrecta'),
(3, 'juan@mesaayuda.com', '192.168.1.8', '2026-06-27 18:56:59', 1, 'Login exitoso'),
(4, 'arely@mesaayuda.com', '192.168.1.30', '2026-06-28 13:05:23', 0, 'Contraseña incorrecta'),
(5, 'arely@mesaayuda.com', '192.168.1.30', '2026-06-28 13:05:33', 0, 'Contraseña incorrecta'),
(6, 'arely@mesaayuda.com', '192.168.1.30', '2026-06-28 13:06:05', 0, 'Cuenta bloqueada por 3 intentos fallidos'),
(7, 'arely@mesaayuda.com', '192.168.1.30', '2026-06-28 13:24:20', 1, 'Login exitoso'),
(8, 'maria@mesaayuda.com', '192.168.1.19', '2026-06-28 11:43:03', 0, 'Contraseña incorrecta'),
(9, 'maria@mesaayuda.com', '192.168.1.19', '2026-06-28 11:53:29', 1, 'Login exitoso'),
(10, 'desconocido@mesaayuda.com', '192.168.1.99', '2026-06-28 07:46:07', 0, 'Usuario no encontrado');
ALTER TABLE `logs_login` AUTO_INCREMENT = 11;

-- --------------------------------------------------------
-- visitas (contador de visitas a la página pública)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `visitas`;
CREATE TABLE `visitas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
