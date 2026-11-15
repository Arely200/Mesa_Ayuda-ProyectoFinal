<?php
// BREVE DESCRIPCIÓN:
// Modelo para la gestión de permisos del sistema.
// Define qué acciones puede realizar cada rol sobre los módulos.

namespace Models;

use Core\Modelo;

class Permiso extends Modelo
{
    protected $tabla = 'permisos';

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM {$this->tabla} ORDER BY rol ASC, modulo ASC, accion ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerMatriz()
    {
        $matriz = [];
        foreach ($this->obtenerTodos() as $permiso) {
            $matriz[$permiso['rol']][$permiso['modulo']][$permiso['accion']] = (int)$permiso['permitido'];
        }

        return $matriz;
    }

    public function guardarPermiso($rol, $modulo, $accion, $permitido)
    {
        $sql = "INSERT INTO {$this->tabla} (rol, modulo, accion, permitido)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE permitido = VALUES(permitido)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$rol, $modulo, $accion, $permitido ? 1 : 0]);
    }
}
