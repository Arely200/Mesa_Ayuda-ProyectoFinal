<?php

namespace Core;

use Interfaces\CriptografiaInterface;

/**
 * ============================================================
 * HASH DE CONTRASEÑAS
 * ============================================================
 * Implementación concreta del contrato CriptografiaInterface
 * para contraseñas de usuarios y colaboradores.
 * ============================================================
 */
class HashPassword implements CriptografiaInterface
{
    public function generar(string $dato): string
    {
        return password_hash($dato, PASSWORD_DEFAULT);
    }

    public function verificar(string $dato, ?string $transformado): bool
    {
        if (empty($transformado)) {
            return false;
        }

        return password_verify($dato, $transformado);
    }
}
