<?php
// ============================================================
// BREVE DESCRIPCIÓN:
// Middleware de roles y permisos.
// Verifica que el usuario tenga el rol requerido o el permiso
// específico para acceder a un módulo/acción.
// ============================================================

namespace Middleware;

use Core\Autenticacion;

class RolMiddleware
{
    public static function check($rol)
    {
        if (!Autenticacion::verificarRol($rol)) {
            http_response_code(403);
            die('Acceso denegado. No tienes permisos para esta sección.');
        }
        return true;
    }

    public static function checkPermission($modulo, $accion)
    {
        if (!Autenticacion::tienePermiso($modulo, $accion)) {
            http_response_code(403);
            die('Acceso denegado. No tienes permisos para esta acción.');
        }
        return true;
    }
}