<?php

namespace Models;

use Core\Modelo;

class Colaborador extends Modelo
{
    protected $tabla = 'colaboradores';

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM {$this->tabla} ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPorIdentificacion($identificacion)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE identificacion = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$identificacion]);
        return $stmt->fetch();
    }

    public function obtenerPorEmail($email)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function crearColaborador($datos)
    {
        return $this->crear($datos);
    }

    public function actualizarColaborador($id, $datos)
    {
        return $this->actualizar($id, $datos);
    }

    public function eliminarColaborador($id)
    {
        return $this->eliminar($id);
    }
    // ============================================================
// PAGINACIÓN
// ============================================================
public function obtenerPaginados($pagina = 1, $porPagina = 5)
{
    $offset = ($pagina - 1) * $porPagina;
    
    $sql = "SELECT * FROM {$this->tabla} ORDER BY id DESC LIMIT ? OFFSET ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$porPagina, $offset]);
    return $stmt->fetchAll();
}

public function contarTodos()
{
    $sql = "SELECT COUNT(*) as total FROM {$this->tabla}";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetch();
    return $resultado['total'] ?? 0;
}
}