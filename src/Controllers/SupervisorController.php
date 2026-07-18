<?php

namespace Controllers;

use Config\BaseDatos;
use Core\Controlador;
use Middleware\AutenticacionMiddleware;

class SupervisorController extends Controlador
{
    public function __construct()
    {
        AutenticacionMiddleware::check();

        if (strtolower($_SESSION['user_rol'] ?? '') !== 'supervisor') {
            http_response_code(403);
            die('Acceso denegado. Este panel es solo para supervisores.');
        }
    }

    public function dashboard()
    {
        $db = BaseDatos::obtenerInstancia()->obtenerConexion();

        $totalTickets = $db->query("SELECT COUNT(*) AS total FROM tickets")->fetch();
        $enProceso = $db->query("SELECT COUNT(*) AS total FROM tickets WHERE estado = 'En proceso'")->fetch();
        $enEspera = $db->query("SELECT COUNT(*) AS total FROM tickets WHERE estado = 'En espera'")->fetch();
        $culminados = $db->query("SELECT COUNT(*) AS total FROM tickets WHERE estado = 'Culminada'")->fetch();

        $stmt = $db->query("SELECT AVG(TIMESTAMPDIFF(HOUR, fecha_creacion, fecha_culminacion)) AS promedio_horas
                            FROM tickets
                            WHERE estado = 'Culminada' AND fecha_culminacion IS NOT NULL");
        $tiempoPromedio = $stmt->fetch();

        $stmt = $db->query("SELECT t.*, 
                                   CONCAT(c.primer_nombre, ' ', c.primer_apellido) AS colaborador_nombre,
                                   u.nombre AS agente_nombre,
                                   cat.nombre AS categoria_nombre
                            FROM tickets t
                            LEFT JOIN colaboradores c ON t.colaborador_id = c.id
                            LEFT JOIN usuarios u ON t.agente_asignado_id = u.id
                            LEFT JOIN categorias_tickets cat ON t.categoria_id = cat.id
                            ORDER BY t.fecha_creacion DESC
                            LIMIT 8");
        $ticketsRecientes = $stmt->fetchAll();

        $stmt = $db->query("SELECT u.nombre, COUNT(t.id) AS total
                            FROM usuarios u
                            LEFT JOIN tickets t ON u.id = t.agente_asignado_id AND t.estado = 'Culminada'
                            WHERE u.rol = 'agente' AND u.activo = 1
                            GROUP BY u.id, u.nombre
                            ORDER BY total DESC");
        $rendimientoAgentes = $stmt->fetchAll();

        $this->vista('supervisor/dashboard', [
            'titulo' => 'Panel Supervisor',
            'nombre' => $_SESSION['user_nombre'] ?? 'Supervisor',
            'totales' => [
                'total' => $totalTickets['total'] ?? 0,
                'proceso' => $enProceso['total'] ?? 0,
                'espera' => $enEspera['total'] ?? 0,
                'culminados' => $culminados['total'] ?? 0,
                'promedio' => round($tiempoPromedio['promedio_horas'] ?? 0, 1)
            ],
            'tickets_recientes' => $ticketsRecientes,
            'rendimiento_agentes' => $rendimientoAgentes
        ]);
    }
}
