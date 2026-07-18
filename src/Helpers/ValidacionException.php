<?php

namespace Helpers;

/**
 * Excepción de validación que además de un mensaje, indica QUÉ campo
 * falló (propiedad $campo). Esto permite que el controlador guarde
 * ese dato y la vista marque en rojo únicamente el input problemático,
 * en vez de un error genérico que no dice dónde está el problema.
 */
class ValidacionException extends \Exception
{
    public $campo;

    public function __construct($mensaje, $campo = null)
    {
        parent::__construct($mensaje);
        $this->campo = $campo;
    }
}