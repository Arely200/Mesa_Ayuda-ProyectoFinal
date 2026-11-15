<?php

namespace Core;

use Interfaces\CriptografiaInterface;

/**
 * FIRMA DIGITAL DE REGISTROS
 * Implementación concreta del contrato CriptografiaInterface
 * para la firma de integridad de los tickets (HMAC-SHA256).
 *
 * Comparte el mismo contrato que HashPassword: ambas clases
 * son intercambiables como "transformadores de datos", aunque
 * resuelvan problemas distintos (contraseñas vs. integridad de
 * registros).
 */
class FirmaDigital implements CriptografiaInterface
{
    public function generar(string $dato): string
    {
        $clave = $_ENV['SECRET_KEY'] ?? 'clave_secreta_defecto';

        return hash_hmac('sha256', $dato, $clave);
    }

    public function verificar(string $dato, ?string $transformado): bool
    {
        if (!is_string($transformado) || $transformado === '') {
            return false;
        }

        $firmaCalculada = $this->generar($dato);
        return hash_equals($firmaCalculada, $transformado);
    }

    /**
     * Arma la cadena canónica de un ticket y genera su firma.
     * Método de conveniencia por encima de generar().
     */
    public function generarFirmaTicket(array $ticket): string
    {
        return $this->generar($this->cadenaCanonica($ticket));
    }

    /**
     * Verifica la firma guardada de un ticket contra sus datos
     * actuales. Método de conveniencia por encima de verificar().
     */
    public function verificarFirmaTicket(array $ticket, ?string $firma): bool
    {
        return $this->verificar($this->cadenaCanonica($ticket), $firma);
    }

    private function cadenaCanonica(array $ticket): string
    {
        return $ticket['id'] . $ticket['titulo'] . $ticket['descripcion'] . $ticket['fecha_creacion'];
    }
}
