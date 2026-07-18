<?php

namespace Controllers;

use Core\Controlador;
use Core\CSRF;
use Models\Ticket;
use Models\Colaborador;
use Models\CategoriaTicket;
use Middleware\AutenticacionMiddleware;
use Middleware\RolMiddleware;

class ReporteController extends Controlador
{
    private $ticketModel;
    private $colaboradorModel;
    private $categoriaModel;

    public function __construct()
    {
        AutenticacionMiddleware::check();
        RolMiddleware::checkPermission('reportes', 'ver');
        $this->ticketModel = new Ticket();
        $this->colaboradorModel = new Colaborador();
        $this->categoriaModel = new CategoriaTicket();
    }

    public function index()
    {
        $prioridad = $_GET['prioridad'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';
        
        $pagina = (int)($_GET['pagina'] ?? 1);
        $porPagina = 5;
        $offset = ($pagina - 1) * $porPagina;

        $tickets = $this->obtenerTicketsFiltrados($prioridad, $estado, $fecha_inicio, $fecha_fin, $porPagina, $offset);
        $total = $this->contarTicketsFiltrados($prioridad, $estado, $fecha_inicio, $fecha_fin);
        $totalPaginas = ceil($total / $porPagina);
        $estadisticas = $this->obtenerEstadisticas();

        $this->vista('admin/reportes/index', [
            'titulo' => 'Reportes y Estadísticas',
            'tickets' => $tickets,
            'estadisticas' => $estadisticas,
            'prioridad' => $prioridad,
            'estado' => $estado,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'total' => $total,
            'porPagina' => $porPagina,
            'csrf_token' => CSRF::generarToken()
        ]);
    }

    public function culminados()
    {
        $pagina = (int)($_GET['pagina'] ?? 1);
        $porPagina = 10;
        $offset = ($pagina - 1) * $porPagina;

        $tickets = $this->obtenerCulminados($porPagina, $offset);
        $total = $this->contarCulminados();
        $totalPaginas = max(1, (int)ceil($total / $porPagina));

        foreach ($tickets as &$t) {
            $t['tiempo_tramite'] = $this->formatearTiempo($t['minutos_tramite'] ?? null);
        }
        unset($t);

        $this->vista('admin/reportes/culminados', [
            'titulo' => 'Tickets Culminados',
            'tickets' => $tickets,
            'total' => $total,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'porPagina' => $porPagina
        ]);
    }

    private function obtenerCulminados($limite = null, $offset = 0)
    {
        $sql = "SELECT t.*,
                c.primer_nombre as colaborador_nombre,
                c.primer_apellido as colaborador_apellido,
                u.nombre as agente_nombre,
                cat.nombre as categoria_nombre,
                TIMESTAMPDIFF(MINUTE, t.fecha_creacion, t.fecha_culminacion) as minutos_tramite
                FROM tickets t
                LEFT JOIN colaboradores c ON t.colaborador_id = c.id
                LEFT JOIN usuarios u ON t.agente_asignado_id = u.id
                LEFT JOIN categorias_tickets cat ON t.categoria_id = cat.id
                WHERE t.estado = 'Culminada'
                ORDER BY t.fecha_culminacion DESC";

        $params = [];
        if ($limite !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limite;
            $params[] = $offset;
        }

        $db = \Config\BaseDatos::obtenerInstancia()->obtenerConexion();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    private function contarCulminados()
    {
        $db = \Config\BaseDatos::obtenerInstancia()->obtenerConexion();
        $stmt = $db->query("SELECT COUNT(*) as total FROM tickets WHERE estado = 'Culminada'");
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    /**
     * Convierte minutos en un texto legible: "2d 4h" o "45min".
     */
    private function formatearTiempo($minutos)
    {
        if ($minutos === null) {
            return 'N/A';
        }

        $minutos = (int)$minutos;
        $dias = intdiv($minutos, 1440);
        $horas = intdiv($minutos % 1440, 60);
        $mins = $minutos % 60;

        $partes = [];
        if ($dias > 0) $partes[] = $dias . 'd';
        if ($horas > 0) $partes[] = $horas . 'h';
        if ($dias === 0) $partes[] = $mins . 'min';

        return implode(' ', $partes);
    }

    private function contarTicketsFiltrados($prioridad, $estado, $fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT COUNT(*) as total FROM tickets t WHERE 1=1";
        $params = [];

        if (!empty($prioridad)) {
            $sql .= " AND t.prioridad = ?";
            $params[] = $prioridad;
        }

        if (!empty($estado)) {
            $sql .= " AND t.estado = ?";
            $params[] = $estado;
        }

        if (!empty($fecha_inicio)) {
            $sql .= " AND DATE(t.fecha_creacion) >= ?";
            $params[] = $fecha_inicio;
        }

        if (!empty($fecha_fin)) {
            $sql .= " AND DATE(t.fecha_creacion) <= ?";
            $params[] = $fecha_fin;
        }

        $db = \Config\BaseDatos::obtenerInstancia()->obtenerConexion();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $resultado = $stmt->fetch();
        return $resultado['total'] ?? 0;
    }

    private function obtenerTicketsFiltrados($prioridad, $estado, $fecha_inicio, $fecha_fin, $limite = null, $offset = 0)
    {
        $sql = "SELECT t.*, 
                c.primer_nombre as colaborador_nombre, 
                c.primer_apellido as colaborador_apellido,
                u.nombre as agente_nombre,
                cat.nombre as categoria_nombre,
                TIMESTAMPDIFF(HOUR, t.fecha_creacion, t.fecha_culminacion) as horas_tramite,
                TIMESTAMPDIFF(MINUTE, t.fecha_creacion, t.fecha_culminacion) as minutos_tramite
                FROM tickets t
                LEFT JOIN colaboradores c ON t.colaborador_id = c.id
                LEFT JOIN usuarios u ON t.agente_asignado_id = u.id
                LEFT JOIN categorias_tickets cat ON t.categoria_id = cat.id
                WHERE 1=1";

        $params = [];

        if (!empty($prioridad)) {
            $sql .= " AND t.prioridad = ?";
            $params[] = $prioridad;
        }

        if (!empty($estado)) {
            $sql .= " AND t.estado = ?";
            $params[] = $estado;
        }

        if (!empty($fecha_inicio)) {
            $sql .= " AND DATE(t.fecha_creacion) >= ?";
            $params[] = $fecha_inicio;
        }

        if (!empty($fecha_fin)) {
            $sql .= " AND DATE(t.fecha_creacion) <= ?";
            $params[] = $fecha_fin;
        }

        $sql .= " ORDER BY t.id DESC";

        if ($limite !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limite;
            $params[] = $offset;
        }

        $db = \Config\BaseDatos::obtenerInstancia()->obtenerConexion();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    private function obtenerEstadisticas()
    {
        $db = \Config\BaseDatos::obtenerInstancia()->obtenerConexion();

        $stmt = $db->query("SELECT COUNT(*) as total FROM tickets");
        $total = $stmt->fetch();

        $stmt = $db->query("SELECT estado, COUNT(*) as total FROM tickets GROUP BY estado");
        $porEstado = $stmt->fetchAll();

        $stmt = $db->query("SELECT prioridad, COUNT(*) as total FROM tickets GROUP BY prioridad");
        $porPrioridad = $stmt->fetchAll();

        $stmt = $db->query("SELECT cat.nombre, COUNT(t.id) as total 
                            FROM categorias_tickets cat 
                            LEFT JOIN tickets t ON cat.id = t.categoria_id 
                            GROUP BY cat.id");
        $porCategoria = $stmt->fetchAll();

        $stmt = $db->query("SELECT AVG(TIMESTAMPDIFF(HOUR, fecha_creacion, fecha_culminacion)) as promedio_horas 
                            FROM tickets 
                            WHERE estado = 'Culminada' AND fecha_culminacion IS NOT NULL");
        $tiempoPromedio = $stmt->fetch();

        $stmt = $db->query("SELECT u.nombre, COUNT(t.id) as total 
                            FROM usuarios u 
                            LEFT JOIN tickets t ON u.id = t.agente_asignado_id 
                            WHERE t.estado = 'Culminada' 
                            GROUP BY u.id 
                            ORDER BY total DESC 
                            LIMIT 1");
        $mejorAgente = $stmt->fetch();

        return [
            'total' => $total['total'] ?? 0,
            'por_estado' => $porEstado,
            'por_prioridad' => $porPrioridad,
            'por_categoria' => $porCategoria,
            'tiempo_promedio' => round($tiempoPromedio['promedio_horas'] ?? 0, 1),
            'mejor_agente' => $mejorAgente['nombre'] ?? 'Sin datos',
            'tickets_mejor_agente' => $mejorAgente['total'] ?? 0
        ];
    }

    public function estadisticasCategoria()
    {
        $periodo = $_GET['periodo'] ?? 'mensual';
        $anio = (int)($_GET['anio'] ?? date('Y'));
        $mes = (int)($_GET['mes'] ?? date('n'));
        $semestre = (int)($_GET['semestre'] ?? (date('n') <= 6 ? 1 : 2));

        [$fechaInicio, $fechaFin] = $this->calcularRangoPeriodo($periodo, $anio, $mes, $semestre);

        $pagina = (int)($_GET['pagina'] ?? 1);
        $porPagina = 5;
        $offset = ($pagina - 1) * $porPagina;

        $categorias = $this->obtenerEstadisticasPorCategoria($fechaInicio, $fechaFin, $porPagina, $offset);
        $totalCategorias = $this->contarCategoriasActivas();
        $totalPaginas = max(1, (int)ceil($totalCategorias / $porPagina));
        $totalAtendidosPeriodo = array_sum(array_column($categorias, 'total_atendidos'));

        $this->vista('admin/reportes/categorias', [
            'titulo' => 'Estadísticas por Categoría',
            'categorias' => $categorias,
            'periodo' => $periodo,
            'anio' => $anio,
            'mes' => $mes,
            'semestre' => $semestre,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'totalAtendidosPeriodo' => $totalAtendidosPeriodo,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'total' => $totalCategorias
        ]);
    }

    /**
     * Traduce el periodo elegido (mensual/semestral/anual) a un
     * rango de fechas [inicio, fin] concreto.
     */
    private function calcularRangoPeriodo($periodo, $anio, $mes, $semestre)
    {
        if ($periodo === 'anual') {
            return ["$anio-01-01 00:00:00", "$anio-12-31 23:59:59"];
        }

        if ($periodo === 'semestral') {
            return $semestre == 1
                ? ["$anio-01-01 00:00:00", "$anio-06-30 23:59:59"]
                : ["$anio-07-01 00:00:00", "$anio-12-31 23:59:59"];
        }

        // Mensual (default)
        $mes = max(1, min(12, $mes));
        $ultimoDia = date('t', strtotime("$anio-$mes-01"));
        return [
            sprintf('%d-%02d-01 00:00:00', $anio, $mes),
            sprintf('%d-%02d-%d 23:59:59', $anio, $mes, $ultimoDia)
        ];
    }

    private function obtenerEstadisticasPorCategoria($fechaInicio, $fechaFin, $limite = null, $offset = 0)
    {
        $sql = "SELECT cat.id, cat.nombre, cat.tipo,
                COUNT(t.id) as total_atendidos
                FROM categorias_tickets cat
                LEFT JOIN tickets t ON t.categoria_id = cat.id
                    AND t.estado = 'Culminada'
                    AND t.fecha_culminacion BETWEEN ? AND ?
                WHERE cat.activo = 1
                GROUP BY cat.id, cat.nombre, cat.tipo
                ORDER BY total_atendidos DESC, cat.nombre ASC";

        $params = [$fechaInicio, $fechaFin];

        if ($limite !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limite;
            $params[] = $offset;
        }

        $db = \Config\BaseDatos::obtenerInstancia()->obtenerConexion();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    private function contarCategoriasActivas()
    {
        $db = \Config\BaseDatos::obtenerInstancia()->obtenerConexion();
        $stmt = $db->query("SELECT COUNT(*) as total FROM categorias_tickets WHERE activo = 1");
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    public function exportarEstadisticasCategoriaExcel()
    {
        RolMiddleware::checkPermission('reportes', 'exportar');

        $periodo = $_GET['periodo'] ?? 'mensual';
        $anio = (int)($_GET['anio'] ?? date('Y'));
        $mes = (int)($_GET['mes'] ?? date('n'));
        $semestre = (int)($_GET['semestre'] ?? (date('n') <= 6 ? 1 : 2));

        [$fechaInicio, $fechaFin] = $this->calcularRangoPeriodo($periodo, $anio, $mes, $semestre);
        $categorias = $this->obtenerEstadisticasPorCategoria($fechaInicio, $fechaFin);

        $etiquetaPeriodo = $periodo === 'anual'
            ? "Año $anio"
            : ($periodo === 'semestral'
                ? "Semestre $semestre - $anio"
                : "Mes $mes/$anio");

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename=estadisticas_categoria_' . date('Y-m-d') . '.xls');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo "<html><head><meta charset=\"UTF-8\"></head><body>";
        echo "<h3>Tickets atendidos por categoría — $etiquetaPeriodo</h3>";
        echo "<table border=\"1\">";
        echo "<tr><th>Categoría</th><th>Tipo</th><th>Periodo</th><th>Total Atendidos</th></tr>";

        foreach ($categorias as $c) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($c['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($c['tipo']) . "</td>";
            echo "<td>" . htmlspecialchars($etiquetaPeriodo) . "</td>";
            echo "<td>" . htmlspecialchars((string)$c['total_atendidos']) . "</td>";
            echo "</tr>";
        }

        echo "</table></body></html>";
        exit;
    }

    public function estadisticas()
    {
        $estadisticas = $this->obtenerEstadisticas();

        $this->vista('admin/reportes/estadisticas', [
            'titulo' => 'Estadísticas del Sistema',
            'estadisticas' => $estadisticas
        ]);
    }

    public function exportarExcel()
    {
        RolMiddleware::checkPermission('reportes', 'exportar');
        $prioridad = $_GET['prioridad'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';
        $tickets = $this->obtenerTicketsFiltrados($prioridad, $estado, $fecha_inicio, $fecha_fin);

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename=reporte_tickets_' . date('Y-m-d') . '.xls');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo "<html><head><meta charset=\"UTF-8\"></head><body>";
        echo "<table border=\"1\">";
        echo "<tr>";
        echo "<th>ID</th><th>Título</th><th>Categoría</th><th>Prioridad</th><th>Estado</th>";
        echo "<th>Colaborador</th><th>Agente</th><th>Fecha Creación</th><th>Fecha Culminación</th><th>Horas Tramitación</th>";
        echo "</tr>";

        foreach ($tickets as $t) {
            $colaborador = trim(($t['colaborador_nombre'] ?? '') . ' ' . ($t['colaborador_apellido'] ?? ''));
            echo "<tr>";
            echo "<td>" . htmlspecialchars((string)$t['id']) . "</td>";
            echo "<td>" . htmlspecialchars($t['titulo']) . "</td>";
            echo "<td>" . htmlspecialchars($t['categoria_nombre'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($t['prioridad']) . "</td>";
            echo "<td>" . htmlspecialchars($t['estado']) . "</td>";
            echo "<td>" . htmlspecialchars($colaborador) . "</td>";
            echo "<td>" . htmlspecialchars($t['agente_nombre'] ?? 'Sin asignar') . "</td>";
            echo "<td>" . htmlspecialchars(date('d/m/Y H:i', strtotime($t['fecha_creacion']))) . "</td>";
            echo "<td>" . htmlspecialchars($t['fecha_culminacion'] ? date('d/m/Y H:i', strtotime($t['fecha_culminacion'])) : 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars((string)($t['horas_tramite'] ?? 0)) . "</td>";
            echo "</tr>";
        }

        echo "</table></body></html>";
        exit;
    }
}
