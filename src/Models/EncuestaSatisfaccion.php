<?php
// BREVE DESCRIPCIÓN:
// Modelo para la gestión de encuestas de satisfacción.
// Registra la calificación y comentarios de los colaboradores al culminar un ticket
namespace Models;

use Core\Modelo;

class EncuestaSatisfaccion extends Modelo
{
    protected $tabla = 'encuestas_satisfaccion';

    public function obtenerPorTicket($ticketId)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE ticket_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$ticketId]);
        return $stmt->fetch();
    }

    public function crearEncuesta($datos)
    {
        return $this->crear($datos);
    }

    public function actualizarEncuesta($id, $datos)
    {
        return $this->actualizar($id, $datos);
    }
}