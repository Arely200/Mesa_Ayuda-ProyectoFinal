<?php

namespace Controllers;

use Core\Autenticacion;
use Core\Controlador;
use Config\BaseDatos;
use Middleware\AutenticacionMiddleware;
use Middleware\RolMiddleware;

class DashboardController extends Controlador
{
    public function index()
    {
        AutenticacionMiddleware::check();
        RolMiddleware::checkPermission('dashboard', 'ver');

        if (!Autenticacion::estaLogueado()) {
            $this->redirigir('/login');
            return;
        }

        $db = BaseDatos::obtenerInstancia()->obtenerConexion();

        // Total de tickets
        $stmt = $db->query("SELECT COUNT(*) as total FROM tickets");
        $totalTickets = $stmt->fetch();

        // Tickets en proceso
        $stmt = $db->query("SELECT COUNT(*) as total FROM tickets WHERE estado = 'En proceso'");
        $enProceso = $stmt->fetch();

        // Tickets culminados
        $stmt = $db->query("SELECT COUNT(*) as total FROM tickets WHERE estado = 'Culminada'");
        $culminados = $stmt->fetch();

        // Agentes activos
        $stmt = $db->query("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'agente' AND activo = 1");
        $agentes = $stmt->fetch();

        // Intentos de login (logs)
        $stmt = $db->query("SELECT COUNT(*) as total FROM logs_login");
        $totalLogs = $stmt->fetch();

        // Intentos fallidos en la última hora
        $stmt = $db->query("SELECT COUNT(*) as fallidos FROM logs_login WHERE exitoso = 0 AND fecha >= DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        $fallidosRecientes = $stmt->fetch();

        // Tickets recientes (últimos 5)
        $stmt = $db->query("SELECT t.*, c.primer_nombre as colaborador 
                            FROM tickets t 
                            LEFT JOIN colaboradores c ON t.colaborador_id = c.id 
                            ORDER BY t.id DESC LIMIT 5");
        $ticketsRecientes = $stmt->fetchAll();

        $this->vista('admin/dashboard', [
            'total_tickets' => $totalTickets['total'] ?? 0,
            'en_proceso' => $enProceso['total'] ?? 0,
            'culminados' => $culminados['total'] ?? 0,
            'agentes' => $agentes['total'] ?? 0,
            'total_logs' => $totalLogs['total'] ?? 0,
            'fallidos_recientes' => $fallidosRecientes['fallidos'] ?? 0,
            'tickets_recientes' => $ticketsRecientes
        ]);
    }
}
