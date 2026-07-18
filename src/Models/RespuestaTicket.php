<?php

namespace Models;

use Core\Modelo;

class RespuestaTicket extends Modelo
{
    protected $tabla = 'respuestas_tickets';

    public function obtenerPorTicket($ticketId)
    {
        $sql = "SELECT r.*, u.nombre as agente_nombre 
                FROM {$this->tabla} r
                LEFT JOIN usuarios u ON r.agente_id = u.id
                WHERE r.ticket_id = ?
                ORDER BY r.fecha_respuesta ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$ticketId]);
        return $stmt->fetchAll();
    }

    public function crearRespuesta($datos)
    {
        return $this->crear($datos);
    }
}