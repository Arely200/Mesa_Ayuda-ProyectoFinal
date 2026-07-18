<?php
// ============================================================
// BREVE DESCRIPCIÓN:
// Clase de validación de datos (OBLIGATORIA).
// Valida emails, contraseñas (RNF-05: 8-12 caracteres),
// campos requeridos, formatos de fecha, etc.
// ============================================================

namespace Helpers;

class Validador
{
    // ============================================================
    // VALIDACIÓN DE CONTRASEÑA (RNF-05: 8-12 caracteres)
    // ============================================================
    public static function validatePassword($password, $campo = 'password')
    {
        $length = strlen($password);
        if ($length < 8 || $length > 12) {
            throw new ValidacionException('La contraseña debe tener entre 8 y 12 caracteres', $campo);
        }
        return true;
    }

    public static function validarPassword($password)
    {
        return self::validatePassword($password);
    }

    // ============================================================
    // VALIDACIÓN DE EMAIL
    // ============================================================
    public static function validateEmail($email, $campo = 'email')
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidacionException('Email inválido', $campo);
        }
        return true;
    }

    public static function validarEmail($email)
    {
        return self::validateEmail($email);
    }

    // ============================================================
    // VALIDACIÓN DE CAMPOS REQUERIDOS (VERSIÓN CON ARRAY DE CAMPOS)
    // ============================================================
    public static function validateRequired($data, $fields)
    {
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                throw new ValidacionException("El campo $field es obligatorio", $field);
            }
        }
        return true;
    }

    public static function validarRequeridos($data, $fields)
    {
        return self::validateRequired($data, $fields);
    }

    // ============================================================
    // VALIDACIÓN DE CAMPOS REQUERIDOS (VERSIÓN CON ARRAY ASOCIATIVO)
    // ============================================================
    public static function validarRequerido($datos)
    {
        foreach ($datos as $campo => $valor) {
            if (empty($valor) && $valor !== '0') {
                throw new ValidacionException("El campo $campo es obligatorio", $campo);
            }
        }
        return true;
    }

    // ============================================================
    // VALIDACIÓN DE IDENTIFICACIÓN
    // ============================================================
   public static function validateIdentificacion($identificacion, $campo = 'identificacion')
{
   
    // Validación de formato: números, o con guiones como 8-1021-4026   
    if (!preg_match('/^[A-Za-z0-9]{1,4}(-[A-Za-z0-9]{1,10}){0,3}$/', $identificacion)) {
        throw new ValidacionException('Identificación inválida. Formato esperado: números, o con guiones como 8-1021-4026.', $campo);
    }
    return true;
}

    public static function validarIdentificacion($identificacion)
    {
        return self::validateIdentificacion($identificacion);
    }

    // ============================================================
    // VALIDACIÓN DE FECHA
    // ============================================================
    public static function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public static function validarFecha($date, $format = 'Y-m-d')
    {
        return self::validateDate($date, $format);
    }
}

class_alias(Validador::class, __NAMESPACE__ . '\\Validator');