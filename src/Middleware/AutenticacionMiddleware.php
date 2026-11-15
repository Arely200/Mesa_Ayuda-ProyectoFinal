<?php
// BREVE DESCRIPCIÓN:
// Middleware de autenticación.
// Verifica que el usuario tenga sesión activa.
// Si no está logueado, redirige al login.


namespace Middleware;

use Core\Autenticacion;

class AutenticacionMiddleware
{
    public static function check()
    {
        if (!Autenticacion::estaLogueado()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        return true;
    }
}