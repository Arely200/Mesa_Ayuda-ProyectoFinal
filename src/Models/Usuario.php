<?php

namespace Models;

use Core\Modelo;

class Usuario extends Modelo
{
    protected $tabla = 'usuarios';

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE activo = 1 ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPorEmail($email)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function encontrarPorId($id)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function crearUsuario($datos)
    {
        return $this->crear($datos);
    }

    public function actualizarUsuario($id, $datos)
    {
        return $this->actualizar($id, $datos);
    }

    public function eliminarUsuario($id)
    {
        // Baja lógica — no elimina físicamente
        return $this->actualizar($id, ['activo' => 0]);
    }

    public function obtenerPaginados($pagina = 1, $porPagina = 5)
    {
        $offset = ($pagina - 1) * $porPagina;
        $sql = "SELECT * FROM {$this->tabla} WHERE activo = 1 ORDER BY id DESC LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$porPagina, $offset]);
        return $stmt->fetchAll();
    }

    public function contarTodos()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->tabla} WHERE activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch();
        return $resultado['total'] ?? 0;
    }
}