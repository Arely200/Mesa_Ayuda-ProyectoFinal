<?php

namespace Models;

use Core\Modelo;

class CategoriaTicket extends Modelo
{
    protected $tabla = 'categorias_tickets';

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE activo = 1 ORDER BY tipo ASC, nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerAgrupadasPorTipo()
    {
        $categorias = $this->obtenerTodos();
        $agrupadas = [
            'Soporte' => [],
            'Académico' => []
        ];

        foreach ($categorias as $categoria) {
            $tipo = $categoria['tipo'] ?? 'Soporte';
            if (!isset($agrupadas[$tipo])) {
                $agrupadas[$tipo] = [];
            }

            $agrupadas[$tipo][] = $categoria;
        }

        return $agrupadas;
    }

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

    public function obtenerPorNombre($nombre)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE nombre = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombre]);
        return $stmt->fetch();
    }

    public function encontrarPorId($id)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function crearCategoria($datos)
    {
        return $this->crear($datos);
    }

    public function actualizarCategoria($id, $datos)
    {
        return $this->actualizar($id, $datos);
    }

    public function eliminarCategoria($id)
    {
        return $this->eliminar($id);
    }
}
