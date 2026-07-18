USE `mesa_ayuda`;

-- ============================================================
-- Agrega la columna donde el agente registra cómo resolvió
-- el ticket. Se completa obligatoriamente al culminar el ticket
-- y queda guardada de forma permanente junto con el ticket.
-- ============================================================
ALTER TABLE `tickets`
    ADD COLUMN `solucion` TEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER `descripcion`;
