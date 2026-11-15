<?php

// BREVE DESCRIPCIÓN:
// Clase de sanitización de datos (OBLIGATORIA).
// Limpia entradas de usuario para prevenir XSS e inyecciones.
// Se usa en todos los controladores antes de procesar datos.

namespace Helpers;

class Sanitizador
{
    public static function sanitizeString($input)
    {
        // IMPORTANTE: aquí NO se aplica htmlspecialchars().
        // Esta función limpia la entrada (espacios, caracteres de control),
        // los datos (con htmlspecialchars), nunca antes de guardar en la BD.
        // Guardar el dato ya escapado corrompe la información (ej: un título
        // con comillas quedaba guardado como "&quot;" en vez de la comilla real)
        // y provoca doble escape al mostrarlo ("&amp;quot;").
        $input = trim($input);
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $input); // caracteres de control
        return $input;
    }

    public static function sanitizarString($input)
    {
        return self::sanitizeString($input);
    }

    public static function sanitizeEmail($email)
    {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }

    public static function sanitizarEmail($email)
    {
        return self::sanitizeEmail($email);
    }

    public static function sanitizeInt($input)
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function sanitizarInt($input)
    {
        return self::sanitizeInt($input);
    }

    public static function sanitizeFloat($input)
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    public static function sanitizarFloat($input)
    {
        return self::sanitizeFloat($input);
    }

    public static function sanitizeArray($array)
    {
        $sanitized = [];
        foreach ($array as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = self::sanitizeString($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }

    public static function sanitizarArray($array)
    {
        return self::sanitizeArray($array);
    }

    public static function sanitizeFileName($filename)
    {
        return preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
    }

    public static function sanitizarNombreArchivo($filename)
    {
        return self::sanitizeFileName($filename);
    }
}

class_alias(Sanitizador::class, __NAMESPACE__ . '\\Sanitizer');
