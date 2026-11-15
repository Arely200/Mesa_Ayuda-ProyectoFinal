<?php

namespace Interfaces;

/*
 * CONTRATO DE SERVICIOS CRIPTOGRÁFICOS
 * Define un único comportamiento para cualquier operación de
 * transformación de datos que necesite la aplicación: hashing
 * de contraseñas, firma digital de registros (tickets), o
 * cualquier otro mecanismo de seguridad que se agregue a futuro
 * (cifrado, tokens, etc.).
 *
 * Al programar contra esta interfaz (y no contra password_hash(),
 * hash_hmac() u otra función concreta), la lógica de negocio
 * queda desacoplada del algoritmo específico: se puede cambiar
 * de PASSWORD_DEFAULT a Argon2id, o de HMAC-SHA256 a otro
 * esquema de firma, implementando una nueva clase que cumpla
 * este mismo contrato — sin tocar los controladores que la usan.
 */
interface CriptografiaInterface
{
    /**
     * Transforma un dato en texto plano (contraseña, cadena de
     * un registro, etc.) en su representación criptográfica.
     */
    public function generar(string $dato): string;

    /**
     * Verifica que un dato en texto plano corresponda con una
     * transformación generada previamente (hash guardado en la
     * base de datos, firma guardada en el ticket, etc.).
     */
    public function verificar(string $dato, ?string $transformado): bool;
}
