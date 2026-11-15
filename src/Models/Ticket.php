<?php
// BREVE DESCRIPCIÓN:
// Modelo principal para la gestión de tickets del sistema.
// Maneja la creación, asignación, seguimiento y culminación de tickets.
// Incluye métodos para obtener tickets por colaborador, agente y estado.

namespace Models;

use Core\Modelo;

class Ticket extends Modelo
{
    protected $tabla = 'tickets';

    public function obtenerTodos()
    {
        $sql = "SELECT t.*, 
                c.primer_nombre as colaborador_nombre, 
                c.primer_apellido as colaborador_apellido,
                u.nombre as agente_nombre,
                cat.nombre as categoria_nombre
                FROM {$this->tabla} t
                LEFT JOIN colaboradores c ON t.colaborador_id = c.id
                LEFT JOIN usuarios u ON t.agente_asignado_id = u.id
                LEFT JOIN categorias_tickets cat ON t.categoria_id = cat.id
                ORDER BY t.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT t.*, 
                c.primer_nombre as colaborador_nombre, 
                c.primer_apellido as colaborador_apellido,
                c.email as colaborador_email,
                u.nombre as agente_nombre,
                u.email as agente_email,
                cat.nombre as categoria_nombre
                FROM {$this->tabla} t
                LEFT JOIN colaboradores c ON t.colaborador_id = c.id
                LEFT JOIN usuarios u ON t.agente_asignado_id = u.id
                LEFT JOIN categorias_tickets cat ON t.categoria_id = cat.id
                WHERE t.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function obtenerPorEstado($estado)
    {
        $sql = "SELECT t.*, 
                c.primer_nombre as colaborador_nombre,
                u.nombre as agente_nombre
                FROM {$this->tabla} t
                LEFT JOIN colaboradores c ON t.colaborador_id = c.id
                LEFT JOIN usuarios u ON t.agente_asignado_id = u.id
                WHERE t.estado = ?
                ORDER BY t.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$estado]);
        return $stmt->fetchAll();
    }

    public function obtenerPorAgente($agenteId)
    {
        $sql = "SELECT t.*, 
                cat.nombre as categoria_nombre,
                CONCAT(col.primer_nombre, ' ', col.primer_apellido) as colaborador_nombre
                FROM {$this->tabla} t
                LEFT JOIN categorias_tickets cat ON t.categoria_id = cat.id
                LEFT JOIN colaboradores col ON t.colaborador_id = col.id
                WHERE t.agente_asignado_id = ?
                ORDER BY t.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$agenteId]);
        return $stmt->fetchAll();
    }

    public function obtenerPorAgentePaginado($agenteId, $pagina = 1, $porPagina = 5)
    {
        $offset = ($pagina - 1) * $porPagina;
        $sql = "SELECT t.*, 
                cat.nombre as categoria_nombre,
                CONCAT(col.primer_nombre, ' ', col.primer_apellido) as colaborador_nombre
                FROM {$this->tabla} t
                LEFT JOIN categorias_tickets cat ON t.categoria_id = cat.id
                LEFT JOIN colaboradores col ON t.colaborador_id = col.id
                WHERE t.agente_asignado_id = ?
                ORDER BY t.fecha_creacion DESC
                LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$agenteId, $porPagina, $offset]);
        return $stmt->fetchAll();
    }

    public function contarPorAgente($agenteId)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->tabla} WHERE agente_asignado_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$agenteId]);
        $resultado = $stmt->fetch();
        return $resultado['total'] ?? 0;
    }

    public function obtenerPorColaborador($colaboradorId)
    {
        $sql = "SELECT t.*, cat.nombre as categoria_nombre, u.nombre as agente_nombre
                FROM {$this->tabla} t
                LEFT JOIN categorias_tickets cat ON t.categoria_id = cat.id
                LEFT JOIN usuarios u ON t.agente_asignado_id = u.id
                WHERE t.colaborador_id = ?
                ORDER BY t.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$colaboradorId]);
        return $stmt->fetchAll();
    }

    public function contarPorColaborador($colaboradorId)
    {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN estado = 'En proceso' THEN 1 ELSE 0 END) as en_proceso,
                SUM(CASE WHEN estado = 'Culminada' THEN 1 ELSE 0 END) as culminados
                FROM {$this->tabla}
                WHERE colaborador_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$colaboradorId]);
        return $stmt->fetch();
    }

    public function crearTicket($datos)
    {
        if (mb_strlen($datos['titulo'] ?? '') > 200) {
            throw new \Exception('El título es muy largo, debe ser corto (máximo 200 caracteres).');
        }
        return $this->crear($datos);
    }

    public function actualizarTicket($id, $datos)
    {
        return $this->actualizar($id, $datos);
    }

    public function eliminarTicket($id)
    {
        return $this->eliminar($id);
    }

    public function contarPorEstado()
    {
        $sql = "SELECT estado, COUNT(*) as total FROM {$this->tabla} GROUP BY estado";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function contarTotales()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->tabla}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function obtenerPaginados($pagina = 1, $porPagina = 5)
    {
        $offset = ($pagina - 1) * $porPagina;
        $sql = "SELECT t.*, 
                c.primer_nombre as colaborador_nombre, 
                c.primer_apellido as colaborador_apellido,
                u.nombre as agente_nombre,
                cat.nombre as categoria_nombre
                FROM {$this->tabla} t
                LEFT JOIN colaboradores c ON t.colaborador_id = c.id
                LEFT JOIN usuarios u ON t.agente_asignado_id = u.id
                LEFT JOIN categorias_tickets cat ON t.categoria_id = cat.id
                ORDER BY t.id DESC
                LIMIT ? OFFSET ?";
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